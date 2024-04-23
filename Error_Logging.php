Author: Xander R
Function: this PHP file defines a function to log errors

<?php

function Log_Error($type, $Error, $line, $file) {

    if ($type == 'query'){
        // Handle query execution error
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error executing query in $file function at line " . $line . ": " . $Error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
    }
    else {
        $Error_Message = "[" . date("Y-m-d H:i:s") . "] Error in $file function at line " . $line . ": " . $Error;
        $Separator = "-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-";
        $Log_Entry = $Error_Message . PHP_EOL . PHP_EOL . $Separator . PHP_EOL;
        error_log($Log_Entry, 3, "error.log");
    }
}

?>