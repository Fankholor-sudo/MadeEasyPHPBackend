<?php
    function mysqli_result($res , $row , $field=0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    require_once "mc_app_connect.php";

    $strArr = array();
    $output = array();
    session_start();
    if($con)
    {
      $byte = base64_encode(openssl_random_pseudo_bytes(16));
      $salt = substr(strtr(1212121212 , '+','.'),0,44);

      $email = $_REQUEST['email'];
      $telephone = $_REQUEST['phone_number'];
      $user_password = $_REQUEST['password'];
      $role = $_REQUEST['role'];
      $user_password = md5($user_password . $salt);

      if($email !="" && $telephone !="" && $user_password !="")
      {
        if($role === "customer")
        {
          if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$email')"),0)==='0')
          {
            if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$email')"),0)==='0')
            {
                    $sql = "INSERT INTO mc_user_customer(email, phone_number, password)
                    VALUES('$email', '$telephone', '$user_password') ";
            }
            else{
              $strArr['register_status'] = "2";
              $strArr['status_message'] = "You are already registered as a Customer you can click Login link bellow to go to login page.";
              $output[] = $strArr;
              mysqli_close($con);
              echo json_encode($output);
              die("You have registered as a Customer.");
            }
          }
          else{
            $strArr['register_status'] = "2";
            $strArr['status_message'] = "You are already registered as an Artisan.You can not register as both artisan and customer.";
            $output[] = $strArr;

            mysqli_close($con);
            echo json_encode($output);
            die("You have registered as an Artisan.");
          }
        }

        else if($role === "artisan"){
          if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$email')"),0)==='0')
          {
            if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$email')"),0)==='0')
            {
                    $sql = "INSERT INTO mc_user_artisan(email, phone_number, password)
                    VALUES('$email', '$telephone', '$user_password') ";
            }
            else{
              $strArr['register_status'] = "2";
              $strArr['status_message'] = "You are already registered as an Artisan you can click Login link bellow to go to login page.";
              $output[] = $strArr;

              mysqli_close($con);
              echo json_encode($output);
              die("You have registered as an Artisan.");
            }
      	  }
          else{
            $strArr['register_status'] = "2";
            $strArr['status_message'] = "You are already registered as a Customer.You can not register as both artisan and customer.";
            $output[] = $strArr;

            mysqli_close($con);
            echo json_encode($output);
            die("You have registered as a Customer.");
          }
        }

        $query = mysqli_query($con , $sql);

        if($query)
        {
          $strArr['register_status'] = "1";
          $strArr['status_message'] = "registration is successful";
          $strArr['email'] = $email;
          $strArr['role'] = $role;
          $output[] = $strArr;

          mysqli_close($con);
          echo json_encode($output);
        }
      }
      else
      {
        $strArr['register_status'] = "0";
        $strArr['status_message'] = "make sure you filled all required fields...";
      	$output[] = $strArr;

        mysqli_close($con);
        echo json_encode($output);
	      die("make sure you filled all required fields...");
      }
    }
?>
