<h2>Create New Task</h2>
<form class="container" method="POST" action="index.php">
    <input type="hidden" name="action" value="create_task">
    <div class="mb-3">
        <label class="form-label" for="title">Title</label>
        <input class="form-control" type="text" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label" for="estimated_duration">Estimated Duration</label>
        <input class="form-control" type="number" id="estimated_duration" name="estimated_duration" min="0" value="0">
    </div>
    <button type="submit" class="btn btn-primary">Create Task</button>
</form>