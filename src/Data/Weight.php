<?php

namespace App\Data;

/**
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Weight-Simple-Form
 * 
 * Object is immutable
 */

final readonly class Weight
{
    /**
     * @param float $amount Weight amount.
     * @param string $unit Unit of measurement, default is 'kg'.
     */
    public function __construct(
        public float $amount,
        public string $unit = 'kg'
    ) {}

    /**
     * Converts the Weight object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value' => (string)$this -> amount,
            'unit'  => $this -> unit,
        ];
    }
}