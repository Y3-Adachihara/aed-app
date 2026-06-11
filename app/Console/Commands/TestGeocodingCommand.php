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

        // 1-a. 島田市のデータセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=8f9aacc5-4c06-4803-acbe-5452b98b6f9c';

        // 1-b. 焼津市のデータセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=e5f81371-ca06-4f6e-af7b-0dd1e853494f';

        // 1-c. 藤枝市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // 元からUTF-8
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=5e3017d5-eaa2-4355-a6a8-b51926b43dbb';

        // 1-c. 静岡市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=dd1347a8-60e9-4781-8dbe-7614924f945e';

        // 1-c. 川根本町データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // 元からUTF-8
        // $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=e504fef5-2762-41da-ad8d-08b93c7f7ae7';

        // 1-c. 牧之原市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
        // 元からUTF-8
        $apiUrl = 'https://opendata.pref.shizuoka.jp/api/package_show?id=f5349248-b2fb-4468-8b67-847de96d68eb';

        // 1-c. 浜松市データセット情報を取得（実験ではシラサギAPIを使用　本番はCKAN APIを使用予定）
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

            $this->info('判定された文字コード: ' . $encoding);
            $this->info('==================== CSVの中身（ここから） ====================');
            
            // 💡 最初の2000文字を表示
            $this->line(mb_substr($csvUtf8Text, 0, 2000));
            
            $this->info('==================== CSVの中身（ここまで） ====================');
            $this->info('--- 実験成功！ ---');

        } else {
            $this->error('CSVファイルのダウンロードに失敗しました。');
        }

        return Command::SUCCESS;
    }
}