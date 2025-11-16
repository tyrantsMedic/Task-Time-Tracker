<div class="vh-100 d-flex justify-content-center align-items-center">
    <form method="POST" action="index.php">
        <input type="hidden" name="action" value="login">
        <label for="username" class="form-label">Username</label>
        <div class="d-flex gap-2">
            <input id="username" class="form-control" type="text" name="username" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
    <div>