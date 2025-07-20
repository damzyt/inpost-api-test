<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Dimensions
 * Represents dimensions as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Dimensions-Simple-Form
 * @package App\Data
 */
final class Dimensions
{   
    /**
     * @param float $height
     * @param float $length
     * @param float $width
     * @param string $unit Unit of Dimensions, default is 'mm'.
     */
    public function __construct(
        public readonly ?float $height = null,
        public readonly ?float $length = null,
        public readonly ?float $width = null,
        public readonly string $unit = 'mm'
    ) {}

    /**
     * Converts the Dimensions object to an associative array.
     * 
     * Transforms all dimension properties into an array format suitable for API requests.
     * Filters out null values while preserving the unit specification.
     * 
     * @return array The dimensions data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return array_filter([
            'height' => $this->height,
            'length' => $this->length,
            'width'  => $this->width,
            'unit'   => $this->unit,
        ]);
    }
}