# AddressVerification

## A Laravel Service that uses intelligentsearch.com to validate mailing addresses.

This class sends an address to https://www.intelligentsearch.com/ via their API and updates the address with the most relevant result.  It is built to run within Laravel.

Note that in its current state it does not return multiple results.

To install:

```
composer install jdavidbakr/AddressVerification
```

Add to your config/app.php file 'providers' array:

```
jdavidbakr\AddressVerification\AddressVerificationServiceProvider::class
```

Then publish the config file:

```
php artisan vendor:publish
```

There will be a file now located at config/address-verification.php to set your intelligentsearch.com username and password.

To use:

Create an object of type \jdavidbakr\AddressVerification\AddressRequest and fill in the appropriate values.  Then call \jdavidbakr\AddressVerification\AddressVerificationService::Verify($request) with the cerated request.  You will receive back an object of type \jdavidbakr\AddressVerification\AddressVerificationResponse.

```
$request = new \jdavidbakr\AddressVerification\AddressRequest;
$request->delivery_line_1 = '1600 Pennsylvania Ave NW';
$request->city_state_zip = 'Washington DC 20500';
$result = \jdavidbakr\AddressVerification\AddressVerificationService::Verify($request);
```

The request defaults to ca_codes of McRy which returns mixed case and enables street address parsing for no-match addresses.

The main properties you'll likely use in the response are:

* ReturnCodes - this will be 1 for success, -1 for failure
* DeliveryLine1
* City
* State
* ZipAddon

View the [https://www.intelligentsearch.com/CorrectAddressWS/Documentation/CorrectAddress%20WebServices.pdf](IntelligentSearch documentation) for details about what all the returned fields are, as well as the request attributes.
