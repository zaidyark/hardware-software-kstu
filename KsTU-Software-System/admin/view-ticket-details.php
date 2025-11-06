<?php 
// Include config at the very top
include '../includes/config.php';

// Check if user is logged in
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

// Handle ticket actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $ticket_id = $_GET['id'];
    $action = $_GET['action'];
    
    if (isset($_SESSION['tickets'][$ticket_id])) {
        switch ($action) {
            case 'delete':
                unset($_SESSION['tickets'][$ticket_id]);
                break;
            case 'close':
                $_SESSION['tickets'][$ticket_id]['status'] = 'Closed';
                $_SESSION['tickets'][$ticket_id]['updated_at'] = date('Y-m-d H:i:s');
                $_SESSION['tickets'][$ticket_id]['updated_by'] = $_SESSION['admin_username'];
                break;
        }
    }
    header('Location: view-tickets.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tickets - College Hardware Support</title>
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

        .btn-success {
            background-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .btn-danger {
            background-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
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

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .no-tickets {
            text-align: center;
            padding: 3rem;
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

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.5rem;
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="view-tickets.php" class="active">View Tickets</a></li>
                <li><a href="technicians.php">Technicians</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h1>All Support Tickets</h1>
            
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($_SESSION['tickets'] ?? []); ?></div>
                    <div class="stat-label">Total Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php 
                        $open = 0;
                        if (isset($_SESSION['tickets'])) {
                            foreach ($_SESSION['tickets'] as $ticket) {
                                if ($ticket['status'] === 'Open') $open++;
                            }
                        }
                        echo $open;
                    ?></div>
                    <div class="stat-label">Open</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php 
                        $progress = 0;
                        if (isset($_SESSION['tickets'])) {
                            foreach ($_SESSION['tickets'] as $ticket) {
                                if ($ticket['status'] === 'In Progress') $progress++;
                            }
                        }
                        echo $progress;
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
                    <div class="stat-label">Resolved</div>
                </div>
            </div>

            <div class="filters">
                <div class="filter-group">
                    <select class="filter-select" onchange="filterTickets()" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="Open">Open</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>
                    <select class="filter-select" onchange="filterTickets()" id="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                    <input type="text" class="filter-select" placeholder="Search..." onkeyup="filterTickets()" id="searchFilter">
                </div>
            </div>

            <div class="ticket-table">
                <table>
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Submitted By</th>
                            <th>Department</th>
                            <th>Issue</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ticketTableBody">
                        <?php if (isset($_SESSION['tickets']) && !empty($_SESSION['tickets'])): ?>
                            <?php foreach ($_SESSION['tickets'] as $ticket): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ticket['ticket_id']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['department']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['issue_category']); ?></td>
                                    <td class="priority-<?php echo strtolower($ticket['priority']); ?>">
                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $ticket['status'])); ?>">
                                            <?php echo htmlspecialchars($ticket['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="view-ticket-details.php?id=<?php echo $ticket['ticket_id']; ?>" class="btn btn-secondary">View</a>
                                            <?php if ($ticket['status'] !== 'Closed'): ?>
                                                <a href="?action=close&id=<?php echo $ticket['ticket_id']; ?>" class="btn btn-success">Close</a>
                                            <?php endif; ?>
                                            <a href="?action=delete&id=<?php echo $ticket['ticket_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="no-tickets">
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
        function filterTickets() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const priorityFilter = document.getElementById('priorityFilter').value.toLowerCase();
            const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
            const rows = document.querySelectorAll('#ticketTableBody tr');
            
            rows.forEach(row => {
                const status = row.querySelector('.status-badge')?.textContent.toLowerCase() || '';
                const priority = row.cells[4]?.textContent.toLowerCase() || '';
                const rowText = row.textContent.toLowerCase();
                
                const statusMatch = !statusFilter || status.includes(statusFilter);
                const priorityMatch = !priorityFilter || priority.includes(priorityFilter);
                const searchMatch = !searchFilter || rowText.includes(searchFilter);
                
                row.style.display = (statusMatch && priorityMatch && searchMatch) ? '' : 'none';
            });
        }
    </script>
</body>
</html>