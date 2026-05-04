<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>{{ $title ?? 'AEDアプリ' }}</title>
    </head>
    <body class="bg-gray-100">
        {{ $slot }}
    </body>
</html>