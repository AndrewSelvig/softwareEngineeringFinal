Author: Xander R
Function: this PHP file updates the inventory of the given productID


<?php

function Update_Inventory($SQL_Connection, $Product_IDs, $Quantity_Purchased) {

    // Dynamically construct the SQL statement
    $SQL_Query = "UPDATE product SET quantity = CASE ";

    foreach($Product_IDs as $index => $product_id) {
        // Add WHEN-THEN pairs for each product_id and quantity
        $SQL_Query .= "WHEN ? THEN ? ";
    }

    $SQL_Query .= "END WHERE productid IN (" . implode(",", array_fill(0, count($Product_IDs), '?')) . ")";
    // echo $SQL_Query;
    // to make sure it was created correctly uncomment the echo it should look something like
    // 
    // UPDATE product SET quantity = 
    //      CASE
    //          WHEN $Product_IDs[0] THEN $Quantities[0]
    //          WHEN $Product_IDs[1] THEN $Quantities[1]
    //          ...
    //          WHEN $Product_IDs[n] THEN $Quantities[n]
    //      END
    //      WHERE productid IN ($Product_IDs[0], $Product_IDs[1], ..., $Product_IDs[n])

    $Statement = $SQL_Connection->prepare($SQL_Query);

    // Bind parameters
    $bindTypes = str_repeat("is", count($Product_IDs)); // Assuming both Product_IDs and Quantity_Purchased are integers
    $bindParams = [$bindTypes];
    foreach($Product_IDs as $index => $product_id) {
        $bindParams[] = $product_id;
        $bindParams[] = $Quantity_Purchased[$index];
    }
    
    // Dynamically bind parameters
    $Statement->bind_param(...$bindParams);
    if (!$Statement->execute()) {
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Login function at line " . __LINE__ . ": " . $Statement->error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
        return false;
    }
    else{
        return true;
    }
}
?>