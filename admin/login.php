<?php
// ============================================
// ADMIN: Login - Subhan Printers
// ============================================

session_start();

// ✅ Load functions FIRST
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/index.php');
    exit;
}

// Load database and models
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // ✅ Validate input
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password.';
    } else {
        $user = new User();
        $result = $user->login($email, $password);
        
        // ✅ Check if login successful AND user is admin
        if ($result['success'] && isset($result['user']['role']) && $result['user']['role'] === 'admin') {
            // Set admin session
            $_SESSION['admin_id'] = $result['user']['id'];
            $_SESSION['admin_name'] = $result['user']['name'];
            $_SESSION['admin_email'] = $result['user']['email'];
            $_SESSION['admin_role'] = $result['user']['role'];
            $_SESSION['logged_in_at'] = time();
            
            // ✅ Clear any redirect after login
            unset($_SESSION['redirect_after_login']);
            
            header('Location: /SP/admin/index.php');
            exit;
        } else {
            // ✅ Better error message
            if (!$result['success']) {
                $error = $result['error'] ?? 'Invalid credentials. Please try again.';
            } else {
                $error = 'You do not have admin privileges.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Subhan Printers</title>
    <link rel="icon" href="/SP/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0a14;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        /* Animated Background */
        .bg-animation {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(139,92,246,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 50%, rgba(245,158,11,0.08) 0%, transparent 60%);
        }
        .bg-animation::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
            background: radial-gradient(circle, rgba(139,92,246,0.1), transparent 70%);
            border-radius: 50%;
            animation: float 20s ease-in-out infinite;
        }
        .bg-animation::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            bottom: -150px;
            left: -150px;
            background: radial-gradient(circle, rgba(245,158,11,0.08), transparent 70%);
            border-radius: 50%;
            animation: float 25s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -30px) scale(1.1); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: rgba(18, 18, 31, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 60px rgba(139,92,246,0.1);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #8b5cf6, #f59e0b, #8b5cf6);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%, 100% { background-position: 0% 0%; }
            50% { background-position: 100% 0%; }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 12px;
        }
        .login-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: #e8e8f0;
        }
        .login-logo h1 span {
            color: #8b5cf6;
        }
        .login-logo p {
            color: #8888aa;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #8888aa;
            margin-bottom: 6px;
        }
        .form-group .input-wrap {
            position: relative;
        }
        .form-group .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #555577;
            font-size: 1rem;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: #1a1a2e;
            border: 1.5px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            color: #e8e8f0;
            font-size: 0.95rem;
            transition: 0.3s ease;
            font-family: inherit;
        }
        .form-group input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139,92,246,0.12);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: inherit;
            position: relative;
            overflow: hidden;
        }
        .login-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: 0.6s ease;
        }
        .login-btn:hover::before {
            transform: translateX(100%);
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(139,92,246,0.4);
        }

        .error-msg {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 10px;
            padding: 12px 16px;
            color: #ef4444;
            font-size: 0.85rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            color: #555577;
            font-size: 0.78rem;
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="bg-animation"></div>

<div class="login-container">
    <div class="login-card">
        <div class="login-logo">
            <img src="/SP/images/logo.png" alt="Subhan Printers">
            <h1>Subhan <span>Printers</span></h1>
            <p>Admin Panel</p>
        </div>

        <?php if ($error): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="admin@subhanprinters.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Login to Admin
            </button>
        </form>

        <div class="login-footer">
            <p>Protected area • Authorized access only</p>
            <p style="margin-top:4px;font-size:0.7rem;color:#444466;">
                <i class="fas fa-key"></i> Default: admin@subhanprinters.com / admin123
            </p>
        </div>
    </div>
</div>

</body>
</html>