<h2>Stop Work on Task</h2>
<?php if (!empty($tasks) && is_array($tasks)): ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Status</th>
                <th scope="col">Estimated Time</th>
                <th scope="col">Actual Time</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo $task->getId(); ?></td>
                    <td><?php echo $task->getTitle(); ?></td>
                    <td><?php echo $task->getStatus(); ?></td>
                    <td><?php echo \App\Helpers\TaskStatistics::formatMinutes($task->getEstimatedDuration()) ?></td>
                    <td><?php echo \App\Helpers\TaskStatistics::formatMinutes($task->getTotalTimeSpent()); ?></td>
                    <td><?php echo $task->getCreatedDate(); ?></td>
                    <td>
                        <form method="POST" action="index.php" style="display: inline;">
                            <input type="hidden" name="action" value="stop_task">
                            <input type="hidden" name="task_id" value="<?php echo $task->getId(); ?>">
                            <button type="submit" class="btn btn-success">Stop Work</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No tasks available.</p>
<?php endif; ?>