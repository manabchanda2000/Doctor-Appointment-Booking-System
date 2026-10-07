<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="animation.css">
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
    }

    .header {
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #EFF6FF 0%, #FAF5FF 50%, #FDF2F8 100%);
        overflow: hidden;


    }

    .logo {
        display: flex;
        align-items: center;
        gap: 20px;
        animation: left-right .8s ease;
    }

    .logo .name {
        font-size: 2em;
        background: linear-gradient(90deg, #2563EB, #4F46E5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;

    }

    .logo img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .log {
        display: flex;
        align-items: center;
        gap: 20px;
        animation: right-left .8s ease;
    }

    .log a {
        text-decoration: none;
        padding: 10px 20px;
        border: transparent;
        border-radius: 50px;
        transition: all .3s ease;
    }

    .log .signin {
        background: linear-gradient(90deg, #2563EB 0%, #4F46E5 100%);
        color: white;
    }

    .log .signin:hover {
        scale: 1.1;
    }

    .log .login {
        border: 2px solid #2563EB;
        color: #2563EB;
    }

    .log .login:hover {
        scale: 1.1;
    }

    .nav-bar {
        padding: 5px 20px;
        background: linear-gradient(135deg, #EFF6FF 0%, #FAF5FF 50%, #FDF2F8 100%);
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        transition: all .3s ease;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .nav-bar ul {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        list-style: none;

    }

    .nav-bar ul li {
        padding: 8px 10px;
    }

    .nav-bar ul a {
        text-decoration: none;
        font-weight: 500;
        color: #1F2937;
        transition: all .3s ease;
    }

    .nav-bar ul a:hover {
        color: #2563EB;
        text-decoration: 1px solid underline #2563EB;
    }
    </style>
</head>

<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="./logo5.jpg" alt="Logo">
                <h1 class="name">Apna Health</h1>
            </div>
            <div class="log">
                <a href="./log.html" class="login">Login</a>
                <a href="./sign.html" class="signin">Sign Up</a>
            </div>
        </div>
    </header>
    <nav class="nav-bar">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./about.php">About</a></li>
            <li><a href="./doctors.php">Doctors</a></li>
            <li><a href="./clinic.php">Clinics</a></li>
            <li><a href="./tips.php">Health Tips</a></li>
            <li><a href="./review.php">Reviews</a></li>
            <li><a href="./notice.php">Announcements</a></li>
            <li><a href="./contact.php">Contact</a></li>
        </ul>
    </nav>
</body>

</html>