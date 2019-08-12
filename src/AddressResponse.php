<?php

namespace jdavidbakr\AddressVerification;

class AddressResponse
{
    public $StreetNumber;
    public $PreDirectional;
    public $StreetName;
    public $StreetSuffix;
    public $PostDirectional;
    public $SecondaryDesignation;
    public $SecondaryNumber;
    public $City;
    public $State;
    public $ZipAddon;
    public $Zip;
    public $Addon;
    public $LOTNumber;
    public $DPCCheckdigit;
    public $RecordType;
    public $DeliveryLine1;
    public $DeliveryLine2;
    public $LastLine;
    public $LACS;
    public $CarrierRoute;
    public $PMBDesignator;
    public $FirmRecipient;
    public $Urbanization;
    public $CountyName;
    public $CountyNumber;
    public $Filler;
    public $GeoTLID;
    public $GeoMatchFlag;
    public $GeoTract;
    public $GeoBlock;
    public $GeofLat;
    public $GeotLat;
    public $GeofLong;
    public $GeotLong;
    public $GeoAddonEnd;
    public $GeoAddonStart;
    public $GeoRetCode;
    public $GeoErrCodes;
    /**
     * Pay attention to Return Codes for value = 1 (success) or -1 (failure)
     * @var [type]
     */
    public $ReturnCodes;
    public $ErrorCodes;
    public $ErrorDesc;
    public $SearchesLeft;
}
