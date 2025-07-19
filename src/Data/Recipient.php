<?php

namespace App\Data;

use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents a recipient as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Receiver-Form
 * 
 * Object is immutable
 */

final readonly class Recipient implements ValidatableInterface
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
        public ?string $email = null,
        public ?Address $address = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $phone = null
    ) {}
    
    /**
     * Validates the recipient's data.
     * Throws an exception if any of the required fields are missing or invalid.
     * 
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {   
        if($this -> address instanceof ValidatableInterface) {
            $this -> address -> validate();
        }

        if(empty($this -> firstName) && empty($this -> lastName) && empty($this -> address)) {
            if(empty($this -> companyName)) {
                throw new InvalidArgumentException('company_name is required if no first_name, last_name and address is provided.');
            }
        }

        if(!empty($this -> companyName) && mb_strlen($this -> companyName) > 255) {
            throw new InvalidArgumentException('company_name must be less than 255 characters.');
        }

        if(!empty($this -> name) && mb_strlen($this -> name) > 255) {
            throw new InvalidArgumentException('name must be less than 255 characters.');
        }

        if(!empty($this -> email) && !filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email must be a valid email address.');
        }

        if(empty($this -> companyName) && empty($this -> email) && empty($this -> phone)) {
            if(empty($this -> firstName) || empty($this -> lastName)) {
                throw new InvalidArgumentException('first_name and last_name is required if no company_name, email and phone is provided.');
            }
        }

        if(!empty($this -> phone) && !empty($this -> address)) {
            $this -> validatePhoneNumber();
        }   
    }

    /**
     * Validates the phone number based on the recipient's address country code if provided.
     * 
     * @throws InvalidArgumentException
     */
    private function validatePhoneNumber(): void
    {
        $cleanedPhone = preg_replace('/[^\d]/', '', $this->phone);

        $rules = [
            'PL' => '/^\d{9}$/', 'FR' => '/^\d{9}$/', 'ES' => '/^\d{9}$/',
            'DE' => '/^\d{10,11}$/', 'BE' => '/^\d{8,9}$/', 'LU' => '/^\d{9}$/',
            'PT' => '/^\d{9}$/', 'AT' => '/^\d{7,13}$/', 'GB' => '/^\d{10}$/',
            'IT' => '/^\d{9,10}$/', 'NL' => '/^\d{10}$/', 'IE' => '/^\d{10}$/',
            'MC' => '/^\d{8,9}$/', 'CH' => '/^\d{9}$/', 'UA' => '/^\d{9}$/',
            'BY' => '/^\d{9}$/', 'BG' => '/^\d{8,9}$/', 'CY' => '/^\d{8}$/',
            'CZ' => '/^\d{9}$/', 'DK' => '/^\d{8}$/', 'EE' => '/^\d{7,8}$/',
            'FI' => '/^\d{7,10}$/', 'GR' => '/^\d{10}$/', 'HU' => '/^\d{8,9}$/',
            'LT' => '/^\d{8}$/', 'LV' => '/^\d{8}$/', 'MT' => '/^\d{8}$/',
            'SE' => '/^\d{9,10}$/', 'SK' => '/^\d{9,10}$/',
        ];

        $countryCode = $this -> address -> countryCode;

        if(!empty($countryCode) && isset($rules[$countryCode]) && !preg_match($rules[$countryCode], $cleanedPhone)) {
             throw new InvalidArgumentException(
                'Phone number "' . $this -> phone . '" is not valid for country ' . $countryCode . '.'
            );
        }
    }
    
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