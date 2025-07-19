<?php

namespace App\Data;

use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents dimensions as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Dimensions-Simple-Form
 * 
 * Object is immutable
 */
final readonly class Dimensions implements ValidatableInterface
{   
    public readonly string $unit;
    /**
     * @param float $height Height of the package.
     * @param float $length Length of the package.
     * @param float $width Width of the package.
     * @param string $unit Unit of measurement, default is 'mm'.
     */
    public function __construct(
        public ?float $height = null,
        public ?float $length = null,
        public ?float $width = null,
    ) {
        $this -> unit = 'mm';
    }

    public function validate(): void
    {
        if(!empty($this -> height)) {
            if($this -> height < 1) {
                throw new InvalidArgumentException('
                    Height must be greater or equal 1.
                ');
            }

            if($this -> height > 1000000) {
                throw new InvalidArgumentException('
                    Height must be less or equal 1000000.
                ');
            }
        }
        

        if(!empty($this -> length)) {
            if($this -> length < 1) {
                throw new InvalidArgumentException('
                    Length must be greater or equal 1.
                ');
            }

            if($this -> length > 1000000) {
                throw new InvalidArgumentException('
                    Length must be less or equal 1000000.
                ');
            }
        }

        if(!empty($this -> width)) {
            if($this -> width < 1) {
                throw new InvalidArgumentException('
                    Width must be greater or equal 1.
                ');
            }

            if($this -> width > 1000000) {
                throw new InvalidArgumentException('
                    Width must be less or equal 1000000.
                ');
            }
        }
    }

    /**
     * Converts the Dimensions object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'height' => (string)$this -> height,
            'length' => (string)$this -> length,
            'width'  => (string)$this -> width,
            'unit'   => $this -> unit,
        ], fn($value) => $value !== null);
    }
}