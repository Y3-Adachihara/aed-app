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
    
    function initMap() {
        const mapOptions = {
            center: { lat: parseFloat(lat), lng: parseFloat(lng) }, // 中心にする座標
            zoom: 16 // 地図の拡大倍率（16は街並みがきれいに見えるっぽい）
        };

        const mapDiv = document.getElementById('map');
        const map = new google.maps.Map(mapDiv, mapOptions);
    }

    console.log("マップコンポーネントが読み込まれました。ターゲット座標:", lat, lng);
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>