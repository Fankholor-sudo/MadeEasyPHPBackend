<?php
    require_once "mc_app_connect.php";

    $result = array();
    $output = array();

    $email = $_REQUEST['email'];
    $username = $_REQUEST['username'];
    $city = $_REQUEST['city'];

    $sql = "UPDATE mc_user_customer SET username = '$username' , city = '$city' WHERE email='$email' ";
    $query = mysqli_query($con , $sql);

    if($query)
    {
         $result['status'] = '1';
         $result['update_user'] = "update successfull";
    }
    else
    {
         $result['status'] = '0';
         $result['update_user'] = "update was not successfull";
    }

    $output[] = $result;
    echo json_encode($output);
?>
