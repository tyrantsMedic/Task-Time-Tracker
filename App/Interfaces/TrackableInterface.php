<?php

namespace App\Interfaces;

interface TrackableInterface {
    public function startTask(int $taskId): bool;
    public function stopTask(int $taskId): bool;
}