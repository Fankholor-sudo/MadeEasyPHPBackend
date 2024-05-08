<?php
    function mysqli_result($res , $row , $field=0)
    {
      $res->data_seek($row);
      $datarow = $res->fetch_array();
      return $datarow[$field];
    }


    require_once "mc_app_connect.php";

    $t_email = $_REQUEST['t_email'];

    $output = array();
    $strArr = array();
    $strCom = array();

    $total_rating;
    $low_rating = 0;
    $high_rating = 0;

    $count = 0;
    $sum = 0;

    $sql = "SELECT com_rating FROM mc_user_comments WHERE com_to_user_email='$t_email'";
    $com_sql = "SELECT * FROM mc_user_comments WHERE com_to_user_email='$t_email'";
    $rat_sql = "SELECT * FROM mc_user_rating WHERE rating_user_email='$t_email'";

    $query = mysqli_query($con , $sql);
    $com_query = mysqli_query($con , $com_sql);
    $rat_query = mysqli_query($con , $rat_sql);

    if(mysqli_num_rows($query)>0)
    {
        while($res = $query->fetch_assoc())
        {
            $count += 1;
            $sum += $res['com_rating'];

            if($res['com_rating'] < 2.5) $low_rating += 1;
            else $high_rating += 1;
        }
        $total_rating = $sum/$count;

        
        if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT rating_user_email FROM mc_user_rating WHERE rating_user_email='$t_email')"),0)==='0')
        {
            $str_email = "INSERT INTO mc_user_rating(rating_user_email) VALUES('$t_email')";
            $query_email = mysqli_query($con , $str_email);
            $strArr['insert_status'] = '1';
            $strArr['insert_message'] = "success email insertion";
        }
        $up_date = "UPDATE mc_user_rating SET total_rating=$total_rating, low_rating=$low_rating, high_rating=$high_rating WHERE rating_user_email='$t_email'";
        $query_update = mysqli_query($con , $up_date);

        if($query_update)
        {
            $strArr['status'] = 1;
            $strArr['status_message'] = "rating update was success";

             if(mysqli_num_rows($rat_query) > 0)
             {
                  $rat_res = $rat_query->fetch_assoc();
                  $strArr['total_rating'] = $rat_res['total_rating'];
                  $strArr['low_rating']   = $rat_res['low_rating'];
                  $strArr['high_rating']  = $rat_res['high_rating'];
             }
             else $strArr['TOTAL_RATING_ERROR']="TOTAL RATING ERROR ";
        }
        else
        {
            $strArr['status'] = 0;
            $strArr['status_message'] = "failed to update ratings";
        }
    }
    else
    {
	 $strArr['status'] = 0;
         $strArr['status_message'] = "no rows for this email";
    }


    if(mysqli_num_rows($com_query) > 0)
    {
        while($com_res = $com_query->fetch_assoc())
	{
             $strCom['f_email'] = $com_res['com_from_user_email'];
             $strCom['t_email'] = $com_res['com_to_user_email'];
             $strCom['rating']  = $com_res['com_rating'];
             $strCom['comment'] = $com_res['com_message'];
             $strCom['date'] = $com_res['com_date'];

	     $f_email = $com_res['com_from_user_email'];

      	     if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$f_email')"),0)==='1'){
      	         $tcom_sql = "SELECT username,profile_pic FROM mc_user_customer WHERE email='$f_email'";
      	     }
             else if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$f_email')"),0)==='1'){
                 $tcom_sql = "SELECT username,profile_pic FROM mc_user_artisan WHERE email='$f_email'";
	     }

	     $tcom_query = mysqli_query($con , $tcom_sql);

	     if(mysqli_num_rows($tcom_query) > 0)
	     {
		  $t_res = $tcom_query->fetch_assoc();
		  $strCom['f_name'] = $t_res['username'];
		  $strCom['profile_pic'] = $t_res['profile_pic'];
	     }


	     $output[] = $strCom;
	}
        $strCom['comment_status'] = 1;
        $strCom['comment_message'] = "comments query was successfull :^)";

    }
    else
    {
        $strArr['comment_status'] = 0;
        $strArr['comment_message'] = "comments error in query!!   (-_-)";
    }

    $output[] = $strArr;
    echo json_encode($output);

?>
