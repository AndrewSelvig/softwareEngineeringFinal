Author: Xander R
Function: This PHP file creates a function that will query a SQL database to check if a user exists for a given username 
    and will create a user if one does not exist


<?php

function Create_User($connection, $new_username, $new_password, $Fname, $Lname) {

    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $new_username);
    if (!$stmt->execute()) {
        // Handle query execution error
        error_log("Error executing query: " . $stmt->error);
        return false;
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // if entered username already exists
        return "Username Already Exists";
    }
    else {// if username does not exist create a userID and create the user in the DB
        
        $uniqid = uniqid();
        $random = mt_rand();
        $userID = $uniqid . $random;

        // hash the password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $userID, $new_username, $hashed_password, $Fname, $Lname);
        if (!$stmt->execute()) {
            // Handle insertion error
            error_log("Error inserting user: " . $stmt->error);
            return false;
        }
        // User successfully created
        return true;
    }
}
?>