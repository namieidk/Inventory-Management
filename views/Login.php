<?php
include '../database/database.php';

session_start();
$error_message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "<script>alert('Login successful!'); window.location.href = 'dashboard.php';</script>";
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../statics/css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/Login.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
   
</head>
<body>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="login-container">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <h3 class="mb-3" style="margin-top: -10px;">USER LOGIN</h3>
        <?php if (!empty($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
        <form method="POST" action="login.php">
        <div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
        <input type="text" name="username" class="form-control" style="margin-top: 10px; height: 25px;" placeholder="Username" required>
    </div>
</div>

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" name="password" class="form-control" style="margin-top: 10px; height: 25px;" placeholder="Password" required>
    </div>
</div>

            <button type="submit" class="btn btn-dark w-100" style="width: 80px; height: 20px; margin-top: 20px">Login</button>
        </form>
        
        <div class="separator"><span style="font-size: 20px;">or</span></div>
        
        <button class="btn btn-light w-100 d-flex align-items-center justify-content-center">
    <img src="../images/google-icon.svg" alt="Google" class="me-2" style="width: 20px; height: 20px;">
    <span>Continue with Email</span>
</button>
        <p class="mt-3">Don't have an account? <a href="Signup.php">Sign up here.</a></p>
    </div>
</body>
</html>
