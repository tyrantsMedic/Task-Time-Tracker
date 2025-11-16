<?php

namespace App\Constants;

class AppConstants
{
    public const string APP_NAME = "Task & Time Tracker";
    public const int MAX_TASKS_PER_USER = 50;
    public const string STATUS_PENDING = "pending";
    public const string STATUS_IN_PROGRESS = "in_progress";
    public const string STATUS_DONE = "done";

    public static function getAllowedStatuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_DONE];
    }
}
