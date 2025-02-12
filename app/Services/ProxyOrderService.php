<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProxyOrderService
{
    protected $itemPriceZoneWiseCustomer;
    protected $placeOrderURL;
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        Log::info("Proxy order service init");
        $this->itemPriceZoneWiseCustomer = config('customconfig.ITEM_PRICE_ZONE_WISE_CUSTOMER');
        $this->placeOrderURL = config('customconfig.PLACE_ORDER');
        $this->loginService = $loginService;
    }

    /**
     * Get user address by UID.
     */
    public function getPriceListZoneWiseCustomer($pin)
    {
        try {
            $url = $this->itemPriceZoneWiseCustomer;
            $params = [
                'pin_code' => $pin,
            ];
            $accessToken = $this->loginService->getAccessToken(); // Reusing getAccessToken
            $response = Http::withHeaders([
                'x-access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->get($url, $params);
            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception("Failed to fetch item list! Response: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Error fetching item list:', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Failed to fetch item list.'];
        }
    }

    public function placeOrder($order)
    {
        try {
            $order = $order;
            $apiUrl = $this->placeOrderURL;
            $accessToken = $this->loginService->getAccessToken();
            Log::info("URL " . $apiUrl);
            Log::info("ORDER " . $order);
            Log::info("TOKEN " . $accessToken);
            // Make the POST request with JSON body
            $response = Http::withHeaders([
                'x-access-token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlfa2V5IjoiVmlkaGlpQlRZMmg2NXR5dTQ1eHV5cWF6emlpTG8iLCJleHAiOjE3MTIyMTUxNDZ9.sU4a9b2fyptnQ3lOV_hfkmPDtNzMnKmzPKk9f1MHxCw'
            ])->post($apiUrl, $order);
            Log::info("Status: " . $response);

            // Check if the request was successful
            if ($response->successful()) {
                // Decode the JSON response
                $responseData = $response->json();
                Log::info("response success with: " . $responseData);
                return response()->json($responseData);
            }
            // throw new \Exception("Failed to place order! Response: " . $response->body());




            // $url = 'http://43.205.45.246:8082/agriMarket/bookCustomersOrder';

            // // Define the request body
            // $data = [
            //     'phone_no' => '7002055225',
            //     'pin_code' => '781003',
            //     'delivery_address_cd' => '700205522520230710155922932083H',
            //     'order_dtls' => [
            //         ['item_cd' => '10101001', 'qty' => '6'],
            //         ['item_cd' => '10101005', 'qty' => '10'],
            //         ['item_cd' => '10101003', 'qty' => '4'],
            //     ],
            // ];

            // // Make the POST request with JSON body
            // $response = Http::withHeaders([
            //     'x-access-token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlfa2V5IjoiVmlkaGlpQlRZMmg2NXR5dTQ1eHV5cWF6emlpTG8iLCJleHAiOjE3NzA3MjM0NTF9.mpvEKIfycLqAAJT5oKnRdWY3ZUwSH2NtFLAoyB906pw',
            //     'Content-Type' => 'application/json', // Ensure the API expects JSON
            // ])->post($url, $data);
            // Log::info("responseeee: " . $response);
            // // Check if the request was successful
            // if ($response->successful()) {
            //     // Decode the JSON response
            //     $responseData = $response->json();

            //     // Return the response (or process it as needed)
            //     return response()->json($responseData);
            // } else {
            //     // Handle the error
            //     return response()->json([
            //         'error' => 'Failed to book order',
            //         'status' => $response->status(),
            //         'message' => $response->body(),
            //     ], $response->status());
            // }
        } catch (\Exception $e) {
            Log::error('Error to place order:', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Failed to place user order.'];
        }
    }
}
