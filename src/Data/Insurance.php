<?php

namespace App\Data;

use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents insurance as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Insurance-Form
 * 
 * Object is immutable
 */

final readonly class Insurance implements ValidatableInterface
{   
    /**
     * @param float $amount Insurance amount.
     * @param string $currency Currency of the insurance amount, default is 'PLN'.
     */
    public function __construct(
        public float $amount,
        public string $currency = 'PLN'
    ) {}
    
    /**
     * Validates the insurance amount.
     * Throws an exception if the amount is not within the valid range.
     * 
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        if($this -> amount < 1) {
            throw new InvalidArgumentException('
                Insurance amount must be greater or equal 1.
            ');
        }

        if($this -> amount > 1000000) {
            throw new InvalidArgumentException('
                Insurance amount must be less or equal 1000000.
            ');
        }
    }

    /**
     * Converts the Insurance object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount'   => (string)$this -> amount,
            'currency' => $this -> currency,
        ];
    }
}