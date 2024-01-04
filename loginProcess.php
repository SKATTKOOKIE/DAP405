<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") 
    {
        session_start();
        
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        $userData = file_get_contents("jsonData/user-logins.json");
        $users = json_decode($userData, true);
        
        // Check if the submitted username and password match
        foreach ($users as $user) 
        {
            if ($user["username"] === $username && $user["password"] === $password) 
            {
                // Successful login - Redirect to employee salaries
                $_SESSION['user'] = $username;
                header("Location: payroll.php");
                exit();
            }
        }
        
        // Invalid credentials - Send back to the login form with an error message
        header("Location: login.php?error=1");
        exit();
    }
?>
