<?php

namespace App\Data;

/**
 * Represents insurance as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Insurance-Form
 * 
 * Object is immutable
 */

final readonly class Insurance
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