<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['login_error'] = "Account not found.";
    }
    // Redirect back to GET to avoid form resubmission warning
    header("Location: index.php");
    exit();
}

// Check for stored errors
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TechShop Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #f8fafc;
            --card-bg: #ffffff;
            --sidebar-navy: #0d2238;
            --accent-teal: #0d9488;
            --text-main: #1e293b;
            --text-light: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--primary-bg);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-box {
            background: var(--sidebar-navy);
            width: 64px;
            height: 64px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin-bottom: 24px;
            box-shadow: 0 10px 15px -3px rgba(13, 34, 56, 0.3);
        }

        .brand-header { text-align: center; margin-bottom: 40px; }
        .brand-header h1 { font-size: 2.2rem; font-weight: 800; color: var(--sidebar-navy); letter-spacing: -1px; margin-bottom: 4px; }
        .brand-header p { font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; font-weight: 700; letter-spacing: 2px; }

        .login-card {
            background: var(--card-bg);
            width: 100%;
            padding: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-group { margin-bottom: 24px; }
        .label-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .label-text { font-size: 0.8rem; font-weight: 700; color: var(--text-main); }
        .forgot-link { font-size: 0.75rem; color: var(--accent-teal); text-decoration: none; font-weight: 700; }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-box i {
            position: absolute;
            left: 16px;
            color: var(--text-light);
            font-size: 1rem;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: #f1f5f990;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            color: var(--text-main);
            outline: none;
        }
        .form-control::placeholder { color: #94a3b8; }
        .form-control:focus { border-color: #cbd5e1; background: #fff; }

        .stay-signed-in {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 24px;
            font-weight: 500;
        }
        .stay-signed-in input { width: 16px; height: 16px; border-radius: 4px; border: 1px solid #e2e8f0; cursor: pointer; }

        .btn-signin {
            width: 100%;
            padding: 14px;
            background: var(--sidebar-navy);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: 0.2s;
        }
        .btn-signin:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }

        .status-footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--text-light);
            font-weight: 600;
        }
        .status-dot { width: 10px; height: 10px; background: #10b981; border-radius: 50%; display: inline-block; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }

        .error-alert {
            background: #fff1f2;
            color: #be123c;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="logo-box">
        <i class="fa-solid fa-microchip"></i>
    </div>

    <div class="brand-header">
        <h1>TechShop Pro</h1>
        <p>Management Suite</p>
    </div>

    <div class="login-card">
        <?php if($error): ?>
            <div class="error-alert"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <div class="label-row">
                    <span class="label-text">Email or Username</span>
                </div>
                <div class="input-box">
                    <i class="fa-regular fa-user"></i>
                    <input type="email" name="email" class="form-control" placeholder="tech.lead@enterprise.com" required>
                </div>
            </div>

            <div class="form-group">
                <div class="label-row">
                    <span class="label-text">Password</span>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <label class="stay-signed-in">
                <input type="checkbox"> Stay signed in for 30 days
            </label>

            <button type="submit" class="btn-signin">
                Sign In <i class="fa-solid fa-right-to-bracket" style="font-size:1.1rem;"></i>
            </button>
        </form>

        <div class="status-footer">
            <span class="status-dot"></span>
            System Status: All Systems Operational
        </div>
    </div>
</div>

</body>
</html>
