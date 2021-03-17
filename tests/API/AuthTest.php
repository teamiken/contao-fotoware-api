<?php

declare(strict_types=1);

use teamiken\Fotoware\API\Auth;
use PHPUnit\Framework\TestCase;
use teamiken\Fotoware\API\HttpClientFactory;
use teamiken\Fotoware\API\TokenStore;

class AuthTest extends TestCase
{

    private $clientId = "9c956982-48dc-4713-a6de-a63244a89e85";
    private $secret = "aIFtOuOCgoquJfThGxdFCaF59vvwnZL0Q6VeqrSFsS4";

//    public function testCanBeInstantiated(): void
//    {
//        $client = new GuzzleHttp\Client([
//            'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/'
//        ]);
//        $cachePath = "/mnt/c/dev/htdocs/neumann/tokenstore.txt";
//        $tokenStore = new TokenStore($cachePath);
//        $auth = new Auth($client, $tokenStore, $this->clientId, $this->secret);
//        $this->assertInstanceOf('teamiken\Fotoware\API\Auth', $auth);
//    }
//
//    public function testGetTokenWithClientIdAndSecret(): void
//    {
//        $client = new GuzzleHttp\Client([
//            'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/'
//        ]);
//        $cachePath = "/mnt/c/dev/htdocs/neumann/tokenstore.txt";
//        $tokenStore = new TokenStore($cachePath);
//        $auth = new Auth($client, $tokenStore, $this->clientId, $this->secret);
//
//        $response = $auth->login($this->clientId, $this->secret);
//
//        $this->assertSame(200, $response->getStatusCode());
//    }
//
//    public function testCanGetRequestApiWithToken(): void
//    {
//        $client = new GuzzleHttp\Client([
//            'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/'
//        ]);
//
//        $cachePath = "/mnt/c/dev/htdocs/neumann/tokenstore.txt";
//        $tokenStore = new TokenStore($cachePath);
//
//        $auth = new Auth($client, $tokenStore, $this->clientId, $this->secret);
//        $response = $auth->login($this->clientId, $this->secret);
//        $body = (string)$response->getBody();
//        $data = json_decode($body);
//        $access_token = $data->access_token;
//
//        $api = new teamiken\Fotoware\API\API($client);
//
//        $archive = $api->getArchive("5014-Highlights-des-Monats", $access_token);
//
//        $this->assertSame(200, $archive->getStatusCode());
//    }

    /**
     * So könnte es aussehen
     * Für den Test ist es ok so.
     *
     * Für die Anwendung gehören die Klassen etc. in die services.yml
     * Am Ende sollte in Deinem Controller nur die API im Konstruktor übergeben werden.
     * Symfony erzeugt dann den TokenStore, den HttpClient (mit Middleware) etc. und erzeugt die API
     *
     *
     */
    public function testCanMakeRequestWithMiddleware(): void
    {
        $nativeClient = new GuzzleHttp\Client([
            'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/'
        ]);

        $cachePath = "/mnt/c/dev/htdocs/neumann/tokenstore.txt";
        $tokenStore = new TokenStore($cachePath);

        $authMiddleware = new Auth($nativeClient, $tokenStore, $this->clientId, $this->secret);
        $clientWithMiddleware = HttpClientFactory::createWithMiddleware($authMiddleware);

        $api = new teamiken\Fotoware\API\API($clientWithMiddleware);

        // Die Abfrage auf ein Archiv erzeugt ein 301 Redirect
        // Ohne Middleware bzw. HandlerStack wird dem Redirect automatisch gefolgt
        // Mit Middleware leider nicht, hier muss man sich scheinbar selbst um den Redirect kümmern...
        $res = $api->get("archives/5014-Highlights-des-Monats");

        //$res = $api->get("data/a/5014.zJkakXp3QRSCQbKCoT6V3SohHrCh-NxOXE_IfuW2MtQ");
        //$body = (string)$res->getBody();

        $this->assertSame(200, $res->getStatusCode());
    }


}
