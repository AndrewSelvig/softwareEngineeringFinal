Author: Xander R
Function: this file connects to a SQL database and then calls other PHP files which contain queries for retrieving information

<?php

// Include other PHP files
require_once 'Create_User.php';
require_once 'Login.php';
require_once 'Search.php';
require_once 'Edit_User.php';
require_once 'Add_to_Cart.php';


// SQL database connection information
$Server_Name = "localhost";
$DB_Username = "username";
$DB_Password = "password";
$DB_Name = "myDB";

// Create connection
$SQL_Connection = new mysqli($Server_Name, $DB_Username, $DB_Password, $DB_Name);
// Check connection
if ($SQL_Connection->connect_error) {
  die("Connection failed: " . $SQL_Connection->connect_error);
}

// statements to call query functions

//////////////////
//  User Stuff  //
//////////////////

///////////////////
//  create user  //
///////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Retrieve form input values
        $Username = $_POST['username'];
        $Password = $_POST['password'];
        $Fname = $_POST['Fname'] ?? 'None';
        $Lname = $_POST['Lname'] ?? 'None';


        // Call your function to create a new user
        $new_user = Create_User($SQL_Connection, $Username, $Password, $Fname, $Lname);
        if ($new_user === true){
            // Success
            $Response = array("success" => true, "message" => "User created successfully");
        }
        elseif ($new_user === false) {
            // SQL Failure
            $Response = array("success" => false, "message" => "SQL error");
        }
        else {
            // User already exists
            $Response = array("success" => false, "$new_user");
        }
    }
    else {
        // Missing username or password
        $Response = array("success" => false, "message" => "Username or password is missing");
    }
    // Send response to front end
    echo json_encode($Response);
}

/////////////
//  Login  //
/////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Retrieve form input values
        $Username = $_POST['username'];
        $Password = $_POST['password'];

        $Login = Login($SQL_Connection, $Username, $Password);

        if ($Login === "Incorrect password" || $Login === "Username does not exist") {
            // username or password incorrect
            $Response = array("success" => false, "message" => "Username or Password incorrect");
        }
        elseif ($Login === "Duplicate Username"){
            // username in DB multiple times (this should not happen and is really bad if it does)
            $Response = array("success" => false, "message" => "Database error: Duplicate Usernames THIS IS A REALLY BAD ERROR");
        }
        elseif ($Login === false) {
            // false return only happens when the query to check the username fails
            $Response = array("success" => false, "message" => "SQL Query Error");
        }
        else {
            // if the above are all false then the login was successful and $row has been returned and can be given to front end
            $Response = array("success" => true, "message" => $Login);
        }
    }
    else {
        // Missing username or password
        $Response = array("success" => false, "message" => "Username or password is missing");
    }
    // Send response to front end
    echo json_encode($Response);
}

////////////////////
//  Edit Profile  //
////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['profile'])) {
    if (isset($_POST['edit'])) { // require login to change information
        if (isset($_POST['username']) && isset($_POST['password'])) {
            
            // Retrieve form input values
            $Username = $_POST['username'];
            $Password = $_POST['password'];

            $Login = Login($SQL_Connection, $Username, $Password);

            if ($Login === "Incorrect password" || $Login === "Username does not exist") {
                // username or password incorrect
                $Response = array("success" => false, "message" => "Username or Password incorrect");
            }
            elseif ($Login === "Duplicate Username"){
                // username in DB multiple times (this should not happen and is really bad if it does)
                $Response = array("success" => false, "message" => "Database error: Duplicate usernames THIS IS A REALLY BAD ERROR");
            }
            elseif ($Login === false) {
                // false return only happens when the query to check the username fails
                $Response = array("success" => false, "message" => "SQL query error");
            }
            else { // if the above are all false then the login was successful and edits can be processed
                
                // parameters that will be changed in the DB.
                $Email = $_POST['email'] ?? null;
                $FName = $_POST['FName'] ?? null;
                $LName = $_POST['LName'] ?? null;
                $Address = $_POST['address'] ?? null;
                $Phone_Number = $_POST['phone_number'] ?? null;

                $loop = 0; 
                do { // all of the above variables should be in this function call
                    $Edit_User_Info = Edit_User($SQL_Connection, $Username, $Password, $Email, $FName, $LName, $Address, $Phone_Number);
                    $loop++;

                } while ($Edit_User_Info === false || $loop != 5); // attempt to update the information up to 5 times

                if ($Edit_User_Info === false) {
                    $Response = array("success" => false, "message" => "Could not edit profile, SQL query error");
                }
                else {
                    $Response = array("success" => true, "message" => $Edit_User_Info);
                }
            }
        }
        else {
            // Missing username or password
            $Response = array("success" => false, "message" => "Username or password is missing");
        }
        // Send response to front end
        echo json_encode($Response);
    }
}

//////////////////
//  Cart Stuff  //
//////////////////

///////////////////
//  Add to Cart  //
///////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart']) && $_POST['add_to_cart'] == true) {
    if (isset($_POST['product_id']) && $_POST['product_id'] > 0 && isset($_POST['quantity']) && $_POST['quantity'] > 0) {
        
        $Product_ID = $_POST['product_id'];
        $Quantity = $_POST['quantity'];
        $User_ID = $_POST['user_id'] ?? null;

        $Cart_Response = Add_to_Cart($SQL_Connection, $Product_ID, $Quantity, $User_ID);

        if ($Cart_Response === "New"){

            $Response = array("success" => true, "message" => "added " . $Quantity . " " . $Product_ID . " to the cart of " . $User_ID);
        }
        elseif ($Cart_Response['updated'] === true) {
            $Response = array("success" => true, "message" => "updated quantity of " . $Product_ID . "to" . $Cart_Response['New_Quantity'] . " in the cart of " . $User_ID);
        }

        elseif ($Cart_Response === false) {
            $Response = array("success" => false, "message" => "SQL query error");
        }
        elseif ($Cart_Response === "Multiple entries of product and/or customer") {
            $Response = array("success" => false, "message" => "Multiple entries of product and customer");
        }
        else{
            $Response = array("success" => false, "message" => "Unexpected Error");
        }

    }
    else {
        $Response = array("success" => false, "message" => "No Product ID Given");
    }
    // Send response to front end
    echo json_encode($Response);
}

/////////////////////
//  Retrieve Cart  //
/////////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['display_cart']) && $_POST['display_cart'] == true) {
    if (isset($_POST['user_id']) && $_POST['user_id'] !== null) {

        $User_ID = $_POST['user_id'];

        $Cart_Contents = Retrieve_Cart($SQL_Connection, $User_ID);

        if ($Cart_Contents === false) {
            $Response = array("success" => false, "message" => "SQL query error");
        }
        elseif ($Cart_Contents === "Nothing") {
            $Response = array("success" => true, "message" => "Nothing in Cart");
        }
        else {
            $Response = array("success" => false, "message" => $Cart_Contents);
        }

    }
    // Send response to front end
    echo json_encode($Response);
}

/////////////
//  Other  //
/////////////

///////////////////
//  Item Search  //
///////////////////
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {

        $Search_Criteria = $_POST['search_criteria'] ?? ''; // if no search criteria is given it will use '' (should return everything)

        $Search_Result = Search($SQL_Connection, $Search_Criteria);

        if ($Search_Result === "No Match"){
            // No matching search
            $Response = array("success" => false, "message" => "No matching results");
        }
        elseif ($Search_Result === "Bad Result"){
            // this should not happen and will only happen if there is an error with the query return but not the query itself
            $Response = array("success" => false, "message" => "Bad result return");
        }
        elseif ($Search_Result === false){
            // false return only happens when the query of the DB fails
            $Response = array("success" => false, "message" => "SQL Query error");
        }
        else{
            // false return only happens when the query to check username fails
            $Response = array("success" => true, "message" => $Search_Result);
        }
    // Send response to front end
    echo json_encode($Response);
}

////////////////////////
//  Process Purchase  //
////////////////////////

$Purchase = true; // placeholder for if purchase is approved

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['process_purchase'] === true) {
    if (isset($_POST['card_number']) && isset($_POST['card_cvv'])){ // rest of necissary payment info

        $Product_IDs = $_POST['products']; // These both need to be arrays and have corrosponding keys
        $Quantities = $_POST['quantities']; // ex. quantities[0] is for products[0] and quantities[10] is for products[10] etc.

        if (count($Product_IDs) === count($Quantities)){
            
            if ($Purchase === true) {
                $Inventory = Update_Inventory($SQL_Connection, $Product_IDs, $Quantities);
            }
        }
    }
    else{
        $Response = array("success" => false, "message" => "Username or password is missing");
    }
}

// close SQL connection
$SQL_Connection->close();
?>

Error log formatting:
[error date/time] error location: error message
More error message information

-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
[error date/time] error location: next error message