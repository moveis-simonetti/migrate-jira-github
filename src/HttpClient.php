<?php

class JiraClient 
{
    public $http;

    protected static $instance;

    public function __construct()
    {
        $this->http = new GuzzleHttp\Client([
            'base_uri' => sprintf('%s/rest/api/2/', getenv('JIRA_URL')),
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $_SERVER['JIRA_USER'], $_SERVER['JIRA_PASSWORD']))
            ],
        ]);
    }

    public function get($resource)
    {
        $response = $this->http->get($resource);
        
        return json_decode($response->getBody(), true);
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new JiraClient();
        }

        return static::$instance;
    }
}

class GithubClient
{
    public $http;

    protected static $instance;

    public function __construct()
    {
        $this->http = new GuzzleHttp\Client([
            'base_uri' => sprintf('https://api.github.com/repos/moveissimonetti/webpdv-all-log/', getenv('JIRA_URL')),
            'headers' => [
                'User-Agent' => 'Doctrine Jira Migration',
                'Authorization' => 'token ' . $_SERVER['GITHUB_TOKEN'],
                'Accept' => 'application/vnd.github.golden-comet-preview+json',
            ],
        ]);
    }

    public function get($resource)
    {
        $response = $this->http->get($resource);
        
        return json_decode($response->getBody(), true);
    }

    public function post($resource, $data, $token = null)
    {
        $response = $this->http->post($resource, [
            'json' => $data,
            'headers' => [
                'User-Agent' => 'Doctrine Jira Migration',
                'Authorization' => 'token ' . ($token ?: $_SERVER['GITHUB_TOKEN']),
                'Accept' => 'application/vnd.github.golden-comet-preview+json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new GithubClient();
        }

        return static::$instance;
    }
}