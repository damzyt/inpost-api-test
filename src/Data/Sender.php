<?php

namespace App\Data;

use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents a sender as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Sender-Form
 * 
 * Object is immutable
 */

final readonly class Sender implements ValidatableInterface
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
        public ?Address $address = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
    ) {}
    
    /**
     * Validates the sender's data.
     * Throws an exception if any of the required fields are missing or invalid.
     * 
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {   
        if($this -> address instanceof ValidatableInterface) {
            $this -> address -> validate();
        }

        if(empty($this -> firstName) && empty($this -> lastName) && empty($this -> companyName)) {
            throw new InvalidArgumentException('company_name is required if no first_name and last_name is provided.');
        }

        if(!empty($this -> companyName) && mb_strlen($this -> companyName) > 255) {
            throw new InvalidArgumentException('company_name must be less than 255 characters.');
        }

        if(!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email must be a valid email address.');
        }

        if(mb_strlen($this -> phone) !== 9) {
            throw new InvalidArgumentException('phone must be 9 characters.');
        }

        if(empty($this -> companyName) && (empty($this -> firstName) || empty($this -> lastName))) {
            throw new InvalidArgumentException('first_name and last_name is required if no company_name is provided.');
        }
    }
    
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