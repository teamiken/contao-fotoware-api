<?php

namespace teamiken\Fotoware\API;

class TokenStore
{
    protected $cachePath;
    protected $filename;

    public function __construct($cachePath)
    {
        $this->cachePath = $cachePath;
        $this->filename = $this->cachePath . "/fotowaretoken.json";
    }

    /**
     *  {
     *    "access_token": ACCESS_TOKEN,
     *    "token_type": "bearer",
     *    "expires_in": EXPIRES_IN_SECONDS,
     *    "refresh_token": REFRESH_TOKEN
     *  }
     *
     * @param $data
     */
    public function getToken()
    {

        if(!file_exists($this->filename)) {
            return false;
        }

        $json = file_get_contents($this->filename);
        $data = json_decode(($json));

        if(!$json || $this->tokenIsExpired($data)) {
            return false;
        }

        return $data->access_token;
    }

    public function tokenIsExpired($data)
    {
        $time = time();
        if($data->expired_at < time()) {
            return true;
        }

        return false;
    }

    /**
     *  {
     *    "access_token": ACCESS_TOKEN,
     *    "token_type": "bearer",
     *    "expires_in": EXPIRES_IN_SECONDS,
     *    "refresh_token": REFRESH_TOKEN
     *  }
     *
     * @param $data
     */
    public function setToken($json)
    {
        $data = json_decode($json);
        $data->expired_at = time() + $data->expires_in;

        $json = json_encode($data);

        if($json) {
            file_put_contents($this->cachePath . "/fotowaretoken.json", $json);
            return true;
        }

        return false;
    }

    public function deleteToken()
    {
        if(file_exists($this->filename)) {
            unlink($this->filename);
        }
    }

}
