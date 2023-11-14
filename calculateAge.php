<?php
function calculateAge($dob)
{
    // Get the current date
    $currentDate = new DateTime();

    // Convert date of birth to DateTime object
    $dobDate = new DateTime($dob);

    // Calculate the interval
    $interval = $currentDate->diff($dobDate);

    // Get the years from the interval
    $years = $interval->y;

    // Output the result
    echo "Age: $years years.";
}
?>
