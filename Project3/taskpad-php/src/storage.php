<?php

function getTasksFilePath(): string
{
    return __DIR__ . '/../data/tasks.json';
}

function loadTasks(): array
{
    $path = getTasksFilePath();

    if (!file_exists($path)) {
        return [];
    }

    $json = file_get_contents($path);

    if ($json === false || trim($json) === '') {
        return [];
    }

    $tasks = json_decode($json, true);

    if (!is_array($tasks)) {
        return [];
    }

    return $tasks;
}

function saveTasks(array $tasks): bool
{
    $path = getTasksFilePath();

    $json = json_encode(
        $tasks,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    );

    if ($json === false) {
        return false;
    }

    return file_put_contents($path, $json, LOCK_EX) !== false;
}