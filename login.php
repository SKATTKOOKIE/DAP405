<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Woodton LTD Login</title>
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php
        session_start();

        session_destroy();
    
        // Check for an error message in the URL
        if (isset($_GET['error']) && $_GET['error'] == 1) 
        {
            echo '<p class="error-message">Username or password is incorrect. Please try again.</p>';
        }
    ?>
    <form class="login-form" method="post" action="login-process.php">
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
            <input type="submit" value="Login">
        </div>
    </form>
</div>

<div class="login-container">
    <p>Usernames: 7265, 3565, 1360, 9784, 9140, 6505, 1532, 6985, 3021, 2694, 8114, 7296, 4213, 4159, 8861, 9790, 2499, 8632, 8734, 9295, </p>
    <p>Passwords: password</p>
</div>


</body>
</html>
