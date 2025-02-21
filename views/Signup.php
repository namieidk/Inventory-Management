<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .eclipse:nth-child(1) { width: 150px; height: 150px; top: 5%; left: 10%; }
        .eclipse:nth-child(2) { width: 200px; height: 200px; top: 20%; right: 15%; }
        .eclipse:nth-child(3) { width: 250px; height: 250px; bottom: 10%; left: 5%; }
        .eclipse:nth-child(4) { width: 300px; height: 300px; bottom: 20%; right: 10%; }
        .eclipse:nth-child(5) { width: 180px; height: 180px; top: 40%; left: 25%; }
        .eclipse:nth-child(6) { width: 220px; height: 220px; top: 60%; right: 20%; }
        .eclipse:nth-child(7) { width: 200px; height: 200px; bottom: 5%; left: 50%; }
        .eclipse:nth-child(8) { width: 280px; height: 280px; top: 10%; right: 50%; }
        .signup-container {
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
    </style>
</head>
<body>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="eclipse"></div>
    <div class="signup-container">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <h3 class="mb-3">SIGN UP</h3>
        <form method="POST" action="">
        <div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
        <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
</div>

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

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
    </div>
</div>

            <button type="submit" class="btn btn-dark w-100">Sign Up</button>
        </form>
        <p class="mt-3">Already have an account? <a href="Login.php">Log in here.</a></p>
    </div>
</body>
</html>