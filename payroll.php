
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
    <div class="PayrollContainer">
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
                require('inc/globalVar.php');
                require('inc/functions.php');

                foreach ($employeeData as $employee) 
                {
                    $id = $employee['id'];
                    $fullName = $employee['firstname'] . ' ' . $employee['lastname'];
                    $jobPosition = $employee['jobtitle'];
                    $salary = $employee['salary'];
                    $currency = $employee['currency'];
                    $hasCompanyCar = $employee['companycar'];
                    $salaryFormatted = number_format($salary, 2);

                    $afterTaxSalary = number_format(calculateTax($salary, $taxTables, $hasCompanyCar, $currency), 2);

                    if($currency == 'GBP')
                    {
                        $employeesCurrency = $pounds;
                    }

                    if($currency == 'USD')
                    {
                        $employeesCurrency = $dollars;
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
</div>
</body>
</html>
