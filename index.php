<!DOCTYPE html>
<!-- saved from url=(0042)file:///C:/xampp/htdocs/f2Pinca/index.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./index.css">
    <link rel="stylesheet" type="text/css" href="./form.css">
    <title>My Project</title>
</head>
<body>

    <nav>
        <div class="logo">
            <img src="./index_files/logo.png">
            <h1 class="logo-name">Regit</h1>
        </div>

        <div class="categories">
            <div class="cate"><a href="index.php">Login</a></div>
            <div class="cate"><a href="about.php">About</a></div>
            <div class="cate"><a href="contacts.html">Contact</a></div>
            <div class="cate"><a href="eventDashboard.php">Dashboard</a></div>
        </div>
        <img src="./index_files/search.png" alt="Search Icon" class="search-icon">
        <input type="text" class="search-input" placeholder="Search">
        
    </nav>

<main>
    <div style="display: flex; justify-content: center;">
    <div class="login-container">
        <h2>Attendee Registration Form</h2>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="login-input" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="login-course" required>

            <button type="submit" class="login-btn" style="margin-bottom: 20px"><a>Login</a></button>

            <button style="background-color: #4CAF50; color: white; border: none; font-size: 15px; width: 50%; padding: 10px;">
                <a href="register.php" style="text-decoration: none; color: white;">Register</a>
            </button>
        </form>

      </div>
    </div>
</main>

<footer>
    Jamiel Kyne Pinca | BS Computer Science - 2
</footer>



</body></html>