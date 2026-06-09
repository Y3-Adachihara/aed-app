@props([
    'latitude' => null,
    'longitude' => null,
])
<div id='map' class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 mb-4">
    <p class="text-sm text-gray-500 font-medium">📍 地図読み込みの準備中... (緯度: {{ $latitude }}, 経度: {{ $longitude }})</p>
</div>

<script>
    const lat = '{{ $latitude }}';
    const lng = '{{ $longitude }}';
    
    // ここでは、まだ定義しているだけ。実行はされない。
    function initMap() {

        // Mapを表示するにあたって指示する設定を保持させる
        const mapOptions = {
            center: { lat: parseFloat(lat), lng: parseFloat(lng) }, // 中心にする座標
            zoom: 16 // 地図の拡大倍率（16は街並みがきれいに見えるっぽい）
        };

        const mapDiv = document.getElementById('map');  // Mapを表示させるUI部品をidで検索して取ってくる
        const map = new google.maps.Map(mapDiv, mapOptions);    // ここでようやく地図が出現。

        // さっきの地図にピンを表示しろコノヤローと指示する部分。
        new google.maps.Marker({
            position: { lat: parseFloat(lat), lng: parseFloat(lng) }, // ピンを立てる座標
            map: map // 💡 上で作った「map」の変数と合体させる
        });
    }

    console.log("マップコンポーネントが読み込まれました。ターゲット座標:", lat, lng);
</script>

{{-- ここでGoogleMapを呼び出している！ --}}
{{-- callbackは、「ダウンロードが終わったら、後ろに示した関数を実行してね～」っていう指示を飛ばすためのURLパラメータ --}}
{{-- async defer は、「後回しでダウンロードしろや」という指示。画面のレイアウトを表示してから、最後にMapを読み込む設計。画面の表示を最優先するため。 --}}
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>