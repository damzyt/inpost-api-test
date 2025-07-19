<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Weight
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Weight-Simple-Form
 * 
 * @package App\Data
 */

final readonly class Weight
{   
    /**
     * @param float|null $amount Weight amount.
     * @param string $unit Unit of Weight, default is 'kg'.
     */
    public function __construct(
        public readonly ?float $amount,
        public readonly string $unit = 'kg'
    ) {}

    /**
     * Converts the Weight object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount' => $this -> amount,
            'unit'   => $this -> unit,
        ];
    }
}