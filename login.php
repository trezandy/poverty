<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ganti dengan logika autentikasi yang sesuai
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user'] = [
            'username' => $username,
            'role' => 'admin'
        ];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Klasifikasi SVM</title>
    <link rel="icon" href="data:,">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --light-color: #ecf0f1;
            --error-color: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        .login-header h1 {
            font-size: 2rem;
            color: var(--text-color);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .login-header p {
            color: #666;
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 1rem 1.2rem;
            border: 2px solid #e0e0e0;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
            border-color: var(--accent-color);
            background-color: #fff;
        }

        .input-group {
            position: relative;
        }

        .input-group .btn-outline-secondary {
            border-radius: 0 12px 12px 0;
            border: 2px solid #e0e0e0;
            border-left: none;
            background-color: #f8f9fa;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .btn-login {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            width: 100%;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2980b9, #2c3e50);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
        }

        .form-label {
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--accent-color);
            font-size: 1.1rem;
        }

        .input-group-text {
            background: none;
            border: none;
            padding: 0;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--accent-color);
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }

            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-brain"></i>
            <h1>Sistem Klasifikasi SVM</h1>
            <p>Silakan login untuk melanjutkan</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username"><i class="fas fa-user"></i> Username</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <button type="button" class="password-toggle" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Masuk
            </button>
        </form>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Animasi smooth untuk container
            document.querySelector('.login-container').style.opacity = '0';
            setTimeout(() => {
                document.querySelector('.login-container').style.transition = 'all 0.5s ease';
                document.querySelector('.login-container').style.opacity = '1';
            }, 100);
        });
    </script>
</body>

</html>