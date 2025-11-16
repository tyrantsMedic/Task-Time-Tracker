<?php

namespace App\Models;

use App\Interfaces\TrackableInterface;

require_once __DIR__ . "/User.php";
require_once __DIR__ . "/Task.php";
require_once __DIR__ . "/../Constants/AppConstants.php";
require_once __DIR__ . "/../Interfaces/TrackableInterface.php";


class Developer extends User implements TrackableInterface
{
    private array $tasks = [];

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function getRole(): string
    {
        return "developer";
    }

    public function addTask(Task $task): bool
    {
        if (count($this->tasks) < \App\Constants\AppConstants::MAX_TASKS_PER_USER) {
            $this->tasks[] = $task;
            return true;
        } else {
            return false;
        }
    }

    public static function generateTaskId(Developer $developer): int
    {
        $tasks = $developer->getTasks();
        if (empty($tasks)) {
            return 1;
        }

        $maxId = 0;
        foreach ($tasks as $task) {
            if ($task->getId() > $maxId) {
                $maxId = $task->getId();
            }
        }

        return $maxId + 1;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getTaskById(int $taskId): ?Task
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $taskId) {
                return $task;
            }
        }
        return null;
    }

    public function startTask(int $taskId): bool
    {
        $task = $this->getTaskById($taskId);
        if ($task === null) {
            return false;
        } elseif ($task->getStatus() === \App\Constants\AppConstants::STATUS_DONE) {
            return false;
        } elseif ($task->getStartTime() !== null) {
            return false;
        } else {
            $task->setStatus(\App\Constants\AppConstants::STATUS_IN_PROGRESS);
            $task->setStartTime(date('Y-m-d H:i:s'));
            return true;
        }
    }

    public function stopTask(int $taskId): bool
    {
        $task = $this->getTaskById($taskId);
        if ($task === null) {
            return false;
        } elseif ($task->getStartTime() === null) {
            return false;
        } else {
            $startTime = strtotime($task->getStartTime());
            $endTime = time();
            $minutesSpent = (int)round(($endTime - $startTime) / 60);
            $task->addTimeSpent($minutesSpent);
            $task->setStartTime(null);
            return true;
        }
    }
}
