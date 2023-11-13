<?php
    // This function was just for extracting IDs to add to user-logins.json

    function getEmployeeIDs() 
    {
        $employeeData = file_get_contents("jsonData/employee-data.json");
        $employees = json_decode($employeeData, true);
        
        $employeeIDs = array();
        
        foreach ($employees as $employee) 
        {
            $employeeIDs[] = $employee['id'];
        }
        
        return $employeeIDs;
    }

    // Call the function and print the IDs to the screen
    $ids = getEmployeeIDs();
    foreach ($ids as $id) 
    {   echo $id . ',<br>';
        // echo '{'. '<br>' .'"username":' . $id . '<br>' .', "password":"password"'. '<br>' . '},'. '<br>';
    }
?>
