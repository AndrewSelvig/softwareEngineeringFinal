Author: Xander R
Function: This PHP file creates a function that will query a SQL database to check if a user exists for a given username 
    and will create a user if one does not exist

<?php

function Create_User($SQL_Connection, $New_Username, $New_Password, $FName, $LName) {

    $SQL_Query = "SELECT username FROM users WHERE username = ?";
    
    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $New_Username);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "Error executing query in Create_User function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows > 0) {
        // if entered username already exists
        return "Username Already Exists";
    }
    else { // if username does not exist create a userID and create the user in the DB
        
        $UniqID = abs(crc32(uniqid()));
        $Random = mt_rand();
        $UserID = $UniqID . $Random;

        // hash the password
        $Hashed_Password = password_hash($New_Password, PASSWORD_DEFAULT);
        $SQL_Query = "INSERT INTO users VALUES (?, ?, ?, ?, ?)";
        $Statement = $SQL_Connection->prepare($SQL_Query);
        $Statement->bind_param("sssss", $UserID, $New_Username, $Hashed_Password, $FName, $LName);
        if (!$Statement->execute()) {
            // Handle insertion error
            $Error_Message = "Error inserting user in Create_User function at line " . __LINE__ . ": " . $Statement->error;
            $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
            error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
            return false;
        }
        // User successfully created
        return true;
    }
}
?>