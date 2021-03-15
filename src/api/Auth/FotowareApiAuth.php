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
$client = new Client(['handler' => $stack]);

// adding a header to each request
class TokenSubscriber
{
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

//
// $client = new Client([
//   // Base URI is used with relative requests
//   'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/',
//   // You can set any number of default request options.
//     'timeout'  => 3.0,
// ]);
//
// $headers = [
//     'Content-Type' => 'application/x-www-form-urlencoded',
//     'Accept'       => 'application/json',
// ];
//
// $options = [
//     'grant_type' => 'client_credentials',
//     'clientId' => 'myclient',
//     'clientSecret' => 'mysecret'
// ];
//
// $response = $client->request('POST', 'oauth2/token', [
//        'headers' => $headers
//    ]);
//


public static function create(Config $config)
{
    $stack = HandlerStack::create();
    foreach ($config->middleware() as $middleware) {
        $stack->push($middleware);
    }
    $client = new HttpClient([
        'base_uri' => $config->baseUri(),
        'http_errors' => $config->useHttpErrors(),
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'handler' => $stack,
    ]);
    $stack->push(new RefreshToken($client, $config));
    return new static($client, $config);
}

//  class for authentication

class Auth
{
public function __construct(HttpClient $client, string $apiKey)
public function __invoke(callable $next)
  {
    return function (RequestInterface $request, array $options = []) use ($next) {
        $request = $this->applyToken($request);
        return $next($request, $options);
    };
  }
  protected function applyToken(RequestInterface $request)
  {
    if (! $this->hasValidToken() {
        $this->acquireAccessToken();
    }
    return \GuzzleHttp\Psr7\modify_request($request, [
        'set_headers' => [
            'Authorization' => (string) $this->getToken(),
        ],
    ]);
  }
  private function acquireAccessToken()
{
    $parameters = $this->getTokenRequestParameters();
    $response = $this->client->request('POST', $this->config->getTokenRoute(), [
        'form_params' => $parameters,
        // We'll use the default handler so we don't rerun our middleware
        'handler' => \GuzzleHttp\choose_handler(),
    ]);
    $response = \GuzzleHttp\json_decode((string) $response->getBody(), true);
    $this->token = new BearerToken(
        $response['access_token'],
        (int) $response['expires_in'],
        $response['refresh_token']
    );
}
private function getTokenRequestParameters()
{
    if ($this->getToken() and $this->getToken()->isRefreshable()) {
        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->getToken()->refreshToken(),
            'clientId' => 'myclient',
            'clientSecret' => 'mysecret'
        ];
    }
    return [
      'grant_type' => 'client_credentials',
      'clientId' => 'myclient',
      'clientSecret' => 'mysecret'
    ];
}
public function __invoke(callable $handler)
{
    return function (RequestInterface $request, array $options = []) use ($handler) {
        $response = $handler($request, $options);
        if ($this->isSuccessful($response)) {
            return $response;
        }
        $this->handleErrorResponse($response);
    };
}

public function isSuccessful(ResponseInterface $response)
{
    return $response->getStatusCode() < Response::HTTP_BAD_REQUEST;
}

public function handleErrorResponse(ResponseInterface $response)
{
    switch($response->getStatusCode()) {
        case Response::HTTP_UNPROCESSABLE_ENTITY:
            throw new ValidationException(json_decode($response->getBody(), true));
        case Response::HTTP_NOT_FOUND:
            throw new NotFoundException;
        case Response::HTTP_UNAUTHORIZED:
            throw new UnauthorizedException;
        default:
            throw new ApiException((string) $response->getBody());
    }
}

function isTokenValid():bool // prüfen, ob wir einen gültigen Token haben und fügen diesen zum Header hinzu.
function refreshTokenIsValid():bool // Falls nicht, prüfen wir, ob es einen gültigen Refreshtoken gibt und holen uns einen neuen Token.
function authenticate():string
function refresh():string
}


 // Haben wir weder Token noch Refreshtoken, soll sich die Klasse einloggen.
 // Da wir ja keinen Endbenutzer (Besucher) authentifizieren, speichern z.B. in der Datenbank.


//
//  // Authorization Bearer header
// $token = 'someToken';
//
// // Guzzle HTTP client with a base URI:
//
// $client = new GuzzleHttp\Client([
//   // Base URI is used with relative requests
//   'base_uri' => 'https://media.neumann-pflanzen.de/fotoweb/',
//   // You can set any number of default request options.
//     'timeout'  => 2.0,
// ]);
//
//  $headers = [
//      'Authorization' => 'Bearer ' . $token,
//      'Accept'        => 'application/json',
//  ];
//
// // client sends a get request to https://media.neumann-pflanzen.de/fotoweb/archives/5014-Highlights-des-Monats/ including headers
//  $response = $client->request('GET', 'archives/5014-Highlights-des-Monats/', [
//         'headers' => $headers
//     ]);
