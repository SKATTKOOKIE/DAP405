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
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Check for an error message in the URL
    if (isset($_GET['error']) && $_GET['error'] == 1) {
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
        <div class="input-group">
            <p>HINT!!!!! Username: "root"   |   Password: "password" </p>
        </div>
    </form>
</div>
</body>
</html>
