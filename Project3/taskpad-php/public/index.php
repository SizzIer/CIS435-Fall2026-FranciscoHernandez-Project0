<?php
session_start();
require_once __DIR__ . '/../src/storage.php';

$pageTitle = 'TaskPad PHP';

function e(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Load all tasks
$tasks = loadTasks();
$flash = $_SESSION['flash'] ?? null;

unset($_SESSION['flash']);
// Read filters from query string
$query = trim($_GET['q'] ?? '');
$priority = trim($_GET['priority'] ?? '');

// Only allow valid priority filters
$allowedPriorities = ['Low', 'Medium', 'High'];

if ($priority !== '' && !in_array($priority, $allowedPriorities, true)) {
    $priority = '';
}

// Filter tasks
$filteredTasks = array_filter(
    $tasks,
    function (array $task) use ($query, $priority): bool {

        $matchesQuery = true;
        $matchesPriority = true;

        // Search title and description
        if ($query !== '') {
            $searchText =
                ($task['title'] ?? '') . ' ' .
                ($task['description'] ?? '');

            $matchesQuery = stripos($searchText, $query) !== false;
        }

        // Filter priority
        if ($priority !== '') {
            $matchesPriority =
                ($task['priority'] ?? '') === $priority;
        }

        return $matchesQuery && $matchesPriority;
    }
);

// Counts
$totalCount = count($tasks);

$openCount = count(array_filter(
    $tasks,
    fn($task) => empty($task['completed'])
));

$completedCount = count(array_filter(
    $tasks,
    fn($task) => !empty($task['completed'])
));

$matchingCount = count($filteredTasks);

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

        <h2>My Tasks</h2>

        <?php if ($flash !== null): ?>
            <p>
                <?= e($flash) ?>
            </p>
        <?php endif; ?>

        <!-- Task Counts -->
        <section>

            <h3>Task Summary</h3>

            <p>Total: <?= $totalCount ?></p>
            <p>Open: <?= $openCount ?></p>
            <p>Completed: <?= $completedCount ?></p>

            <?php if ($query !== '' || $priority !== ''): ?>
                <p>Matching: <?= $matchingCount ?></p>
            <?php endif; ?>
        </section>


        <!-- Filters -->
        <section>

            <h3>Filter Tasks</h3>

            <form method="GET" action="index.php">

                <div>
                    <label for="q">
                        Search
                    </label>

                    <input
                        type="search"
                        id="q"
                        name="q"
                        value="<?= e($query) ?>"
                        placeholder="Search tasks..."
                    >
                </div>

                <div>
                    <label for="priority">
                        Priority
                    </label>

                    <select
                        id="priority"
                        name="priority"
                    >
                        <option value="">
                            All Priorities
                        </option>

                        <option
                            value="Low"
                            <?= $priority === 'Low' ? 'selected' : '' ?>
                        >
                            Low
                        </option>

                        <option
                            value="Medium"
                            <?= $priority === 'Medium' ? 'selected' : '' ?>
                        >
                            Medium
                        </option>

                        <option
                            value="High"
                            <?= $priority === 'High' ? 'selected' : '' ?>
                        >
                            High
                        </option>

                    </select>
                </div>

                <button type="submit">
                    Filter
                </button>

                <?php if ($query !== '' || $priority !== ''): ?>
                    <a href="index.php">
                        Clear Filters
                    </a>
                <?php endif; ?>

            </form>

        </section>


        <!-- Task List -->
        <section>

            <h3>Tasks</h3>

            <?php if (empty($tasks)): ?>

                <p>No tasks yet.</p>

                <p>
                    <a href="create.php">
                        Create your first task
                    </a>
                </p>

            <?php elseif (empty($filteredTasks)): ?>

                <p>No tasks match your filters.</p>

                <p>
                    Try changing your search or
                    <a href="index.php">clear the filters</a>.
                </p>

            <?php else: ?>

                <?php foreach ($filteredTasks as $task): ?>

                    <article>

                        <h4>
                            <?= e($task['title'] ?? '') ?>
                        </h4>

                        <?php if (!empty($task['description'])): ?>

                            <p>
                                <?= nl2br(e($task['description'])) ?>
                            </p>

                        <?php endif; ?>


                        <p>
                            <strong>Priority:</strong>
                            <?= e($task['priority'] ?? '') ?>
                        </p>


                        <p>
                            <strong>Status:</strong>

                            <?php if (!empty($task['completed'])): ?>
                                Completed
                            <?php else: ?>
                                Open
                            <?php endif; ?>
                        </p>


                        <?php if (!empty($task['due'])): ?>

                            <p>
                                <strong>Due:</strong>
                                <?= e($task['due']) ?>
                            </p>

                        <?php endif; ?>


                        <!-- Actions -->
                        <div>

                            <?php if (empty($task['completed'])): ?>

                                <form
                                    method="POST"
                                    action="actions.php"
                                >
                                    <input
                                        type="hidden"
                                        name="action"
                                        value="complete"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= e($task['id'] ?? '') ?>"
                                    >

                                    <button type="submit">
                                        Complete
                                    </button>
                                </form>

                            <?php endif; ?>


                            <form
                                method="POST"
                                action="actions.php"
                            >
                                <input
                                    type="hidden"
                                    name="action"
                                    value="delete"
                                >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= e($task['id'] ?? '') ?>"
                                >

                                <button type="submit">
                                    Delete
                                </button>
                            </form>

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>