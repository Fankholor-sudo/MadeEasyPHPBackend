<?php
    $strArr = array();
    $output = array();

    session_start();
    if(session_destroy()){
        $strArr['logout_status'] = "1";
        $strArr['logout_message'] = "you have logged out";

        $output[] = $strArr;
    } 

    echo json_encode($output);
?>
