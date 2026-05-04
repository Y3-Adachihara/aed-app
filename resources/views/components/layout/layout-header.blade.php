@props([
	'view_name' => '',
	'top_title' => '',
])

@php
    $user_name = auth()->user()->name;
	if (auth()->user()->role == 'admin') {
		$user_name = '管理者' . $user_name;
	}
@endphp

<div>
	<header>
	    <h1>
	        <a href="{{ route('userHome-page') }}">AED設置場所案内アプリ</a>
	    </h1>

	    <p>{{ $user_name }}さん - {{ $view_name }}</p>

	    <nav>
			<ul>
				<li>
					<a href="{{ route('userinfo-page') }}">アカウント情報参照</a>
				</li>
				<li>
					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<button onclick="event.preventDefault(); this.closest('form').submit();">
							ログアウト
						</button>
					</form>
				</li>
			</ul>
	    </nav>
	</header>
	<div>
		<div>
			<h2>{{ $top_title }}</h2>
	    </div>
		{{ $slot }}
	</div>
</div>