<?php

namespace App\Http\Controllers;

use App\GuestTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestTransactionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function save()
    {
        $guestTransChk = GuestTransaction::where(['reservation_id' => request('reservation_id'),
                                              'transaction_id' => request('transaction_id')
                                              ])->get();
        if(count($guestTransChk) > 0){
            return response()->json(["message" => "The given data is invalid.",
            "errors" => ["room_id" => ["Transaction has already been assigned."]]], 422);
        }


        $addDetails = array("user_id" => Auth::user()->id);
        request()->merge($addDetails);

        $vData = $this->validate(request(),[
            'reservation_id' => 'required|numeric',
            'transaction_id' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);

        $guestTrans = GuestTransaction::create($vData);
        return response()->json($guestTrans, 200);
    }
}
