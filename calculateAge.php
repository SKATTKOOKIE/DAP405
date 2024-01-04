<?php
    function calculateAge($dob)
    {
        require('inc/globalVar.php');

        // Get the current date
        $currentDate = new DateTime();

        // Convert date of birth to DateTime object
        $dobDate = new DateTime($dob);

        // Calculate the interval
        $interval = $currentDate->diff($dobDate);

        // Get the years from the interval
        $years = $interval->y;

        if($years < $minimumWorkingAge)
        {
            echo "Age is beneath required minimum please check records";
        }
        else
        {
            // Output the result
            echo "Age: $years years old.";
        }
    }
?>
