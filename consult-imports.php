<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$statusFiles = scandir(__DIR__ . '/data/issues');

$createdIssues = [];

$statics = [
    'pending' => 0,
    'imported' => 0,
    'failed' => 0,
];

foreach ($statusFiles as $statusFile) {
    if (in_array($statusFile, ['.', '..'])) {
        continue;
    }

    $status = getStatus(substr($statusFile, 0, strpos($statusFile, '.json')));

    if('pending' != $status['status']) {
        $statics[$status['status']]++;
        continue;
    }

    $response = $githubClient->get('import/issues/' . $status['id']);

    $status['status'] = $response['status'];
    $statics[$status['status']]++;
    switch ($response['status']) {
        case 'imported':
            $data = array_merge($status, $response);
            $data['githubId'] = substr($data['issue_url'], strpos($data['issue_url'], 'issues/')+7);
            file_put_contents(__DIR__ . '/data/map/' . $status['jiraId'] . '.json', json_encode($data));
            file_put_contents(__DIR__ . '/data/issues/' . $statusFile, json_encode($status));
            break;
        case 'failed':
            printf('A importacao da tarefa %s falhou.%s', $status['jiraId'], PHP_EOL);
            file_put_contents(__DIR__ . '/data/issues/' . $statusFile, json_encode($status));
            break;
    }

}

var_dump($statics);