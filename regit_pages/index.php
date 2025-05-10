<?php    
    include 'header.php';
?>

<main>
    <div style="display: flex; justify-content: center;">
    <div class="login-container">
        <h2>Attendee Registration Form</h2>
        <form action="file:///C:/xampp/htdocs/f2Pinca/index.html#">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" class="login-input" required="">

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" class="login-course" required="">
      
          <button type="submit" class="login-btn"><a>Login</a></button>
            <button style="background-color: #4CAF50; color: white; border: none; font-size: 15px; width: 50%; padding: 10px; cursor: default;">
                <a href="./crud_functionalities/register.php" style="text-decoration: none; color: white; display: block; width: 100%; height: 100%;">Sign Up</a></button><br>

        </form>
      </div>
    </div>
</main>

<footer>
    James Ewican | BS Computer Science - 2
</footer>

</body></html>