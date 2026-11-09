<?php

session_start();
require_once __DIR__ . '/../src/storage.php';
require_once __DIR__ . '/../src/validation.php';

$pageTitle = 'Create Task';

// Default form values
$values = [
    'title' => '',
    'description' => '',
    'priority' => 'Medium',
    'due' => ''
];

$errors = [];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate the submitted form
    $result = validateTask($_POST);

    $errors = $result['errors'];
    $sanitized = $result['sanitized'];

    // Preserve user input if validation fails
    $values = [
        'title' => $sanitized['title'],
        'description' => $sanitized['description'],
        'priority' => $sanitized['priority'],
        'due' => $sanitized['due'] ?? ''
    ];

    // If everything is valid, create the task
    if ($result['isValid']) {

        $task = [
            'id' => uniqid(),
            'title' => $sanitized['title'],
            'description' => $sanitized['description'],
            'priority' => $sanitized['priority'],
            'due' => $sanitized['due'],
            'completed' => false
        ];

        $tasks = loadTasks();

        $tasks[] = $task;

        if (saveTasks($tasks)) {
        $_SESSION['flash'] = 'Task created successfully.';           
        header('Location: index.php');
            exit;
        } else {
            $errors['general'] = 'Unable to save the task. Please try again.';
        }
    }
}

// Escape values before displaying them in HTML
function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <header>
        <h1>TaskPad PHP</h1>

        <nav>
            <a href="index.php">Task List</a>
            <a href="create.php">Create Task</a>
        </nav>
    </header>

    <main>

        <h2>Create Task</h2>

        <?php if (isset($errors['general'])): ?>
            <p>
                <?= e($errors['general']) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="create.php">

            <!-- Title -->
            <div>
                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    maxlength="100"
                    value="<?= e($values['title']) ?>"
                    required
                >

                <?php if (isset($errors['title'])): ?>
                    <p>
                        <?= e($errors['title']) ?>
                    </p>
                <?php endif; ?>
            </div>


            <!-- Description -->
            <div>
                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    maxlength="1000"
                    rows="5"
                ><?= e($values['description']) ?></textarea>

                <?php if (isset($errors['description'])): ?>
                    <p>
                        <?= e($errors['description']) ?>
                    </p>
                <?php endif; ?>
            </div>


            <!-- Priority -->
            <div>
                <label for="priority">
                    Priority
                </label>

                <select
                    id="priority"
                    name="priority"
                >
                    <option
                        value="Low"
                        <?= $values['priority'] === 'Low' ? 'selected' : '' ?>
                    >
                        Low
                    </option>

                    <option
                        value="Medium"
                        <?= $values['priority'] === 'Medium' ? 'selected' : '' ?>
                    >
                        Medium
                    </option>

                    <option
                        value="High"
                        <?= $values['priority'] === 'High' ? 'selected' : '' ?>
                    >
                        High
                    </option>
                </select>

                <?php if (isset($errors['priority'])): ?>
                    <p>
                        <?= e($errors['priority']) ?>
                    </p>
                <?php endif; ?>
            </div>


            <!-- Due Date -->
            <div>
                <label for="due">
                    Due Date
                </label>

                <input
                    type="date"
                    id="due"
                    name="due"
                    value="<?= e($values['due']) ?>"
                >

                <?php if (isset($errors['due'])): ?>
                    <p>
                        <?= e($errors['due']) ?>
                    </p>
                <?php endif; ?>
            </div>


            <!-- Submit -->
            <div>
                <button type="submit">
                    Add Task
                </button>

                <a href="index.php">
                    Cancel
                </a>
            </div>

        </form>

    </main>

</body>

</html>
