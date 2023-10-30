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
                    <li class="nav-item"><a href="#" class="nav-link">Return to Dashboard</a></li>
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
    if ($selectedEmployee) {
        echo "<h2>Name: " . $selectedEmployee['firstname'] . ' ' . $selectedEmployee['lastname'] . "</h2>";
        echo "<p>ID: " . $selectedEmployee['id'] . "</p>";
        echo "<p>Job Title: " . $selectedEmployee['jobtitle'] . "</p>";
    }
    echo '</div>';

    // Create a div for the rest of the information on the left
    echo '<div class="other-info">';
    echo "<h2>Pay Details</h2>";
    if ($selectedEmployee) {
        echo "<p>Salary (per year): " . "£" . $selectedEmployee['salary'] . "</p>";

        // Calculate the applicable tax rate based on the salary
        $taxRate = 0;
        foreach ($taxTables as $taxTable) {
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

    <a href="javascript:history.back()">Go Back</a>
</body>
</html>
