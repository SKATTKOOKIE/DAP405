<?php
    require('fpdf/fpdf.php'); 
    require('inc/globalVar.php');
    require('inc/functions.php');
    require('inc/config.php');

    session_start();
    
    // Check if the user is logged in, if not, redirect to the login page
    if (!isset($_SESSION['user'])) 
    {
        header("Location: login.php");
        exit();
    }

    // Read the employee data & tax brackets from corresponding external JSON
    $employeeData = json_decode(file_get_contents('jsonData/employee-data.json'), true);
    $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);
    
    // Check if the "id" parameter is set in the URL
    if (isset($_GET['id'])) 
    {   
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

        // Create a new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();

        // Add content to the PDF
        if ($selectedEmployee) 
        {
            $salary = $selectedEmployee['salary'];
            $salaryFormatted = number_format($salary, 2);
            $currency = $selectedEmployee['currency'];
            $hasCompanyCar = $selectedEmployee['companycar'];
            $id = $selectedEmployee['id'];

            if($currency == 'GBP')
            {
                $employeesCurrency = iconv('UTF-8', 'ISO-8859-1', $pounds);
                // Calculate after-tax salary
                $afterTaxSalary = calculateTax($salary, $taxTables, $hasCompanyCar);
                $numericSalary = (int)$afterTaxSalary;
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
                $numericSalary = (int)$afterTaxSalary;
                $afterTaxSalary = $afterTaxSalary * $gbpToUsd;
                $afterTaxSalary = number_format($afterTaxSalary, 2);
            }

            // Set the title and center it
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Woodton LTD Payslip', 0, 1, 'C');
            $pdf->Ln(10);

            // Add employee's pay details to the PDF
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Date: ' . date('d-m-y'), 0, 1);
            $pdf->Cell(0, 10, 'ID: ' . $selectedEmployee['id'], 0, 1);
            $pdf->Cell(0, 10, 'Name: ' . $selectedEmployee['firstname'] . ' ' . $selectedEmployee['lastname'], 0, 1);
            $pdf->Cell(0, 10, 'Job Title: ' . $selectedEmployee['jobtitle'], 0, 1);
            $pdf->Cell(0, 10, 'National Insurance Number: ' . $selectedEmployee['nationalinsurance'], 0, 1);
            $pdf->Ln(10);

            // Create a table for financial information
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(100, 10, 'Description', 1);
            $pdf->Cell(80, 10, 'Amount', 1);
            $pdf->Ln();

            // Display Salary
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(100, 10, 'Salary (per year):', 1);
            $pdf->Cell(80, 10, $employeesCurrency . $salaryFormatted, 1);
            $pdf->Ln();

            // Calculate the applicable tax rate based on the salary
            $taxRate = 0;
            foreach ($taxTables as $taxTable) 
            {
                if ($selectedEmployee['salary'] >= $taxTable['minsalary'] && $selectedEmployee['salary'] <= $taxTable['maxsalary']) 
                {
                    $taxRate = $taxTable['rate'];
                    break;
                }
            }

            // Calculate tax amount
            $taxAmount = $salary - $numericSalary;

            // Display Tax Rate
            $pdf->Cell(100, 10, 'Tax Rate:', 1);
            $pdf->Cell(80, 10, $taxRate . '%', 1);
            $pdf->Ln();

            // Display Tax Amount
            $pdf->Cell(100, 10, 'Tax Amount:', 1);
            $pdf->Cell(80, 10, $employeesCurrency . number_format($taxAmount, 2), 1);
            $pdf->Ln();

            // Display Take-Home Pay
            $pdf->Cell(100, 10, 'Take-Home Pay:', 1);
            $pdf->Cell(80, 10, $employeesCurrency . $afterTaxSalary, 1);
            $pdf->Ln();

            // Output the PDF (you can save it or display it)
            $pdf->Output();
        }

        else 
        {
            echo 'Employee not found.';
        }
    }
    else 
    {
        echo 'Invalid request.';
    }
?>
