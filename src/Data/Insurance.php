<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Insurance
 * Represents insurance as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Insurance-Form
 * @package App\Data
 */

final class Insurance
{   
    /**
     * Insurance constructor.
     * 
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
     * Transforms insurance properties into an array format suitable for API requests.
     * Returns the amount and currency for shipment insurance configuration.
     * 
     * @return array The insurance data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
    }
}