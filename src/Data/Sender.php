<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Sender
 * Represents a sender as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Sender-Form
 * 
 * @package App\Data
 */

final readonly class Sender
{
    /**
     * @param string|null $companyName
     * @param string|null $name
     * @param string $email
     * @param string $phone
     * @param Address|null $address
     * @param string|null $firstName
     * @param string|null $lastName
     */
    public function __construct(
        public readonly ?string $companyName = null,
        public readonly string $email,
        public readonly string $phone,
        public readonly ?Address $address = null,
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
    ) {}
    
    /**
     * Converts the Sender object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'company_name' => $this -> companyName,
            'email'        => $this -> email,
            'phone'        => $this -> phone,
            'address'      => $this -> address -> toArray(),
            'first_name'   => $this -> firstName,
            'last_name'    => $this -> lastName,
        ];
    }
}