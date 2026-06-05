<?php

namespace App\Http\Controllers\Aed\EditAed;

use App\Http\Controllers\Controller;
use App\Http\Requests\Aed\EditRequest;
use App\Models\Aed;

class EditAedPageController extends Controller
{
    public function __invoke(EditRequest $request)
    {
        $aed = Aed::where('id', $request->aedId())->firstOrFail();

        $aed->name = $request->name();
        $aed->postcode = $request->postcode();
        $aed->prefecture = $request->prefecture();
        $aed->municipality = $request->municipality();
        $aed->address = $request->address();
        $aed->description = $request->description();

        // 少数第四位まで。第五位以降は切り捨て。
        $aed->latitude = floor($request->latitude() * 10000) / 10000;
        $aed->longitude = floor($request->longitude() * 10000) / 10000;
        
        $aed->save();
        return redirect()->route('home');
    }
}
