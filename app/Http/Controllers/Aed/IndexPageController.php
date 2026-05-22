<?php

namespace App\Http\Controllers\Aed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aed;

class IndexPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $view_name = "AEDアプリ | ホーム";
        $top_title = "島田市 AED設置場所一覧";

        $aeds = Aed::all();
        return view('aed.index', compact('view_name', 'top_title', 'aeds'));
    }
}
