<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; // 💡 APIを叩くためのLaravelの便利な機能

class TestGeocodingCommand extends Command
{
    // 💡 ターミナルで実行するときのコマンド名（命令文）を定義
    protected $signature = 'test:geo';

    // 💡 コマンドの説明（なくても動きます）
    protected $description = 'Google Geocoding APIの住所分解実験';

    public function handle()
    {
        $this->info('--- CSV中身取得実験を開始します ---');

        // 1-a. 島田市のデータセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=8f9aacc5-4c06-4803-acbe-5452b98b6f9c';

        // 1-b. 焼津市のデータセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=e5f81371-ca06-4f6e-af7b-0dd1e853494f';

        // 1-c. 藤枝市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // 元からUTF-8
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=5e3017d5-eaa2-4355-a6a8-b51926b43dbb';

        // 1-c. 静岡市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=dd1347a8-60e9-4781-8dbe-7614924f945e';

        // 1-c. 川根本町データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // 元からUTF-8
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=e504fef5-2762-41da-ad8d-08b93c7f7ae7';

        // 1-c. 牧之原市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // 元からUTF-8
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=f5349248-b2fb-4468-8b67-847de96d68eb';

        // 1-c. 浜松市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定 ← 訂正。シラサギAPIヲ引き続き使用(2026-06-25)）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=f70e5a59-5d61-4009-9024-1bc40879c662';

        $apiResponse = Http::get($apiUrl);

        if (!$apiResponse->successful()) {
            $this->error('API通信に失敗しました。');
            return Command::FAILURE;
        }

        $data = $apiResponse->json();
        $csvUrl = '';

        // 2. 本命(非H29年度)のCSVのURLを特定
        foreach ($data['result']['resources'] as $resource) {
            if ($resource['format'] === 'CSV' && !str_contains($resource['name'], 'H29年度')) {
                $csvUrl = $resource['download_url'];
                break;
            }
        }

        if (!$csvUrl) {
            $this->error('本命のCSV URLが見つかりませんでした。');
            return Command::FAILURE;
        }

        $this->info('対象のCSV URL: ' . $csvUrl);

        // 🔥 3. ここから追加：特定したURLからCSVのファイルそのものをダウンロードする
        $csvResponse = Http::get($csvUrl);

        if ($csvResponse->successful()) {
            // 1. 生のテキストデータを取得
            $csvRawText = $csvResponse->body();

            // 🔥 2. 文字コードを自動判定！ (UTF-8かSJISかを調べる)
            $encoding = mb_detect_encoding($csvRawText, ['UTF-8', 'SJIS-win', 'SJIS', 'EUC-JP'], true);

            // 🔥 3. 判定結果に合わせて変換
            if ($encoding !== 'UTF-8') {
                // UTF-8以外（SJISなど）なら、UTF-8に変換
                $csvUtf8Text = mb_convert_encoding($csvRawText, 'UTF-8', $encoding ?: 'SJIS-win');
            } else {
                // すでにUTF-8なら、そのまま使う
                $csvUtf8Text = $csvRawText;
            }

            // 🔥 4. 【実務の裏技】UTF-8の先頭に付くことがある見えないゴミ（BOM）を削除
            $csvUtf8Text = preg_replace('/^\xEF\xBB\xBF/', '', $csvUtf8Text);

            // =================================================================
            // 🔥 ここからCSV解析処理の追加
            // =================================================================
            
            // 1. テキストを「改行」で区切って、1行ずつの配列にする
            // 💡 Windows(\r\n)とLinux(\n)の両方の改行コードに対応する正規表現です
            $lines = preg_split('/\r\n|\r|\n/', $csvUtf8Text);
            
            // 空の行（一番最後など）を除去
            $lines = array_filter($lines);

            // 2. 一番先頭の行（ヘッダー・項目名）を取り出す
            $firstLine = array_shift($lines);
            $headers = str_getcsv($firstLine); // ['名称', '名称(英語)', '設置場所', ...] となる

            // 3. 残りの行（データ）をループして、ヘッダーと合体させて連想配列を作る
            $parsedRows = [];
            foreach ($lines as $line) {
                // 1行の文字列を、カンマで区切って配列にする
                $rowValues = str_getcsv($line);
                
                // ヘッダーの数とデータの数が一致しているかチェック（念のため）
                if (count($headers) === count($rowValues)) {
                    // 💡 array_combine が超強力！
                    // ヘッダーの配列（鍵）と、データの配列（値）を合体させて連想配列を作ってくれる！
                    $parsedRows[] = array_combine($headers, $rowValues);
                }
            }

            // 4. 解析結果の最初の2件だけをお試しでダンプ表示してみる！
            $this->info('==================== 解析後のデータ（最初の2件） ====================');
            dump(array_slice($parsedRows, 0, 2));
            $this->info('===================================================================');
            
            $this->info('総データ件数: ' . count($parsedRows) . ' 件');
            $this->info('--- 実験成功！ ---');

        } else {
            $this->error('CSVファイルのダウンロードに失敗しました。');
        }

        return Command::SUCCESS;
    }
}