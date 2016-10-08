<?php
require_once __DIR__ . '/bootstrap.php';

$projects = getProjects();

$mappedIssues = [];

foreach($projects as $project) {

    $issues = getIssues($project['key']);

    foreach($issues as $issue) {
        //var_dump($issue['type']);
        //var_dump(array_keys($issue));
        //exit;

        $import = [
            'issue' => [
                'title' => sprintf('%s: %s', $issue['id'], $issue['summary']),
                'body' => sprintf(
                    "Jira issue originally created by user %s:\n\n%s",
                    mentionName($issue['creator']),
                    toMarkdown($issue['description'])
                ),
                'created_at' => substr($issue['created'], 0, 19) . 'Z',
                'closed' => in_array($issue['status'], ['Resolved', 'Closed']),
                'creator' => getKey($issue['creator']),
                'assignee' => $issue['assignee'],
                'labels' => [$issue['type']],
                'parent' => $issue['parent'],
                'milestone' => $project['milestone']['id'],
            ],
            'comments' => array_map(function($comment) {
                return [
                    'created_at' => isset($comment['created']) ? substr($comment['created'], 0, 19) . 'Z' : null ,
                    'author' => getKey(isset($comment['author']) ? $comment['author'] : null ),
                    'body' => toMarkdown($comment['body']),
                ];
            }, $issue['comments']),
        ];

        $mappedIssues[] = $import;
    }
}

file_put_contents(__DIR__ . '/data/issues.json', json_encode($mappedIssues));