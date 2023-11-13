<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
            if (isset($pageTitle)) 
            {
                echo $pageTitle;
            } 
            else 
            {
                echo 'Woodton Ltd';
            }
        ?>
    </title>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="header-title">
                <h1>Woodton Ltd Payroll</h1>
            </div>
            <nav class="header-nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="payroll.php" class="nav-link">Return to Dashboard</a></li>
                    <li class="nav-item"><a href="login.php" class="nav-link">Logout</a></li>
                </ul>
                <div class="user-info">
                <?php
                    // Check if the user is logged in and display their username and image
                    session_start();
                    if (isset($_SESSION['user'])) {
                        $loggedInUser = $_SESSION['user'];
                        
                        // Load and decode the employee data
                        $employeeData = file_get_contents("jsonData/employee-data.json");
                        $employees = json_decode($employeeData, true);
                        
                        // Find the employee with a matching ID
                        $employeeName = 'Admin';
                        $employeeImage = 'default.jpg'; // Default image if not found
                        
                        foreach ($employees as $employee) 
                        {
                            if ($employee['id'] == $loggedInUser) 
                            {
                                $employeeImage = $employee['photo'];
                                $employeeName = $employee['firstname'] . ' ' . $employee['lastname'];
                                break; // Stop searching after finding a match
                            }
                        }
                        
                        echo '<img class="profilePhotoNavbar" src="images/' . $employeeImage . '" alt="User Image">';
                        echo '<p>' . $employeeName;
                    }
                ?>
                </div>
                <button class="burger-menu">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </button>
            </nav>
        </div>
    </header>
</body>
</html>
