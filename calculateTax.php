<?php
function calculateAfterTaxSalary($salary, $taxTables) 
{
    $afterTaxSalary = $salary; // Default value in case there's no matching tax bracket

    foreach ($taxTables as $taxBracket) {
        $minSalary = $taxBracket['minsalary'];
        $maxSalary = $taxBracket['maxsalary'];
        $taxRate = $taxBracket['rate'];

        if ($salary >= $minSalary && $salary <= $maxSalary) 
        {
            // Calculate the taxable amount
            $taxableAmount = ($salary - $minSalary) * ($taxRate / 100);

            // Calculate the after-tax salary
            $afterTaxSalary = $salary - $taxableAmount;

            break; // Exit the loop once the correct tax bracket is found
        }
    }

    return $afterTaxSalary;
}

?>
