<?php

namespace Kubinyete\KnightShieldSdk\App;

use JsonSerializable;
use Kubinyete\KnightShieldSdk\Shared\Util\ArrayUtil;
use Kubinyete\KnightShieldSdk\App\Exception\ResponseRuntimeException;

class Response implements JsonSerializable
{
    protected array $body;

    protected function __construct(
        array $body
    ) {
        $this->body = $body;
    }

    public static function createFrom($body): self
    {
        if (!is_array($body)) {
            $body = json_decode(strval($body), true);
            ResponseRuntimeException::assert($body !== null && $body !== false);
        }

        return new static($body);
    }

    //

    public function getBody(): array
    {
        return $this->body;
    }

    public function getStatus()
    {
        return $this->getPath('status');
    }

    public function getMessage()
    {
        return $this->getPath('message');
    }

    public function getResponse()
    {
        return $this->getPath('response');
    }

    public function getPath(string $path)
    {
        return ArrayUtil::get($path, $this->body);
    }

    public function getResponsePath(string $path)
    {
        return ArrayUtil::get($path, $this->getResponse());
    }

    public function jsonSerialize(): mixed
    {
        return $this->body;
    }
}
