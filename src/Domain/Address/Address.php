<?php

namespace Kubinyete\KnightShieldSdk\Domain\Address;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Domain\Locale\CountryCode;
use Kubinyete\KnightShieldSdk\Domain\Locale\StateCode;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;
use Stringable;

abstract class Address implements JsonSerializable
{
    protected CountryCode $country;
    protected StateCode $state;
    protected ?string $street;
    protected ?string $number;
    protected ?string $district;
    protected ?string $complement;
    protected ?string $city;
    protected ?string $zipcode;

    public function __construct(
        CountryCode $country,
        StateCode $state,
        ?string $street,
        ?string $number,
        ?string $district,
        ?string $complement,
        ?string $city,
        ?string $zipcode
    ) {
        $this->country = $country;
        $this->state = $state;
        $this->street = mb_strcut(trim($street), 0, 128);
        $this->number = mb_strcut(trim($number), 0, 16);
        $this->district = mb_strcut(trim($district), 0, 64);
        $this->complement = $complement ? mb_strcut(trim($complement), 0, 64) : $complement;
        $this->city = mb_strcut(trim($city), 0, 64);
        $this->zipcode = preg_replace('/[^0-9]/', '', $zipcode);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'country' => (string)$this->country,
            'state' => (string)$this->state,
            'street' => $this->street,
            'number' => $this->number,
            'district' => $this->district,
            'complement' => $this->complement,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
        ];
    }
}
