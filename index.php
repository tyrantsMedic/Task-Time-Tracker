<?php
session_start();

require_once __DIR__ . '/App/Constants/AppConstants.php';
require_once __DIR__ . '/App/Models/User.php';
require_once __DIR__ . '/App/Models/Developer.php';
require_once __DIR__ . '/App/Models/Task.php';
require_once __DIR__ . '/App/Interfaces/TrackableInterface.php';
require_once __DIR__ . '/App/Services/TaskManager.php';
require_once __DIR__ . '/App/Helpers/TaskStatistics.php';

use App\Models\Developer;
use App\Services\TaskManager;
use App\Constants\AppConstants;
use App\Helpers\TaskStatistics;
use App\Models\Task;

$developer = null;
$error = '';
$successMessage = '';
$tasks = null;
$editTask = null;
$statistics = null;

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}

function taskToArray(Task $task): array
{
    return [
        'id' => $task->getId(),
        'title' => $task->getTitle(),
        'description' => $task->getDescription(),
        'status' => $task->getStatus(),
        'estimatedDuration' => $task->getEstimatedDuration(),
        'totalTimeSpent' => $task->getTotalTimeSpent(),
        'createdDate' => $task->getCreatedDate(),
        'startTime' => $task->getStartTime(),
    ];
}

function arrayToTask(array $taskData): Task
{
    $task = new Task(
        $taskData['id'],
        $taskData['title'],
        $taskData['description'],
        $taskData['estimatedDuration']
    );
    $task->setStatus($taskData['status']);
    $task->setCreatedDate($taskData['createdDate']);
    $task->setStartTime($taskData['startTime']);
    $task->setTotalTimeSpent($taskData['totalTimeSpent']);
    return $task;
}

function getTasksFilePath(string $username): string
{
    $dataDir = __DIR__ . '/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    return $dataDir . '/tasks_' . $username . '.json';
}

function loadTasksFromFile(string $username): array
{
    $filePath = getTasksFilePath($username);
    if (!file_exists($filePath)) {
        return [];
    }

    $jsonContent = file_get_contents($filePath);
    if ($jsonContent === false) {
        return [];
    }

    $tasksArray = json_decode($jsonContent, true);
    if (!is_array($tasksArray)) {
        return [];
    }

    $tasks = [];
    foreach ($tasksArray as $taskData) {
        $tasks[] = arrayToTask($taskData);
    }

    return $tasks;
}

function saveTasksToFile(string $username, array $tasks): bool
{
    $filePath = getTasksFilePath($username);
    $tasksArray = [];

    foreach ($tasks as $task) {
        $tasksArray[] = taskToArray($task);
    }

    $jsonContent = json_encode($tasksArray, JSON_PRETTY_PRINT);
    return file_put_contents($filePath, $jsonContent) !== false;
}

function getStatusColor(string $status): string
{
    if ($status === AppConstants::STATUS_PENDING) {
        return "#c87026";
    } elseif ($status === AppConstants::STATUS_IN_PROGRESS) {
        return "blue";
    } else {
        return "green";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');

    if (!empty($username)) {
        $_SESSION['username'] = $username;
    } else {
        $error = "Please enter a valid username";
    }
}

if (isset($_SESSION['username'])) {
    $developer = new Developer($_SESSION['username']);
    $savedTasks = loadTasksFromFile($_SESSION['username']);
    foreach ($savedTasks as $task) {
        $developer->addTask($task);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_task') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $estimatedDuration = (int)($_POST['estimated_duration'] ?? 0);

    if (!empty($title) && $developer !== null) {
        $taskManager = new TaskManager($developer);
        $task = $taskManager->createTask($title, $description, $estimatedDuration);
        if ($task !== null) {
            $_SESSION['successMessage'] = "Task has been created successfully with ID " . $task->getId();
            saveTasksToFile($_SESSION['username'], $developer->getTasks());
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Getting an error while creating a task";
            header('Location: index.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Getting an error while creating a task";
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'list') {
    if (isset($_GET['filter'])) {
        $taskManager = new TaskManager($developer);
        $tasks = $taskManager->getTasksByStatus($_GET['filter']);
    } else {
        $tasks = $developer->getTasks();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'start') {
    $taskManager = new TaskManager($developer);
    $tasks = $taskManager->getStartableTasks();
}

if (isset($_GET['action']) && $_GET['action'] === 'stop') {
    $taskManager = new TaskManager($developer);
    $tasks = $taskManager->getStopableTasks();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start_task' && isset($_POST['task_id'])) {
    if ($developer !== null) {
        $taskId = (int)($_POST['task_id']);
        if ($taskId > 0 && $developer->startTask($taskId)) {
            $_SESSION['successMessage'] = "Task started successfully!";
            saveTasksToFile($_SESSION['username'], $developer->getTasks());
            header('Location: index.php?action=start');
            exit;
        } else {
            $_SESSION['error'] = "Failed to start task. Task may not exist, is already started, or is done.";
            header('Location: index.php?action=start');
            exit;
        }
    } else {
        $_SESSION['error'] = "Please login first.";
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'stop_task' && isset($_POST['task_id'])) {
    if ($developer !== null) {
        $taskId = (int)($_POST['task_id']);
        if ($taskId > 0 && $developer->stopTask($taskId)) {
            $_SESSION['successMessage'] = "Task stopped successfully!";
            saveTasksToFile($_SESSION['username'], $developer->getTasks());
            header('Location: index.php?action=stop');
            exit;
        } else {
            $_SESSION['error'] = "Failed to stop task. Task may not exist, is already stopped, or isn't started.";
            header('Location: index.php?action=stop');
            exit;
        }
    } else {
        $_SESSION['error'] = "Please login first.";
        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'statistics') {
    if ($developer !== null) {
        $tasks = $developer->getTasks();
        $statistics = TaskStatistics::calculateStatistics($tasks);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'edit_task' && isset($_GET['task_id'])) {
    if ($developer !== null) {
        $editTask = $developer->getTaskById((int)($_GET['task_id']));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_task') {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $estimatedDuration = (int)($_POST['estimated_duration'] ?? 0);
    $status = $_POST['status'] ?? '';

    if (!empty($title) && $taskId > 0 && $developer !== null) {
        $taskManager = new TaskManager($developer);
        $task = $taskManager->editTask($taskId, $title, $description, $estimatedDuration, $status);
        if ($task !== null) {
            $_SESSION['successMessage'] = "Task has been updated successfully!";
            saveTasksToFile($_SESSION['username'], $developer->getTasks());
            header('Location: index.php?action=list');
            exit;
        } else {
            $_SESSION['error'] = "Failed to update task. Task may not exist or invalid status.";
            header('Location: index.php?action=edit&id=' . $taskId);
            exit;
        }
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
        header('Location: index.php?action=edit&id=' . $taskId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_status' && isset($_POST['task_id']) && isset($_POST['status'])) {
    $taskId = (int)($_POST['task_id']);
    if ($taskId > 0 && $developer !== null) {
        $taskManager = new TaskManager($developer);
        if ($taskManager->changeTaskStatus($taskId, $_POST['status'])) {
            $_SESSION['successMessage'] = "Task status has been updated successfully!";
            saveTasksToFile($_SESSION['username'], $developer->getTasks());
            header('Location: index.php?action=list');
            exit;
        } else {
            $_SESSION['error'] = "Failed to change task status. Task may not exist or invalid status.";
            header('Location: index.php?action=list');
            exit;
        }
    } else {
        $_SESSION['error'] = "Unexpected error, please try again";
        header('Location: index.php?action=list');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title><?php echo AppConstants::APP_NAME; ?></title>
</head>

<body class="container">
    <?php if ($developer === null): ?>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        <?php include 'views/login.php'; ?>
    <?php else: ?>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        <nav class="navbar bg-body-tertiary mb-3">
            <div class="container">
                <p>Logged in as: <?php echo htmlspecialchars($developer->getName()); ?></p>
                <?php if (isset($_GET['action'])): ?><a href="index.php" class="btn btn-primary">Back to Menu</a><?php endif; ?>
            </div>
        </nav>
        <?php if (isset($_GET['action']) && $_GET['action'] === 'create'): ?>
            <?php include 'views/create_task.php'; ?>
        <?php elseif (isset($_GET['action']) && $_GET['action'] === 'list'): ?>
            <?php include 'views/list_task.php'; ?>
        <?php elseif (isset($_GET['action']) && $_GET['action'] === 'start'): ?>
            <?php include 'views/start_task.php'; ?>
        <?php elseif (isset($_GET['action']) && $_GET['action'] === 'stop'): ?>
            <?php include 'views/stop_task.php'; ?>
        <?php elseif (isset($_GET['action']) && $_GET['action'] === 'statistics'): ?>
            <?php include 'views/statistics.php'; ?>
        <?php elseif (isset($_GET['action']) && $_GET['action'] === 'edit_task'): ?>
            <?php include 'views/edit_task.php'; ?>
        <?php else: ?>
            <?php include 'views/menu.php'; ?>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>