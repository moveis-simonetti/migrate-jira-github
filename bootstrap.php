<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/functions.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

define('USUARIOS', include_once __DIR__ . '/users.php');

function getProjects()
{
    return json_decode(
        file_get_contents(__DIR__ . '/data/projects.json'),
        true
    );
}

function getIssues($project)
{
    return json_decode(
        file_get_contents(__DIR__ . '/data/projects/' . $project . '-issues.json'),
        true
    ); 
}

function getUser($login)
{
    return USUARIOS[$login];
}

function getAllIssues()
{
    return json_decode(
        file_get_contents(__DIR__ . '/data/issues.json'),
        true
    );
}