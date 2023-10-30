<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Load user data from an external JSON file (e.g., users.json)
    $userData = file_get_contents("json-data/user-logins.json");
    $users = json_decode($userData, true);

    // Check if the submitted username and password match
    if (isset($users[$username]) && $users[$username]["password"] === $password) {
        // Successful login - Redirect to employee salaries
        header("Location: welcome.php");
        exit();
    } else {
        // Invalid credentials - Send back to the login form with an error message
        header("Location: login.php?error=1");
        exit();
    }
}
?>
