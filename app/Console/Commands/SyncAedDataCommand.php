<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
                
                // 【ここに、次回以降CSVダウンロードや解析、保存のロジックを追加していきます】
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
