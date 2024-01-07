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
?>