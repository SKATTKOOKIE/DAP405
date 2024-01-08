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

    <div class="Button-container">
        <button class="printPayslipButton" onclick="window.location.href='generatePayslip.php?id=<?php echo $_GET['id']; ?>'">Print Payslip</button>

        <a href="generatePayslip.php?id=<?php echo $_GET['id']; ?>" download="payslip.pdf">
            <button class="printPayslipButton">Download Payslip</button>
        </a>
    </div>

    <?php
    require('inc/globalVar.php');
    require('inc/functions.php');

    // Check if the "id" parameter is set in the URL
    if (isset($_GET['id'])) 
    {
        // Import Json files
        $employeeDataFile = 'jsonData/employee-data.json';
        $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);

        if (file_exists($employeeDataFile)) 
        {
            $employeeData = json_decode(file_get_contents($employeeDataFile), true);
        } 
        else 
        {
            // If the file doesn't exist, show "no data"
            echo "<div><h2>No data</h2></div>";
            exit(); // Exit the script
        }

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
        echo '<div id="top-of-payslip" class="top-of-payslip">';
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

        echo '<div class="other-info">';
        // ==========================================================================================
        echo "<h2>Pay Details</h2>";

        

        if ($selectedEmployee) 
        {
            $salary = $selectedEmployee['salary'];
            $currency = $selectedEmployee['currency'];
            $salaryFormatted = number_format($salary, 2);
            $hasCompanyCar = $selectedEmployee['companycar'];
            $cheese = $selectedEmployee['companycar'];


            if($currency == 'GBP')
            {
                $employeesCurrency = $pounds;
                // Calculate after-tax salary
                $afterTaxSalary = calculateTax($salary, $taxTables, $hasCompanyCar);
                $afterTaxSalary = number_format($afterTaxSalary, 2);
            }

            if($currency == 'USD')
            {
                $employeesCurrency = $dollars;
                // Convert dollars to pounds
                $exchangedSalary = $salary * $usdToGbp;
                // Tax at british rate
                $afterTaxSalary = calculateTax($exchangedSalary, $taxTables, $hasCompanyCar);
                // Convert back to USD
                $afterTaxSalary = $afterTaxSalary * $gbpToUsd;
                $afterTaxSalary = number_format($afterTaxSalary, 2);
            }


            echo "<p>Reports to: " . $cheese . "</p>";
            echo "<p>National Insurance Number: " . $selectedEmployee['nationalinsurance'] . "</p>";
            echo "<p>Salary (per year): ". $employeesCurrency . $salaryFormatted . "</p>\n";
            echo "<p> After tax salary (per year): ". $employeesCurrency . $afterTaxSalary . "</p>\n";

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
            
            // Display the applicable tax rate
            if ($taxRate !== null) 
            {
                echo "<p>Tax Rate: " . $taxRate . "%</p>";
            } 
            else 
            {
                echo "<p>Tax Rate: N/A</p>";
            }
        }
    //    =================================================================================================================
        echo '</div>';

        echo '</div>';



        echo '<div class="employee-info">';
        if ($selectedEmployee) 
        {
            echo "<h2>Personal details</h2>";
            echo "<p>Grade: " . $selectedEmployee['grade'] . "</p>";
            echo "<p>Date Of Birth: " . $selectedEmployee['dob'] . "</p>";
            // Calculate age
            if (isset($selectedEmployee['dob'])) 
            {
                calculateAge($selectedEmployee['dob']);
            }
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
            echo "<p>Employment End Date: " . $selectedEmployee['employmentend'] . "</p>";
            echo "<p>Pension: " . $selectedEmployee['pension'] . "</p>";
            echo "<p>Pension Type: " . $selectedEmployee['pensiontype'] . "</p>";
            echo "<p>Company Car: " . $selectedEmployee['companycar'] . "</p>";
            echo "<p>Previous Roles: " . implode(', ', $selectedEmployee['previousroles']) . "</p>";
            echo "<p>Other Roles: " . implode(', ', $selectedEmployee['otherroles']) . "</p>";
        }
        echo '</div>';


        echo '</div>';
    } 
    else 
    {
        echo "<p>Invalid request. Please select an employee from the payroll data.</p>";
    }
?>

</body>
</html>
