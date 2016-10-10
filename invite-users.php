<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

foreach(USERS as $user) {
    if(!$user) {
        continue;
    }

    $response = $githubClient->put('collaborators/' . $user, ['permission' => 'push']);
}