<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initializePayment(Request $request)
    {
        $url = "https://api.paystack.co/transaction/initialize";
        $callback_url = "https://laravel.com/callback-url"; 
        $fields = [
            'email' => $request->input('email'),
            'amount' => $request->input('amount') * 100,
            'callback_url' => $callback_url,
            'metadata' => json_encode([
                'custom_fields' => [
                    [
                        'display_name' => 'Domain',
                        'variable_name' => 'domain',
                        'value' => $request->input('domain'),
                    ],
                ],
            ]),
        ];

        $fields_string = http_build_query($fields);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['error' => $err], 500);
        } else {
            return response()->json(json_decode($response, true));
        }
    }

    public function verifyTransaction(Request $request)
    {
        $reference = $request->input('reference');
        $url = "https://api.paystack.co/transaction/verify/{$reference}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $result = json_decode($response, true);
            if ($result['status'] && $result['data']['status'] === 'success') {
                // Payment was successful
                // Deliver value to the customer
                return response()->json(['status' => 'success', 'message' => 'Payment was successful']);
            } else {
                // Payment was not successful
                return response()->json(['status' => 'failed', 'message' => 'Payment failed']);
            }
        } else {
            // Error occurred
            return response()->json(['status' => 'error', 'message' => 'Payment verification failed']);
        }
    }
    public function listTransactions()
{
    $url = 'https://api.paystack.co/transaction';
    $authorization = 'Bearer ' . env('PAYSTACK_SECRET_KEY');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $authorization",
        "Cache-Control: no-cache",
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return response()->json(['error' => $error], 500);
    }

    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['data']) && is_array($data['data'])) {
        // Reverse the order of transactions
        $transactions = array_reverse($data['data']);
    } else {
        $transactions = [];
    }

    return response()->json($transactions);
}

}
