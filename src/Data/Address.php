<?php

namespace App\Data;

use App\Validation\ValidatableInterface;

/**
 * Represents an address as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Address-Form
 * 
 * Object is immutable
 */

final readonly class Address implements ValidatableInterface
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
        public ?string $countryCode = null,
        public string $street,
        public string $postCode,
    ) {}

    public function validate(): void
    {
        if(!empty($this -> line1) && mb_strlen($this -> line1) > 255) {
            throw new \InvalidArgumentException('
                line1 must be less than 255 characters.
            ');
        }

        if(mb_strlen($this -> city) > 255) {
            throw new \InvalidArgumentException('
                city must be less than 255 characters.
            ');
        }

        if(mb_strlen($this -> buidlingNumber) > 255) {
            throw new \InvalidArgumentException('
                building_number must be less than 255 characters.
            ');
        }

        if(mb_strlen($this -> street) > 255) {
            throw new \InvalidArgumentException('
                street must be less than 255 characters.
            ');
        }

        if(!preg_match('/^\d{2}-\d{3}$/', $this -> postCode)) {
            throw new \InvalidArgumentException('
                post_code must be in "00-000" format.
            ');
        }
    }

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