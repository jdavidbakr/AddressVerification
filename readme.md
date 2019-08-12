# AddressVerification

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

### Upgrading from version 1.x

The following changes happened betweer 1.x and 2.0:

* The config information is not added to the `config/services.php` file instead of having its own file
* The package now throws exceptions and events instead of expecting you to handle it.

### How to use

A Laravel Service that uses intelligentsearch.com to validate mailing addresses.

This class sends an address to https://www.intelligentsearch.com/ via their API and updates the address with the most relevant result.  It is built to run within Laravel.

Note that in its current state it does not return multiple results.

#### To install:

```
composer require jdavidbakr/address-verification
```

The package is auto-discovered in Laravel.

Add the following section to `config/services.php`:

```
    'intelligentsearch'=>[
        'username'=>env('INTELLIGENTSEARCH_USERNAME'),
        'password'=>env('INTELLIGENTSEARCH_PASSWORD'),
        'cache_time'=>90, // in days.  Set to 0 to have no cache.
    ],
```

There will be a file now located at config/address-verification.php to set your intelligentsearch.com username and password.

#### To use:

Create an object of type \jdavidbakr\AddressVerification\AddressRequest and fill in the appropriate values.  Then call \jdavidbakr\AddressVerification\AddressVerificationService::Verify($request) with the cerated request.  You will receive back an object of type \jdavidbakr\AddressVerification\AddressVerificationResponse.

```
$request = new \jdavidbakr\AddressVerification\AddressRequest;
$request->delivery_line_1 = '1600 Pennsylvania Ave NW';
$request->city_state_zip = 'Washington DC 20500';
try {
    $result = \jdavidbakr\AddressVerification\AddressVerificationService::Verify($request);
    // Alternatively use the facade:
    $result = AddressVerification::Verify($request);
} catch(\jdavidbakr\AddressVerification\VerificationFailedException $e) {
    // Handle what to do if the verification failed
}
```

The request defaults to ca_codes of McRy which returns mixed case and enables street address parsing for no-match addresses.

You will receive an `AddressResponse` object if successful. The main properties you'll likely use in the response are:

* DeliveryLine1
* City
* State
* ZipAddon

#### Exceptions

The following exceptions can be thrown (all namespaced to the package):

* `VerificationFailedException` - you should watch for this exception and decide how to handle it.  Basically it means that you are not getting any data from the response.
* `MissingIntelligentSearchCredentialsException` - Fired if you haven't included credentials.

#### Events

* `AddressVerificationCompleted` - this is an event that is fired every time an address verification is completed.  Use it to check the `SearchesLeft` attribute, if you want to send a notification that it's running low.  It receives in its payload the `AddressResponse`.

View the [IntelligentSearch documentation](https://www.intelligentsearch.com/CorrectAddressWS/Documentation/CorrectAddress%20WebServices.pdf) for details about what all the returned fields are, as well as the request attributes.

[ico-version]: https://img.shields.io/packagist/v/jdavidbakr/address-verification.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jdavidbakr/address-verification.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jdavidbakr/address-verification
[link-downloads]: https://packagist.org/packages/jdavidbakr/address-verification
[link-author]: https://github.com/jdavidbakr
[link-contributors]: ../../contributors
