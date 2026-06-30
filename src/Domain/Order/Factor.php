<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;

class Factor implements JsonSerializable
{
    protected ?string $with_ip_address;
    protected ?string $with_fingerprint;
    protected ?bool $is_vip_customer;
    protected ?int $days_since_first_purchase;

    public function __construct(
        ?string $with_ip_address = null,
        ?string $with_fingerprint = null,
        bool $is_vip_customer = false,
        int $days_since_first_purchase = 0
    ) {
        $this->with_ip_address = is_null($with_ip_address) ? $with_ip_address : mb_strcut(trim($with_ip_address), 0, 64);
        $this->with_fingerprint = $with_fingerprint;
        $this->is_vip_customer = boolval($is_vip_customer);
        $this->days_since_first_purchase = is_null($days_since_first_purchase) ? 0 : abs($days_since_first_purchase);

        $this->validateWithIpAddress();
    }

    protected function validateWithIpAddress(): void
    {
        DomainException::assert(is_null($this->with_ip_address) || filter_var($this->with_ip_address, FILTER_VALIDATE_IP), "Should be an valid IP address.");
    }

    public function jsonSerialize(): mixed
    {
        return [
            'with_ip_address' => $this->with_ip_address,
            'with_fingerprint' => $this->with_fingerprint,
            'is_vip_customer' => $this->is_vip_customer,
            'days_since_first_purchase' => $this->days_since_first_purchase,
        ];
    }
}
