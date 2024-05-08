<?php
    function mysqli_result($res , $row , $field=0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    $output = array();
    $strArr = array();

    require_once "mc_app_connect.php";

    $sql = "SELECT email FROM mc_user_customer UNION SELECT email FROM mc_user_artisan";
    $result = mysqli_query($con , $sql);

    if(mysqli_num_rows($result)>0)
    {
        while($res = $result->fetch_assoc())
        {
            $email = $res['email'];
            if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT rating_user_email FROM mc_user_rating WHERE rating_user_email='$email')"),0)==='0')
            {
                $str_email = "INSERT INTO mc_user_rating(rating_user_email) VALUES('$email')";
                $query_email = mysqli_query($con , $str_email);
            }
        }
        $strArr['status'] = 1;
        $strArr['status_message'] = "successfully inserted into ratings table";
    }
    else
    {
        $strArr['status'] = 0;
        $strArr['status_message'] = "failed to update ratings";
    }

    $output[] = $strArr;
    echo json_encode($output);

?>
