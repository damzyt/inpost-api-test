<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Enums\SendingMethod;

/**
 * Class CustomAttributes
 * Represents custom attributes as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Custom-Attributes-Form
 * 
 * @package App\Data
 */
final readonly class CustomAttributes
{
    /**
     * @param string|null $targetPoint
     * @param string|null $dropoffPoint
     * @param SendingMethod|null $sendingMethod
     */

    public function __construct(
        public readonly ?string $targetPoint = null,
        public readonly ?string $dropoffPoint = null,
        public readonly ?SendingMethod $sendingMethod = null
    ) {}

    /**
     * Converts the CustomAttributes object to an associative array.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'target_point'   => $this -> targetPoint,
            'dropoff_point'  => $this -> dropoffPoint,
            'sending_method' => $this -> sendingMethod ?-> value,
        ];
    }
}