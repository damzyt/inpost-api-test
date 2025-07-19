<?php

namespace App\Data;

use App\Data\Enums\SendingMethod;

/**
 * Represents custom attributes as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Custom-Attributes-Form
 * 
 * Object is immutable
 */

final readonly class CustomAttributes
{
    /**
     * @param string|null $targetPoint Target point for the package.
     * @param string|null $dropoffPoint Drop-off point for the package.
     * @param SendingMethod|null $sendingMethod Sending method, if applicable.
     */

    public function __construct(
        public ?string $targetPoint = null,
        public ?string $dropoffPoint = null,
        public ?SendingMethod $sendingMethod = null
    ) {}

    /**
     * Converts the CustomAttributes object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'target_point'   => $this -> targetPoint,
            'dropoff_point'  => $this -> dropoffPoint,
            'sending_method' => $this -> sendingMethod ?-> value,
        ], fn($value) => $value !== null);
    }
}