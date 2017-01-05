<?php
require_once __DIR__ . '/bootstrap.php';

$githubClient = \Simonetti\Migrate\ClientFactory::getGithubClient();

$labels = <<<LABELS
Category: Backend
Category: Business/Meetings
Category: DevOps
Category: Frontend
Category: Unit test
Priority: High
Priority: Highest
Priority: Low
Priority: Lowest
Priority: Medium
Stage: Analysis
Stage: Backlog
Stage: In progress
Stage: Review
Stage: Testing
Status: Blocked
Status: Duplicated
Status: Impediment
Status: Testable by tech team
Type: Bug
Type: Improvement
Type: New feature
Type: Sub-task
LABELS;

$colors = [
    'b60205',
    'fbca04',
    '0052cc',
    'd4c5f9',
    '0e8a16',
];

foreach(explode(PHP_EOL, $labels) as $label) {

    $githubClient->post('labels', [
        'name' => $label,
        'color' => $colors[array_rand($colors)],
    ]);
}