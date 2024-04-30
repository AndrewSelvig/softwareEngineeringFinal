Author: Xander R
Function:

<?php

function Complete_Sale($SQL_Connection, $User_ID) {

    $SQL_Query = "SELECT userid FROM cart WHERE userid = ?";

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

    if ($Result->num_rows === 0) {
        return "No items in cart";
    }
    else{
        $SQL_Query = "INSERT INTO Sales (order_number, user_id, product_id, quantity)
                    SELECT 
                        NEWID(),
                        user_id,
                        product_id,
                        quantity
                    FROM 
                        cart
                    WHERE 
                        user_id = 'your_user_id';
                    DELETE FROM cart
                    WHERE 
                        user_id = :user_id";

        $Statement = $SQL_Connection->prepare($SQL_Query);
        $Statement->bind_param("s", $User_ID);
        if (!$Statement->execute()) {
            // Handle query execution error
            $Error_Message = "Error executing query in Create_User function at line " . __LINE__ . ": " . $Statement->error;
            $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
            error_log(date("[Y-m-d H:i:s]") . " " . $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL, 3, "error.log");
            return false;
        }
        else{
            return true;
        }
    }

}
?>