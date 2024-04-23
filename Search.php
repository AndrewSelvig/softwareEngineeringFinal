Author: Xander R
Function: This PHP file will query a DB to output the product information that was searched for

<?php

function Search($SQL_Connection, $Search_Criteria) {

    $SQL_Query = "SELECT * FROM product WHERE product_name LIKE '%?%'";
    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $Search_Criteria);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Search function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows === 0) {
        // if nothing matches search criteria
        return "No Match";
    }
    elseif ($Result->num_rows > 0) {
        // if there are results return them
        return $Result;
    }
    else {
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error during results return in Search function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return "Bad Result";
    }
}
?>