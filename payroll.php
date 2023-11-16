
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
        $pageTitle = 'Woodton Ltd Payroll Display';
        require_once('inc/navbar.php');
    ?>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>ID</th>
                <th>Name</th>
                <th>Job Position</th>
                <th>Salary (per year)</th>
                <th>After Tax Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Global variables
                require('inc/globalVar.php');
                require('calculateTax.php');
                require('getUserPhoto.php');

                $employeeDataFile = 'jsonData/employee-data.json';

                if (file_exists($employeeDataFile)) 
                {
                    $employeeData = json_decode(file_get_contents($employeeDataFile), true);
                } 
                else 
                {
                    // If the file doesn't exist, show "no data"
                    echo "<tr><td colspan='7'>No data</td></tr>";
                    exit(); // Exit the script
                }

                $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);

                foreach ($employeeData as $employee) 
                {
                    $id = $employee['id'];
                    $fullName = $employee['firstname'] . ' ' . $employee['lastname'];
                    $jobPosition = $employee['jobtitle'];
                    $salary = $employee['salary'];
                    $currency = $employee['currency'];
                    $salaryFormatted = number_format($salary, 2);

                    if($currency == 'GBP')
                    {
                        $employeesCurrency = $pounds;
                        // Calculate after-tax salary
                        $afterTaxSalary = calculateAfterTaxSalary($salary, $taxTables);
                        $afterTaxSalary = number_format($afterTaxSalary, 2);
                    }

                    if($currency == 'USD')
                    {
                        $employeesCurrency = $dollars;
                        // Convert dollars to pounds
                        $exchangedSalary = $salary * $usdToGbp;
                        // Tax at british rate
                        $afterTaxSalary = calculateAfterTaxSalary($exchangedSalary, $taxTables);
                        // Convert back to USD
                        $afterTaxSalary = $afterTaxSalary * $gbpToUsd;
                        $afterTaxSalary = number_format($afterTaxSalary, 2);
                    }

                    $userPhotoCell = getUserPhotoCell($id);

                    echo "<tr>
                            $userPhotoCell
                            <td>$id</td>
                            <td>$fullName</td>
                            <td>$jobPosition</td>
                            <td>$employeesCurrency$salaryFormatted</td>
                            <td>$employeesCurrency$afterTaxSalary</td>
                            <td><a class='viewPayslipLink' href='payslip.php?id=$id'>View Payslip</a></td>
                        </tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
