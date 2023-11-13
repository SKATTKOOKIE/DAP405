<?php
    function calculateTimeServed($jsonFilePath) 
    {
        // Read JSON file and decode it
        $employeeData = json_decode(file_get_contents($jsonFilePath), true);

        // Check if the array is not empty
        if (!empty($employeeData)) 
        {
            // Access the first employee in the array
            $firstEmployee = $employeeData[0];

            // Check if the key exists in the employee data
            if (isset($firstEmployee['employmentstart'])) 
            {
                // Get the employment start date
                $employmentStartDate = strtotime($firstEmployee['employmentstart']);

                // Get the current date
                $currentDate = time();

                // Calculate the time difference
                $timeDifference = $currentDate - $employmentStartDate;

                // Calculate years and months
                $years = floor($timeDifference / (365 * 24 * 60 * 60));
                $months = floor(($timeDifference - ($years * 365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));

                // Output the result
                echo "Time served: $years years and $months months.";
            } 
            else 
            {
                echo "Key 'employmentstart' not found in the employee data.";
            }
        } 
        else 
        {
            echo "The employee data array is empty.";
        }
    }
?>
