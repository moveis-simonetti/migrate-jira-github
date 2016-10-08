<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = GithubClient::instance();

$issues = getAllIssues();

$createdIssues = [];

foreach($issues as &$issue) {
    $response = $githubClient->post('import/issues', $issue);
    break;
}

file_put_contents(__DIR__ . '/data/created-issues.json', json_encode($createdIssues));
