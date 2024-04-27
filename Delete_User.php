Author: Xander R
Function: This PHP file will delete a user from a DB

<?php

function Delete_User($SQL_Connection, $User_ID) {

    if ($User_ID === null){
        return "No User_ID given";
    }
    else{

        $SQL_Query = "SELECT userid FROM users WHERE userid = ?";
        
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

        if ($Result->num_rows === 0){
            return "User does not exist";
        }
        elseif ($Result->num_rows > 1){
            // if given UserID has duplicates
            $Error_Message = "Error due to duplicate UserIDs in Delete_User function at line " . __LINE__ . ": " . $Statement->error;
            $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
            error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL, 3, "error.log");
            error_log("Query: " . $SQL_Query . PHP_EOL, 3, "error.log");
            error_log("User_ID: " . $User_ID . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
            return "Duplicate UserIDs";
        }
        elseif ($Result->num_rows === 1) {
            return true;
        }
        else {
            return false;
        }

    }
}
?>
