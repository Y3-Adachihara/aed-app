@props([
    'action' => null,
    'method' => null,
    'title' => null,
    'confirm_message' => null,
])
<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm_message }}')">
    @method($method)    // method()の中は、自動的にphpと解釈される
    @csrf
    
    <button type="submit">
        {{ $title }}
    </button>
</form>