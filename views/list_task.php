<?php

use App\Constants\AppConstants;
use App\Helpers\TaskStatistics;

?>

<div class="d-flex justify-content-between align-items-center">
    <h2>Task List</h2>
    <ul class="filters">
        <li><a href="index.php?action=list" class="filter-link">All</a></li>
        <li><a href="index.php?action=list&filter=pending" class="filter-link">Pending</a></li>
        <li><a href="index.php?action=list&filter=in_progress" class="filter-link">In Progress</a></li>
        <li><a href="index.php?action=list&filter=done" class="filter-link">Done</a></li>
    </ul>
</div>
<?php if (!empty($tasks) && is_array($tasks)): ?>
    <div class="row g-2">
        <?php foreach ($tasks as $task): ?>
            <div class="col-6">
                <div class="card p-0 h-100" style="border-left: 5px solid <?php echo getStatusColor($task->getStatus()); ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><?php echo $task->getTitle(); ?></h4>
                        <div class="d-flex align-items-center gap-2">
                            <p>Task ID: <?php echo $task->getId(); ?></p>
                            <button onclick="location.href='index.php?action=edit_task&task_id=<?php echo $task->getId(); ?>'" <?php echo $task->getStartTime() !== null ? "disabled" : ""; ?> class="btn btn-primary">Edit</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo $task->getDescription(); ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <form method="POST" action="index.php">
                            <input type="hidden" name="action" value="change_status" />
                            <input type="hidden" name="task_id" value="<?php echo $task->getId(); ?>" />
                            <select class="form-select" name="status" onchange="this.form.submit()" style="width: 150px;">
                                <option value="<?php echo AppConstants::STATUS_PENDING ?>" <?php echo $task->getStatus() === AppConstants::STATUS_PENDING ? "selected" : ""; ?>>Pending</option>
                                <option value="<?php echo AppConstants::STATUS_IN_PROGRESS ?>" <?php echo $task->getStatus() === AppConstants::STATUS_IN_PROGRESS ? "selected" : ""; ?>>In Progress</option>
                                <option value="<?php echo AppConstants::STATUS_DONE ?>" <?php echo $task->getStatus() === AppConstants::STATUS_DONE ? "selected" : ""; ?>>Done</option>
                            </select>
                        </form>
                        <p>Created At: <?php echo $task->getCreatedDate() ?></p>
                        <div>
                            <p>Estimated Time: <?php echo TaskStatistics::formatMinutes($task->getEstimatedDuration()) ?></p>
                            <p>Actual Time: <?php echo TaskStatistics::formatMinutes($task->getTotalTimeSpent()) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No tasks found.</p>
<?php endif; ?>

<style>
    p {
        margin: 0;
    }

    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        list-style: none;
    }

    .filter-link {
        padding: 8px 16px;
        text-decoration: none;
        background-color: #f0f0f0;
        border-radius: 5px;
        color: black;
    }

    .filter-link:hover {
        background-color: #e0e0e0;
    }
</style>