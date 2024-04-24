Author: Xander R
Function: This PHP file queries a DB when a user tried to edit their profile information


<?php

function Edit_User($SQL_Connection, $Username, $Password, $Email, $FName, $LName, $Address, $Phone_Number) {

    $FieldsToUpdate = array(); // array to store non-null fields
    $Parameters = array(); // array to store parameter values

    // Define the fields and corresponding parameters
    $Fields = array("password" => $Password, "email" => $Email, "fname" => $FName, "lname" => $LName, "address" => $Address, "phone_number" => $Phone_Number);

    foreach ($Fields as $Field => $Value) { // Loop through the fields and parameters
        // If the value is not null, add the field to the FieldsToUpdate array
        if ($Value !== null) {
            $FieldsToUpdate[] = "$Field = ?";
            $Parameters[] = $Value;
        }
    }

    // Dynamically construct the SQL query
    $SQL_Query = "UPDATE users SET " . implode(", ", $FieldsToUpdate) . " WHERE username = ?";
    $Statement = $SQL_Connection->prepare($SQL_Query);

    $Parameters[] = $Username; // Add the username parameter
    $types = str_repeat("s", count($Parameters));
    $Statement->bind_param($types, ...$Parameters);

    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Edit_User function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $log_entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($log_entry, 3, "error.log");
        return false;
    }

    $SQL_Query = "SELECT * FROM users WHERE username = ?";
    
    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $Username);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Edit_User function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }

    $Result = $Statement->get_result();

    if ($Result->num_rows === 1) {
        // return updated information
        $Row = $Result->fetch_assoc();
        return $Row;
    }
}
?>