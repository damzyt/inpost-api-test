<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Address
 * Represents an Address as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Address-Form
 * @package App\Data
 */
final class Address
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
        public readonly string $city,
        public readonly string $buidlingNumber,
        public readonly string $street,
        public readonly string $postCode,
        public readonly ?string $line1 = null,
        public readonly ?string $countryCode = null
    ) {}

    /**
     * Converts the Address object to an associative array.
     * 
     * Transforms all address properties into an array format suitable for API requests.
     * Filters out null values and maps property names to API-compatible field names.
     *
     * @return array The address data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return array_filter([
            'city'            => $this->city,
            'building_number' => $this->buidlingNumber,
            'street'          => $this->street,
            'post_code'       => $this->postCode,
            'line1'           => $this->line1,
            'country_code'    => $this->countryCode
        ]);
    }
}