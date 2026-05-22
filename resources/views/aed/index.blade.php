@props([
    'aeds' => [],
])
<x-layout.layout>
    <x-layout.layout-header view_name="AEDアプリ | ホーム" top_title="島田市 AED設置場所一覧">
        @foreach ($aeds as $aed)
            <br/>
            <p>{{ $aed->name }}</p>
            <p>{{ $aed->postcode }}</p>
            <p>{{ $aed->prefecture }}</p>
            <p>{{ $aed->municipality }}</p>
            <p>{{ $aed->address }}</p>
            <p>{{ $aed->description }}</p>
            <p>{{ $aed->latitude }}</p>
            <p>{{ $aed->longitude }}</p>
            <br/>
        @endforeach
    </x-layout.layout-header>
</x-layout.layout>