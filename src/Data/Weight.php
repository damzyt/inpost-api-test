<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Weight
 * Represents dimensions as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Weight-Simple-Form
 * @package App\Data
 */

final class Weight
{   
    /**
     * Weight constructor.
     * 
     * @param float $amount Weight amount.
     * @param string $unit Unit of Weight, default is 'kg'.
     */
    public function __construct(
        public readonly float $amount,
        public readonly string $unit = 'kg'
    ) {}

    /**
     * Converts the Weight object to an associative array.
     * 
     * Transforms weight properties into an array format suitable for API requests.
     * Returns the amount and unit for parcel weight specification.
     * 
     * @return array The weight data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'unit'   => $this->unit,
        ];
    }
}