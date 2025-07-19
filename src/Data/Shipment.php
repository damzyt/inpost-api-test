<?php

declare(strict_types=1);

namespace App\Data;

use App\Data\Enums\ServiceType;
use App\Data\Enums\AdditionalService;

/**
 * Class Shipment
 * Represents shipment as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731061/Tworzenie+przesy+ki+w+trybie+uproszczonym
 * 
 * @package App\Data
 */
final class Shipment
{   
    /**
     * @param Recipient|null $receiver
     * @param Sender|null $sender
     * @param Parcel[] $parcels
     * @param CustomAttributes|null $customAttributes
     * @param Cod|null $cod
     * @param Insurance|null $insurance
     * @param string|null $reference
     * @param bool|null $isReturn
     * @param ServiceType $service
     * @param AdditionalService[]|null $additionalServices
     * @param string|null $externalCustomerId
     * @param bool|null $onlyChoiceOfOffer
     * @param string|null $mpk
     * @param string|null $comments
     */
    public function __construct(
        public readonly ?Recipient $receiver = null,
        public readonly ?Sender $sender = null,
        public readonly array $parcels,
        public readonly ?CustomAttributes $customAttributes = null,
        public readonly ?Cod $cod = null,
        public readonly ?Insurance $insurance = null,
        public readonly ?string $reference = null,
        public readonly ?bool $isReturn = null,
        public readonly ServiceType $service = ServiceType::INPOST_COURIER_STANDARD,
        public readonly ?array $additionalServices = null,
        public readonly ?string $externalCustomerId = null,
        public readonly ?bool $onlyChoiceOfOffer = null,
        public readonly ?string $mpk = null,
        public readonly ?string $comments = null
    ) {}

    /**
     * Converts the Shipment object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'receiver'             => $this -> receiver ?-> toArray(),
            'sender'               => $this -> sender ?-> toArray(),
            'parcels'              => array_map(fn($parcel) => $parcel -> toArray(), $this -> parcels),
            'custom_attributes'    => $this -> customAttributes ?-> toArray(),
            'cod'                  => $this -> cod ?-> toArray(),
            'insurance'            => $this -> insurance ?-> toArray(),
            'reference'            => $this -> reference,
            'isReturn'             => $this -> isReturn,
            'service'              => $this -> service -> value,
            'additional_services'  => $this -> additionalServices ? array_map(fn(AdditionalService $service) => $service -> value, $this -> additionalServices) : null,
            'external_customer_id' => $this -> externalCustomerId,
            'only_choice_of_offer' => $this -> onlyChoiceOfOffer,
            'mpk'                  => $this -> mpk,
            'comments'             => $this -> comments,
        ]);
    }
}