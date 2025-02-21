<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../statics/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #0c1445, #64668c);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .eclipse {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            filter: blur(10px);
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .logo {
            width: 80px;
            margin-bottom: 15px;
        }
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
        }
        .separator::before, .separator::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ccc;
        }
        .separator span {
            padding: 0 10px;
            font-size: 14px;
            color: #666;
        }
        .btn-light {
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            color: #333;
        }
        .btn-light:hover {
            background-color: #e2e6ea;
        }
    </style>
</head>
<body>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="login-container">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <h3 class="mb-3">USER LOGIN</h3>
        <?php if (!empty($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
        <form method="POST" action="login.php">
        <div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
        <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
</div>

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
</div>

            <button type="submit" class="btn btn-dark w-100">Login</button>
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
