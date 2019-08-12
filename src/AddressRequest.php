<?php

namespace jdavidbakr\AddressVerification;

class AddressRequest
{
    /**
     * (Optional) firm or recipient name for input address
     * @var string
     */
    public $firmname = '';
    /**
     * (Optional) urbanization name (Puerto Rico addresses only)
     * @var string
     */
    public $urbanization = '';
    /**
     * input address line 1 information
     * @var string
     */
    public $delivery_line_1 = '';
    /**
     * (Optional) input address line 2 information
     * @var string
     */
    public $delivery_line_2 = '';
    /**
     * input address city, state, ZIP information
     * (municipality, province, postal code for Canada)
     * @var [type]
     */
    public $city_state_zip = '';
    /**
     * (Optional) additional codes for address parsing (see Remarks)
     * @var string
     */
    public $ca_codes = 'McRy';
    /**
     * (Optional) internal use (see Remarks)
     * @var string
     */
    public $ca_filler = ' ';
    /**
     * (Optional) name of batch space, if one exists, to which the specified address belongs to (this option is
     * enabled for ability to produce ps3553 USPS forms).
     * @var string
     */
    public $batchname = '';

    /**
     * Remarks
     *	By default, output address is returned in capital letters. To enable mixed case output, pass “Mc” in the leftmost two
     *	available characters of ca_codes.
     *	To enable street address parsing for no-match addresses, pass “Ry” in the leftmost two available characters of
     *	ca_codes.
     *	DPV and LACSLink™ output data is returned through Filler output field (see sample output for wsCorrectA function
     *	call)
     *	[Ex. CA_CODES = McRy
     */
}
