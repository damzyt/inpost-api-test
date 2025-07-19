<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Enums\ParcelTemplate;

/**
 * Class Parcel
 * Represents a parcel as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Parcels-Simple-Form
 * 
 * @package App\Data
 */
final readonly class Parcel
{
    public function __construct(
        public ?Dimensions $dimensions = null,
        public ?ParcelTemplate $template = null,
        public ?Weight $weight = null,
        public ?string $id = null,
        public ?bool $isNotStandard = null
    ) {}

    /**
     * Converts the Parcel object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'dimensions'      => $this -> dimensions ?-> toArray(),
            'template'        => $this -> template ?-> value,
            'weight'          => $this -> weight ?-> toArray(),
            'id'              => $this -> id,
            'is_not_standard' => $this -> isNotStandard
        ];
    }
}