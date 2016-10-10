<?php
namespace Simonetti\Migrate;

use GuzzleHttp\Client;

class HttpClient
{
    protected $http;

    protected static $instance;

    public function __construct($baseUri, $headers)
    {
        $this->http = new Client([
            'base_uri' => $baseUri,
            'headers' => $headers,
        ]);
    }

    public function get($resource)
    {
        $response = $this->http->get($resource);

        return json_decode($response->getBody(), true);
    }

    public function post($resource, $data = [])
    {
        $response = $this->http->post($resource, [
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function put($resource, $data = [])
    {
        $response = $this->http->put($resource, [
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }

}