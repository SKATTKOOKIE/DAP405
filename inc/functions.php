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

function calculateAfterTaxSalary($salary, $taxTables) 
{
    $afterTaxSalary = $salary; // Default value in case there's no matching tax bracket

    foreach ($taxTables as $taxBracket) 
    {
        $minSalary = $taxBracket['minsalary'];
        $maxSalary = $taxBracket['maxsalary'];
        $taxRate = $taxBracket['rate'];
        // First £10,000 is untaxable
        $untaxableIncome = 10000;
        // 20% of £30,000 which is made for if salary is exceeds tax band 2
        $taxBandTwoDeductionIfUserExceedsThreshold = 6000;
        $taxBandThreeDeductionIfUserExceedsThreshold = 44000;

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
                // Remove first £10,000 and band 2s £30,000 as these are calculated
                // at a different tax percentage
                $removedUnTaxablePay = $salary - $minSalary;
                // Calculate the Deductable from the salary
                $payAfterTax = $removedUnTaxablePay * ($taxRate / 100);
                $totalDeductions = $payAfterTax + $taxBandTwoDeductionIfUserExceedsThreshold;
                // Calculate the after-tax salary for Higher rate
                $afterTaxSalary = $salary - $totalDeductions;
            } 

            elseif ($taxBracket['id'] == 4) 
            {
                // half untaxable and remove, band 2s £30,000 & band 3s £110,000
                // as these are calculated at a different tax percentage
                $removedUnTaxablePay = $salary - $minSalary;
                // Calculate the Deductable from the salary
                $payAfterTax = $removedUnTaxablePay * ($taxRate / 100);
                $totalDeductions = $payAfterTax + $taxBandTwoDeductionIfUserExceedsThreshold + $taxBandThreeDeductionIfUserExceedsThreshold + ($untaxableIncome * 0.5);

                // Calculate the after-tax salary for Super rate
                $afterTaxSalary = $salary - $totalDeductions;
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