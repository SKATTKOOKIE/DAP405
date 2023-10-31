<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/header.css">
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
                <button class="burger-menu">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </button>
            </nav>
        </div>
    </header>
    <h1>Payslip</h1>
    <?php
// Check if the "id" parameter is set in the URL
if (isset($_GET['id'])) {
    // Read the employee data from the JSON file
    $employeeData = json_decode(file_get_contents('employee-data.json'), true);

    // Read the tax tables from the tax-tables.json file
    $taxTables = json_decode(file_get_contents('tax-tables.json'), true);

    // Find the user with the specified ID
    $selectedEmployee = null;
    foreach ($employeeData as $employee) {
        if ($employee['id'] == $_GET['id']) {
            $selectedEmployee = $employee;
            break;
        }
    }

    // Start an HTML container for styling
    echo '<div class="employee-container">';

    // Create a div for name and role on the right
    echo '<div class="name-role">';
    if ($selectedEmployee) 
    {
        echo "<h2>Basics Details</h2>";
        echo '<img class="profilePhoto" src="images/' . $selectedEmployee['photo'] . '" alt="Employee Photo">';
        echo "<p>Name: " . $selectedEmployee['firstname'] . ' ' . $selectedEmployee['lastname'] . "</p>";
        echo "<p>ID: " . $selectedEmployee['id'] . "</p>";
        echo "<p>Department: " . $selectedEmployee['department'] . "</p>";
        echo "<p>Job Title: " . $selectedEmployee['jobtitle'] . "</p>";
        echo "<p>Line Manager: " . $selectedEmployee['linemanager'] . "</p>";
        echo "<p>Line Manager ID: " . $selectedEmployee['linemanagerid'] . "</p>";
        echo "<p>Reports to: " . implode(', ', $selectedEmployee['reports']) . "</p>";
    }
    echo '</div>';

    echo '<div class="name-role">';
    if ($selectedEmployee) 
    {
        echo "<h2>Personal details</h2>";
        echo "<p>Grade: " . $selectedEmployee['grade'] . "</p>";
        echo "<p>Date Of Birth: " . $selectedEmployee['dob'] . "</p>";
        echo "<p>Phone Number: " . $selectedEmployee['phone'] . "</p>";
        echo "<p>Work Email: " . $selectedEmployee['email'] . "</p>";
        echo "<p>Home Email: " . $selectedEmployee['homeemail'] . "</p>";
        echo "<p>Home Address: " . $selectedEmployee['homeaddress'] . "</p>";
        echo "<p>Next Of Kin: " . $selectedEmployee['nextofkin'] . "</p>";
        echo "<p>Employment Start Date: " . $selectedEmployee['employmentstart'] . "</p>";
        echo "<p>Employment End Date: " . $selectedEmployee['employmentend'] . "</p>";
        echo "<p>Pension: " . $selectedEmployee['pension'] . "</p>";
        echo "<p>Pension Type: " . $selectedEmployee['pensiontype'] . "</p>";
        echo "<p>Company Car: " . $selectedEmployee['companycar'] . "</p>";
        echo "<p>Previous Roles: " . implode(', ', $selectedEmployee['previousroles']) . "</p>";
        echo "<p>Other Roles: " . implode(', ', $selectedEmployee['otherroles']) . "</p>";
    }
    echo '</div>';

    // Create a div for the rest of the information on the left
    echo '<div class="other-info">';
    echo "<h2>Pay Details</h2>";
    if ($selectedEmployee) 
    {
        echo "<p>National Insurance Number: " . $selectedEmployee['nationalinsurance'] . "</p>";
        echo "<p>Salary (per year): " . "£" . $selectedEmployee['salary'] . "</p>";

        // Calculate the applicable tax rate based on the salary
        $taxRate = 0;
        foreach ($taxTables as $taxTable) 
        {
            if ($selectedEmployee['salary'] >= $taxTable['minsalary'] && $selectedEmployee['salary'] <= $taxTable['maxsalary']) {
                $taxRate = $taxTable['rate'];
                break;
            }
        }

        // Calculate the take-home pay
        $taxAmount = ($selectedEmployee['salary'] * $taxRate) / 100;
        $takeHomePay = $selectedEmployee['salary'] - $taxAmount;

        echo "<p>Tax Rate: " . $taxRate . "%</p>";
        echo "<p>Tax Amount: " . "£"  . $taxAmount . "</p>";
        echo "<p>Take-Home Pay: " . "£" . $takeHomePay . "</p>";
    }
    echo '</div>';

    // Close the HTML container
    echo '</div>';
} else {
    echo "<p>Invalid request. Please select an employee from the payroll data.</p>";
}
?>
</body>
</html>
