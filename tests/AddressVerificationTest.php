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

	public function testFacade()
	{
		$request = new \jdavidbakr\AddressVerification\AddressRequest;
		$request->delivery_line_1 = '1600 Pennsylvania Ave NW';
		$request->city_state_zip = 'Washington DC 20500';
		$result = AddressVerification::Verify($request);
		$this->assertEquals($result->ZipAddon, '20500-0003');
	}

	public function testMockingFacade()
	{
		$request = new \jdavidbakr\AddressVerification\AddressRequest;
		$request->delivery_line_1 = '1600 Pennsylvania Ave NW';
		$request->city_state_zip = 'Washington DC 20500';
		$request->ZipAddon = 'facade';
		AddressVerification::shouldReceive('Verify')
			->andReturn($request);
		$result = AddressVerification::Verify($request);
		$this->assertEquals($result->ZipAddon, 'facade');
	}
}