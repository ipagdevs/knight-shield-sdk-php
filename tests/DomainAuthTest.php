<?php

namespace Tests;

use Kubinyete\KnightShieldSdk\App\ApiClient;
use Kubinyete\KnightShieldSdk\Domain\Auth\ApiToken;
use Kubinyete\KnightShieldSdk\Domain\Auth\Exception\InvalidTokenFormatException;
use Kubinyete\KnightShieldSdk\Domain\Auth\PlatformToken;

class DomainAuthTest extends TestCase
{
    public function testApiTokenWithInvalidFormatThrowsException()
    {
        $this->expectException(InvalidTokenFormatException::class);
        $token = new ApiToken('1|abc#123');
    }

    public function testCanCreateApiTokenWithValidFormat()
    {
        $stringToken = '1|Aigh3pusheirePaiho7ge7eeF5haimoh8aih6che';
        $token = new ApiToken($stringToken);
        $this->assertEquals($stringToken, (string)$token);
    }

    public function testCanCreateApiTokenWithChecksumFormat()
    {
        $stringToken = '1|Aigh3pusheirePaiho7ge7eeF5haimoh8aih6che1a2b3c4d';
        $token = new ApiToken($stringToken);
        $this->assertEquals($stringToken, (string)$token);
    }

    public function testCanCreateApiTokenWithPrefixedChecksumFormat()
    {
        $stringToken = '1|ks_live-Aigh3pusheirePaiho7ge7eeF5haimoh8aih6che1a2b3c4d';
        $token = new ApiToken($stringToken);
        $this->assertEquals($stringToken, (string)$token);
    }

    public function testPlatformTokenWithInvalidFormatThrowsException()
    {
        $this->expectException(InvalidTokenFormatException::class);
        $token = new PlatformToken('eyJhbGciOiJI#z@1NiIsInR5cC$6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');
    }

    public function testCanCreatePlatformTokenWithValidFormat()
    {
        $stringToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';
        $token = new PlatformToken($stringToken);
        $this->assertEquals($stringToken, (string)$token);
    }
}
