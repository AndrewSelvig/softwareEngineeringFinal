Author: Xander R
Function:

<?php
function Sales_Report($SQL_Connection) {

    $SQL_Query = "SELECT * FROM sales";

    $Statement = $SQL_Connection->prepare($SQL_Query);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "Error executing query in Create_User function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows === 0){
        return "No Sales Orders";
    }
    else{
        return $Result;
    }

}
?>