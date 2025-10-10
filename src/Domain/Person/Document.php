<?php

namespace Kubinyete\KnightShieldSdk\Domain\Person;

use Stringable;
use JsonSerializable;
use Kubinyete\KnightShieldSdk\Domain\Locale\CountryCode;
use Kubinyete\KnightShieldSdk\Shared\Exception\DomainException;
use Kubinyete\KnightShieldSdk\Domain\Person\Validation\DocumentLocaleValidator;
use Kubinyete\KnightShieldSdk\Domain\Person\Validation\Exception\ValidationNotImplementedException;

class Document implements JsonSerializable, Stringable
{
    protected CountryCode $nationality;
    protected string $value;
    protected DocumentType $type;

    public function __construct(
        CountryCode $nationality,
        string $value,
        DocumentType $type
    ) {
        $this->nationality = $nationality;
        $this->type = $type;
        $this->value = $this->assertValueIsCorrect($value);
    }

    protected function assertValueIsCorrect(string $value): string
    {
        $value = trim($value);
        DomainException::assert(mb_strlen($value) > 0, "Cannot specify an empty document number");
        return $value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'nationality' => (string)$this->nationality,
            'number' => $this->value,
            'type' => (string)$this->type,
        ];
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
