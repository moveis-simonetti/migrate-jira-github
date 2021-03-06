<?php
require_once __DIR__ . '/bootstrap.php';

$projects = getProjects();

$mappedIssues = [];
$subTaks = [];
$resolutions = RESOLUTIONS;
$statusMap = STATUS_MAP;
$issueTypesMap = ISSUETYPES_MAP;
$resolutionsMap = RESOLUTIONS_MAP;

foreach ($projects as $project) {

    $issues = getIssues($project['key']);

    foreach ($issues as $issue) {

        $closed = false;
        $labels = [
            $issueTypesMap[$issue['type']]
        ];

        $status = $statusMap[$issue['status']];



        if (isset($issue['resolution']['name'])) {
            $closed = in_array($issue['resolution']['name'], $resolutions);
            $labels[] = $resolutionsMap[$issue['resolution']['name']];
        }

        if(STATUS_CLOSED != $status && $closed) {
            $closed = false;
        }

        if(!$_SERVER['IMPORT_CLOSED_ISSUES']) {
            continue;
        }

        $labels[] = $status;

        $labels = array_unique($labels);

        $import = [
            'issue' => [
                'title' => sprintf('%s: %s', $issue['id'], $issue['summary']),
                'jira_id' => $issue['id'],
                'body' => sprintf(
                    "Tarefa criada no Jira por %s:\n\n%s",
                    mentionName($issue['creator']),
                    toMarkdown($issue['description'])
                ),
                'created_at' => substr($issue['created'], 0, 19) . 'Z',
                'closed' => $closed,
                'assignee' => getLogin($issue['assignee']),
                'labels' => $labels,
                'parent' => $issue['parent'],
                'milestone' => $project['milestone']['id'],
            ],
            'comments' => array_map(function ($comment) {
                $createdAt = isset($comment['created']) ? $comment['created'] : $comment['created_at'];

                if (!empty($comment['author'])) {
                    $comment['body'] = sprintf(
                        "Comentário realizado no Jira por %s:\n\n%s",
                        mentionName($comment['author']),
                        toMarkdown($comment['body'])
                    );
                }

                return [
                    'created_at' => substr($createdAt, 0, 19) . 'Z',
                    'body' => $comment['body'],
                ];
            }, $issue['comments']),
        ];

        if ($issue['parent']) {
            $subTaks[] = $import;
        } else {
            $mappedIssues[] = $import;
        }
    }
}

file_put_contents(__DIR__ . '/data/issues_2.json', json_encode($mappedIssues));
file_put_contents(__DIR__ . '/data/subtasks_2.json', json_encode($subTaks));