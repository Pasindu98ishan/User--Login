<?php 
require_once "config.php";
$username=$_POST["username"];
$password=$confirm_password="";
$username_err=$password_err=$confirm_password_err="";

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if (empty(trim($username))){
        $username_err="Please enter a username";
    }elseif(!preg_match('/^[a-zA-Z0-9_]+$/',trim($username))){
        $username_err="Username can only contain letters,numbers,and underscores";

    }else{
        $sql="SELECT id from users WHERE username=?";

        // $stmt = mysqli_prepare($link,$sql);
        // mysqli_stmt_bind_param($stmt,'s',$username);

        // $result = mysqli_stmt_execute($stmt);
        // mysqli_stmt_store_result($stmt);

        // $rowCount = mysqli_stmt_num_rows($stmt);

        // if($rowCount == 1){
        //     echo "User already exist";
        // }else{
        //     echo "User dkfjsaf";
        // }

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                    echo "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

    }
    if (empty(trim($_POST["password"]))){
        $password_err="Please enter a password";
        echo $password_err;
    }elseif(strlen(trim($_POST["password"]))<3){
        $password_err="Password must have atleast 3 chrarcters";
        echo $password_err;
    }else{
        $password=trim($_POST["password"]);
    }
    if (empty(trim($_POST["comfirm_password"]))){
        $confirm_password_err="Please enter a correct password";
        echo $confirm_password_err;
    }else{
        $confirm_password=trim($_POST["comfirm_password"]);
        if(empty($password_err) &&($password!=$confirm_password)){
            $confirm_password_err="pass worddid not match";
            echo $confirm_password_err;
        }
    }
    if (empty($username_err) && empty($password_err)&& empty($confirm_password_err)){
        $sql="INSERT INTO users(username,password) VALUES(?,?)";
        if($stmt=mysqli_prepare($link,$sql)){
            mysqli_stmt_bind_param($stmt,"ss",$param_username,$param_password);
            $param_username=$username;
            $param_password=password_hash($password,PASSWORD_DEFAULT);
            if(mysqli_stmt_execute($stmt)){
                echo"sign up succelfully";
                header("location: Frontpage.php");
            }else{
                echo"Oops! Something went wrong.please try again later";

            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    }


}

/* pasword send method
encryption use key value
hash---md5/sha1,sha/
encode
*/



?>