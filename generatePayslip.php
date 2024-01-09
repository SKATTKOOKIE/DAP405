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
            $afterTaxSalary = calculateTax($salary, $taxTables, $hasCompanyCar, $currency);

            // Calculate tax amount
            $taxAmount = $salary - $afterTaxSalary;
            $taxAmount = number_format($taxAmount,2);

            if($currency == 'GBP')
            {
                $employeesCurrency = $poundCharFormatted;
            }

            if($currency == 'USD')
            {
                $employeesCurrency = $dollars;
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

            // Display Tax Rate
            $pdf->Cell(100, 10, 'Tax Rate:', 1);
            $pdf->Cell(80, 10, $taxRate . '%', 1);
            $pdf->Ln();

            // Display Tax Amount
            $pdf->Cell(100, 10, 'Tax Amount:', 1);
            $pdf->Cell(80, 10, $employeesCurrency . $taxAmount, 1);
            $pdf->Ln();

            // Display Take-Home Pay
            $pdf->Cell(100, 10, 'Take-Home Pay:', 1);
            $pdf->Cell(80, 10, $employeesCurrency . number_format($afterTaxSalary, 2), 1);
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
