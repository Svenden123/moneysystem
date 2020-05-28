<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function register(Request $request)
    {
        $payment = new Payment();
        $payment->sum = $request->get('sum');
        $payment->description = $request->get('description');
        $payment->save();
        return ['link'=>$payment->uuid];
    }


    public function form($uuid)
    {
        $payment = Payment::where('uuid', $uuid)->first();
        return view('form', ['payment' => $payment]);
    }


    public function pay($uuid,Request $request)
    {
        $is_valid_credit_card = function($s) {
            $s = strrev(preg_replace('/[^\d]/','',$s));
            $sum = 0;
            for ($i = 0, $j = strlen($s); $i < $j; $i++) {
                if (($i % 2) == 0) {
                    $val = $s[$i];
                } else {
                    $val = $s[$i] * 2;
                    if ($val > 9)  $val -= 9;
                }
                $sum += $val;
            }
            return (($sum % 10) == 0);
        };
        $payment = Payment::where('uuid', $uuid)->first();
        $card = $request->get('card');
        $result = 'fail';
        if($is_valid_credit_card($card)){
            $payment->status = 'success';
            $payment->save();
            $result = 'success';
        }
        return ['result'=>$result];
    }
}
