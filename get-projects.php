<?php
require_once __DIR__ . '/bootstrap.php';

$jiraClient = JiraClient::instance();

$response = $jiraClient->get('project?expand=description');

file_put_contents(__DIR__ . '/data/projects.json', json_encode($response));