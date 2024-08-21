<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class PhoneController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function lookup(Request $request)
    {

        $phoneNumber = "+113062000";

        try {
            // Use the lookups method
            $result = $this->client->lookups->v1->phoneNumbers($phoneNumber)->fetch();

            // Extract relevant information
            $data = [
                'phoneNumber' => $result->phoneNumber,
                'countryCode' => $result->countryCode,
                'nationalFormat' => $result->nationalFormat,
                'callerName' => $result->callerName,
                'carrier' => $result->carrier,
                'url' => $result->url,
            ];

            return response()->json($data);
        } catch (\Twilio\Exceptions\RestException $e) {
            // Log error details and return a response
            \Log::error('Twilio Lookup API error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to fetch record'], 404);
        }
    }
}
