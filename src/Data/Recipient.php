<?php

namespace App\Data;

/**
 * Represents a recipient as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Receiver-Form
 * 
 * Object is immutable
 */

final readonly class Recipient
{   
    /**
     * @param string|null $companyName Optional company name,
     * @param string|null $name Optional recipient's name.
     * @param string $email Recipient's email address.
     * @param Address $address Recipient's address.
     * @param string $firstName Recipient's first name.
     * @param string $lastName Recipient's last name.
     * @param string $phone Recipient's phone number, must match provided regex for country as descriped in InPost API.
     */
    public function __construct(
        public ?string $companyName = null,
        public ?string $name = null,
        public string $email,
        public Address $address,
        public string $firstName,
        public string $lastName,
        public string $phone
    ) {}
    
    /**
     * Converts the Recipient object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'company_name' => $this -> companyName,
            'name'         => $this -> name,
            'email'        => $this -> email,
            'address'      => $this -> address -> toArray(),
            'first_name'   => $this -> firstName,
            'last_name'    => $this -> lastName,
            'phone'        => $this -> phone,
        ], fn($value) => $value !== null);
    }
}