<?php

namespace Kubinyete\KnightShieldSdk\App;

use Kubinyete\KnightShieldSdk\App\Exception\UnsupportedApiVersionException;
use Kubinyete\KnightShieldSdk\Domain\Auth\ApiToken;
use Kubinyete\KnightShieldSdk\Domain\Order\Order;
use Kubinyete\KnightShieldSdk\Domain\Order\OrderStatus;

class ApiClient extends Client
{
    public const SUPPORTED_API_VERSION_LATEST = 1;
    private const SUPPORTED_API_VERSION_MAP = [
        '1' => 'v1',
    ];

    protected float $versionNumber;

    public function __construct(ApiToken $token, ?float $version = self::SUPPORTED_API_VERSION_LATEST, float $timeout = self::DEFAULT_TIMEOUT_SECONDS, ?string $host = null, ?string $protocol = null, ?string $port = null)
    {
        parent::__construct($token, $timeout, $host, $protocol, $port);
        $this->setVersion(is_null($version) ? self::SUPPORTED_API_VERSION_LATEST : $version);
    }

    //

    protected function path(string $relativePath = ''): string
    {
        $relativePath = ltrim($relativePath, '/');
        $versionString = self::getSupportedApiVersionPath($this->versionNumber);

        return parent::path("{$versionString}/{$relativePath}");
    }

    protected function requiresVersion(float $version): void
    {
        UnsupportedApiVersionException::assert($version == $this->versionNumber, "This method requires version $version, but client is pointing to version $this->versionNumber.");
    }

    protected function requiresAtleastVersion(float $version): void
    {
        UnsupportedApiVersionException::assert($this->versionNumber >= $version, "This method requires version $version or greater, but client is pointing to an older version $this->versionNumber.");
    }

    public function setVersion(float $version): void
    {
        UnsupportedApiVersionException::assert(($versionString = self::getSupportedApiVersionPath($version)), "Version identifier '$version' is not yet supported.");
        $this->versionNumber = $version;
    }

    //

    protected static function getSupportedApiVersionPath(float $version): ?string
    {
        return array_key_exists(strval($version), self::SUPPORTED_API_VERSION_MAP) 
            ? self::SUPPORTED_API_VERSION_MAP[strval($version)] 
            : null;
    }

    //

    public function getMe(): Response
    {
        return $this->request('GET', "/me");
    }

    public function getRequestLogs(): Response
    {
        return $this->request('GET', "/request_logs");
    }

    //

    public function getOrder(string $id, bool $asMerchantOrderId = false): Response
    {
        return $this->request('GET', "/orders/{$id}", ['as_merchant_order_id' => $asMerchantOrderId]);
    }

    public function getOrders(array $filters = []): Response
    {
        return $this->request('GET', "/orders", $filters);
    }

    public function getRequestsSentFromOrder(string $id): Response
    {
        return $this->request('GET', "/orders/{$id}/requests_sent");
    }

    public function createOrder(Order $order): Response
    {
        return $this->request('POST', "/orders", [], [], $order->jsonSerialize());
    }

    public function updateOrderStatus(string $id, OrderStatus $status, bool $asMerchantOrderId = false): Response
    {
        return $this->request('PATCH', "/orders/{$id}", ['as_merchant_order_id' => $asMerchantOrderId], [], $status->jsonSerialize());
    }
}
