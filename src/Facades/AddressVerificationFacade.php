<?php

namespace jdavidbakr\AddressVerification\Facades;

use Illuminate\Support\Facades\Facade;

class AddressVerificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'address-verification';
    }
}
