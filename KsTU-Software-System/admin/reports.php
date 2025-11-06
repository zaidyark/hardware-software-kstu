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
    <title>Reports - College Hardware Support</title>
    <style>
        /* Same base CSS as other admin pages */
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

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .report-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .report-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .btn {
            display: inline-block;
            background-color: var(--secondary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #2980b9;
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
                <li><a href="technicians.php">Technicians</a></li>
                <li><a href="reports.php" class="active">Reports</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Reports & Analytics</h1>
            <p>Generate detailed reports and analyze support performance.</p>

            <div class="reports-grid">
                <div class="report-card">
                    <div class="report-icon">📊</div>
                    <h3>Ticket Statistics</h3>
                    <p>Comprehensive overview of ticket volumes, status distribution, and trends</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>

                <div class="report-card">
                    <div class="report-icon">⏱️</div>
                    <h3>Response Time Report</h3>
                    <p>Average response times and resolution times by technician and priority</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>

                <div class="report-card">
                    <div class="report-icon">🔧</div>
                    <h3>Technician Performance</h3>
                    <p>Individual technician performance metrics and workload distribution</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>

                <div class="report-card">
                    <div class="report-icon">📈</div>
                    <h3>Trend Analysis</h3>
                    <p>Monthly trends, peak hours, and seasonal patterns in support requests</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>

                <div class="report-card">
                    <div class="report-icon">💻</div>
                    <h3>Hardware Inventory</h3>
                    <p>Detailed report on supported devices and common hardware issues</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>

                <div class="report-card">
                    <div class="report-icon">🎯</div>
                    <h3>SLA Compliance</h3>
                    <p>Service Level Agreement compliance reports and performance metrics</p>
                    <a href="#" class="btn">Generate Report</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>