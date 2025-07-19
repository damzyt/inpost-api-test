<?php

namespace App\Data;

use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Weight-Simple-Form
 * 
 * Object is immutable
 */

final readonly class Weight implements ValidatableInterface
{   
    public readonly string $unit;
    /**
     * @param float $amount Weight amount.
     * @param string $unit Unit of measurement, default is 'kg'.
     */
    public function __construct(
        public ?float $amount
    ) {
        $this -> unit = 'kg';
    }

    /**
     * Validates the weight amount.
     * Throws an exception if the amount is not within the valid range.
     * 
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {
        if(!empty($this -> amount)) {
            if($this -> amount < 1) {
                throw new InvalidArgumentException('
                    Weight must be greater or equal 1 kg.
                ');
            }

            if($this -> amount > 1000000) {
                throw new InvalidArgumentException('
                    Weight must be less or equal 1000000 kg.
                ');
            }
        }
    }

    /**
     * Converts the Weight object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'amount' => (string)$this -> amount,
            'unit'   => $this -> unit,
        ], fn($value) => $value !== null);
    }
}