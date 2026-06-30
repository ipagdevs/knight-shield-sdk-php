<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Domain\Locale\CurrencyCode;
use Kubinyete\KnightShieldSdk\Domain\Address\BillingAddress;
use Kubinyete\KnightShieldSdk\Domain\Address\ShippingAddress;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;

class Order implements JsonSerializable
{
    protected string $merchant_order_id;
    protected float $amount;
    protected int $installments;
    protected bool $is_recurring;
    protected CurrencyCode $currency;
    protected Customer $customer;
    protected ?BillingAddress $billing_address;
    protected ?ShippingAddress $shipping_address;
    protected Cart $cart;
    protected Factor $factors;
    protected array $payments = [];
    protected array $metadata = [];
    protected OrderStatus $status;

    public function __construct(
        string $merchant_order_id,
        float $amount,
        int $installments,
        CurrencyCode $currency,
        Customer $customer,
        ?BillingAddress $billing_address,
        ?ShippingAddress $shipping_address,
        Factor $factors,
        array $items = [],
        array $payments = [],
        array $metadata = [],
        bool $is_recurring = false
    ) {
        $this->merchant_order_id = $merchant_order_id;
        $this->amount = $amount;
        $this->installments = $installments;
        $this->is_recurring = $is_recurring;
        $this->currency = $currency;
        $this->customer = $customer;
        $this->billing_address = $billing_address;
        $this->shipping_address = $shipping_address;
        $this->factors = $factors;
        $this->cart = new Cart();
        $this->addItems($items);
        $this->addPayments($payments);
        $this->metadata = $metadata;
        $this->status = OrderStatus::neutral();

        $this->assertValidAmount();
        $this->assertValidInstallments();
        // $this->assertValidMerchantOrderId();
    }

    protected function assertValidAmount(): void
    {
        DomainException::assert($this->amount > 0, "Amount should be greater than zero");
    }

    protected function assertValidInstallments(): void
    {
        DomainException::assert($this->installments >= 1 && $this->installments <= 48, "Installments value should be between 1 and 48");
    }

    protected function assertValidMerchantOrderId(): void
    {
        DomainException::assert(preg_match('/^[0-9a-zA-Z_-]{1,255}$/', $this->merchant_order_id), "Merchant Order ID should not be omitted or exceed maximum length");
    }

    public function addItem(Item $item): void
    {
        $this->cart->addItem($item);
    }

    public function addItems(array $items): void
    {
        $this->cart->addItems($items);
    }

    public function addPayment(Payment $payment): void
    {
        $this->payments[] = $payment;
    }

    public function addPayments(array $payments): void
    {
        foreach ($payments as $payment) {
            $this->addPayment($payment);
        }
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'merchant_order_id' => $this->merchant_order_id,
            'amount' => $this->amount,
            'installments' => $this->installments,
            'is_recurring' => $this->is_recurring,
            'currency' => (string)$this->currency,
            'status' => (string)$this->status,
            'customer' => $this->customer->jsonSerialize(),
            'billing_address' => $this->billing_address ? $this->billing_address->jsonSerialize() : null,
            'shipping_address' => $this->shipping_address ? $this->shipping_address->jsonSerialize() : null,
            'cart' => $this->cart->jsonSerialize(),
            'factors' => $this->factors->jsonSerialize(),
            'payments' => array_map(function ($x) {
                return $x->jsonSerialize();
            }, $this->payments),
            'metadata' => $this->metadata ? $this->metadata : null,
        ];
    }
}
