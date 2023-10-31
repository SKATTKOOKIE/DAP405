<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payroll Data</title>
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
    <h1>Employee Payroll Data</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Job Position</th>
                <th>Salary (per year)</th>
                <th>After Tax Salary</th>
                <th>Action</th> <!-- New column for the "View Payslip" button -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Read employee data from JSON file
            $employeeData = json_decode(file_get_contents('employee-data.json'), true);

            // Read tax rate data from JSON file
            $taxTables = json_decode(file_get_contents('tax-tables.json'), true);

            foreach ($employeeData as $employee) {
                $id = $employee['id'];
                $fullName = $employee['firstname'] . ' ' . $employee['lastname'];
                $jobPosition = $employee['jobtitle'];
                $salary = $employee['salary'];

                // Calculate after-tax salary
                $afterTaxSalary = calculateAfterTaxSalary($salary, $taxTables);

                echo "<tr>
                        <td>$id</td>
                        <td>$fullName</td>
                        <td>$jobPosition</td>
                        <td>£$salary</td>
                        <td>£$afterTaxSalary</td>
                        <td><a class='viewPayslipLink' href='payslip.php?id=$id'>View Payslip</a></td>
                      </tr>";
            }

            // Function to calculate after-tax salary based on tax tables
            function calculateAfterTaxSalary($salary, $taxTables) {
                $afterTaxSalary = $salary;

                foreach ($taxTables as $taxTable) {
                    if ($salary >= $taxTable['minsalary'] && $salary <= $taxTable['maxsalary']) {
                        $afterTaxSalary -= ($taxTable['rate'] / 100) * ($salary - $taxTable['minsalary']);
                        break;
                    }
                }

                return $afterTaxSalary;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
