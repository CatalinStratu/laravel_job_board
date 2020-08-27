<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pricing;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Checkout
    public function checkout($slug){
        $package = Pricing::where('package_slug', $slug)->first();
        return view('checkout', compact('title', 'package'));
    }

    public function checkoutPost(Request $request, $package_id){

        $user = Auth::user();
        $package = Pricing::find($package_id);
        $gateway = $request->gateway;
        $currency = get_option('currency_sign');

        $transaction_id = 'tran_'.time().str_random(6);
        // get unique recharge transaction id
        while( ( Payment::whereLocalTransactionId($transaction_id)->count() ) > 0) {
            $transaction_id = 'reid'.time().str_random(5);
        }
        $transaction_id = strtoupper($transaction_id);

        $paymentData = [
            'user_id'           => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'package_name'      => $package->package_name,
            'package_id'        => $package_id,
            'amount'            => $package->price,
            'premium_job'       => $package->premium_job,
            'payment_method'    => $gateway,
            'status'            => 'initial',
            'currency'          => $currency,
            'local_transaction_id'  => $transaction_id,
        ];

        $payment = Payment::create($paymentData);
        return redirect(route('payment', $payment->local_transaction_id));
    }

    //Succes payment
    public function paymentSuccess($transaction_id = null){
        if ( ! $transaction_id){
            abort(404);
        }
        $title = "Thank you";
        $type = 'success';
        $msg = "Your payment has been success";
        return view('notice', compact('title', 'type','msg'));
    }

    //Cancelled payment
    public function paymentCancelled($transaction_id = null){
        if ( ! $transaction_id){
            abort(404);
        }
        $title = "Payment has been cancelled";
        $msg = "Your payment has been cancelled";
        return view('notice', compact('title', 'msg'));
    }
}
