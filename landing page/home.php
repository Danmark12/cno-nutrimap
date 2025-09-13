<?php
// home.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNO NutriMap</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background: white;
            box-shadow: 0px 1px 5px rgba(0,0,0,0.1);
        }
        .logo {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .logo span {
            color: #00B2B2; /* CNO color */
        }
        .nav {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        .nav a {
            text-decoration: none;
            color: black;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }
        .nav a:hover {
            color: #00B2B2;
        }
        .login-btn {
            border: 1px solid black;
            background: white;
            padding: 5px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .login-btn:hover {
            background: #f2f2f2;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: url('https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?fit=crop&w=1000&q=80') no-repeat center center/cover;
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            color: white;
            padding: 20px;
        }
        .hero-content {
            background: rgba(0, 0, 0, 0.4);
            padding: 40px;
            border-radius: 10px;
            max-width: 600px;
        }
        .hero h1 {
            font-size: 2rem;
            margin: 0;
        }
        .hero h2 {
            font-size: 2.5rem;
            color: #00B2B2;
            margin: 10px 0;
        }
        .hero p {
            margin-bottom: 20px;
        }
        .hero button {
            background: #00B2B2;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        .hero button:hover {
            background: #008C8C;
        }
        .hero-img {
            position: absolute;
            right: 5%;
            bottom: 10%;
            max-width: 300px;
        }
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }
            .hero-img {
                position: static;
                margin-top: 20px;
                max-width: 250px;
            }
        }

        /* Login Box at Bottom */
        .login-section {
            background: #f8f8f8;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
        }
        .login-section form {
            display: inline-block;
            max-width: 350px;
            text-align: left;
        }
        .login-section input {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .login-section button {
            width: 100%;
            background: #008080;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .login-section button:hover {
            background: #006666;
        }
        .options {
            margin-top: 10px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .options a {
            text-decoration: none;
            color: #00AEEF;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="logo"><span>CNO</span> NutriMap</div>
        <div class="nav">
            <a href="#">HOME</a>
            <a href="#">NUTRITIONAL MAP</a>
            <a href="#">GET TO KNOW US</a>
            <a href="#">CONTACT US</a>
            <a href="login.php"><button class="login-btn">LOGIN</button></a>
        </div>
    </div>

    <!-- HERO SECTION -->
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to</h1>
            <h2>City Nutrition Office</h2>
            <p>El Salvador, Misamis Oriental</p>
            <button>Know More About Us!</button>
        </div>
        <img class="hero-img" src="https://cdn.pixabay.com/photo/2020/10/17/08/06/nutrition-5660917_1280.png" alt="Nutrition Illustration">
    </div>

    <!-- LOGIN SECTION -->
    <div class="login-section">
        <form method="POST" action="login_api.php">
            <input type="text" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Log in</button>
            <div class="options">
                <label><input type="checkbox" name="remember"> Remember me!</label>
                <a href="index.php">Just visit!</a>
            </div>
        </form>
    </div>

</body>
</html>
