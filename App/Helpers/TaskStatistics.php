<?php

namespace App\Helpers;

require_once __DIR__ . "/../Models/Task.php";
require_once __DIR__ . "/../Constants/AppConstants.php";

use App\Constants\AppConstants;

final class TaskStatistics
{
    public static function calculateStatistics(array $tasks): array
    {
        $stats = [
            'total' => count($tasks),
            'byStatus' => [
                AppConstants::STATUS_PENDING => 0,
                AppConstants::STATUS_IN_PROGRESS => 0,
                AppConstants::STATUS_DONE => 0,
            ],
            'totalEstimated' => 0,
            'totalActual' => 0,
            'todayTime' => 0,
            'weekTime' => 0,
        ];

        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('-7 days'));

        foreach ($tasks as $task) {
            $taskStatus = $task->getStatus();
            $taskEstimatedDuration = $task->getEstimatedDuration();
            $taskTotalTimeSpent = $task->getTotalTimeSpent();
            $stats['byStatus'][$taskStatus]++;
            $stats['totalEstimated'] += $taskEstimatedDuration;
            $stats['totalActual'] += $taskTotalTimeSpent;
            $createdDate = substr($task->getCreatedDate(), 0, 10);
            if (
                $createdDate === $today ||
                ($task->getStartTime() !== null && substr($task->getStartTime(), 0, 10) === $today)
            ) {
                $stats['todayTime'] += $taskTotalTimeSpent;
            }
            if ($createdDate >= $weekStart) {
                $stats['weekTime'] += $taskTotalTimeSpent;
            }
        }

        return $stats;
    }

    public static function formatMinutes(int $minutes): string
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . 'h ' . $mins . 'm';
    }
}
