<?php

namespace App\Http\Controllers\Aed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user_name = auth()->user()->name;
        if (auth()->user()->role == 'admin') {
            $user_name = '管理者' . $user_name;
        }

        $view_name = "AEDアプリ | ホーム";
        $top_title = "島田市 AED設置場所一覧";

        return view('index')
    }
}
