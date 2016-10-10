<?php

function mentionName($name) {
    $login = getLogin($name);
    if($login) {
        return '@' . $login;
    }

    return $name;
}

function getLogin($name) {
    if ( !empty(USERS[$name])) {
        return USERS[$name];
    }
    return $_SERVER['GITHUB_USERNAME'];
}

function toMarkdown($text) {
    $converted = $text;
    $converted = preg_replace_callback('/^h([0-6])\.(.*)$/m', function ($matches) {
        return str_repeat('#', $matches[1]) . $matches[2];
    }, $converted);
    $converted = preg_replace('/\{\{([^}]+)\}\}/', '`$1`', $converted);
    $converted = preg_replace('/\?\?((?:.[^?]|[^?].)+)\?\?/', '<cite>$1</cite>', $converted);
    $converted = preg_replace('/\+([^+]*)\+/', '<ins>$1</ins>', $converted);
    $converted = preg_replace('/\^([^^]*)\^/', '<sup>$1</sup>', $converted);
    $converted = preg_replace('/~([^~]*)~/', '<sub>$1</sub>', $converted);
    $converted = preg_replace('/-([^-]*)-/', '-$1-', $converted);
    $converted = preg_replace('/{code(:([a-z]+))?}/', '```$2', $converted);
    $converted = preg_replace('/{code(:([^}]+))?}/', '```', $converted);
    $converted = preg_replace('/\[(.+?)\|(.+?)\]/', '[$1]($2)', $converted);
    $converted = preg_replace('/{noformat}/', '```', $converted);
    return $converted;
}

function getJsonData($filename)
{
    $filename = __DIR__ . '/../data/' . $filename . '.json';

    if (!file_exists($filename)) {
        throw new \Simonetti\Migrate\FileNotFoundExeception(
            sprintf('File not found. Filename: %s', $filename)
        );
    }

    return json_decode(
        file_get_contents($filename),
        true
    );
}

function getProjects()
{
    return getJsonData('projects');
}

function getIssues($project)
{
    return getJsonData('projects/' . $project . '-issues');
}

function getUser($login)
{
    return USERS[$login];
}

function getAllIssues()
{
    return getJsonData('issues');
}

function getAllSubTasks()
{
    return getJsonData('subtasks');
}

function getStatus($jiraId)
{
    return getJsonData('issues/' . $jiraId);
}

function getMapData($jiraId)
{
    try {
        return getJsonData('map/' . $jiraId . '.json');
    } catch (\Simonetti\Migrate\FileNotFoundExeception $e) {
        return false;
    }
}