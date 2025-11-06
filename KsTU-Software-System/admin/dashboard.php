<?php 
// Include config at the very top - NO WHITESPACE BEFORE THIS
include '../includes/config.php';

// Check if user is logged in, redirect to login if not
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - College Hardware Support</title>
    <style>
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

        a {
            text-decoration: none;
            color: var(--secondary-color);
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

        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #2980b9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .ticket-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }

        .status-open {
            background-color: var(--warning-color);
        }

        .status-in-progress {
            background-color: var(--secondary-color);
        }

        .status-resolved {
            background-color: var(--success-color);
        }

        .status-closed {
            background-color: #95a5a6;
        }

        .priority-high {
            color: var(--danger-color);
            font-weight: bold;
        }

        .priority-medium {
            color: var(--warning-color);
            font-weight: bold;
        }

        .priority-low {
            color: var(--success-color);
            font-weight: bold;
        }

        .priority-critical {
            color: var(--danger-color);
            font-weight: bold;
            background: #f8d7da;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .action-card:hover {
            transform: translateY(-2px);
        }

        .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .no-tickets {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1rem 0;
            }

            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }

            .sidebar-menu li {
                margin-bottom: 0;
            }

            .sidebar-menu a {
                padding: 0.5rem 1rem;
                border-radius: 4px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .header-bar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .user-info {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <div class="logo">College Hardware Support - Admin Panel</div>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="?logout=true" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="view-tickets.php">View Tickets</a></li>
                <li><a href="technicians.php">Technicians</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="welcome-section">
                <h1>Admin Dashboard</h1>
                <p>Welcome to the College Hardware Support administration panel. Here's an overview of your support system.</p>
                
                <div class="quick-actions">
                    <div class="action-card">
                        <div class="action-icon">📝</div>
                        <h3>New Ticket</h3>
                        <p>Create a new support ticket</p>
                        <a href="../submit-ticket.php" class="btn">Create</a>
                    </div>
                    <div class="action-card">
                        <div class="action-icon">👥</div>
                        <h3>Manage Team</h3>
                        <p>View and manage technicians</p>
                        <a href="technicians.php" class="btn">Manage</a>
                    </div>
                    <div class="action-card">
                        <div class="action-icon">📊</div>
                        <h3>Reports</h3>
                        <p>Generate system reports</p>
                        <a href="reports.php" class="btn">View</a>
                    </div>
                    <div class="action-card">
                        <div class="action-icon">⚙️</div>
                        <h3>Settings</h3>
                        <p>Configure system settings</p>
                        <a href="settings.php" class="btn">Configure</a>
                    </div>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($_SESSION['tickets'] ?? []); ?></div>
                    <div class="stat-label">Total Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php 
                        $open_tickets = 0;
                        if (isset($_SESSION['tickets'])) {
                            foreach ($_SESSION['tickets'] as $ticket) {
                                if ($ticket['status'] === 'Open') $open_tickets++;
                            }
                        }
                        echo $open_tickets;
                    ?></div>
                    <div class="stat-label">Open Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php 
                        $in_progress = 0;
                        if (isset($_SESSION['tickets'])) {
                            foreach ($_SESSION['tickets'] as $ticket) {
                                if ($ticket['status'] === 'In Progress') $in_progress++;
                            }
                        }
                        echo $in_progress;
                    ?></div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php 
                        $resolved = 0;
                        if (isset($_SESSION['tickets'])) {
                            foreach ($_SESSION['tickets'] as $ticket) {
                                if ($ticket['status'] === 'Resolved') $resolved++;
                            }
                        }
                        echo $resolved;
                    ?></div>
                    <div class="stat-label">Resolved Today</div>
                </div>
            </div>
            
            <h2>Recent Tickets</h2>
            <div class="ticket-table">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Submitted By</th>
                            <th>Issue</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_SESSION['tickets']) && !empty($_SESSION['tickets'])): ?>
                            <?php 
                            $recent_tickets = array_slice($_SESSION['tickets'], -5, 5, true);
                            $recent_tickets = array_reverse($recent_tickets);
                            ?>
                            <?php foreach ($recent_tickets as $ticket): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ticket['ticket_id']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['issue_category']); ?></td>
                                    <td class="priority-<?php echo strtolower($ticket['priority']); ?>">
                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $ticket['status'])); ?>">
                                            <?php echo htmlspecialchars($ticket['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view-ticket-details.php?id=<?php echo $ticket['ticket_id']; ?>" class="btn btn-secondary">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-tickets">
                                    No tickets found. <a href="../submit-ticket.php">Submit a ticket</a> to get started.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Simple dashboard functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin Dashboard Loaded');
            
            // Add click handlers for stat cards if needed
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('click', function() {
                    // You can add functionality here, like filtering tickets
                    console.log('Stat card clicked');
                });
            });
        });
    </script>
</body>
</html>