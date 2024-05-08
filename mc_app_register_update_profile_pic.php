<?php
    function mysqli_result($res , $row , $field=0)
    {
        $res->data_seek($row);
        $datarow = $res->fetch_array();
        return $datarow[$field];
    }

    $result = array();
    $time = time();

    $target_dir = "uploads/";
    $target_file = $target_dir.$time . "-".basename($_FILES['image']['name']);
    $uploadOK = 1;
    $imageFileType = strtolower(pathinfo($target_file , PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['image']['tmp_name']);

    $extend = trim($time."-");

    if($check != false)
    {
       $uploadOK = 1;
    }
    else
    {
       $uploadOK = 0;
       $error = "Uploaded file is not a valid image";
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && 
    $imageFileType != "gif")
    {
        $uploadOK = 0;
        $error = "image type is not supported";
    }
    else
    {
        $uploadOK = 1;
    }

    if($uploadOK == 0)
    {
        $result['status'] = 0;
        $result['message'] = $error;
    }
    else
    {
        if(move_uploaded_file($_FILES['image']['tmp_name'] , $target_file))
        {
            require_once "mc_app_connect.php";

            $image = $extend . $_FILES['image']['name'];
            $email = $_REQUEST['email'];

            if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_customer WHERE email='$email')"),0)==='1')
            {
                $sql = "UPDATE mc_user_customer SET profile_pic='$image' WHERE email='$email'";
            }
            else if(mysqli_result(mysqli_query($con , "SELECT EXISTS(SELECT email FROM mc_user_artisan WHERE email='$email')"),0)==='1')
            {
                $sql = "UPDATE mc_user_artisan SET profile_pic='$image' WHERE email='$email'";
            }
            else
            {
                $result['upload_status'] = 0;
                $sql = "";
                mysqli_close($con);
                $result['status_messaage'] = "email does not exist in neither customer nor artisan";
            }

            $query = mysqli_query($con , $sql);
            if($query)
            {
                $result['upload_status'] = 1;
                $result['status_message'] = "profile image update was successfull";
            }
            else
            {
                $result['upload_status'] = 2;
                $result['status_message'] = "sorry your update was not successfull";
            }

            $result['status'] = 1;
            $result['message'] = "image uploaded to the server successfully";
        }
        else
        {
            $result['status'] = 0;
            $result['message'] = "unable to upload image to the server";
        }
    }

    echo json_encode($result);
?>
