<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Enums\ParcelTemplate;

/**
 * Class Parcel
 * Represents a parcel as defined in the InPost API.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Parcels-Simple-Form
 * @package App\Data
 */
final class Parcel
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
     * Transforms all parcel properties into an array format suitable for API requests.
     * Filters out null values and handles nested objects and enums appropriately.
     * 
     * @return array The parcel data as an associative array with API-compatible structure
     */
    public function toArray(): array
    {
        return array_filter([
            'dimensions'      => $this->dimensions?->toArray(),
            'template'        => $this->template?->value,
            'weight'          => $this->weight?->toArray(),
            'id'              => $this->id,
            'is_not_standard' => $this->isNotStandard
        ]);
    }
}