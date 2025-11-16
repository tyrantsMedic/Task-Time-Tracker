<?php

use App\Constants\AppConstants;

?>

<h2>Edit Task</h2>
<?php if ($editTask === null): ?>
    <p>No task exists with the given ID</p>
<?php elseif ($editTask->getStartTime() === null): ?>
    <form class="container" method="POST" action="index.php">
        <input type="hidden" name="action" value="edit_task">
        <input type="hidden" name="task_id" value="<?php echo $editTask->getId(); ?>">

        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?php echo $editTask->getTitle(); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo $editTask->getDescription(); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="estimated_duration">Estimated Duration</label>
            <input class="form-control" type="number" id="estimated_duration" name="estimated_duration" min="0" value="<?php echo $editTask->getEstimatedDuration(); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" name="status" style="width: 200px;">
                <option value="<?php echo AppConstants::STATUS_PENDING ?>" <?php echo $editTask->getStatus() === AppConstants::STATUS_PENDING ? "selected" : ""; ?>>Pending</option>
                <option value="<?php echo AppConstants::STATUS_IN_PROGRESS ?>" <?php echo $editTask->getStatus() === AppConstants::STATUS_IN_PROGRESS ? "selected" : ""; ?>>In Progress</option>
                <option value="<?php echo AppConstants::STATUS_DONE ?>" <?php echo $editTask->getStatus() === AppConstants::STATUS_DONE ? "selected" : ""; ?>>Done</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Edit Task</button>
    </form>
<?php else: ?>
    <p>Cannot Edit a Started Task</p>
<?php endif; ?>

<style>
    #description {
        height: 200px;
    }
</style>