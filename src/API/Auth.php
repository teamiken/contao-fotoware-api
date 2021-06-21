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
                $access_token = $this->getAccessToken();
                $request = $request->withAddedHeader('Authorization', 'Bearer ' . $access_token);
                return $handler($request, $options);
            };
        };
    }

    /**
     * @return false|mixed
     */
    public function getAccessToken()
    {
        $token = $this->tokenStore->getToken();

        // not valid or not exists
        if(!$token) {
            $token = $this->login();
        }

        if(!$token) {
            return "";
        }

        return $token;
    }


    public function login()
    {
        $response = $this->client->request('POST', '/fotoweb/oauth2/token', [
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

        if($response->getStatusCode() != 200) {
            return false;
        }

        $json = (string)$response->getBody();
        $this->tokenStore->setToken($json);
        $data = json_decode($json);

        return $data->access_token;
    }

}
