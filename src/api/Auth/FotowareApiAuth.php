<?php

namespace teamiken\ContaoFotowareApiBundle\api\Auth;


// configuration HttpClient

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

$handler = new CurlHandler();
$stack = HandlerStack::create($handler); // Wrap w/ middleware
$stack->push(TokenSubscriber)
$client = new Client(
  'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/',
  'handler' => $stack
]);

$headers = [
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Accept'       => 'application/json',
];

$options = [
    'grant_type' => 'client_credentials',
    'clientId' => 'myclient',
    'clientSecret' => 'mysecret'
];

$response = $client->request('POST', 'oauth2/token', [
       'headers' => $headers
   ]);


// adding a header to each request
class TokenSubscriber
{
//   Middleware::mapRequest(function (RequestInterface $request) {
//     return $request->withHeader('X-Foo', 'bar');
// }));
public function add_header($header, $value)
  {
    return function (callable $handler) use ($header, $value) {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler, $header, $value) {
            $request = $request->withHeader($header, $value);
            return $handler($request, $options);
        };
    };
  }
}


//  class for authentication

class Auth
{
public function __construct(HttpClient $client, string $apiKey)
public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (is_null($token = Cache::get($this->token_name))) {
                $response = $this->getJWT();
                Cache::put($this->token_name, $token = $response->access_token, floor($response->expires_in));
            }

            return $handler(
                $request->withAddedHeader('Authorization', 'Bearer '.$token)
                    ->withAddedHeader('Api-Key', $this->api_key), $options
                );
        };
    }

    private function getJWT()
    {
        $response = (new Client)->request('POST', 'oauth2/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'clientId' => 'myclient',
                'clientSecret' => 'mysecret'
            ],
        ]);
        return json_decode($response->getBody());
    }
}
function isTokenValid():bool // prüfen, ob wir einen gültigen Token haben und fügen diesen zum Header hinzu.
function refreshTokenIsValid():bool // Falls nicht, prüfen wir, ob es einen gültigen Refreshtoken gibt und holen uns einen neuen Token.
function authenticate():string
function refresh():string
}


 // Haben wir weder Token noch Refreshtoken, soll sich die Klasse einloggen.
 // Da wir ja keinen Endbenutzer (Besucher) authentifizieren, speichern z.B. in der Datenbank.
