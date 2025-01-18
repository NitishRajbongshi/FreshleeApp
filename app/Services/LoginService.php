<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginService
{
    protected $tokenUrl;
    protected $accessToken;
    protected $tokenExpiry;

    protected $generateOTP;
    protected $verifyOTP;

    public function __construct()
    {
        $this->tokenUrl = config('customconfig.ACCESS_TOKEN');
        $this->generateOTP = config('customconfig.GENERATE_OTP');
        $this->verifyOTP = config('customconfig.VERIFY_OTP');
    }

    /**
     * Generate a new access token.
     */
    public function generateAccessToken()
    {
        $username = config('customconfig.UserName');
        $password = config('customconfig.Password');
        $response = Http::withBasicAuth($username, $password)->get($this->tokenUrl);
        if ($response->successful()) {
            $data = $response->json();
            $this->accessToken = $data['token'] ?? null;
            Session::put('accessToken', $this->accessToken);
            $this->tokenExpiry = now()->addHours(6);;
            Log::info('Access Token: ' . $this->accessToken);
            Log::info('Token expiration: ' . $this->tokenExpiry);
            return $this->accessToken;
        }

        throw new \Exception("Failed to generate access token!");
    }

    /**
     * Get the access token, generate a new one if expired.
     */
    public function getAccessToken()
    {
        $this->accessToken = Session::get('accessToken');
        if (!$this->accessToken || now()->greaterThan($this->tokenExpiry)) {
            Log::info('Access token expired!');
            return $this->generateAccessToken();
        }
        return $this->accessToken;
    }

    /**
     * Generate OTP using the access token.
     */
    public function generateOtp($phoneNumber)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withHeaders([
                'x-access-token' => $accessToken,
            ])->get($this->generateOTP, [
                'ph_no' => $phoneNumber,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception("Failed to generate OTP! Response: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Error generating OTP:', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Failed to generate OTP.'];
        }
    }


    /**
     * Verify OTP.
     */
    public function validateOtp($otp, $phoneNumber)
    {
        try {
            $accessToken = $this->getAccessToken();
            $url = $this->verifyOTP . '?otp=' . $otp . '&ph_no=' . $phoneNumber;
            $response =  Http::withHeaders([
                'x-access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, [
                //
            ]);
            if ($response->successful()) {
                return $response->json();
            }
            throw new \Exception("Failed to verify OTP! Response: " . $response->body());
        } catch (\Exception $e) {
            Log::error('Error verifying OTP:', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => 'Failed to verify OTP.'];
        }
    }
}
