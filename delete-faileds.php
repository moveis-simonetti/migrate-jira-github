<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$statusFiles = scandir(__DIR__ . '/data/issues');

$createdIssues = [];


foreach ($statusFiles as $statusFile) {
    if (in_array($statusFile, ['.', '..'])) {
        continue;
    }

    $status = getStatus($statusFile);

    if('failed' != $status['status']) {
        continue;
    }

    unlink(__DIR__ . '/data/issues/' . $statusFile);
}
