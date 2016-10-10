<?php
require_once __DIR__ . '/bootstrap.php';

$jiraClient = \Simonetti\Migrate\ClientFactory::getJiraClient();

$projects = getProjects();

$issuesCount = 0;

foreach($projects as $project) {

    $startAt = 0;
    $issues = [];
    do {

        $resource = sprintf(
                'search?jql=%s&fields=%s&startAt=%d',
                urlencode(
                    sprintf(
                        'project = %s ORDER BY priority DESC, updated DESC',
                        $project['key']
                    )
                ),
                urlencode("*all"),
                $startAt
            );

        print($resource . PHP_EOL);

        $result = $jiraClient->get($resource);

        printf(
            'Max results for %s:%d. %s', 
            $project['key'],
            count($result['issues']),
            PHP_EOL
        );

        $startAt += 50;
        
        foreach($result['issues'] as $issue) {
            $issuesCount++;

            $issueData = [
                'id' => $issue['key'],
                'parent' => isset($issue['fields']['parent']['key']) ? $issue['fields']['parent']['key'] : null,
                'summary' => $issue['fields']['summary'],
                'resolution' => $issue['fields']['resolution'],
                'description' => $issue['fields']['description'],
                'creator' => $issue['fields']['creator']['key'],
                'status' => $issue['fields']['status']['name'],
                'assignee' => $issue['fields']['assignee']['key'],
                'created' => $issue['fields']['created'],
                'type' => $issue['fields']['issuetype']['name'],
                'comments' => array_map(function($comment) {
                    return [
                        'author' => $comment['author']['key'],
                        'body' => $comment['body'],
                        'created' => $comment['created'],
                    ];
                }, $issue['fields']['comment']['comments']),
            ];

            if (isset($issue['fields']['resolutiondate']) && $issue['fields']['resolutiondate']) {
                $issueData['comments'][] = [
                    'created_at' => substr($issue['fields']['resolutiondate'], 0, 19) . 'Z',
                    'body' => sprintf('Issue was closed with resolution "%s"', $issue['fields']['resolution']['name']),
                ];
            }

            $issues[] = $issueData;

        }
    }
    while(50 == count($result['issues']));

    file_put_contents(__DIR__ . '/data/projects/' . $project['key'] . '-issues.json', json_encode($issues));
}
printf('%d issues', $issuesCount);