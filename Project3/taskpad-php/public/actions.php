<?php

session_start();

require_once __DIR__ . '/../src/storage.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$action = trim($_POST['action'] ?? '');
$id = trim($_POST['id'] ?? '');

// Only allow the actions we expect
$allowedActions = ['complete', 'delete'];

if (!in_array($action, $allowedActions, true)) {
    $_SESSION['flash'] = 'Invalid action.';
    header('Location: index.php');
    exit;
}

// A task ID is required
if ($id === '') {
    $_SESSION['flash'] = 'Invalid task ID.';
    header('Location: index.php');
    exit;
}

$tasks = loadTasks();
$taskFound = false;


// COMPLETE TASK
if ($action === 'complete') {

    foreach ($tasks as &$task) {

        if (($task['id'] ?? '') === $id) {
            $task['completed'] = true;
            $taskFound = true;
            break;
        }
    }

    unset($task);

    if ($taskFound && saveTasks($tasks)) {
        $_SESSION['flash'] = 'Task marked as completed.';
    } else {
        $_SESSION['flash'] = 'Unable to complete task.';
    }
}


// DELETE TASK
if ($action === 'delete') {

    $originalCount = count($tasks);

    $tasks = array_values(
        array_filter(
            $tasks,
            fn($task) => ($task['id'] ?? '') !== $id
        )
    );

    $taskFound = count($tasks) < $originalCount;

    if ($taskFound && saveTasks($tasks)) {
        $_SESSION['flash'] = 'Task deleted successfully.';
    } else {
        $_SESSION['flash'] = 'Unable to delete task.';
    }
}


// Redirect back to task list
header('Location: index.php');
exit;