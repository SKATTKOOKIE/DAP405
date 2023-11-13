<?php
    require('fpdf/fpdf.php'); 

    // This has been done so the pound sign prints correctly within the PDF as before I was getting (Â£)
    // I used this website for the £ code and how to perform the function:
    // https://www.slideshare.net/Daniel_Rhodes/charset-iconv-phpsourcecode
    $pound = '£';
    $poundCharFormatted = iconv('UTF-8', 'ISO-8859-1', $pound);

    // Check if the "id" parameter is set in the URL
    if (isset($_GET['id'])) 
    {
        // Read the employee data from the JSON file
        $employeeData = json_decode(file_get_contents('jsonData/employee-data.json'), true);

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

            $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);

            // Set the title and center it
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Woodton LTD Payslip', 0, 1, 'C');
            $pdf->Ln(10);

            // Add employee's pay details to the PDF
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'ID: ' . $selectedEmployee['id'], 0, 1);
            $pdf->Cell(0, 10, 'Name: ' . $selectedEmployee['firstname'] . ' ' . $selectedEmployee['lastname'], 0, 1);
            $pdf->Cell(0, 10, 'Job Title: ' . $selectedEmployee['jobtitle'], 0, 1);
            $pdf->Cell(0, 10, 'National Insurance Number: ' . $selectedEmployee['nationalinsurance'], 0, 1);
            $pdf->Ln(10);

            // Create a table for financial information
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(50, 10, 'Description', 1);
            $pdf->Cell(40, 10, 'Amount', 1);
            $pdf->Ln();

            // Display Salary
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 10, 'Salary (per year):', 1);
            $pdf->Cell(40, 10, $poundCharFormatted . $selectedEmployee['salary'], 1);
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

            // Calculate the take-home pay
            $taxAmount = ($selectedEmployee['salary'] * $taxRate) / 100;
            $takeHomePay = $selectedEmployee['salary'] - $taxAmount;

            // Display Tax Rate
            $pdf->Cell(50, 10, 'Tax Rate:', 1);
            $pdf->Cell(40, 10, $taxRate . '%', 1);
            $pdf->Ln();

            // Display Tax Amount
            $pdf->Cell(50, 10, 'Tax Amount:', 1);
            $pdf->Cell(40, 10, $poundCharFormatted . $taxAmount, 1);
            $pdf->Ln();

            // Display Take-Home Pay
            $pdf->Cell(50, 10, 'Take-Home Pay:', 1);
            $pdf->Cell(40, 10, $poundCharFormatted . $takeHomePay, 1);
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
