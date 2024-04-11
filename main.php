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

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Retrieve form input values
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login = Login($conn, $username, $password);

        if ($login === "Incorrect password" || $login === "Username does not exist") {
            // username or password incorrect
            $response = array("success" => false, "message" => "Username or Password incorrect");
        }
        elseif ($login === "Duplicate Username"){
            // username in DB multiple times (this should not happen and is really bad if it does)
            $response = array("success" => false, "message" => "Database error: Duplicate Usernames");
        }
        elseif ($login === false) {
            // false return only happens when the query to check the username fails
            $response = array("success" => false, "message" => "SQL Query Error");
        }
        else {
            // if the above are all false then $row showld have been returned and can be given to front end
            $response = array("success" => true, "message" => $login);
        }
    }
    else {
        // Missing username or password
        $response = array("success" => false, "message" => "Username or password is missing");
    }
    // Send response to front end
    echo json_encode($response);
}

// Search for item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
    if (isset($_POST['search_criteria'])) {
        // Retrieve form input values
        $search_criteria = $_POST['search_criteria'];

        $search_result = Search($conn, $search_criteria);

        if ($search_result === "No Match"){
            // No matching search
            $response = array("success" => false, "message" => "No matching results");
        }
        elseif ($search_result === "Bad Result"){
            // this should not happen and will only happen if there is an error with the query return but not the query itself
            $response = array("success" => false, "message" => "Bad result return");
        }
        elseif ($search_result === false){
            // false return only happens when the query of the DB fails
            $response = array("success" => false, "message" => "SQL Query error");
        }
        else{
            // false return only happens when the query to check username fails
            $response = array("success" => true, "message" => $search_result);
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

Error log formatting:
[error date/time] error location: error message
More error message information

-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
[error date/time] error location: next error message