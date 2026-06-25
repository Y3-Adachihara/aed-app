<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Aed;

#[Signature('sync:aed')]
#[Description('各自治体のオープンデータ（AED）を自動ダウンロードしてデータベースを同期します')]
class SyncAedDataCommand extends Command
{

    /**
     * 本番のロジックを実行するメインメソッド
     */
    public function handle()
    {
        $this->info('🚀 AEDデータの自動同期バッチを開始します。');

        // 1. マッピングテーブル（指示書）から全自治体の設定を取得
        $mappings = DB::table('csv_mappings')->get();

        if ($mappings->isEmpty()) {
            $this->error('❌ マッピングデータ（指示書）が登録されていません。シーダーを実行してください。');
            return Command::FAILURE;
        }

        // 2. 自治体の数だけループ処理を開始
        foreach ($mappings as $mapping) {
            $this->newLine(); // 見やすくするために空行を入れる
            $this->info("==================================================");
            $this->info(" 現在処理中: 【{$mapping->municipality_name}】 (コード: {$mapping->municipality_code})");
            $this->info("==================================================");

            // 静岡県共通のシラサギAPIベースURLに、自治体ごとのデータセットIDをガッチャンコ
            $apiUrl = "https://opendata.pref.shizuoka.jp/api/package_show?id={$mapping->dataset_id}";
            $this->comment("🔗 APIに接続中: {$apiUrl}");

            try {
                // 3. APIを叩いてデータセット情報を取得
                $response = Http::timeout(10)->get($apiUrl);

                if ($response->failed()) {
                    $this->error("❌ APIの取得に失敗しました。ステータスコード: " . $response->status());
                    continue; // この自治体はスキップして次の自治体へ
                }

                // 現段階では、ちゃんとAPIの接続までループが回っているか確認するためのログ
                $this->info("✅ APIへの接続に成功しました！");
                

                // ----------------------------------------------------------------
                // 💡 ステップ1: APIのJSONからCSVのダウンロードURLを見つける（修正版）
                // ----------------------------------------------------------------
                $data = $response->json();
                $csvUrl = null;

                if (isset($data['result']['resources'])) {
                    foreach ($data['result']['resources'] as $resource) {
                        // 1. まずはCSVファイルかどうかを判定
                        $isCsv = str_ends_with(strtolower($resource['url']), '.csv') || strtolower($resource['format'] ?? '') === 'csv';
                        
                        // 🌟 ユーザーさん大正格！「H29年度」などの古いデータを含まないか判定
                        $resourceName = $resource['name'] ?? '';
                        $isNotOldData = !str_contains($resourceName, 'H29年度');

                        // 両方の条件を満たしたものだけを「本命」として採用！
                        if ($isCsv && $isNotOldData) {
                            $csvUrl = $resource['url'];
                            break;
                        }
                    }
                }

                if (!$csvUrl) {
                    $this->error("❌ 本命のCSVファイルのURLが見つかりませんでした。");
                    continue;
                }

                $this->comment("📥 本命CSVをダウンロード中: {$csvUrl}");


                // ----------------------------------------------------------------
                // 💡 ステップ2: CSVのダウンロードと文字化け解消（テストの引っ越し）
                // ----------------------------------------------------------------
                $csvResponse = Http::timeout(30)->get($csvUrl);
                if ($csvResponse->failed()) {
                    $this->error("❌ CSVのダウンロードに失敗しました。");
                    continue;
                }

                $rawBody = $csvResponse->body();

                // 🌟 テストで大活躍した「文字コード自動判定＆変換」の処理
                $currentEncoding = mb_detect_encoding($rawBody, ['UTF-8', 'SJIS-win', 'SJIS', 'EUC-JP'], true);
                if ($currentEncoding !== 'UTF-8') {
                    $rawBody = mb_convert_encoding($rawBody, 'UTF-8', $currentEncoding);
                }

                // 🌟 テストで大活躍した「BOM（割れ文字）削除」の処理
                $rawBody = preg_replace('/^\xEF\xBB\xBF/', '', $rawBody);

                // 文字列を「行」に分解
                $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $rawBody));
                
                // ヘッダー行（1行目）を取得して、空行を除外するためのクレンジング
                $header = str_getcsv(array_shift($lines));
                
                // データ行をループして解析
                $parsedRows = [];
                foreach ($lines as $line) {
                    if (empty(trim($line))) continue; // 空行はパス
                    
                    $row = str_getcsv($line);
                    if (count($header) !== count($row)) continue; // 列数が合わない行はパス
                    
                    $parsedRows[] = array_combine($header, $row);
                }

                $this->info("📊 CSVの解析完了！レコード数: " . count($parsedRows) . " 件");

                // ----------------------------------------------------------------
                // 💡 ステップ3: 指示書を使ってデータを整形し、aedsテーブルへ保存
                // ----------------------------------------------------------------
                $this->comment("💾 データベースへ同期中...");
                $successCount = 0;

                foreach ($parsedRows as $row) {
                    // ① 施設名（名称）の取得
                    $name = $row[$mapping->name_column] ?? null;
                    if (!$name) continue; // 名前がないデータはスキップ

                    // ② 住所のガッチャンコ処理（カンマ区切りに対応）
                    $addressParts = explode(',', $mapping->address_columns);
                    $fullAddress = '';
                    foreach ($addressParts as $part) {
                        $fullAddress .= $row[trim($part)] ?? '';
                    }

                    // ③ 緯度・経度の取得（nullが許可されている場合は、そのままnullを入れる）
                    $latitude = $mapping->latitude_column ? ($row[$mapping->latitude_column] ?? null) : null;
                    $longitude = $mapping->longitude_column ? ($row[$mapping->longitude_column] ?? null) : null;

                    // ④ 説明文（設置場所や備考など）のガッチャンコ処理
                    $description = '';
                    if ($mapping->description_columns) {
                        $descParts = explode(',', $mapping->description_columns);
                        $descList = [];
                        foreach ($descParts as $part) {
                            $trimmedPart = trim($part);
                            if (!empty($row[$trimmedPart])) {
                                // 「設置位置: ○○階」のような形で見やすく組み立てる
                                $descList[] = "【{$trimmedPart}】: " . $row[$trimmedPart];
                            }
                        }
                        $description = implode("\n", $descList);
                    }

                    // ⑤ 🌟 重複を防ぎながらデータベースへ保存・更新！
                    // 「自治体コード」と「施設名」と「住所」が完全に一致するデータがあれば上書き、なければ新規登録
                    Aed::updateOrCreate(
                        [
                            'municipality_code' => $mapping->municipality_code,
                            'name' => $name,
                            'address' => $fullAddress,
                        ],
                        [
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'description' => $description,
                        ]
                    );

                    $successCount++;
                }

                $this->info("✨ 【{$mapping->municipality_name}】のデータを {$successCount} 件 同期しました！");
                
            } catch (\Exception $e) {
                $this->error("❌ 予期せぬエラーが発生しました: " . $e->getMessage());
                continue;
            }
        }

        $this->newLine();
        $this->info('🎉 すべての自治体の同期処理が終了しました！');
        return Command::SUCCESS;

    }
}
