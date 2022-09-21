<?php

namespace Kubinyete\KnightShieldSdk\Domain\Order;

use DateInterval;
use DateTime;
use JsonSerializable;
use Kubinyete\KnightShieldSdk\Domain\Contact\Email;
use Kubinyete\KnightShieldSdk\Domain\Contact\FixedLinePhone;
use Kubinyete\KnightShieldSdk\Domain\Contact\MobilePhone;
use Kubinyete\KnightShieldSdk\Domain\Person\Document;
use Kubinyete\KnightShieldSdk\Domain\Person\Gender;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;

class Customer implements JsonSerializable
{
    protected string $full_name;
    protected Document $document;
    protected ?Gender $gender;
    protected DateTime $birth_date;
    protected Email $email;
    protected MobilePhone $mobile_phone;
    protected ?FixedLinePhone $phone;

    public function __construct(
        string $full_name,
        Document $document,
        Gender $gender,
        DateTime $birth_date,
        Email $email,
        MobilePhone $mobile_phone,
        ?FixedLinePhone $phone,
    ) {
        $this->full_name = $full_name;
        $this->document = $document;
        $this->gender = $gender;
        $this->birth_date = $birth_date;
        $this->email = $email;
        $this->mobile_phone = $mobile_phone;
        $this->phone = $phone;

        $this->assertValidFullname();
        $this->assertValidBirthdate();
    }

    protected function assertValidFullname(): void
    {
        $len = strlen($this->full_name);
        DomainException::assert($len > 0 && $len <= 128, "Full name should not be empty or is too long to be accepted.");
    }

    protected function assertValidBirthdate(): void
    {
        $now = new DateTime();
        $diff = $now->diff($this->birth_date);
        DomainException::assert($diff->y >= 12, "Birth date doesn't make sense and cannot be accepted.");
    }

    public function __toString(): string
    {
        return $this->full_name;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'full_name' => $this->full_name,
            'document' => $this->document->jsonSerialize(),
            'gender' => $this->gender ? (string)$this->gender : null,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'email' => (string)$this->email,
            'mobile_phone' => (string)$this->mobile_phone,
            'phone' => $this->phone ? (string)$this->phone : null,
        ];
    }
}