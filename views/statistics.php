<?php

use App\Helpers\TaskStatistics;

?>

<h2 class="text-center mb-4 fw-bold">Task Statistics</h2>

<?php if (!empty($statistics) && is_array($statistics)): ?>
    <div class="row g-4">
        <!-- Total Tasks Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <h3 class="stat-value mb-2"><?php echo $statistics['total']; ?></h3>
                    <p class="stat-label mb-0">Total Tasks</p>
                </div>
            </div>
        </div>

        <!-- Tasks by Status Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Tasks by Status</h5>
                    <div class="status-item mb-2">
                        <span class="status-badge status-pending"></span>
                        <span class="status-label">Pending</span>
                        <span class="status-value"><?php echo $statistics['byStatus']['pending']; ?></span>
                    </div>
                    <div class="status-item mb-2">
                        <span class="status-badge status-in-progress"></span>
                        <span class="status-label">In Progress</span>
                        <span class="status-value"><?php echo $statistics['byStatus']['in_progress']; ?></span>
                    </div>
                    <div class="status-item">
                        <span class="status-badge status-done"></span>
                        <span class="status-label">Done</span>
                        <span class="status-value"><?php echo $statistics['byStatus']['done']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Tracking Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Time Tracking</h5>
                    <div class="time-item mb-3">
                        <span class="time-label">Estimated</span>
                        <span class="time-value"><?php echo TaskStatistics::formatMinutes($statistics['totalEstimated']); ?></span>
                    </div>
                    <div class="time-item mb-3">
                        <span class="time-label">Actual</span>
                        <span class="time-value text-success"><?php echo TaskStatistics::formatMinutes($statistics['totalActual']); ?></span>
                    </div>
                    <div class="time-item mb-3">
                        <span class="time-label">Today</span>
                        <span class="time-value text-primary"><?php echo TaskStatistics::formatMinutes($statistics['todayTime']); ?></span>
                    </div>
                    <div class="time-item">
                        <span class="time-label">This Week</span>
                        <span class="time-value text-info"><?php echo TaskStatistics::formatMinutes($statistics['weekTime']); ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php else: ?>
    <div class="alert alert-info text-center">
        <p class="mb-0">No statistics available.</p>
    </div>
<?php endif; ?>

<style>
    .stat-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin: 0;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .status-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
    }
    
    .status-badge {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .status-pending {
        background-color: #c87026;
    }
    
    .status-in-progress {
        background-color: #0d6efd;
    }
    
    .status-done {
        background-color: #198754;
    }
    
    .status-label {
        flex: 1;
        font-size: 0.95rem;
        color: #495057;
    }
    
    .status-value {
        font-weight: 600;
        color: #212529;
        font-size: 1.1rem;
    }
    
    .time-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .time-item:last-child {
        border-bottom: none;
    }
    
    .time-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .time-value {
        font-weight: 600;
        font-size: 1rem;
    }
    
    .card-title {
        font-weight: 600;
        color: #212529;
        font-size: 1.1rem;
    }
</style>