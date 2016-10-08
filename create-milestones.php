<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = GithubClient::instance();

$projects = getProjects();
$createdMilestones = $githubClient->get('milestones');

$milestonesIds = array_column($createdMilestones, 'title');

foreach($projects as &$project) {

    $title = sprintf('%s - %s', $project['key'], $project['name']);

    if(in_array($title, $milestonesIds)) {
        continue;
    }

    $milestone = [
        'title' => $title,
        'description' => $project['description']
    ];

    $data = $githubClient->post('milestones', $milestone);

    $milestone['id'] = $data['number'];

    $createdMilestones[] = $milestone;

    $project['milestone'] = $milestone;
}

file_put_contents(__DIR__ . '/data/projects.json', json_encode($projects));
