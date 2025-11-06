<?php 
include '../includes/config.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technicians - College Hardware Support</title>
    <style>
        /* Same CSS structure as above but specific to technicians */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 0;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: block;
            color: white;
            padding: 0.8rem 1.5rem;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.1);
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            background: #f8f9fa;
        }

        .header-bar {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -2rem -2rem 2rem -2rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .technician-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .technician-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .tech-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .tech-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }

        .specialty-tag {
            display: inline-block;
            background: #e3f2fd;
            color: var(--secondary-color);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 0.2rem;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <div class="logo">College Hardware Support - Admin Panel</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="dashboard.php?logout=true" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view-tickets.php">View Tickets</a></li>
                <li><a href="technicians.php" class="active">Technicians</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Technician Management</h1>
            <p>Manage your support team and assign tickets to technicians.</p>

            <div class="technician-grid">
                <div class="technician-card">
                    <div class="tech-avatar">JS</div>
                    <h3>John Smith</h3>
                    <p>Senior IT Support Specialist</p>
                    <div class="tech-stats">
                        <div class="stat">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Active Tickets</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">94%</div>
                            <div class="stat-label">Success Rate</div>
                        </div>
                    </div>
                    <div>
                        <span class="specialty-tag">Hardware</span>
                        <span class="specialty-tag">Networking</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 1rem;">View Profile</a>
                </div>

                <div class="technician-card">
                    <div class="tech-avatar">SJ</div>
                    <h3>Sarah Johnson</h3>
                    <p>IT Support Technician</p>
                    <div class="tech-stats">
                        <div class="stat">
                            <div class="stat-number">8</div>
                            <div class="stat-label">Active Tickets</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">89%</div>
                            <div class="stat-label">Success Rate</div>
                        </div>
                    </div>
                    <div>
                        <span class="specialty-tag">Software</span>
                        <span class="specialty-tag">Printers</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 1rem;">View Profile</a>
                </div>

                <div class="technician-card">
                    <div class="tech-avatar">MD</div>
                    <h3>Mike Davis</h3>
                    <p>Hardware Specialist</p>
                    <div class="tech-stats">
                        <div class="stat">
                            <div class="stat-number">6</div>
                            <div class="stat-label">Active Tickets</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">96%</div>
                            <div class="stat-label">Success Rate</div>
                        </div>
                    </div>
                    <div>
                        <span class="specialty-tag">Laptops</span>
                        <span class="specialty-tag">Desktops</span>
                        <span class="specialty-tag">Mobile</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 1rem;">View Profile</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>