@proos([
    'latitude' => null,
    'longitude' => null,
])
<div id='map' class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 mb-4">
    <p class="text-sm text-gray-500 font-medium">📍 地図読み込みの準備中... (緯度: {{ $latitude }}, 経度: {{ $longitude }})</p>
</div>

<script>
    // ここにピン立て処理
    console.log("マップコンポーネントが読み込まれました。ターゲット座標:", {{ $latitude }}, {{ $longitude }});
</script>