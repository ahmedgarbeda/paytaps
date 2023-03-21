<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;


class PaymentRefund extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function refund(Request $request)
    {
        // first the data in the prepare function is static
        // you have to use the data come from $request and auth user data to replace the fake data with it
        $preparedData = $this->paymentService->prepareRefundData();
        $response = $this->paymentService->sendRequest('/payment/request',$preparedData);

        // in the response you will find aparameter called payment_result with contain the payment status
        // if "A" then set you order heere
        return $response;
    }




}
