<?php

namespace App\Data;

use App\Data\Enums\AdditionalService;
use App\Data\Enums\ServiceType;

/**
 * Represents shipment data as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731061/Tworzenie+przesy+ki+w+trybie+uproszczonym
 * 
 * Object is immutable
 */

final readonly class ShipmentData
{       
    /**
     * @param Recipient $recipient
     * @param Sender $sender
     * @param Parcel[] $parcels
     * @param CustomAttributes|null $customAttributes
     * @param Cod|null $cod
     * @param Insurance $insurance
     * @param string $reference
     * @param bool|null $isReturn
     * @param ServiceType $service
     * @param AdditionalService[]|null $additionalServices
     * @param string|null $externalCustomerId
     * @param bool|null $onlyChoiceOfOffer
     * @param string|null $mpk
     * @param string|null $comments
     */
    public function __construct(
        public Recipient $recipient,
        public Sender $sender,
        public array $parcels,
        public ?CustomAttributes $customAttributes = null,
        public ?Cod $cod = null,
        public Insurance $insurance,
        public string $reference,
        public ?bool $isReturn = null,
        public ServiceType $service = ServiceType::INPOST_COURIER_STANDARD,
        public ?array $additionalServices = null,
        public ?string $externalCustomerId = null,
        public ?bool $onlyChoiceOfOffer = null,
        public ?string $mpk = null,
        public ?string $comments = null
    ) {}
    
    /**
     * Converts the ShipmentData object object to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'recipient'          => $this -> recipient -> toArray(),
            'sender'             => $this -> sender -> toArray(),
            'parcels'            => array_map(fn($parcel) => $parcel -> toArray(), $this -> parcels),
            'customAttributes'   => $this -> customAttributes ?-> toArray(),
            'cod'                => $this -> cod ?-> toArray(),
            'insurance'          => $this -> insurance -> toArray(),
            'reference'          => $this -> reference,
            'isReturn'           => $this -> isReturn,
            'service'            => $this -> service,
            'additionalServices' => $this -> additionalServices ? array_map(fn(AdditionalService $service) => $service -> value, $this -> additionalServices) : null,
            'externalCustomerId' => $this -> externalCustomerId,
            'onlyChoiceOfOffer'  => $this -> onlyChoiceOfOffer,
            'mpk'                => $this -> mpk,
            'comments'           => $this -> comments,
        ];
    }
}