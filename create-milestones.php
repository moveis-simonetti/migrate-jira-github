<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$projects = getProjects();
$createdMilestones = $githubClient->get('milestones');

foreach($projects as &$project) {

    $title = sprintf('%s - %s', $project['key'], $project['name']);

    $milestone = [
        'title' => $title,
        'description' => $project['description']
    ];

    foreach ($createdMilestones as $createdMilestone) {
        if ($createdMilestone['title'] == $title) {
            $milestone['id'] = $createdMilestone['number'];
            $project['milestone'] = $milestone;
            continue 2;
        }
    }


    $data = $githubClient->post('milestones', $milestone);

    $milestone['id'] = $data['number'];

    $createdMilestones[] = $milestone;

    $project['milestone'] = $milestone;
}

file_put_contents(__DIR__ . '/data/projects.json', json_encode($projects));
