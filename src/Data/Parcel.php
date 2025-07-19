<?php

namespace App\Data;

/**
 * Represents a parcel as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Parcels-Simple-Form
 * 
 * Object is immutable
 */

final readonly class Parcel
{
    public function __construct(
        public Dimensions $dimensions,
        public ?string $template = null,
        public Weight $weight,
        public ?bool $isNotStandard = null,
        public string $id
    ) {}

    /**
     * Converts the Parcel object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'dimensions'      => $this -> dimensions -> toArray(),
            'template'        => $this -> template,
            'weight'          => $this -> weight -> toArray(),
            'is_not_standard' => $this -> isNotStandard,
            'id'              => $this -> id,
        ], fn($value) => $value !== null);
    }
}