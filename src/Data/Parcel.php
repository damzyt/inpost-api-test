<?php

namespace App\Data;

use App\Data\Enums\ParcelTemplate;
use App\Validation\ValidatableInterface;
use InvalidArgumentException;

/**
 * Represents a parcel as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Parcels-Simple-Form
 * 
 * Object is immutable
 */

final readonly class Parcel implements ValidatableInterface
{
    public function __construct(
        public ?Dimensions $dimensions = null,
        public ?ParcelTemplate $template = null,
        public ?Weight $weight = null,
        public ?string $id = null,
        public ?bool $isNotStandard = null
    ) {}
    
    /**
     * Validates the parcel's data.
     * Throws an exception if any of the required fields are missing or invalid.
     * 
     * @throws InvalidArgumentException
     */
    public function validate(): void
    {   
        if($this -> dimensions instanceof ValidatableInterface) {
            $this -> dimensions -> validate();
        }

        if($this -> weight instanceof ValidatableInterface) {
            $this -> weight -> validate();
        }

        if(empty($this -> template) && (empty($this -> dimensions) || empty($this -> weight))) {
            throw new InvalidArgumentException(
                'template is required if no dimensions and weight is provided or vice versa.'
            );
        }

        if(!empty($this -> template) && (!empty($this -> dimensions) || !empty($this -> weight))) {
            throw new InvalidArgumentException(
                'template cannot be used with dimensions or weight and vice versa.'
            );
        }

        
    }

    /**
     * Converts the Parcel object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'dimensions'      => $this -> dimensions ?-> toArray(),
            'template'        => $this -> template ?-> value,
            'weight'          => $this -> weight ?-> toArray(),
            'id'              => $this -> id,
            'is_not_standard' => $this -> isNotStandard
        ], fn($value) => $value !== null);
    }
}