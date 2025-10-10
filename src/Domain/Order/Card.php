<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Domain\Locale\PaymentMethod;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;
use Kubinyete\KnightShieldSdk\Shared\Util\Luhn;

class Card implements JsonSerializable
{
    protected string $holder;
    protected string $number;
    protected string $expiry_month;
    protected string $expiry_year;

    public function __construct(
        string $holder,
        string $number,
        string $expiry_month,
        string $expiry_year
    ) {
        $this->holder = mb_strcut(trim($holder), 0, 128);
        $this->number = mb_strcut(trim($number), 0, 19);
        $this->expiry_month = mb_strcut(trim($expiry_month), 0, 2);
        $this->expiry_year = mb_strcut(trim($expiry_year), 0, 4);

        // $this->assertValidHolder();
        $this->assertValidNumber();
        $this->assertValidExpiryMonth();
        $this->assertValidExpiryYear();
    }

    protected function assertValidHolder(): void
    {
        DomainException::assert(preg_match('/^[a-zA-Z0-9 ]+$/', $this->holder), "Card holder should be alphanumeric.");
    }

    protected function assertValidNumber(): void
    {
        DomainException::assert(preg_match('/^[0-9\\*]{13,19}$/', $this->number), "Card number should only contain digits with length between 13 and 19 characters.");
        // DomainException::assert(Luhn::check($this->number), "Number is not a valid credit card number.");
    }

    protected function assertValidExpiryMonth(): void
    {
        DomainException::assert(preg_match('/^[0-9]{2}$/', $this->expiry_month), "Card expiry month should only contain 2 digits.");
        DomainException::assert(($n = abs(intval($this->expiry_month))) && $n <= 12, "Card expiry month should be between 01 and 12.");
    }

    protected function assertValidExpiryYear(): void
    {
        DomainException::assert(preg_match('/^[0-9]{4}$/', $this->expiry_year), "Card expiry year should only contain 4 digits.");
        DomainException::assert(($n = abs(intval($this->expiry_year))) && $n >= 1900, "Card expiry year should be valid.");
    }

    public function jsonSerialize(): mixed
    {
        return [
            'holder' => $this->holder,
            'number' => $this->number,
            'expiry_month' => $this->expiry_month,
            'expiry_year' => $this->expiry_year,
        ];
    }
}
