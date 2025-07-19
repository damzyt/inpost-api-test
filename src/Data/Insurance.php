<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Insurance
 * Represents insurance as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Insurance-Form
 * 
 * @package App\Data
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
            'amount'   => $this -> amount,
            'currency' => $this -> currency,
        ];
    }
}