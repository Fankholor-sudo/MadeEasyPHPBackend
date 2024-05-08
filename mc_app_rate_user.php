<?php

    require_once "mc_app_connect.php";

    $output = array();
    $strArr = array();

    $f_email = $_REQUEST['f_email'];
    $t_email = $_REQUEST['t_email'];
    $comment = $_REQUEST['comment'];
    $rating  = $_REQUEST['rating'];

    $sql = "INSERT INTO mc_user_comments (com_from_user_email , com_to_user_email , com_rating , com_message, com_date)
            VALUES('$f_email' , '$t_email' , $rating , '$comment', CURRENT_TIMESTAMP())";

    $query = mysqli_query($con , $sql);

    if($query)
    {
         $result['status'] = '1';
         $result['update_user'] = "Insert successfull";
	    $result['to_email'] = $t_email;
    }
    else
    {
         $result['status'] = '0';
         $result['update_user'] = "Insert was not successfull";
    }

    $output[] = $result;
    echo json_encode($output);
?>

