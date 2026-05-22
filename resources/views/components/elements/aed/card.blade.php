@props([
    'aed' => [],
])
<div>
    <br/>
        <p>{{ $aed->name }}</p>
        <p>{{ $aed->postcode }}</p>
        <p>{{ $aed->prefecture }}</p>
        <p>{{ $aed->municipality }}</p>
        <p>{{ $aed->address }}</p>
    <br/>
    @if (auth()->user()->role == 'admin')
        <button >編集</button>
        <button >削除</button>
    @endif

    <button>詳細</button>
</div>