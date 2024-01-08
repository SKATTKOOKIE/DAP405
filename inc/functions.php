<?PHP
// ================================================================================
// Calculate employees age, also to see if data is valid coming in

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
// ________________________________________________________________________________

// ================================================================================
// Calculate employees salary after appropriate tax

function calculateAfterTaxSalary($salary, $taxTables, $hasCompanyCar) 
{
    $afterTaxSalary = $salary; // Default value in case there's no matching tax bracket

    if($hasCompanyCar == 'y' or $taxTables['id'] == 4)
    {
        $untaxableIncome = 5000;
    }
    else
    {
        $untaxableIncome = 10000;
    }

    foreach ($taxTables as $taxBracket) 
    {
        $minSalary = $taxBracket['minsalary'];
        $maxSalary = $taxBracket['maxsalary'];
        $taxRate = $taxBracket['rate'];

        // Max amount for tax band 2
        $taxBandTwoMaxAmount = 24000;
        $taxBandThreeMaxAmount = 66000;
        // Simplify calculation to 1 so then you can if ID perform last calculation rather than 3 different calculations

        if ($salary >= $minSalary && $salary <= $maxSalary) 
        {

            if ($taxBracket['id'] == 1) 
            {
                // If employee is earning less than £10,000 do not make any calculations
                // and return salary as is
                $afterTaxSalary = $salary;
            } 
            
            elseif ($taxBracket['id'] == 2) 
            {
                // Remove untaxable £10,000 from salary
                $removedUnTaxablePay = $salary - $untaxableIncome;
                // Calculate the Deductable from the salary
                $payAfterTax = $removedUnTaxablePay * ($taxRate / 100);
                // Calculate the after-tax salary for Basic rate
                $afterTaxSalary = $salary - $payAfterTax;
            } 

            elseif ($taxBracket['id'] == 3) 
            {
                // $untaxablePayAmount = $minSalary + $untaxableIncome;

                // Salary - untaxable money and previous band
                $removedUnTaxablePay = $salary - $minSalary;

                // Calculate the Deductable from left over salary
                $taxAmount = $removedUnTaxablePay * ($taxRate / 100);

                // Work out tax free amount
                $band3LeftOver = $removedUnTaxablePay - $taxAmount;

                // Work out final salary after taxes
                $afterTaxSalary = $band3LeftOver + $taxBandTwoMaxAmount + $untaxableIncome;
            } 

            elseif ($taxBracket['id'] == 4) 
            {
                // $untaxablePayAmount = $minSalary + $untaxableIncome;

                // Salary - untaxable money and previous band
                $removedUnTaxablePay = $salary - $minSalary;

                // Calculate the Deductable from left over salary
                $taxAmount = $removedUnTaxablePay * ($taxRate / 100);

                // Work out tax free amount
                $band3LeftOver = $removedUnTaxablePay - $taxAmount;

                // Work out final salary after taxes
                $afterTaxSalary = $band3LeftOver + $taxBandTwoMaxAmount + $taxBandThreeMaxAmount + $untaxableIncome;
            }

            break; // Exit the loop once the correct tax bracket is found
        }
    }

    return $afterTaxSalary;
}
// ________________________________________________________________________________

// ================================================================================
// Calculate employees time served with the company

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
// ________________________________________________________________________________

// ================================================================================
// Get users ID to find appropriate image for login

function getUserPhotoCell($userId)
{
    $imagePath = "images/{$userId}.png";

    if (file_exists($imagePath)) 
    {
        return "<td><img id='table-image' src='$imagePath' alt='User Photo'></td>";
    } 
    else 
    {
        return "<td>No Photo</td>";
    }
}
// ________________________________________________________________________________
?>