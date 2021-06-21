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

    /**
     * Abfrage eines Archives:
     * https://media.neumann-pflanzen.de/fotoweb/archives/5014-Highlights-des-Monats
     *
     * mit Suche auf einen Taxanomiewert:
     * https://media.neumann-pflanzen.de/fotoweb/archives/5014-Highlights-des-Monats/?209=Nadelgeh%C3%B6lz
     *
     * @param $query
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($query)
    {
//        $headers['Accept'] = 'application/vnd.fotoware.collectionlist+json';
        $headers['Accept'] = 'application/json';

        return $this->client->request('GET', $query, ['headers' => $headers]);
    }
}
