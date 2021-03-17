<?php

namespace teamiken\Fotoware\API;

class TokenStore
{
    protected $cachePath;

    public function __construct($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    public function getToken()
    {
        $data = file_get_contents($this->cachePath);
        return json_decode($data);
    }

    public function setToken($data)
    {
        $data = json_encode($data);
        file_put_contents($this->cachePath, $data);
    }

}
