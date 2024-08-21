<?php

namespace App\Services;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');

        if (empty($sid) || empty($token)) {
            throw new \Exception('Twilio credentials are missing.');
        }

        $this->client = new Client($sid, $token);
    }

    public function lookup(string $phoneNumber)
    {
        try {
            // Call lookupPhoneNumber to fetch phone details
            $result = $this->lookupPhoneNumber($phoneNumber);

            $isReal = $this->isPhoneNumberReal($result);

            return response()->json([
                'data' => $result,
                'is_real' => $isReal,
            ]);
        } catch (\Twilio\Exceptions\RestException $e) {
            \Log::error('Twilio Lookup API error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to fetch record'], 404);
        }
    }

    /**
     * Check if the phone number is valid based on the response from Twilio.
     *
     * @param \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberInstance $result
     * @return bool
     */
    protected function isPhoneNumberReal($result): bool
    {
        // Check if essential fields are populated
        return !empty($result->phoneNumber) &&
            !empty($result->countryCode) &&
            !empty($result->nationalFormat);
    }

    /**
     * Perform the actual lookup of the phone number.
     *
     * @param string $phoneNumber
     * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberInstance
     */
    protected function lookupPhoneNumber(string $phoneNumber)
    {
        return $this->client->lookups->v1->phoneNumbers($phoneNumber)->fetch();
    }
}
