Author: Xander R
Function: this file connects to a SQL database and then calls other PHP files which contain queries for retrieving information

<?php

// Include other PHP files
require_once 'Create_User.php';
require_once 'Login.php';


// SQL database connection information
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// statements to call query functions

// create user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Retrieve form input values
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (isset($_POST['Fname']) && isset($_POST['Lname'])) {
            $Fname = $_POST['Fname'];
            $Lname = $_POST['Lname'];
        }
        else {
            $Fname = 'none';
            $Lname = 'none';
        }

        // Call your function to create a new user
        $new_user = Create_User($conn, $username, $password, $Fname, $Lname);
        if ($new_user === true){
            // Success
            $response = array("success" => true, "message" => "User created successfully");
        }
        elseif ($new_user === false) {
            // SQL Failure
            $response = array("success" => false, "message" => "SQL error");
        }
        else {
            // User already exists
            $response = array("success" => false, "$new_user");
        }
    }
    else {
        // Missing username or password
        $response = array("success" => false, "message" => "Username or password is missing");
    }
    // Send response to front end
    echo json_encode($response);
}

// close SQL connection
$conn->close();
?>