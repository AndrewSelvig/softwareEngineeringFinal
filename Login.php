Author: Xander R
Function: This PHP file creates a function that queries a database for an existing user and checks if the login 
    credentials given are correct


<?php

function Login($SQL_Connection, $Username, $Password) {

    $SQL_Query = "SELECT * FROM users WHERE username = ?";
    
    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $Username);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows === 0) {
        // if entered username is not in DB
        return "Username does not exist";
    }
    elseif ($Result->num_rows > 1) {
        // if entered username has duplicates
        $Error_Message = "Error due to duplicate usernames in Login function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL, 3, "error.log");
        error_log("Query: " . $SQL_Query . PHP_EOL, 3, "error.log");
        error_log("Username: " . $Username . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
        return "Duplicate Username";
    }
    else{
        $Row = $Result->fetch_assoc();
        if (password_verify($Password, $Row['password'])) {
            // Password is correct and returns the user data
            return $Row;
        }
        else {
            // Password is incorrect
            return "Incorrect password";
        }
    }
}
?>