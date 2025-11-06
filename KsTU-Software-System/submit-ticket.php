<?php 
// Include config at the very top
include 'includes/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $device_type = isset($_POST['device_type']) ? trim($_POST['device_type']) : '';
    $device_model = isset($_POST['device_model']) ? trim($_POST['device_model']) : '';
    $issue_category = isset($_POST['issue_category']) ? trim($_POST['issue_category']) : '';
    $priority = isset($_POST['priority']) ? trim($_POST['priority']) : 'Medium';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    
    // Basic validation
    $errors = [];
    
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($department)) $errors[] = "Department is required";
    if (empty($device_type)) $errors[] = "Device type is required";
    if (empty($device_model)) $errors[] = "Device model is required";
    if (empty($issue_category)) $errors[] = "Issue category is required";
    if (empty($description)) $errors[] = "Problem description is required";
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (!empty($description) && strlen($description) < 10) {
        $errors[] = "Problem description must be at least 10 characters long";
    }
    
    if (empty($errors)) {
        // Generate ticket ID
        $ticket_id = generateTicketID();
        
        // Store ticket data in session
        $_SESSION['tickets'][$ticket_id] = [
            'name' => htmlspecialchars($name),
            'email' => htmlspecialchars($email),
            'phone' => htmlspecialchars($phone),
            'department' => htmlspecialchars($department),
            'device_type' => htmlspecialchars($device_type),
            'device_model' => htmlspecialchars($device_model),
            'issue_category' => htmlspecialchars($issue_category),
            'priority' => htmlspecialchars($priority),
            'description' => htmlspecialchars($description),
            'status' => 'Open',
            'created_at' => date('Y-m-d H:i:s'),
            'ticket_id' => $ticket_id
        ];
        
        $success = true;
        $submitted_ticket_id = $ticket_id;
        
        // Clear form data
        $name = $email = $phone = $department = $device_type = $device_model = $issue_category = $priority = $description = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket - College Hardware Support</title>
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
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: var(--secondary-color);
            transition: color 0.3s;
        }

        a:hover {
            color: var(--primary-color);
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

        /* Header Styles */
        header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
        }

        .nav-menu a {
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient);
            color: white;
            padding: 4rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="1000,100 1000,0 0,100"/></svg>');
            background-size: cover;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            position: relative;
            opacity: 0.9;
        }

        /* Main Content */
        .main-content {
            padding: 4rem 0;
        }

        .ticket-form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            transform: translateY(-50px);
            margin-top: -50px;
        }

        .form-header {
            background: var(--gradient);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-header h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            opacity: 0.9;
        }

        .form-body {
            padding: 3rem;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            line-height: 1.5;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            background: var(--gradient);
            color: white;
            padding: 1.2rem 2.5rem;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-block {
            width: 100%;
        }

        /* Success Message */
        .success-container {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .success-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .ticket-id-display {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            border-left: 4px solid var(--success-color);
        }

        .ticket-id {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            font-family: 'Courier New', monospace;
        }

        /* Alert Styles */
        .alert {
            padding: 1.2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid transparent;
        }

        .alert-error {
            background: #fdeded;
            color: #5f2120;
            border-left-color: var(--danger-color);
        }

        .alert-success {
            background: #edf7ed;
            color: #1e4620;
            border-left-color: var(--success-color);
        }

        /* Footer */
        footer {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-logo {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #3498db, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            margin: 2rem 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-links a {
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .copyright {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.7);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--primary-color);
                padding: 1rem 0;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }

            .nav-menu.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2.2rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-body {
                padding: 2rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 3rem 0;
            }

            .hero h1 {
                font-size: 1.8rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .btn {
                padding: 1rem 2rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Priority Colors */
        .priority-low { color: var(--success-color); font-weight: bold; }
        .priority-medium { color: var(--warning-color); font-weight: bold; }
        .priority-high { color: var(--danger-color); font-weight: bold; }
        .priority-critical { 
            color: var(--danger-color); 
            font-weight: bold; 
            background: #f8d7da; 
            padding: 0.3rem 0.8rem; 
            border-radius: 20px; 
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">College Hardware Support</div>
            <button class="mobile-menu-btn" type="button">☰</button>
            <ul class="nav-menu">
                <li><a href="index.html">Home</a></li>
                <li><a href="submit-ticket.php" class="active">Submit Ticket</a></li>
                <li><a href="check-status.php">Check Status</a></li>
                <li><a href="admin/login.php">Admin Login</a></li>
            </ul>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1 class="fade-in-up">Submit a Support Ticket</h1>
            <p class="fade-in-up">Get expert help for all your hardware issues. Our team is ready to assist you!</p>
        </div>
    </section>

    <section class="main-content">
        <div class="container">
            <?php if (isset($success) && $success): ?>
                <div class="success-container fade-in-up">
                    <div class="success-icon">✅</div>
                    <h2>Ticket Submitted Successfully!</h2>
                    <div class="ticket-id-display">
                        <strong>Your Ticket ID:</strong><br>
                        <span class="ticket-id"><?php echo $submitted_ticket_id; ?></span>
                    </div>
                    <p>We have received your support request and will contact you shortly.</p>
                    <p><strong>Please save your Ticket ID for future reference.</strong></p>
                    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                        <a href="check-status.php" class="btn">Check Status</a>
                        <a href="submit-ticket.php" class="btn" style="background: var(--secondary-color);">Submit Another Ticket</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="ticket-form-container fade-in-up">
                    <div class="form-header">
                        <h2>Hardware Support Request</h2>
                        <p>Fill out the form below and we'll get back to you ASAP</p>
                    </div>
                    
                    <div class="form-body">
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-error">
                                <h4>Please fix the following errors:</h4>
                                <ul style="margin-left: 1.5rem;">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="submit-ticket.php" method="POST" id="ticketForm">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" id="name" name="name" class="form-control" 
                                           value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" 
                                           required placeholder="Enter your full name">
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" 
                                           required placeholder="your.email@college.edu">
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" 
                                           value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" 
                                           required placeholder="(555) 123-4567">
                                </div>

                                <div class="form-group">
                                    <label for="department" class="form-label">Department *</label>
                                    <select id="department" name="department" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <option value="Computer Science" <?php echo (isset($department) && $department == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                        <option value="Engineering" <?php echo (isset($department) && $department == 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                                        <option value="Business" <?php echo (isset($department) && $department == 'Business') ? 'selected' : ''; ?>>Business</option>
                                        <option value="Arts" <?php echo (isset($department) && $department == 'Arts') ? 'selected' : ''; ?>>Arts</option>
                                        <option value="Sciences" <?php echo (isset($department) && $department == 'Sciences') ? 'selected' : ''; ?>>Sciences</option>
                                        <option value="Administration" <?php echo (isset($department) && $department == 'Administration') ? 'selected' : ''; ?>>Administration</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="device_type" class="form-label">Device Type *</label>
                                    <select id="device_type" name="device_type" class="form-control" required>
                                        <option value="">Select Device Type</option>
                                        <option value="Laptop" <?php echo (isset($device_type) && $device_type == 'Laptop') ? 'selected' : ''; ?>>Laptop</option>
                                        <option value="Desktop" <?php echo (isset($device_type) && $device_type == 'Desktop') ? 'selected' : ''; ?>>Desktop</option>
                                        <option value="Tablet" <?php echo (isset($device_type) && $device_type == 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                                        <option value="Printer" <?php echo (isset($device_type) && $device_type == 'Printer') ? 'selected' : ''; ?>>Printer</option>
                                        <option value="Projector" <?php echo (isset($device_type) && $device_type == 'Projector') ? 'selected' : ''; ?>>Projector</option>
                                        <option value="Network Device" <?php echo (isset($device_type) && $device_type == 'Network Device') ? 'selected' : ''; ?>>Network Device</option>
                                        <option value="Other" <?php echo (isset($device_type) && $device_type == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="device_model" class="form-label">Device Model/Brand *</label>
                                    <input type="text" id="device_model" name="device_model" class="form-control" 
                                           value="<?php echo isset($device_model) ? htmlspecialchars($device_model) : ''; ?>" 
                                           required placeholder="e.g., Dell XPS 15, HP LaserJet Pro, etc.">
                                </div>

                                <div class="form-group">
                                    <label for="issue_category" class="form-label">Issue Category *</label>
                                    <select id="issue_category" name="issue_category" class="form-control" required>
                                        <option value="">Select Issue Category</option>
                                        <option value="Hardware Failure" <?php echo (isset($issue_category) && $issue_category == 'Hardware Failure') ? 'selected' : ''; ?>>Hardware Failure</option>
                                        <option value="Performance Issues" <?php echo (isset($issue_category) && $issue_category == 'Performance Issues') ? 'selected' : ''; ?>>Performance Issues</option>
                                        <option value="Connectivity Problems" <?php echo (isset($issue_category) && $issue_category == 'Connectivity Problems') ? 'selected' : ''; ?>>Connectivity Problems</option>
                                        <option value="Display Issues" <?php echo (isset($issue_category) && $issue_category == 'Display Issues') ? 'selected' : ''; ?>>Display Issues</option>
                                        <option value="Power Problems" <?php echo (isset($issue_category) && $issue_category == 'Power Problems') ? 'selected' : ''; ?>>Power Problems</option>
                                        <option value="Other" <?php echo (isset($issue_category) && $issue_category == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="priority" class="form-label">Priority Level *</label>
                                    <select id="priority" name="priority" class="form-control" required>
                                        <option value="Low" <?php echo (isset($priority) && $priority == 'Low') ? 'selected' : ''; ?>>Low - General inquiry</option>
                                        <option value="Medium" <?php echo (isset($priority) && $priority == 'Medium') ? 'selected' : ''; ?>>Medium - Affecting work</option>
                                        <option value="High" <?php echo (isset($priority) && $priority == 'High') ? 'selected' : ''; ?>>High - Critical functionality</option>
                                        <option value="Critical" <?php echo (isset($priority) && $priority == 'Critical') ? 'selected' : ''; ?>>Critical - System down</option>
                                    </select>
                                </div>

                                <div class="form-group full-width">
                                    <label for="description" class="form-label">Problem Description *</label>
                                    <textarea id="description" name="description" class="form-control" rows="6" required 
                                              placeholder="Please describe the issue in detail, including any error messages, when it started, and what you've tried so far..."><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                                    <small style="color: #666; display: block; margin-top: 0.5rem;">Minimum 10 characters required</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-block">Submit Support Ticket</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="container footer-content">
            <div class="footer-logo">College Hardware Support</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');
            
            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!navMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                        navMenu.classList.remove('active');
                    }
                });
            }

            // Form validation
            const form = document.getElementById('ticketForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const description = document.getElementById('description');
                    if (description && description.value.trim().length < 10) {
                        e.preventDefault();
                        alert('Please provide a more detailed description of the problem (at least 10 characters).');
                        description.focus();
                    }
                });
            }

            // Add character count for description
            const descriptionField = document.getElementById('description');
            if (descriptionField) {
                const charCount = document.createElement('div');
                charCount.style.marginTop = '0.5rem';
                charCount.style.fontSize = '0.9rem';
                charCount.style.color = '#666';
                descriptionField.parentNode.appendChild(charCount);

                function updateCharCount() {
                    const count = descriptionField.value.length;
                    charCount.textContent = `${count} characters` + (count < 10 ? ' (minimum 10 required)' : '');
                    charCount.style.color = count < 10 ? '#e74c3c' : '#666';
                }

                descriptionField.addEventListener('input', updateCharCount);
                updateCharCount();
            }
        });
    </script>
</body>
</html>