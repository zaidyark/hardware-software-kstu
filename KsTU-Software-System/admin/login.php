<?php 
// Include config at the very top - NO WHITESPACE BEFORE THIS
include '../includes/config.php';

// Handle form submission at the top
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    
    // Simple authentication (in real app, use proper password hashing)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_login_time'] = time();
        
        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        $login_error = 'Invalid credentials. Please verify your username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - KsTU Hardware & Software Unit</title>
    <style>
        :root {
            --kstu-primary: #003366;
            --kstu-secondary: #FF6600;
            --kstu-accent: #00A86B;
            --kstu-light: #F5F5F5;
            --kstu-dark: #1A1A1A;
            --gradient-primary: linear-gradient(135deg, var(--kstu-primary) 0%, #004080 100%);
            --gradient-secondary: linear-gradient(135deg, var(--kstu-secondary) 0%, #FF8533 100%);
            --gradient-accent: linear-gradient(135deg, var(--kstu-accent) 0%, #00CC7A 100%);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.15);
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            line-height: 1.7;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
            background: var(--gradient-primary);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--kstu-primary);
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-main {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .logo-sub {
            font-size: 0.8rem;
            opacity: 0.9;
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
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-menu a:hover, .nav-menu a.active {
            background: rgba(255, 255, 255, 0.15);
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

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            margin: 2rem;
        }

        .login-header {
            background: var(--gradient-primary);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.05)"><polygon points="1000,100 1000,0 0,100"/></svg>');
            background-size: cover;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--kstu-primary);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--kstu-secondary);
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
        }

        .btn {
            display: inline-block;
            background: var(--gradient-secondary);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            text-align: center;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .security-notice {
            background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            border-left: 4px solid var(--kstu-accent);
        }

        .security-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.8rem;
        }

        .security-icon {
            color: var(--kstu-accent);
            font-size: 1.2rem;
        }

        .security-title {
            font-weight: 600;
            color: var(--kstu-primary);
            font-size: 1rem;
        }

        .security-content {
            font-size: 0.9rem;
            color: #555;
            line-height: 1.5;
        }

        .security-features {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .security-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #666;
        }

        .feature-icon {
            color: var(--kstu-accent);
            font-size: 0.9rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid transparent;
        }

        .alert-error {
            background: #fdeded;
            color: #5f2120;
            border-left-color: #e74c3c;
        }

        footer {
            background: var(--kstu-dark);
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
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--kstu-secondary);
        }

        .copyright {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
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
                background: var(--kstu-primary);
                padding: 1rem 0;
                box-shadow: var(--shadow);
            }

            .nav-menu.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .login-container {
                margin: 1rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .security-features {
                grid-template-columns: 1fr;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo-container">
                <div class="logo">KsTU</div>
                <div class="logo-text">
                    <div class="logo-main">Hardware & Software Unit</div>
                    <div class="logo-sub">Administrative Access</div>
                </div>
            </div>
            <button class="mobile-menu-btn" type="button">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="nav-menu">
                <a href="../index.html"><i class="fas fa-home"></i> Home</a>
                <a href="../submit-ticket.php"><i class="fas fa-ticket-alt"></i> Submit Ticket</a>
                <a href="../check-status.php"><i class="fas fa-search"></i> Check Status</a>
                <a href="login.php" class="active"><i class="fas fa-user-shield"></i> Admin Login</a>
            </nav>
        </div>
    </header>

    <section class="main-content">
        <div class="container">
            <div class="login-container fade-in-up">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h1>Secure Admin Access</h1>
                    <p>Restricted Area - Authorized Personnel Only</p>
                </div>
                
                <div class="login-body">
                    <?php if (isset($login_error)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($login_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="POST" id="loginForm">
                        <div class="form-group">
                            <label for="username"><i class="fas fa-user"></i> Administrator ID</label>
                            <input type="text" id="username" name="username" class="form-control" required 
                                   placeholder="Enter your administrator identifier">
                        </div>
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock"></i> Secure Passphrase</label>
                            <input type="password" id="password" name="password" class="form-control" required 
                                   placeholder="Enter your secure authentication token">
                        </div>
                        <button type="submit" class="btn">
                            <i class="fas fa-sign-in-alt"></i> Authenticate & Access System
                        </button>
                    </form>

                    <div class="security-notice">
                        <div class="security-header">
                            <i class="fas fa-shield-check security-icon"></i>
                            <div class="security-title">System Security Protocols</div>
                        </div>
                        <div class="security-content">
                            This portal is protected by enterprise-grade security measures. Access is monitored and logged for security compliance.
                        </div>
                        <div class="security-features">
                            <div class="security-feature">
                                <i class="fas fa-check-circle feature-icon"></i>
                                <span>Multi-factor Ready</span>
                            </div>
                            <div class="security-feature">
                                <i class="fas fa-check-circle feature-icon"></i>
                                <span>Encrypted Session</span>
                            </div>
                            <div class="security-feature">
                                <i class="fas fa-check-circle feature-icon"></i>
                                <span>Access Logging</span>
                            </div>
                            <div class="security-feature">
                                <i class="fas fa-check-circle feature-icon"></i>
                                <span>Audit Trail</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-content">
            <div class="footer-links">
                <a href="../index.html">Home</a>
                <a href="../submit-ticket.php">Submit Ticket</a>
                <a href="../check-status.php">Check Status</a>
                <a href="login.php">Admin Login</a>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Kumasi Technical University - Hardware & Software Unit. All rights reserved.</p>
                <p style="margin-top: 0.5rem; font-size: 0.8rem;">Secure Access Portal v2.1</p>
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
            const form = document.getElementById('loginForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const username = document.getElementById('username');
                    const password = document.getElementById('password');
                    
                    if (username && password) {
                        if (username.value.trim() === '' || password.value.trim() === '') {
                            e.preventDefault();
                            alert('Please provide both Administrator ID and Secure Passphrase to continue.');
                            if (username.value.trim() === '') {
                                username.focus();
                            } else {
                                password.focus();
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>