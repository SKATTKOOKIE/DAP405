<?php
    // Variables that are accessed by multiple pages
    $gbpToUsd = 1.22;
    $usdToGbp = 0.8081;
    $pounds = '£';
    $dollars = '$';
    $minimumWorkingAge = 18;
    
    // This has been done so the pound sign prints correctly within the PDF as before I was getting (Â£)
    // I used this website for the £ code and how to perform the function:
    // https://www.slideshare.net/Daniel_Rhodes/charset-iconv-phpsourcecode
    $poundCharFormatted = iconv('UTF-8', 'ISO-8859-1', $pounds);

    $employeeDataFile = 'jsonData/employee-data.json';
    $taxTables = json_decode(file_get_contents('jsonData/tax-tables.json'), true);

    if (file_exists($employeeDataFile)) 
    {
        $employeeData = json_decode(file_get_contents($employeeDataFile), true);

    } 

    else 
    {
        // If the file doesn't exist, show "no data"
        echo "<tr><td colspan='7'>No data</td></tr>";
        exit(); // Exit the script
    }

?>