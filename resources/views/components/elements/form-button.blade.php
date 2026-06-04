@props([
    'action' => null,
    'method' => null,
    'title' => null,
    'confirm_message' => null,
    'theme' => 'default',
])
@php
    $themeClass = match($theme) {
        'edit' => 'text-amber-600 hover:bg-amber-50',
        'delete' => 'text-red-600 hover:bg-red-50',
        'default' => 'text-white bg-blue-600 hover:bg-blue-700 shadow-sm',
    };
    $baseClass = 'px-3 py-1.5 text-sm font-medium rounded-md transition-colors';
@endphp

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm_message }}')">
    @method($method)    {{-- method()の中は、自動的にphpと解釈される --}}
    @csrf
    
    {{ $slot }}
    
    <button type="submit" class="{{ $baseClass }} {{ $themeClass }}">
        {{ $title }}
    </button>
</form>