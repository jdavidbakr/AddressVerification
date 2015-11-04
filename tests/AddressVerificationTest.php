<?php

class AddressVerificationTest extends TestCase
{
	public function testVerifyAddress()
	{
		$request = new \jdavidbakr\AddressVerification\AddressRequest;
		$request->delivery_line_1 = '1600 Pennsylvania Ave NW';
		$request->city_state_zip = 'Washington DC 20500';
		$result = \jdavidbakr\AddressVerification\AddressVerificationService::Verify($request);
		$this->assertEquals($result->ZipAddon, '20500-0003');
	}
}