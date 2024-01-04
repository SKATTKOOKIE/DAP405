<!-- Core code from: https://www.w3schools.com/php/func_mail_mail.asp -->

<?php

    function sendEmail()
    {
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

        if ($selectedEmployee) 
        {
            $employeeName = $selectedEmployee['firstname'] . $selectedEmployee['lastname'];

            // Message string to send
            $msg = $employeeName;

            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);

            // send email
            mail("brandon.hinton@saabgroup.com","Testing PHP email sending",$msg);
        }
    }
?>