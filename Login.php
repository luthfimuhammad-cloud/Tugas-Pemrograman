<?php
session_start();
require_once 'koneksi.php';

if (!isset($koneksi) || !$koneksi) {
    $koneksi = null;
}

if (isset($_SESSION['login'])) {
    header("Location: UTS aplikasi.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$koneksi) {
        $error = "Database belum dibuat di phpMyAdmin!";
    } else {
        $username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
        
        if ($query && mysqli_num_rows($query) === 1) {
            $user = mysqli_fetch_assoc($query);
            
            if ($password === $user['password'] || password_verify($password, $user['password'])) {
                $_SESSION['login']        = true;
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'] ?? $user['username'];

                header("Location: UTS aplikasi.php");
                exit;
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Kas - KasKuApp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-box {
            background: #ffffff;
            width: 100%;
            max-width: 380px;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .login-header h2 {
            font-size: 1.4rem;
            color: #0f172a;
        }
        .login-header h2 span {
            color: #38bdf8;
        }
        .error {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fca5a5;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #0f172a;
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            font-size: 0.9rem;
            background: #f8fafc;
        }
        .form-group input:focus {
            background: #ffffff;
            border-color: #2563eb;
        }
        .btn-login {
            width: 100%;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 11px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }
        .btn-login:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-header">
        <i class="fa-solid fa-wallet"></i>
        <h2>KasKu<span>App</span></h2>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">Login untuk mengelola kas keuangan</p>
    </div>

    <?php if ($error != ""): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan Username" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
        </div>

        <button type="submit" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> LOGIN</button>
    </form>
</div>

</body>
</html>
