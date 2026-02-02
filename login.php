<?php
session_start();
require 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Correct query
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email=? AND role='user'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if($result && password_verify($password, $result['password'])) {

        // Store user session
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_name'] = $result['name'];

        header("Location: list.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}

include 'header.php';
?>

<style>
.login-container {
    max-width: 450px;
    margin: 40px auto;
}
.login-card {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.login-card h2 {
    text-align: center;
    margin-bottom: 15px;
    font-weight: 700;
}
.input-group { margin-bottom: 18px; }
.input-group label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}
.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #ddd;
}
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    background: #0b74de;
    color: #fff;
    border: none;
    font-size: 17px;
    cursor: pointer;
}
.btn-primary:hover { background: #095bb0; }
.error {
    text-align: center;
    padding: 10px;
    background: #fee2e2;
    color: #b91c1c;
    border-radius: 10px;
    margin-bottom: 15px;
}
</style>

<div class="login-container">
    <div class="login-card">
        <h2>User Login</h2>

        <?php if(!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button class="btn-primary" type="submit">Login</button>
        </form>

        <p style="margin-top: 12px; text-align:center;">
            <a href="register.php">Don't have an account? Register</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
