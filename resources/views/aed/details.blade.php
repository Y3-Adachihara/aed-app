@props([
    'aed' => null,
    'view_name' => null,
    'top_title' => null,
])
<x-layout.layout>
    <x-layout.layout-header view_name="{{ $view_name }}" top_title="{{ $top_title }}">
        <p>{{ $aed->prefecture }}{{ $aed->municipality }}{{ $aed->address }}</p>
        <p>{{ $aed->description }}</p>
        <p>{{ $aed->latitude }}</p>
        <p>{{ $aed->longitude }}</p>


        <x-elements.aed.map :latitude="$aed->latitude" :longitude ="$aed->longitude" />

        <a href="{{ route('home') }}">戻る</a>
    </x-layout.layout-header>
</x-layout.layout>