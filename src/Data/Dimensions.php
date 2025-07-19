<?php

declare(strict_types=1);

namespace App\Data;

/**
 * Class Dimensions
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Dimensions-Simple-Form
 * 
 * @package App\Data
 */
final readonly class Dimensions
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
     * @return array
     */
    public function toArray(): array
    {
        return [
            'height' => $this -> height,
            'length' => $this -> length,
            'width'  => $this -> width,
            'unit'   => $this -> unit,
        ];
    }
}