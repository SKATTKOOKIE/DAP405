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
function calculateTax($salary, $taxTables, $hasCompanyCar, $currency) 
{
    $afterTaxSalary = $salary; // Default value in case there's no matching tax bracket

    // Exchange rates
    $gbpToUsd = 1.22;
    $usdToGbp = 0.8081;

    // If statement alters calculations for if user has company car
    // If true then reduce tax free cash
    if($hasCompanyCar == 'y' or $taxTables['id'] == 4)
    {
        $untaxableIncome = 5000;
        // Max amount for tax band 2
        $taxBandTwoMaxAmount = 28000;
    }
    
    else
    {
        $untaxableIncome = 10000;
        // Max amount for tax band 2
        $taxBandTwoMaxAmount = 24000;
    }

    // Change to GBP for correct taxation
    if($currency === 'USD')
    {
        $salary = $salary * $usdToGbp;
    }

    // Max amount for tax band 3
    $taxBandThreeMaxAmount = 66000;

    foreach ($taxTables as $taxBracket) 
    {
        $minSalary = $taxBracket['minsalary'];
        $maxSalary = $taxBracket['maxsalary'];
        $taxRate = $taxBracket['rate'];

        if ($salary >= $minSalary && $salary <= $maxSalary) 
        {
            // Remove minimum salary to have correct tax calculation
            $taxableAmount = $salary - $minSalary;

            // Calculate the Deductable from left over salary
            $taxAmount = $taxableAmount * ($taxRate / 100);

            // Remove tax from salary
            $taxedAmount = $taxableAmount - $taxAmount;

            if ($taxBracket['id'] == 1) 
            {
                // If employee is earning less than £10,000 do not make any calculations
                // and return salary as is
                $afterTaxSalary = $salary;
            } 
            
            elseif ($taxBracket['id'] == 2) 
            {
                // Work out final salary after taxes for tax band 2
                $afterTaxSalary = $taxedAmount + $untaxableIncome;
            } 

            elseif ($taxBracket['id'] == 3) 
            {
                // Work out final salary after taxes for tax band 3
                $afterTaxSalary = $taxedAmount + $untaxableIncome + $taxBandTwoMaxAmount;
            } 

            elseif ($taxBracket['id'] == 4) 
            {   // Work out final salary after taxes for tax band 4
                $afterTaxSalary = $taxedAmount + $untaxableIncome + $taxBandTwoMaxAmount + $taxBandThreeMaxAmount;
            }

            break; // Exit the loop once the correct tax bracket is found
        }
    }

    // Change to USD for people who use this currency
    if($currency === 'USD')
    {
        $afterTaxSalary = $afterTaxSalary * $gbpToUsd;
    }


    // $afterTaxSalary = number_format($afterTaxSalary, 2);
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