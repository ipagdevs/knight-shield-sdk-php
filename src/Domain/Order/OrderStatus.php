<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;
use Stringable;

class OrderStatus implements Stringable, JsonSerializable
{
    protected string $value;

    public const STATUS_NEUTRAL                     = 'N';
    public const STATUS_PAYMENT_HAS_BEEN_APPROVED   = 'A';
    public const STATUS_PAYMENT_HAS_BEEN_DECLINED   = 'D';
    public const STATUS_PAYMENT_HAS_BEEN_CANCELED   = 'C';
    public const STATUS_FRAUD                       = 'F';

    public const ALLOWED = [
        self::STATUS_NEUTRAL,
        self::STATUS_PAYMENT_HAS_BEEN_APPROVED,
        self::STATUS_PAYMENT_HAS_BEEN_DECLINED,
        self::STATUS_PAYMENT_HAS_BEEN_CANCELED,
        self::STATUS_FRAUD,
    ];

    public function __construct(
        string $value
    ) {
        $this->value = $value;
        $this->assertValueIsCorrect();
    }

    protected function assertValueIsCorrect(): void
    {
        DomainException::assert(in_array($this->value, self::ALLOWED), "Order status '$this->value' is not supported, should be one of: " . implode(', ', self::ALLOWED));
    }

    public static function neutral(): self
    {
        return new static(self::STATUS_NEUTRAL);
    }

    public static function paymentApproved(): self
    {
        return new static(self::STATUS_PAYMENT_HAS_BEEN_APPROVED);
    }

    public static function paymentDeclined(): self
    {
        return new static(self::STATUS_PAYMENT_HAS_BEEN_DECLINED);
    }

    public static function paymentCanceled(): self
    {
        return new static(self::STATUS_PAYMENT_HAS_BEEN_CANCELED);
    }

    public static function fraud(): self
    {
        return new static(self::STATUS_FRAUD);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'status' => (string)$this
        ];
    }
}
