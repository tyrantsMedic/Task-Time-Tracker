<?php

namespace App\Services;

require_once __DIR__ . "/../Models/Developer.php";
require_once __DIR__ . "/../Models/Task.php";
require_once __DIR__ . "/../Constants/AppConstants.php";

use App\Models\Developer;
use App\Models\Task;
use App\Constants\AppConstants;

class TaskManager
{
    private Developer $developer;

    public function __construct(Developer $developer)
    {
        $this->developer = $developer;
    }

    public function getDeveloper(): Developer
    {
        return $this->developer;
    }

    public function createTask(string $title, string $description, int $estimatedDuration = 0): ?Task
    {
        if (count($this->developer->getTasks()) < \App\Constants\AppConstants::MAX_TASKS_PER_USER) {
            $generatedId = Developer::generateTaskId($this->developer);
            $task = new Task($generatedId, $title, $description, $estimatedDuration);
            $isTaskAdded = $this->developer->addTask($task);

            if ($isTaskAdded) {
                return $task;
            } else {
                return null;
            }
        }
        return null;
    }

    public function editTask(int $taskId, string $title, string $description, int $estimatedDuration, string $status): ?Task
    {
        if ($taskId > 0) {
            if (!in_array($status, AppConstants::getAllowedStatuses())) {
                return null;
            }
            $task = $this->developer->getTaskById($taskId);
            if ($task !== null) {
                $task->setTitle($title);
                $task->setDescription($description);
                $task->setEstimatedDuration($estimatedDuration);
                $task->setStatus($status);
                return $task;
            }
        }
        return null;
    }

    public function changeTaskStatus(int $taskId, string $newStatus): bool
    {
        if (in_array($newStatus, AppConstants::getAllowedStatuses())) {
            $task = $this->developer->getTaskById($taskId);
            if ($task === null) {
                return false;
            } else if ($task->getStartTime() !== null && $newStatus !== AppConstants::STATUS_IN_PROGRESS) {
                $isTaskStopped = $this->developer->stopTask($taskId);
                if ($isTaskStopped) {
                    $task->setStatus($newStatus);
                    return true;
                } else {
                    return false;
                }
            } else {
                $task->setStatus($newStatus);
                return true;
            }
        }
        return false;
    }

    public function getTasksByStatus(?string $status = null): array
    {
        $tasks = $this->developer->getTasks();
        $filteredTasks = [];
        if ($status === null) {
            return $tasks;
        } else {
            foreach ($tasks as $task) {
                if ($task->getStatus() === $status) {
                    $filteredTasks[] = $task;
                }
            }
            return $filteredTasks;
        }
    }

    public function getStartableTasks(): array
    {
        $tasks = $this->developer->getTasks();
        $startableTasks = [];
        foreach ($tasks as $task) {
            if ($task->getStartTime() === null && ($task->getStatus() === AppConstants::STATUS_PENDING || $task->getStatus() === AppConstants::STATUS_IN_PROGRESS)) {
                $startableTasks[] = $task;
            }
        }
        return $startableTasks;
    }

    public function getStopableTasks(): array
    {
        $tasks = $this->developer->getTasks();
        $stopableTasks = [];
        foreach ($tasks as $task) {
            if ($task->getStartTime() !== null) {
                $stopableTasks[] = $task;
            }
        }
        return $stopableTasks;
    }
}
