Author: Xander R
Function: This PHP file will query a DB to output the product information that was searched for

<?php

function Search($connection, $search_criteria) {

    $sql = "SELECT * FROM product WHERE product_name LIKE '%?%'";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $search_criteria);
    if (!$stmt->execute()) {
        // Handle query execution error
        $error_message = "[" . date("Y-m-d H:i:s") . "] Error executing query in Search function at line " . __LINE__ . ": " . $stmt->error;
        $separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $log_entry = $error_message . PHP_EOL . PHP_EOL . $separator . PHP_EOL;
        error_log($log_entry, 3, "error.log");
        return false;
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // if nothing matches search criteria
        return "No Match";
    }
    elseif ($result->num_rows > 0) {
        // if there are results return them
        return $result;
    }
    else {
        $error_message = "[" . date("Y-m-d H:i:s") . "] Error during results return in Search function at line " . __LINE__ . ": " . $stmt->error;
        $separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $log_entry = $error_message . PHP_EOL . PHP_EOL . $separator . PHP_EOL;
        error_log($log_entry, 3, "error.log");
        return "Bad Result";
    }
}
?>