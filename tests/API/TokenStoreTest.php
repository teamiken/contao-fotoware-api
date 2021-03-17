<?php

declare(strict_types=1);

use teamiken\Fotoware\API\Auth;
use PHPUnit\Framework\TestCase;
use teamiken\Fotoware\API\HttpClientFactory;
use teamiken\Fotoware\API\TokenStore;

class TokenStoreTest extends TestCase
{
    public function testCanSaveToken(): void
    {
        $cachePath = "/mnt/c/dev/htdocs/neumann/tokenstore.txt";
        $tokenStore = new TokenStore($cachePath);
        $tokenStore->setToken("test");

        $token = $tokenStore->getToken();

        $this->assertSame("test", $token);
    }
}
