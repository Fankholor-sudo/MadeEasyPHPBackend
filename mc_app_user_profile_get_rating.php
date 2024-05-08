<?php
    require_once "mc_app_connect.php";

    $strArr = array();
    $output = array();

    $email = $_REQUEST['email'];
    $role = $_REQUEST['role'];

    $sql = "SELECT total_rating, low_rating, high_rating FROM mc_user_rating WHERE rating_user_email='$email' ";
    $query = mysqli_query($con , $sql);

    if(mysqli_num_rows($query)>0)
    {
        $res = $query->fetch_assoc();
        
        $strArr['rating'] = $res['total_rating'];
        $strArr['high_rating'] = $res['high_rating'];
        $strArr['low_rating'] = $res['low_rating'];
    
        if($role==="customer")
            $user_sql = "SELECT * FROM mc_user_customer WHERE email='$email'";
        else if($role==="artisan")
            $user_sql = "SELECT * FROM mc_user_artisan WHERE email='$email'";

        $user_query = mysqli_query($con, $user_sql);

        if(mysqli_num_rows($user_query)>0)
        {
            $user_res = $user_query->fetch_assoc();

            $strArr['username'] =$user_res['username'];
            $strArr['email'] = $user_res['email'];
            $strArr['role'] = $role;
            $strArr['city'] = $user_res['city'];
            $strArr['mobile'] = $user_res['phone_number'];
            $strArr['profile_pic'] = $user_res['profile_pic'];
            if($role === "artisan")$strArr['skills'] = $user_res['skills'];
        }
        else $strArr['user_erro']="cannot get the user information";

        $strArr['status'] = 1;
        $strArr['message'] = "query was successful!.";

        $output[] = $strArr;
        echo json_encode($output);
    }
    else
    {
        $strArr['status'] = 0;
        $strArr['message'] = "Error in a query, it was not successful.";

        $output[] = $strArr;
        echo json_encode($output);
    }

?>
