<?php

    function mysqli_result($res , $row , $field=0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    require_once "mc_app_connect.php";

    $email = $_REQUEST['email'];
    $name = $_REQUEST['username'];
    $city = $_REQUEST['city'];
    $mobile = $_REQUEST['mobile'];
    $role = $_REQUEST['role'];

    $strArr = array();
    $output = array();

    if($role === 'artisan')
	$check_sql = "SELECT email FROM mc_user_artisan";
    else if($role === 'customer')
	$check_sql = "SELECT email FROM mc_user_customer";
    $check_query = mysqli_query($con , $check_sql);

    if(mysqli_num_rows($check_query) > 0){
        if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$email')"),0)==='1')
        {
            $sql = "UPDATE mc_user_customer SET username='$name', city='$city' , phone_number='$mobile' WHERE email='$email' ";
        }
        else if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$email')"),0)==='1')
        {
            $sql = "UPDATE mc_user_artisan SET username='$name', city='$city' , phone_number='$mobile' WHERE email='$email' ";
        }
        else
        {
            $strArr['login_status'] = "2";
            $strArr['login_message'] = "Incorrect email address";

            mysqli_close($con);
            $output[] = $strArr;

            echo json_encode($output);
            die("Incorrect email");
        }

        $query = mysqli_query($con , $sql);

        if($query)
        {
            $strArr['status'] = 1;
            $strArr['message'] = "query was successful.";
        }
        else
        {
            $strArr['status'] = 0;
            $strArr['message'] = "Error in a query, it was not successful.";
        }
    }
    else
    {
        $strArr['status'] = 0;
        $strArr['message'] = "email does not exist";
    }

    $output[] = $strArr;
    echo json_encode($output);
?>



