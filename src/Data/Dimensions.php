<?php

namespace App\Data;

/**
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Dimensions-Simple-Form
 * 
 * Object is immutable
 */
final readonly class Dimensions
{   
    /**
     * @param float $height Height of the package.
     * @param float $length Length of the package.
     * @param float $width Width of the package.
     * @param string $unit Unit of measurement, default is 'mm'.
     */
    public function __construct(
        public float $height,
        public float $length,
        public float $width,
        public string $unit = 'mm'
    ) {}

    /**
     * Converts the Dimensions object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'height' => (string)$this -> height,
            'length' => (string)$this -> length,
            'width'  => (string)$this -> width,
            'unit'   => $this -> unit,
        ];
    }
}