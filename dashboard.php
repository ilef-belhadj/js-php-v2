<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['parent_id'])) {
    header("Location: login.php");
    exit();
}

// Get parent information
try {
    $stmt = $pdo->prepare("SELECT * FROM parents WHERE id = ?");
    $stmt->execute([$_SESSION['parent_id']]);
    $parent = $stmt->fetch();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mes premiers pas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            position: fixed;
            height: 100vh;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .topbar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text {
            font-size: 1.2em;
            color: var(--dark-color);
        }

        .logout-btn {
            background: var(--danger-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            color: var(--dark-color);
            font-size: 1.1em;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .bg-primary {
            background: var(--primary-color);
        }

        .bg-success {
            background: var(--success-color);
        }

        .bg-warning {
            background: var(--warning-color);
        }

        .bg-info {
            background: var(--info-color);
        }

        .card-value {
            font-size: 1.8em;
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .card-label {
            color: var(--secondary-color);
            font-size: 0.9em;
        }

        /* Recent Activity Section */
        .recent-activity {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .activity-details {
            flex: 1;
        }

        .activity-title {
            font-weight: bold;
            color: var(--dark-color);
        }

        .activity-time {
            color: var(--secondary-color);
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo.png" alt="Logo">
                <h3>Admin Panel</h3>
            </div>
            <div class="sidebar-menu">
                <a href="#" class="menu-item">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    Students
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Teachers
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-calendar-alt"></i>
                    Schedule
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-book"></i>
                    Courses
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="welcome-text">
                    Welcome, <?php echo htmlspecialchars($parent['first_name']); ?>!
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Total Students</div>
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value">150</div>
                    <div class="card-label">Active Students</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Teachers</div>
                        <div class="card-icon bg-success">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="card-value">12</div>
                    <div class="card-label">Active Teachers</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Courses</div>
                        <div class="card-icon bg-warning">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="card-value">25</div>
                    <div class="card-label">Active Courses</div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Events</div>
                        <div class="card-icon bg-info">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <div class="card-value">8</div>
                    <div class="card-label">Upcoming Events</div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <h3>Recent Activity</h3>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">New Student Registration</div>
                            <div class="activity-time">2 hours ago</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">New Course Added</div>
                            <div class="activity-time">5 hours ago</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">Event Scheduled</div>
                            <div class="activity-time">1 day ago</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>