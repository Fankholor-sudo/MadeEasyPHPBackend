<?php

	require_once "mc_app_connect.php";

	$result = array();
        $output = array();

        $email = $_REQUEST['email'];
        $username = $_REQUEST['username'];
        $city = $_REQUEST['city'];
        $skills = $_REQUEST['skills'];

        $sql = "UPDATE mc_user_artisan SET username = '$username' , city = '$city' , skills = '$skills'  WHERE email='$email' ";
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
