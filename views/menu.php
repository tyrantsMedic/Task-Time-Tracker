<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="text-center mb-4 fw-bold">Main Menu</h2>
            <div class="row g-4">
                <div class="col-4">
                    <a href="index.php?action=create" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">Create New Task</h5>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="index.php?action=list" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">List All Tasks</h5>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="index.php?action=start" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">Start Work on Task</h5>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="index.php?action=stop" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">Stop Work on Task</h5>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="index.php?action=statistics" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">View Statistics</h5>
                        </div>
                    </a>
                </div>
                <div class="col-4">
                    <a href="index.php?action=logout" class="menu-card card h-100 text-decoration-none">
                        <div class="card-body text-center p-4">
                            <h5 class="card-title mb-0">Exit/Logout</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .menu-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
        overflow: hidden;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        border-color: #0d6efd;
        text-decoration: none;
        background: #0d6efd;
        color: white;
    }

    .card-title {
        font-weight: 600;
        color: #212529;
        transition: color 0.3s ease;
        font-size: 1.1rem;
    }

    .menu-card:hover .card-title {
        color: white;
    }

    .menu-card .card-body {
        padding: 2.5rem 1.5rem;
    }
</style>