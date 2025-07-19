<?php

namespace App\Data;

/**
 * Represents a sender as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Sender-Form
 * 
 * Object is immutable
 */

final readonly class Sender
{
    /**
     * @param string|null $companyName Optional company name.
     * @param string|null $name Optional sender's name.
     * @param string $email Sender's email address.
     * @param Address $address Sender's address.
     * @param string $firstName Sender's first name.
     * @param string $lastName Sender's last name.
     * @param string $phone Sender's phone number, must match provided regex for country as described in InPost API.
     */
    public function __construct(
        public ?string $companyName = null,
        public string $email,
        public string $phone,
        public Address $address,
        public string $firstName,
        public string $lastName,
    ) {}
    
    /**
     * Converts the Sender object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'company_name' => $this -> companyName,
            'email'        => $this -> email,
            'phone'        => $this -> phone,
            'address'      => $this -> address -> toArray(),
            'first_name'   => $this -> firstName,
            'last_name'    => $this -> lastName,
        ], fn($value) => $value !== null);
    }
}