<?php
    require_once "mc_app_connect.php";

    $result = array();
    $output = array();

    $sql = "SELECT * FROM mc_user_artisan,mc_user_rating WHERE email=rating_user_email";
    $query = mysqli_query($con , $sql);

    if($query)
    {
      	while($res = $query->fetch_assoc())
      	{
            $result['username']     = $res['username'];
            $result['email']        = $res['email'];
            $result['phone_number'] = $res['phone_number'];
            $result['profile_pic']  = $res['profile_pic'];
            $result['skills']       = $res['skills'];
            $result['city']         = $res['city'];
            $result['total_rating'] = $res['total_rating'];
            $result['high_rating']  = $res['high_rating'];
            $result['low_rating']  = $res['low_rating'];

            $output[] = $result;
      	}
    }
    else
    {
        $result['status'] = '0';
        $result['update_user'] = "update was not successfull";
	    $output[] = $result;
    }


    echo json_encode($output);
?>
