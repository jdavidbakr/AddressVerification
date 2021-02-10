<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Event;
use jdavidbakr\AddressVerification\AddressRequest;
use jdavidbakr\AddressVerification\AddressResponse;
use jdavidbakr\AddressVerification\AddressVerificationCompletedEvent;
use jdavidbakr\AddressVerification\AddressVerificationService;
use jdavidbakr\AddressVerification\VerificationFailedException;
use Orchestra\Testbench\TestCase;
use Psr\Http\Message\ResponseInterface;
use Spatie\ArrayToXml\ArrayToXml;

class AddressVerificationTest extends TestCase
{
    protected function getEnvironmentSEtup($app)
    {
        $app['config']->set('services.intelligentsearch', [
            'username' => 'the-intelligent-search-user',
            'password' => 'the-intelligent-search-password',
            'cache_time' => 30,
        ]);
    }

    /**
     * @test
     */
    public function it_verifies_an_address()
    {
        Event::fake();
        $request = new AddressRequest;
        $request->delivery_line_1 = '1600 Pennsylvania Ave NW';
        $request->city_state_zip = 'Washington DC 20500';
        $response = new AddressResponse;
        $response->ZipAddon = '20500-0003';
        $response->ReturnCodes = 1;
        $guzzle = Mockery::mock(Client::class);
        app()->instance(Client::class, $guzzle);
        $guzzle->shouldReceive('get')
            ->once()
            ->with(
                'https://www.intelligentsearch.com/CorrectAddressWS/CorrectAddressWebService.asmx/wsCorrectA',
                [
                    'query' => [
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
                    ]
                ]
            )->andReturn(Mockery::mock(ResponseInterface::class, [
                'getBody' => ArrayToXML::convert(['WsCorrectAddress' => (array)$response])
            ]));

        $result = AddressVerificationService::Verify($request);

        $this->assertEquals($result->ZipAddon, '20500-0003');
        Event::assertDispatched(AddressVerificationCompletedEvent::class);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_match_is_not_found()
    {
        $this->expectException(VerificationFailedException::class);
        $request = new AddressRequest;
        $request->delivery_line_1 = '1600 Pennsylvania Ave NW';
        $request->city_state_zip = 'Washington DC 20500';
        $response = new AddressResponse;
        $response->ZipAddon = '20500-0003';
        $response->ReturnCodes = -1;
        $guzzle = Mockery::mock(Client::class);
        app()->instance(Client::class, $guzzle);
        $guzzle->shouldReceive('get')
            ->once()
            ->with(
                'https://www.intelligentsearch.com/CorrectAddressWS/CorrectAddressWebService.asmx/wsCorrectA',
                [
                    'query' => [
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
                    ]
                ]
            )->andReturn(Mockery::mock(ResponseInterface::class, [
                'getBody' => ArrayToXML::convert(['WsCorrectAddress' => (array)$response])
            ]));

        $result = AddressVerificationService::Verify($request);

        $this->assertEquals($result->ZipAddon, '20500-0003');
    }
}
