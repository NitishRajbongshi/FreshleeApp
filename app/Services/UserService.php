<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserService {
    protected $userAddressUrl;
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        Log::info("user service init");
        $this->userAddressUrl = config('customconfig.USER_ADDRESS');
        $this->loginService = $loginService;
    }

    /**
     * Get user address by UID.
     */
    public function getUserAddress($uid)
    {
        try {
            $url = $this->userAddressUrl . '?uid=' . $uid;
            $accessToken = $this->loginService->getAccessToken(); // Reusing getAccessToken
            $response = Http::withHeaders([
                'x-access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->get($url, [
                //
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception("Failed to fetch user address! Response: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Error fetching user address:', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Failed to fetch user address.'];
        }
    }
}