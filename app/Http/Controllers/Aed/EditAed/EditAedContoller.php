<?php

namespace App\Http\Controllers\Aed\EditAed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditAedContoller extends Controller
{
    public function __invoke(Request $request, $aedId)
    {
        /*
        $aed = Aed::where('id', $aedId)->firstOrFail();
        $aed->delete();
        return redirect()->route('home');
        */
    }
}
