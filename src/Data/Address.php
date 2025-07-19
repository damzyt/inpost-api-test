<?php

namespace App\Data;

/**
 * Represents an address as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Address-Form
 * 
 * Object is immutable
 */

final readonly class Address
{
    /**
     * @param string|null $line1 Optional first line of the address.
     * @param string $city City name.
     * @param string $buidlingNumber Building number.
     * @param string $countryCode Country code, default is 'PL'.
     * @param string $street Street name.
     * @param string $postCode Postal code in "00-000" format.
     */
    public function __construct(
        public ?string $line1 = null,
        public string $city,
        public string $buidlingNumber,
        public string $countryCode = 'PL',
        public string $street,
        public string $postCode,
    ) {}

    /**
     * Converts the Address object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'line1'           => $this -> line1,
            'city'            => $this -> city,
            'building_number' => $this -> buidlingNumber,
            'country_code'    => $this -> countryCode,
            'street'          => $this -> street,
            'post_code'       => $this -> postCode,
        ], fn($value) => $value !== null);
    }
}