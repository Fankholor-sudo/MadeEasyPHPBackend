<?php
    function mysqli_result($res , $row , $field=0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    require_once "mc_app_connect.php";
    session_start();

    $output = array();
    $strArr = array();
    if($con)
    {
      $byte = base64_encode(openssl_random_pseudo_bytes(16));
      $salt = substr(strtr(1212121212 , '+','.'),0,44);

      $email = $_REQUEST['email'];
      $user_password = $_REQUEST['password'];
      $user_password = md5($user_password . $salt);

      $check_user;
      if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$email')"),0)==='1'){
      	$sql = "SELECT email,username,city,phone_number,profile_pic,password FROM mc_user_customer WHERE email=?";
	      $check_user = 'customer';
      }
      else if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$email')"),0)==='1'){
      	$sql = "SELECT email,username,city,skills,phone_number,profile_pic,password FROM mc_user_artisan WHERE email=?";
        $check_user = 'artisan';
      }
      else{
        $strArr['login_status'] = 2;
        $strArr['login_message'] = "Incorrect email, make sure you have registered before you can login.";

        mysqli_close($con);
        $output[] = $strArr;
        echo json_encode($output);
        die("Incorrect email, make sure you have registered before you can login.");
      }

      $stmt = $con->prepare($sql);
      $stmt->bind_param("s", $email);
      $executed = $stmt->execute();

      if($check_user === 'customer')
      	$stmt->bind_result($email, $name, $city, $mobile, $profile_pic, $pass_key);
      else if($check_user === 'artisan')
	      $stmt->bind_result($email, $name, $city, $skills, $mobile, $profile_pic, $pass_key);

      $res = $stmt->fetch();

      if($res === TRUE)
      {
        if($user_password === $pass_key)
        {
          session_regenerate_id();
          $_SESSION['username'] = $name;
          $_SESSION['email'] = $email;
          session_write_close();

          $strArr['username'] = $name;
          $strArr['email'] = $email;
          $strArr['role'] = $check_user;

          $strArr['login_status'] = 1;
          $strArr['login_message'] = "Login successful. Enjoy!";

          mysqli_close($con);
          $output[] = $strArr;
          echo json_encode($output);
       }
       else
       {
          $strArr['login_status'] = 0;
          $strArr['login_message'] = "Incorrect Password, you can click the link below for a new password!.";

          mysqli_close($con);
          $output[] = $strArr;
          echo json_encode($output);
       }
     }
   }
   else
   {
      $strArr['login_status'] = 3;
      $strArr['login_message'] = "Sever Connection failed!.";

      $output[] = $strArr;
      echo json_encode($output);
   }

?>
