<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Sender
 * Represents a sender as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Sender-Form
 * @package App\Data
 */

final class Sender
{
    /**
     * Sender constructor.
     * 
     * @param string $email
     * @param string $phone
     * @param Address|null $address
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $companyName
     */
    public function __construct(
        public readonly string $email,
        public readonly string $phone,
        public readonly ?Address $address = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $companyName = null,
    ) {}
    
    /**
     * Converts the Sender object to an associative array.
     * 
     * Transforms sender properties into an array format suitable for API requests.
     * Filters out null values and handles nested address object conversion.
     * 
     * @return array The sender data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return array_filter([
            'email'        => $this->email,
            'phone'        => $this->phone,
            'address'      => $this->address->toArray(),
            'first_name'   => $this->firstName,
            'last_name'    => $this->lastName,
            'company_name' => $this->companyName,
        ]);
    }
}