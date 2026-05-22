@props([
    'aeds' => null,
])
<x-layout.layout>
    <x-layout.layout-header view_name="AEDアプリ | ホーム" top_title="島田市 AED設置場所一覧">
        @foreach ($aeds as $aed)
            <x-elements.aed.card :aed="$aed" />
        @endforeach
    </x-layout.layout-header>
</x-layout.layout>