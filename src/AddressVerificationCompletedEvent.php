<?php

namespace jdavidbakr\AddressVerification;

use jdavidbakr\AddressVerification\AddressResponse;

class AddressVerificationCompletedEvent
{
    public $response;

    public function __construct(AddressResponse $response)
    {
        $this->response = $response;
    }
}
