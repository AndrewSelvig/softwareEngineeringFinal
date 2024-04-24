Author: Xander R
Function: This PHP file queries a DB for everything in the cart table with a specified userID and returns it


<?php

function Retrieve_Cart($SQL_Connection, $User_ID) {

    $SQL_Query = "SELECT * FROM cart WHERE userid LIKE '%?%'";

    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $User_ID);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result === 0) {

        return "Nothing";
    }
    else {
        return $Result;
    }
}
?>




