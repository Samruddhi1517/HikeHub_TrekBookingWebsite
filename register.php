<?php
require_once __DIR__ . '/../db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
    $stmt->bind_param('sss',$name,$email,$password);
    if($stmt->execute()){
        header('Location: login.php?registered=1');
        exit;
    } else {
        $error = 'Email already exists or database error.';
    }
}

include 'header.php';
?>

<style>
    .register-container {
        max-width: 480px;
        margin: 40px auto;
    }
    .register-card {
        background: #ffffff;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .register-card h2 {
        font-size: 26px;
        margin-bottom: 12px;
        font-weight: 700;
        color: #111827;
        text-align: center;
    }
    .register-sub {
        text-align: center;
        color: #6b7280;
        margin-bottom: 25px;
        font-size: 14px;
    }
    .form-group {
        margin-bottom: 18px;
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #374151;
    }
    .form-group input {
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        font-size: 15px;
        background: #f9fafb;
        transition: 0.2s;
    }
    .form-group input:focus {
        border-color: #0b74de;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(11,116,222,0.2);
    }
    .btn-primary {
        width: 100%;
        padding: 12px;
        background: #0b74de;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 17px;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: #095bb0;
    }
    .login-link {
        margin-top: 18px;
        text-align: center;
        color: #374151;
        font-size: 14px;
    }
    .login-link a {
        color: #0b74de;
        font-weight: 600;
        text-decoration: none;
    }
    .login-link a:hover {
        text-decoration: underline;
    }
    .error {
        color: #dc2626;
        text-align: center;
        margin-bottom: 15px;
        font-size: 14px;
    }
</style>

<div class="register-container">
    <div class="register-card">

        <h2>Create Account</h2>
        <p class="register-sub">Join us and start booking amazing trips!</p>

        <?php if(isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input name="email" type="email" placeholder="example@gmail.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" placeholder="Choose a strong password" required>
            </div>

            <button class="btn-primary" type="submit">Register</button>
        </form>

        <p class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </p>

    </div>
</div>

<?php include 'footer.php'; ?>
