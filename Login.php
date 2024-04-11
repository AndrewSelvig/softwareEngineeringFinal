Author: Xander R
Function: This PHP file creates a function that queries a database for an existing user and checks if the login 
    credentials given are correct


<?php

function Login($connection, $username, $password) {

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        // Handle query execution error
        $error_message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $stmt->error;
        $separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $log_entry = $error_message . PHP_EOL . PHP_EOL . $separator . PHP_EOL;
        error_log($log_entry, 3, "error.log");
        return false;
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // if entered username is not in DB
        return "Username does not exist";
    }
    elseif ($result->num_rows < 1) {
        // if entered username has duplicates
        $error_message = "Error due to duplicate usernames in Login function at line " . __LINE__ . ": " . $stmt->error;
        $separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $error_message . PHP_EOL, 3, "error.log");
        error_log("Query: " . $sql . PHP_EOL, 3, "error.log");
        error_log("Username: " . $username . PHP_EOL . PHP_EOL . $separator . PHP_EOL, 3, "error.log");
        return "Duplicate Username";
    }
    else{
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct and returns the user data
            return $row;
        }
        else {
            // Password is incorrect
            return "Incorrect password";
        }
    }
}
?>