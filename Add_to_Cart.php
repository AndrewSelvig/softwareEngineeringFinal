Author: Xander R
Function: This PHP file will query a DB and add or update entries in a table


<?php

function Add_to_Cart($SQL_Connection, $Product_ID, $Quantity, $User_ID) {

    $SQL_Query = "SELECT * FROM cart WHERE userid = ? AND productid = ?";

    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("ss", $User_ID, $Product_ID);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows === 0) { // User does not already have this item in their cart

        $SQL_Query = "INSERT INTO cart VALUES (?, ?, ?)";

        $Statement = $SQL_Connection->prepare($SQL_Query);
        $Statement->bind_param("ss", $User_ID, $Product_ID, $Quantity);
        if (!$Statement->execute()) {
            // Handle query execution error
            $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
            $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
            $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
            error_log($Log_Entry, 3, "error.log");
            return false;
        }
        else {
            return "New";
        }
    }
    elseif ($Result->num_rows === 1) {

        $Row = $Result->fetch_assoc();
        $DB_Quantity = $Row['quantity'];

        $New_Quantity = $DB_Quantity + $Quantity;

        $SQL_Query = "UPDATE cart SET quantity = ? WHERE userid = ? AND productid = ?";

        $Statement = $SQL_Connection->prepare($SQL_Query);
        $Statement->bind_param("sss", $New_Quantity, $User_ID, $Product_ID);
        if (!$Statement->execute()) {
            // Handle query execution error
            $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
            $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
            $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
            error_log($Log_Entry, 3, "error.log");
            return false;
        } else {
            return array("updated" => true, "New_Quantity" => $New_Quantity);
        }
    }
    else {
        return "Multiple entries of product and/or customer";
    }
}
?>