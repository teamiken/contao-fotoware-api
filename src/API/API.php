<?php

namespace teamiken\Fotoware\API;

use GuzzleHttp\Client;

class API
{

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getArchive($archive, $token = "")
    {
        $headers['Accept'] = 'application/vnd.fotoware.collectionlist+json';

        if($token != "") {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $this->client->request('GET', 'archives/' . $archive, ['headers' => $headers]);
    }

    public function get($query)
    {
        $headers['Accept'] = 'application/vnd.fotoware.collectionlist+json';

        return $this->client->request('GET', $query, ['headers' => $headers]);
    }
}
