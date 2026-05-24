@props([
	'view_name' => '',
	'top_title' => '',
])

@php
	// コントローラに書くべきなのは、画面ごとに変わるデータ。
	// どの画面でも同じ処理（ユーザネームは画面ごとに変わらない。管理者の判定も、どの画面でも同じ）をするのなら、変数として渡すのではなく、コンポーネントで直接判定して作った方が効率的。
    $user_name = auth()->user()->name;
	if (auth()->user()->role == 'admin') {
		$user_name = '管理者' . $user_name;
	}
@endphp

<div class="min-h-screen bg-gray-50 text-gray-800">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center space-x-3">
                <span class="text-blue-600 text-2xl">❤️‍🩹</span>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 hover:opacity-80 transition-opacity">
                    <a href="{{ route('home') }}">AED設置場所案内アプリ</a>
                </h1>
            </div>

            <div class="flex items-center space-x-6">
                <p class="text-sm text-gray-600 hidden md:block">
                    <span class="font-semibold text-gray-800">{{ $user_name }}</span> さん 
                    <span class="text-gray-400 mx-1">|</span> 
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs">{{ $view_name }}</span>
                </p>

                <nav>
                    <ul class="flex items-center space-x-4 text-sm font-medium">
                        <li>
                            <a href="{{ route('userinfo-page') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md hover:bg-gray-50 transition-colors">
                                アカウント情報
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-md transition-colors">
                                    ログアウト
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ $top_title }}
            </h2>
        </div>
        
        <div class="space-y-4">
            {{ $slot }}
        </div>
    </main>
</div>