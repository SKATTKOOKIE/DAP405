<link rel="stylesheet" href="stylesheets/mainstyle.css">

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $userData = file_get_contents("jsonData/user-logins.json");
    $users = json_decode($userData, true);

    var_dump($users);
    print_r($users);

    // Check if the submitted username and password match
    foreach ($users as $user) {
        if ($user["username"] === $username && $user["password"] === $password) {
            // Successful login - Redirect to employee salaries
            header("Location: payroll.php");
            exit();
        }
    }

    // Invalid credentials - Send back to the login form with an error message
    header("Location: login.php?error=1");
    exit();

    // This line was for checking inputs as I was originally getting error redirecting to the payroll
    // print_r($username . "//spacer//" . "$password");
}

?>
