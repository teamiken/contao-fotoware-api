<?php

namespace teamiken\Fotoware\API;

use \GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

class Auth {

    protected $client;
    protected $tokenStore;
    protected $clientId;
    protected $secret;

    public function __construct(Client $client, TokenStore $tokenStore, $clientId, $secret)
    {
        $this->client = $client;
        $this->tokenStore = $tokenStore;
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    /**
     * Middleware
     *
     * @param $header
     * @param $value
     * @return \Closure
     */
    public function __invoke()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler)  {
                // Token vorhanden?
                // Wenn ja, füge Token hinzu
                // Wenn nein, Refresh-Token vorhanden?
                // Wenn nein, Login
                $access_token = $this->getAccessToken();
                $request = $request->withAddedHeader('Authorization', 'Bearer ' . $access_token);
                return $handler($request, $options);
            };
        };
    }

    /**
     * @todo Hier fehlt noch alles. Ist nur ein Quick-Fix
     *
     * @return false|mixed
     */
    public function getAccessToken()
    {
        $token = $this->tokenStore->getToken();
        if($this->isValid($token)) {
            return $token;
        }

        $loginResponse = $this->login();
        if($loginResponse->getStatusCode() !== 200) {
            return false;
        }

        $body = (string)$loginResponse->getBody();
        $data = json_decode($body);

        $this->tokenStore->setToken($data);
        return $data->access_token;
    }

    public function isValid($token):bool
    {
        return false;
    }

    public function login()
    {
        $response = $this->client->request('POST', 'oauth2/token', [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->secret
            ]
        ]);

        return $response;
    }

}
