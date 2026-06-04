<?php

namespace App\Http\Controllers\Aed\EditAed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Aed;

class EditAedPageContoller extends Controller
{
    public function __invoke(Request $request, $aedId)
    {
        $aed = Aed::where('id', $aedId)->firstOrFail();
        $top_title = $aed->name;

        $view_name = "AEDアプリ | 詳細";

        return view('aed.edit', compact('view_name', 'top_title', 'aed'));
    }
}
