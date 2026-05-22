@props([
    'aed' => null,
])
<div class="bg-white shadow p-6">
    <br/>
        <p>{{ $aed->name }}</p>
        <p>{{ $aed->postcode }}</p>
        <p>{{ $aed->prefecture }}</p>
        <p>{{ $aed->municipality }}</p>
        <p>{{ $aed->address }}</p>
    <br/>
    @if (auth()->user()->role == 'admin')
        <a href="#">編集</a>
        <a href="#">削除</a>
    @endif

    <a href="#">詳細</a>
</div>