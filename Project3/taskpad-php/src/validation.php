<?php

function validateTask(array $input): array
{
    $errors = [];

    $title = trim($input['title'] ?? '');
    $description = trim($input['description'] ?? '');
    $priority = trim($input['priority'] ?? 'Medium');
    $due = trim($input['due'] ?? '');

    // Validate title
    if ($title === '') {
        $errors['title'] = 'Title is required.';
    } elseif (strlen($title) > 100) {
        $errors['title'] = 'Title must be 100 characters or less.';
    }

    // Validate description
    if (strlen($description) > 1000) {
        $errors['description'] = 'Description must be 1000 characters or less.';
    }

    // Validate priority
    $allowedPriorities = ['Low', 'Medium', 'High'];

    if (!in_array($priority, $allowedPriorities, true)) {
        $errors['priority'] = 'Please select a valid priority.';
    }

    // Validate due date
    if ($due !== '') {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $due)) {
            $errors['due'] = 'Due date must use YYYY-MM-DD format.';
        } else {
            [$year, $month, $day] = array_map('intval', explode('-', $due));

            if (!checkdate($month, $day, $year)) {
                $errors['due'] = 'Please enter a valid due date.';
            }
        }
    }

    $sanitized = [
        'title' => $title,
        'description' => $description,
        'priority' => $priority,
        'due' => $due === '' ? null : $due
    ];

    return [
        'isValid' => empty($errors),
        'errors' => $errors,
        'sanitized' => $sanitized
    ];
}