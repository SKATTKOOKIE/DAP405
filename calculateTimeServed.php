<?php
    function calculateTimeServed($employmentStartDate)
    {
        // Get the current date
        $currentDate = time();

        // Calculate the time difference
        $timeDifference = $currentDate - strtotime($employmentStartDate);

        // Calculate years and months
        $years = floor($timeDifference / (365 * 24 * 60 * 60));
        $months = floor(($timeDifference - ($years * 365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));

        // Output the result
        echo "Time served: $years years and $months months.";
    }
?>
