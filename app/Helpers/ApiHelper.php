<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ApiHelper
{
    // Live - upasdata
    // private static $apiKey = 'A450BD7D-DD86-4A54-A6A5-A971B64CC7AB';
    // Test - upastraindata
    private static $apiKey = '12A3A9C3-3D8F-4204-9737-3C4ADE94650F';
    private static $sanityUrl = 'http://192.168.110.10:57772/api/upastraindata/intimusapiservice/sanity';

    /**
     * Perform sanity check on the API.
     *
     * @return bool
     */
    public static function sanityCheck()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => self::$apiKey, // Add Authorization header
            ])->get(self::$sanityUrl);

            if ($response->ok()) {
                $data = $response->json();
                return isset($data['status']) && $data['status'] === 1;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Fetch member details from the API.
     *
     * @param string $memberId
     * @return array|null
     * @throws \Exception
     */
    public static function getMemberDetails(string $memberId)
    {
        $endpoint = "http://192.168.110.10:57772/api/upastraindata/intimusapiservice/getmemberdet";

        // Perform sanity check first
        if (!self::sanityCheck()) {
            throw new \Exception('Sanity check failed. API might be down.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => self::$apiKey, // Add Authorization header
            ])->get($endpoint, [
                'memid' => $memberId,
            ]);

            if ($response->ok()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch data. Error: ' . $response->body());
        } catch (\Exception $e) {
            throw new \Exception('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Fetch member transaction data from the API.
     *
     * @param string $memid
     * @param string $from
     * @param string $to
     * @param string $type
     * @return array
     */
    public static function fetchMemberTransactions(string $memid, string $from, string $to, string $type): array
    {
        $apiUrl = 'http://192.168.110.10:57772/api/upastraindata/intimusapiservice/getmembertrx';

        try {
          
            $response = Http::withHeaders([
                'Authorization' => self::$apiKey, // Add Authorization header
            ])->get($apiUrl, [
                'memid' => $memid,
                'from' => $from,
                'to' => $to,
                'type' => $type
            ]);

            if ($response->ok()) {
                return $response->json();
            }

            return ['error' => 'Failed to fetch data from the API.'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
