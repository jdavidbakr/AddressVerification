<?php

namespace jdavidbakr\AddressVerification;

use GuzzleHttp\Client;
use \Cache;
use \Carbon\Carbon;

class AddressVerificationService {
	static public function Verify(AddressRequest $request)
	{
		// Cache?
		if(config('address-verification.cache_time') > 0) {
			$key = md5(json_encode($request));
			return Cache::remember(
				$key,
				Carbon::now()->addDays(config('address-verification.cache_time')),
				function() use($request) {
					return AddressVerificationService::Query($request);
				});
		} else {
			return AddressVerificationService::Query($request);
		}
	}

	static private function Query(AddressRequest $request)
	{
		$client = new Client([
		    'base_url' => config('address-verification.intelligentsearch.base_uri'),
		]);
		$query = [
			'username'=>config('address-verification.intelligentsearch.username'),
			'password'=>config('address-verification.intelligentsearch.password'),
			'firmname'=>$request->firmname,
			'urbanization'=>$request->urbanization,
			'delivery_line_1'=>$request->delivery_line_1,
			'delivery_line_2'=>$request->delivery_line_2,
			'city_state_zip'=>$request->city_state_zip,
			'ca_codes'=>$request->ca_codes,
			'ca_filler'=>$request->ca_filler,
			'batchname'=>$request->batchname,
		];
		$response = $client->get(config('address-verification.intelligentsearch.uri'),[
				'query'=>$query,
			]);
		$response_data = new AddressResponse;
		$xml = $response->xml()->WsCorrectAddress;
		foreach(get_object_vars($response_data) as $key=>$value) {
			$response_data->$key = (string) $xml->$key;
		}
		return $response_data;
	}
}
?>