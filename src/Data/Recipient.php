<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Recipient
 * Represents a recipient as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Receiver-Form
 * 
 * @package App\Data
 */
final class Recipient
{   
    /**
     * @param string|null $companyName
     * @param string|null $name
     * @param string $email
     * @param Address $address
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     */
    public function __construct(
        public readonly ?string $companyName = null,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?Address $address = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $phone = null
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
        ]);
    }
}