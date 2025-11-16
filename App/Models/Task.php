<?php

namespace App\Models;

require_once __DIR__ . "/../Constants/AppConstants.php";

class Task
{
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private int $estimatedDuration;
    private int $totalTimeSpent;
    private string $createdDate;
    private ?string $startTime = null;

    public function __construct(int $id, string $title, string $description, int $estimatedDuration = 0)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->estimatedDuration = $estimatedDuration;
        $this->status = \App\Constants\AppConstants::STATUS_PENDING;
        $this->totalTimeSpent = 0;
        $this->createdDate = date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function getEstimatedDuration(): int
    {
        return $this->estimatedDuration;
    }

    public function getTotalTimeSpent(): int
    {
        return $this->totalTimeSpent;
    }

    public function getCreatedDate(): string
    {
        return $this->createdDate;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setCreatedDate(string $date): void
    {
        $this->createdDate = $date;
    }

    public function setEstimatedDuration(int $estimatedDuration): void
    {
        $this->estimatedDuration = $estimatedDuration;
    }

    public function setStartTime(?string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function setTotalTimeSpent(int $minutes): void
    {
        $this->totalTimeSpent = $minutes;
    }

    public function addTimeSpent(int $minutes): void
    {
        $this->totalTimeSpent += $minutes;
    }
}
