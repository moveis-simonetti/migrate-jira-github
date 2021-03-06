<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$issues = getAllIssues();

$createdIssues = [];

foreach($issues as $issue) {
    $parent = $issue['issue']['parent'];
    $jiraId = $issue['issue']['jira_id'];
    $fileStatus = __DIR__ . '/data/issues/' . $jiraId . '.status.json';

    if(file_exists($fileStatus)) {
        continue;
    }

    unset($issue['issue']['jira_id']);
    unset($issue['issue']['parent']);
    try {
        $response = $githubClient->post('import/issues', $issue);
        $createdIssues[$jiraId] = [
            $response
        ];
        $response['jiraId'] = $jiraId;
        file_put_contents($fileStatus, json_encode($response));
    }catch (\GuzzleHttp\Exception\RequestException $e) {
        echo $e->getResponse()->getBody();
        echo $e->getMessage();
        break;
    }
}