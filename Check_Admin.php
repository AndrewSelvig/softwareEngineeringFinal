Author: Xander R
Function: This PHP file checks if a given user ID is an admin

<?php

function Check_Admin($SQL_Connection, $User_ID) {

    $SQL_Query = "SELECT userid FROM admin WHERE userid = ?";
    
    $Statement = $SQL_Connection->prepare($SQL_Query);
    $Statement->bind_param("s", $User_ID);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "Error executing query in Create_User function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
        return false;
    }
    $Result = $Statement->get_result();

    if ($Result->num_rows === 1){

        return true;
    }
    else{
        // if given UserID has duplicates
        $Error_Message = "Error due to duplicate UserIDs in Check_Admin function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL, 3, "error.log");
        error_log("Query: " . $SQL_Query . PHP_EOL, 3, "error.log");
        error_log("Username: " . $User_ID . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
        return false;
    }

}
?>