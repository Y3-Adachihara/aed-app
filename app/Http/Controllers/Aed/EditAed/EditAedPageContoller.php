<?php

namespace App\Http\Controllers\Aed\EditAed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditAedPageContoller extends Controller
{
    public function __invoke(Request $request, $aedId)
    {
        $aed = Aed::where('id', $aedId)->firstOrFail();
        // $top_title = $aed->name;

        $view_name = "AEDアプリ | 詳細";

        // return view('aed.details', compact('view_name', 'top_title', 'aed'));
        return view('aed.details', compact('top_title', 'aed'));
    }
}
