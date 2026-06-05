@props([
    'aed' => null,
    'view_name' => null,
    'top_title' => null,
])
<x-layout.layout>
    <x-layout.layout-header view_name="{{ $view_name }}" top_title="{{ $top_title }}">
        <x-elements.form-button action="{{ route('aed-edit', ['todoId' => $aed->todoId]) }}" method="PUT" title="変更を保存する" confirm_message="この内容で確定しますか？" theme="edit">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">施設名</label>
                <input type="text" name="name" value="{{ $aed->name }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">郵便番号</label>
                <input type="text" name="postcode" value="{{ $aed->postcode }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">県</label>
                <input type="text" name="prefecture" value="{{ $aed->prefecture }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">市町村</label>
                <input type="text" name="municipality" value="{{ $aed->municipality }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">番地</label>
                <input type="text" name="address" value="{{ $aed->address }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">建物内の場所の説明</label>
                <input type="text" name="description" value="{{ $aed->description }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">緯度</label>
                <input type="text" name="latitude" value="{{ $aed->latitude }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">経度</label>
                <input type="text" name="longitude" value="{{ $aed->longitude }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        
        </x-elements.form-button>

        <a href="{{ route('home') }}">戻る</a>

    </x-layout.layout-header>
</x-layout.layout>