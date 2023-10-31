<?php
    require('fpdf/fpdf.php'); 

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

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Woodton LTD', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Payslip', 0, 1, 'C');
            $pdf->Ln(10);

            // Add employee's pay details to the PDF
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'ID: ' . $selectedEmployee['id'], 0, 1);
            $pdf->Cell(0, 10, 'Name: ' . $selectedEmployee['firstname'] . ' ' . $selectedEmployee['lastname'], 0, 1);
            $pdf->Cell(0, 10, 'Job Title: ' . $selectedEmployee['jobtitle'], 0, 1);
            $pdf->Cell(0, 10, 'National Insurance Number: ' . $selectedEmployee['nationalinsurance'], 0, 1);

            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Financial Information', 0, 1);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Salary (per year): £' . $selectedEmployee['salary'], 0, 1);

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

            $pdf->Cell(0, 10, 'Tax Rate: ' . $taxRate . '%', 0, 1);
            $pdf->Cell(0, 10, 'Tax Amount: £' . $taxAmount, 0, 1);
            $pdf->Cell(0, 10, 'Take-Home Pay: £' . $takeHomePay, 0, 1);
            // Add more pay details here

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
