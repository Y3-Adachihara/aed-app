@props([
    'aed' => null,
    'view_name' => null,
    'top_title' => null,
])
<x-layout.layout>
    <x-layout.layout-header view_name="{{ $view_name }}" top_title="{{ $top_title }}">
            <div class="space-y-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100 mt-4">
            <p class="text-lg font-medium text-gray-800">{{ $aed->prefecture }}{{ $aed->municipality }}{{ $aed->address }}</p>
            <p class="text-gray-600">{{ $aed->description }}</p>
            <p class="text-sm text-gray-400">緯度: {{ $aed->latitude }} / 経度: {{ $aed->longitude }}</p>

            <x-elements.aed.map :latitude="$aed->latitude" :longitude="$aed->longitude" />

            <div class="pt-4">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                    ← 戻る
                </a>
            </div>
        </div>
    </x-layout.layout-header>
</x-layout.layout>