<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;


class PayWithCard extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function initRequest(Request $request)
    {
        // first the data in the prepare function is static
        // you have to use the data come from $request and auth user data to replace the fake data with it
        $preparedData = $this->paymentService->prepareContent();
        $response = $this->paymentService->sendRequest('/payment/request',$preparedData);
        return redirect($response['redirect_url']);
    }

    public function initWithOwnForm()
    {
        $preparedData = $this->paymentService->preparePayWithOwnCardData();
        $response = $this->paymentService->sendRequest('/payment/request',$preparedData);

        return $response;
    }

    public function callback(Request $request)
    {
//        return json_encode($request->all());
    }

    public function callbackReturn(Request $request)
    {
        $data = $request->all();
        if (!$this->paymentService->is_valid_redirect($data)){
            return response()->json(['message' => 'invalid callback url']);
        }

        return $data;
        // the data variable contain the token and tran_ref attributes
        // which we need to save them in user profile for next payment transactions

        // then you have to update your order to set status true if you pay first time with order
        // if you only verify the card don't do any thing with order only save the needed data to user data
    }




}
