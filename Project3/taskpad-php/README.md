# TaskPad PHP

TaskPad PHP is a server-side task tracking web application created by Francisco Hernandez and Matthew Bilko

The application allows users to create, view, filter, complete, and delete tasks. Tasks are persisted in a JSON file instead of a database.

## Features

- Create new tasks
- Required task title
- Optional task description
- Low, Medium, and High priority levels
- Optional due date
- Server-side PHP validation
- Display validation errors while preserving form input
- Store tasks in a JSON file
- View all tasks
- Search tasks by title or description
- Filter tasks by priority
- View total, open, and completed task counts
- Mark tasks as completed
- Delete tasks
- Flash messages after completing or deleting tasks
- POST/Redirect/GET pattern to prevent duplicate submissions
- Responsive user interface for desktop, tablet, and mobile

## Technologies Used

- PHP 8.x
- HTML5
- CSS3
- JSON
- PlantUML

No database or PHP framework is required to run this project

## Project Structure

    taskpad-php/
    |
    |-- public/
    |   |-- index.php
    |   |-- create.php
    |   |-- actions.php
    |   |
    |   `-- assets/
    |       `-- style.css
    |
    |-- src/
    |   |-- storage.php
    |   `-- validation.php
    |
    |-- data/
    |   `-- tasks.json
    |
    |-- docs/
    |   |-- uml-class.pdf
    |   |-- uml-usecase.pdf  
    |   |-- class-diagram.puml
    |   |-- usecase-diagram.puml
    |   |-- screenshots/
    |
    `-- README.md

## Running the Application

1. Clone or download the repository.

2. Open a terminal and navigate to the TaskPad PHP directory.

Example:

    cd Project3/taskpad-php

3. Start the PHP development server:

    php -S localhost:8000 -t public

4. Open a web browser and navigate to:

    http://localhost:8000

5. The TaskPad task list should appear.

To stop the PHP development server, return to the terminal and press:

    Ctrl + C

## Creating a Task

Select **Create Task** from the navigation menu.

A task contains:

- Title - required
- Description - optional
- Priority - Low, Medium, or High
- Due Date - optional

The server validates submitted data before the task is saved.

If validation fails, error messages are displayed and the previously entered values remain in the form.

If validation succeeds, the task is stored in `data/tasks.json` and the user is redirected to the task list.

## Task Storage

Tasks are persisted in:

    data/tasks.json

Each task contains the following data:

    {
        "id": "string",
        "title": "string",
        "description": "string",
        "priority": "Low|Medium|High",
        "due": "YYYY-MM-DD|null",
        "completed": false
    }

The application uses PHP to read, modify, and save the JSON data.

No database is used.

## Filtering Tasks

Tasks can be filtered using:

- Text search
- Priority

The text search checks both the task title and description and is case-insensitive.

Filtering is performed using GET query parameters.

Example:

    ?q=project&priority=High

## Complete and Delete Actions

Tasks can be marked as completed or deleted from the task list.

Both operations use HTTP POST requests.

After an operation is performed, the application redirects back to the task list and