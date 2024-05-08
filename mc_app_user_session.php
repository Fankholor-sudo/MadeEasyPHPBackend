
<?php
    session_start();

    $strArr = array();
    $output = array();

    $strArr['session_email'] = $_SESSION['email'];
    $strArr['session_username'] = $_SESSION['username'];
    $strArr['session_role'] = $_SESSION['role'];

    $output[] = $strArr;

    echo json_encode($output);

?>