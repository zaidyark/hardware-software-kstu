<?php 
include '../includes/config.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_settings'])) {
        $_SESSION['admin_settings'] = [
            'system_name' => $_POST['system_name'],
            'support_email' => $_POST['support_email'],
            'auto_assign' => isset($_POST['auto_assign']),
            'notifications' => isset($_POST['notifications'])
        ];
        $settings_updated = true;
    }
}

// Initialize default settings if not set
if (!isset($_SESSION['admin_settings'])) {
    $_SESSION['admin_settings'] = [
        'system_name' => 'College Hardware Support',
        'support_email' => 'support@college.edu',
        'auto_assign' => true,
        'notifications' => true
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - College Hardware Support</title>
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

        .settings-form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
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
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
                <li><a href="reports.php">Reports</a></li>
                <li><a href="settings.php" class="active">Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>System Settings</h1>
            <p>Configure your support system preferences and options.</p>

            <?php if (isset($settings_updated)): ?>
                <div class="alert alert-success">
                    Settings updated successfully!
                </div>
            <?php endif; ?>

            <div class="settings-form">
                <form action="settings.php" method="POST">
                    <div class="form-group">
                        <label for="system_name">System Name</label>
                        <input type="text" id="system_name" name="system_name" class="form-control" 
                               value="<?php echo htmlspecialchars($_SESSION['admin_settings']['system_name']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="support_email">Support Email</label>
                        <input type="email" id="support_email" name="support_email" class="form-control" 
                               value="<?php echo htmlspecialchars($_SESSION['admin_settings']['support_email']); ?>">
                    </div>

                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="auto_assign" value="1" 
                                   <?php echo $_SESSION['admin_settings']['auto_assign'] ? 'checked' : ''; ?>>
                            Auto-assign tickets to available technicians
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="notifications" value="1" 
                                   <?php echo $_SESSION['admin_settings']['notifications'] ? 'checked' : ''; ?>>
                            Enable email notifications for new tickets
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="sla_response">SLA Response Time (hours)</label>
                        <select id="sla_response" name="sla_response" class="form-control">
                            <option value="1">1 hour</option>
                            <option value="2">2 hours</option>
                            <option value="4" selected>4 hours</option>
                            <option value="8">8 hours</option>
                            <option value="24">24 hours</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="default_priority">Default Ticket Priority</label>
                        <select id="default_priority" name="default_priority" class="form-control">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>

                    <button type="submit" name="update_settings" class="btn">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>