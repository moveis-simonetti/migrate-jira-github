<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$response = $githubClient->get('/rate_limit');

foreach($response['resources'] as $resource => $rate) {
    printf('%s%s%s', PHP_EOL, $resource, PHP_EOL);
    printf('--The limit is: %d.%s', $rate['limit'], PHP_EOL);
    printf('--The limit remaining is: %d.%s', $rate['remaining'], PHP_EOL);
    printf('--The reset will accour at: %s.%s', date('H:i:s', $rate['reset']), PHP_EOL);
}