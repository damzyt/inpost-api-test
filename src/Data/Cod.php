<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Cod
 * Represents COD as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Cod-Form
 * 
 * @package App\Data
 */
final readonly class Cod
{
    /**
     * @param float $amount Amount for the COD.
     * @param string $currency Currency of the COD amount, default is 'PLN'.
     */
    public function __construct(
        public readonly float $amount,
        public readonly string $currency = 'PLN'
    ) {}

    /**
     * Converts the Cod object to an associative array.
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