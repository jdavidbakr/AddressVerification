<?php

namespace jdavidbakr\AddressVerification;

use Cache;
use Carbon\Carbon;
use SimpleXMLElement;
use GuzzleHttp\Client;
use jdavidbakr\AddressVerification\AddressVerificationCompletedEvent;
use jdavidbakr\AddressVerification\MissingIntelligentSearchCredentialsException;

class AddressVerificationService
{
    public static function Verify(AddressRequest $request)
    {
        // Bail if we don't have username and password
        // This will allow us to prevent its usage during tests
        if (config('services.intelligentsearch.username') == null) {
            throw new MissingIntelligentSearchCredentialsException;
        }
        // Cache?
        if (config('address-verification.cache_time') > 0) {
            $key = 'jdavidbakr/AddressVerification'.md5(json_encode($request));
            return Cache::remember(
                $key,
                Carbon::now()->addDays(config('address-verification.cache_time') ?? 15),
                function () use ($request) {
                    return AddressVerificationService::Query($request);
                }
            );
        } else {
            return AddressVerificationService::Query($request);
        }
    }

    private static function Query(AddressRequest $request)
    {
        $client = app(Client::class);
        $query = [
            'username' => config('services.intelligentsearch.username'),
            'password' => config('services.intelligentsearch.password'),
            'firmname' => $request->firmname,
            'urbanization' => $request->urbanization,
            'delivery_line_1' => $request->delivery_line_1,
            'delivery_line_2' => $request->delivery_line_2,
            'city_state_zip' => $request->city_state_zip,
            'ca_codes' => $request->ca_codes,
            'ca_filler' => $request->ca_filler,
            'batchname' => $request->batchname,
        ];
        $response = $client->get(
            'https://www.intelligentsearch.com/CorrectAddressWS/CorrectAddressWebService.asmx/wsCorrectA',
            [
                'query' => $query,
            ]
        );
        $body = $response->getBody();
        $xml = new SimpleXMLElement((string)$body);
        $address = $xml->WsCorrectAddress;
        if ($address->ReturnCodes != 1) {
            if ($address->ErrorCodes == 'xx') {
                throw new MissingIntelligentSearchCredentialsException($address->ErrorDesc);
            } else {
                throw new VerificationFailedException($address->ErrorDesc);
            }
        }

        $response_data = new AddressResponse;
        foreach (get_object_vars($response_data) as $key => $value) {
            $response_data->$key = (string) $address->$key;
        }
        event(new AddressVerificationCompletedEvent($response_data));
        return $response_data;
    }
}
