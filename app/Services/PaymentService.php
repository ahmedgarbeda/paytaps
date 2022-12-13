<?php

namespace App\Services;

class PaymentService
{
    const   PROFILE_ID = 108266,
        SERVER_KEY = 'SRJN6ZWZ2G-JGKTZBJJWW-JLL66Z2ML6',
        BASE_URL = 'https://secure-egypt.paytabs.com';

    public function prepareContent()
    {
        return [
            "profile_id" => self::PROFILE_ID,
            "tran_type"=> "sale",
            "tran_class"=> "ecom",
            "cart_id"=> "1212",
            "tokenise"=> "2",
            "show_save_card"=> true,
            "cart_currency"=> "EGP",
            "cart_amount"=> 1,
            "cart_description"=> "Description of the items/services",
            "paypage_lang"=> "en",
            "customer_details"=> [
                "name"=> "first last",
                "email"=> "email@domain.com",
                "phone"=> "0522222222",
                "street1"=> "address street",
                "city"=> "dubai",
                "state"=> "du",
                "country"=> "AE",
                "zip"=> "12345"
            ],
            "hide_shipping"=>true,
            "callback"=> 'http://paytaps.aplus-code.com/public/api/callback',
            "return"=> 'https://paytaps.aplus-code.com/public/api/callback-return'
        ];
    }

    public function preparePayWithTokenContent()
    {
        return [
            "profile_id" => self::PROFILE_ID,
            "tran_type"=> "sale",
            "tran_class"=> "recurring",
            "cart_id"=> "1222",
            "token"=> "2C4652BD67A3E831C6B390F463877AB8",
            "tran_ref"=> "TST2234601407318",
            "cart_currency"=> "EGP",
            "cart_amount"=> 12,
            "cart_description"=> "Description of the items/services",
            "callback"=> "https://paytaps.aplus-code.com/public/api/callback",
        ];
    }
public function preparePayWithOwnCardData()
    {
        return [
            "profile_id" => self::PROFILE_ID,
            "tran_type"=> "sale",
            "tran_class"=> "ecom",
            "cart_id"=> "1212",
            "tokenise"=> "2",
            "show_save_card"=> true,
            "cart_currency"=> "EGP",
            "cart_amount"=> 1,
            "cart_description"=> "Description of the items/services",
            "paypage_lang"=> "en",
            "hide_shipping"=>true,
            "callback"=> 'http://paytaps.aplus-code.com/public/api/callback',
            "return"=> 'https://paytaps.aplus-code.com/public/api/callback-return',
            "card_details"=> [
                "pan"=> "4111111111111111",
                "cvv"=> "123",
                "expiry_month"=> 12,
                "expiry_year"=> 25
            ]
        ];
    }

    public function prepareRefundData()
    {
        return[
            "profile_id"=> self::PROFILE_ID,
            "tran_type"=> "refund",
            "tran_class"=> "ecom",
            "tran_ref"=> "TST2008500000227",
            "cart_id"=> "897961dd-d91e-45a9-ac9e-d1b34d49bad9",
            "cart_description"=> "Dummy Order 4696563498614784",
            "cart_currency"=> "EGP",
            "cart_amount"=> 12
        ];
    }


    function sendRequest($url, $prepared_data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::BASE_URL.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CUSTOMREQUEST => isset($request_method) ? $request_method : 'POST',
            CURLOPT_POSTFIELDS => json_encode($prepared_data, true),
            CURLOPT_HTTPHEADER => array(
                'authorization:' . self::SERVER_KEY,
                'Content-Type:application/json'
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $response;
    }

    function is_valid_redirect($post_values)
    {

        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);

        // Sort form fields
        ksort($fields);

        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);

        $signature = hash_hmac('sha256', $query, self::SERVER_KEY);
        if (hash_equals($signature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }

}
