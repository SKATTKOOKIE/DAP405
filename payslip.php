<?php
    session_start();
    require('inc/config.php');
    
    // Check if the user is logged in, if not, redirect to the login page
    if (!isset($_SESSION['user'])) 
    {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/mainstyle.css">
    <link rel="stylesheet" href="stylesheets/header.css">
</head>
<body>
    <?php
        $pageTitle = 'Woodton Ltd Payslip Display';
        require_once('inc/navbar.php');
    ?>
    
    <h1 class="payslipTitle">Payslip</h1>

    <?php
    require('calculateTax.php');
    require('calculateAge.php');
    require_once('calculateTimeServed.php');
    require('inc/globalVar.php');

    // Check if the "id" parameter is set in the URL
    if (isset($_GET['id'])) 
    {
        // Import Json files
        $employeeData = json_decode(file_get_contents('jsonData/employee-data.json'), true);
        $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);

        // Find the user with the specified ID
        $selectedEmployee = null;
        foreach ($employeeData as $employee) 
        {
            if ($employee['id'] == $_GET['id']) 
            {
                $selectedEmployee = $employee;
                break;
            }
        }

        echo '<div class="employee-container">';
        echo '<div class="name-role">';
        if ($selectedEmployee) 
        {
            echo "<h2>Basic Details</h2>";
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

        echo '<div class="employee-info">';
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
            // Calculate time served
            if (isset($selectedEmployee['employmentstart'])) 
            {
                calculateTimeServed($selectedEmployee['employmentstart']);
            }
            // Calculate age
            if (isset($selectedEmployee['dob'])) 
            {
                calculateAge($selectedEmployee['dob']);
            }

            echo "<p>Employment End Date: " . $selectedEmployee['employmentend'] . "</p>";
            echo "<p>Pension: " . $selectedEmployee['pension'] . "</p>";
            echo "<p>Pension Type: " . $selectedEmployee['pensiontype'] . "</p>";
            echo "<p>Company Car: " . $selectedEmployee['companycar'] . "</p>";
            echo "<p>Previous Roles: " . implode(', ', $selectedEmployee['previousroles']) . "</p>";
            echo "<p>Other Roles: " . implode(', ', $selectedEmployee['otherroles']) . "</p>";
        }
        echo '</div>';

        echo '<div class="other-info">';
        echo "<h2>Pay Details</h2>";

        if ($selectedEmployee) 
        {
            $salary = $selectedEmployee['salary'];
            $currency = $selectedEmployee['currency'];

            // Check the currency and perform calculations accordingly
            if ($currency == 'GBP') 
            {
                $employeesCurrency = $pounds;
                // Calculate after-tax salary
                $afterTaxSalary = calculateAfterTaxSalary($salary, $taxTables);
            } 
            elseif ($currency == 'USD') 
            {
                $employeesCurrency = $dollars;
                // Convert dollars to pounds
                $exchangedSalary = $salary * $usdToGbp;
                // Tax at British rate
                $afterTaxSalary = calculateAfterTaxSalary($exchangedSalary, $taxTables);
                // Convert back to USD
                $afterTaxSalary = $afterTaxSalary * $gbpToUsd;
            }

            echo "<p>National Insurance Number: " . $selectedEmployee['nationalinsurance'] . "</p>";
            echo "<p>Salary (per year): ". $employeesCurrency . $selectedEmployee['salary'] . "</p\n";

            // Fetch the applicable tax rate
            $taxRate = null;
            foreach ($taxTables as $taxBracket) 
            {
                $minSalary = $taxBracket['minsalary'];
                $maxSalary = $taxBracket['maxsalary'];
                if ($selectedEmployee['salary'] >= $minSalary && $selectedEmployee['salary'] <= $maxSalary)
                {
                    $taxRate = $taxBracket['rate'];
                    break;
                }
            }

            // Calculate gross pay
            $grossPay = $selectedEmployee['salary'];

            // Calculate the total amount of tax paid
            $totalTaxPaid = $grossPay - $afterTaxSalary;

            echo "<p>Take-Home Pay: ". $employeesCurrency . number_format($afterTaxSalary, 2) . "</p\n";
            
            // Display the applicable tax rate
            if ($taxRate !== null) {
                echo "<p>Tax Rate: " . $taxRate . "%</p>";
            } 
            else 
            {
                echo "<p>Tax Rate: N/A</p>";
            }

            echo "<p>Total Tax Paid: ". $employeesCurrency . number_format($totalTaxPaid, 2) . "</p>";
        }
       
        echo '</div>';
        echo '</div>';
    } 
    else 
    {
        echo "<p>Invalid request. Please select an employee from the payroll data.</p>";
    }
?>
<button class="printPayslipButton" onclick="window.location.href='generate_payslip.php?id=<?php echo $_GET['id']; ?>'">Print Payslip</button>
</body>
</html>
