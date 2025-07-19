<?php

namespace App\Data;

use App\Validation\ValidatableInterface;

/**
 * Represents Cash on Delivery (COD) as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Cod-Form
 * 
 * Object is immutable
 */

final readonly class Cod implements ValidatableInterface
{
    /**
     * @param float $amount Amount for the COD.
     * @param string $currency Currency of the COD amount, default is 'PLN'.
     */
    public function __construct(
        public float $amount,
        public string $currency = 'PLN'
    ) {}

    public function validate(): void
    {
        if($this -> amount < 1) {
            throw new \InvalidArgumentException('
                COD amount must be greater or equal 1.
            ');
        }

        if($this -> amount > 1000000) {
            throw new \InvalidArgumentException('
                COD amount must be less or equal 1000000.
            ');
        }
    }

    /**
     * Converts the Cod object to an associative array.
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