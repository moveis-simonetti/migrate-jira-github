<?php
namespace Simonetti\Migrate;

/**
 * Class JiraClient
 * @package Simonetti\Migrate
 */
class ClientFactory
{
    /**
     * @var array|HttpClient[]
     */
    protected static $instances = [];

    protected static function factory($name, $baseUri, array $headers) : HttpClient
    {
        if (!array_key_exists($name, static::$instances)) {
            static::$instances[$name] = new HttpClient($baseUri, $headers);
        }

        return static::$instances[$name];
    }

    public static function getJiraClient() : HttpClient
    {
        return self::factory(
            'jira',
            sprintf('%s/rest/api/2/', getenv('JIRA_URL')),
            [
                'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $_SERVER['JIRA_USER'], $_SERVER['JIRA_PASSWORD']))
            ]
        );
    }

    public static function getGithubClient() : HttpClient
    {
        return self::factory(
            'github',
            sprintf('https://api.github.com/repos/%s/', $_SERVER['GITUB_REPOSITORY']),
            [
                'User-Agent' => 'Doctrine Jira Migration',
                'Authorization' => 'token ' . $_SERVER['GITHUB_TOKEN'],
                'Accept' => 'application/vnd.github.golden-comet-preview+json',
            ]
        );
    }
}