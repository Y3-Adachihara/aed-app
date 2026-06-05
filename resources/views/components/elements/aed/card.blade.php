@props([
    'aed' => null,
])
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-4 hover:shadow-md transition-shadow duration-200">
    <h3 class="text-xl font-bold text-gray-800 mb-2">
        {{ $aed->name }}
    </h3>
    <div class="text-sm text-gray-600 mb-4 space-y-1">
        <p class="flex items-center">
            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded mr-2">〒{{ $aed->postcode }}</span>
            <span>{{ $aed->prefecture }}{{ $aed->municipality }}{{ $aed->address }}</span>
        </p>
    </div>
    <div class="flex items-center justify-end space-x-2 pt-4 border-t border-gray-100">
        @if (auth()->user()->role == 'admin')
            <a href="{{ route('aed-edit-page', ['aedId' => $aed->id]) }}" class="px-3 py-1.5 text-sm font-medium text-amber-600 hover:bg-amber-50 rounded-md transition-colors">
                編集
            </a>
            <x-elements.form-button :action="route('aed-delete', ['aedId' => $aed->id])" method="DELETE" title="削除" confirm_message="この設置場所の登録を抹消しますか？" theme='delete'/>
        @endif
    </div>
    <a href="{{ route('aed-detail', ['aedId' => $aed->id]) }}" class="px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm transition-colors">
        詳細を見る
    </a>
</div>