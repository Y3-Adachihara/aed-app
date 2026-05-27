<?php

namespace App\Http\Controllers\Aed\DeleteAed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aed;

class DeleteAedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $aedId)
    {
        $aed = Aed::where('id', $aedId)->firstOrFail();
        $aed->delete();
        return redirect()->route('home');
    }
}
