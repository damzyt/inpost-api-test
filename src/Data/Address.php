<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Address
 * Represents an Address as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Address-Form
 * 
 * @package App\Data
 */
final readonly class Address
{
    /** 
     * @param string|null $line1 Optional first line of the address.
     * @param string $city
     * @param string $buidlingNumber
     * @param string|null $countryCode Optional country code (ISO 3166-1 alpha-2).
     * @param string $street
     * @param string $postCode
     */
    public function __construct(
        public readonly ?string $line1 = null,
        public readonly string $city,
        public readonly string $buidlingNumber,
        public readonly ?string $countryCode = null,
        public readonly string $street,
        public readonly string $postCode,
    ) {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'line1'           => $this -> line1,
            'city'            => $this -> city,
            'building_number' => $this -> buidlingNumber,
            'country_code'    => $this -> countryCode,
            'street'          => $this -> street,
            'post_code'       => $this -> postCode,
        ];
    }
}