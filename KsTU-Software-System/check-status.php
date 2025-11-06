<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Ticket Status - College Hardware Support</title>
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
        }

        a {
            text-decoration: none;
            color: var(--secondary-color);
        }

        ul {
            list-style: none;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-menu {
            display: flex;
        }

        .nav-menu li {
            margin-left: 1.5rem;
        }

        .nav-menu a {
            color: white;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: var(--secondary-color);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #c0392b;
        }

        .status-check {
            background-color: var(--light-color);
            padding: 4rem 0;
            text-align: center;
        }

        .status-form {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .footer-links {
            display: flex;
            margin: 1.5rem 0;
        }

        .footer-links a {
            color: white;
            margin: 0 1rem;
        }

        .copyright {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }

        .ticket-details {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: left;
        }

        .ticket-info {
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: flex;
            margin-bottom: 0.5rem;
        }

        .info-label {
            width: 150px;
            font-weight: 600;
        }

        .info-value {
            flex: 1;
        }

        .ticket-description {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
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

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: var(--primary-color);
                padding: 1rem 0;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu li {
                margin: 0.5rem 0;
                text-align: center;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">College Hardware Support</div>
            <button class="mobile-menu-btn">☰</button>
            <ul class="nav-menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="submit-ticket.php">Submit Ticket</a></li>
                <li><a href="check-status.php" class="active">Check Status</a></li>
                <li><a href="admin/login.php">Admin Login</a></li>
            </ul>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Check Ticket Status</h1>
            <p>Enter your ticket ID and email to check the status of your support request.</p>
        </div>
    </section>

    <section class="status-check">
        <div class="container">
            <div class="status-form">
                <h2 class="section-title">Check Your Ticket</h2>
                <form action="check-status.php" method="POST">
                    <div class="form-group">
                        <label for="ticket-id">Ticket ID</label>
                        <input type="text" id="ticket-id" name="ticket_id" class="form-control" placeholder="Enter your ticket ID" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn">Check Status</button>
                </form>
                
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $ticket_id = htmlspecialchars($_POST['ticket_id']);
                    $email = htmlspecialchars($_POST['email']);
                    
                    // Simulate ticket lookup
                    if (!empty($ticket_id) && !empty($email)) {
                        echo '<div class="ticket-details" style="margin-top: 2rem;">';
                        echo '<h3>Ticket Status</h3>';
                        echo '<div class="ticket-info">';
                        echo '<div class="info-row"><div class="info-label">Ticket ID:</div><div class="info-value">' . $ticket_id . '</div></div>';
                        echo '<div class="info-row"><div class="info-label">Status:</div><div class="info-value"><span class="status-badge status-in-progress">In Progress</span></div></div>';
                        echo '<div class="info-row"><div class="info-label">Submitted:</div><div class="info-value">2023-10-15 14:30</div></div>';
                        echo '<div class="info-row"><div class="info-label">Last Updated:</div><div class="info-value">2023-10-16 09:15</div></div>';
                        echo '<div class="info-row"><div class="info-label">Assigned To:</div><div class="info-value">John Smith (IT Support)</div></div>';
                        echo '</div>';
                        echo '<div class="ticket-description">';
                        echo '<h4>Problem Description</h4>';
                        echo '<p>Laptop not turning on. No power indicator lights when connected to charger. Tried different power outlets with no success.</p>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-error">Please enter both ticket ID and email address.</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-content">
            <div class="logo">College Hardware Support</div>
            <div class="footer-links">
                <a href="index.html">Home</a>
                <a href="submit-ticket.php">Submit Ticket</a>
                <a href="check-status.php">Check Status</a>
                <a href="admin/login.php">Admin Login</a>
            </div>
            <div class="copyright">
                &copy; 2023 College Hardware Support System. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.nav-menu').classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.querySelector('.nav-menu');
            const button = document.querySelector('.mobile-menu-btn');
            
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>