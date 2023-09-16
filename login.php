<?php 

session_start();
if (isset($_SESSION["loggedin"])&& $_SESSION["loggedin"]===true) {

    header("Location: welcom.php");
    exit;
}
require_once "config.php";
$username =$password="";
$username_err=$password_err=$login_err="";


if($_SERVER["REQUEST_METHOD"]=="POST"){
   


    if(empty(trim($_POST["username"]))){
        $username_err="please enter user name";

    }else{
        $username=trim($_POST["username"]);

    }
    if(empty(trim($_POST["password"]))){
        $password_err="please enter your password";

    }else{
        $password=trim($_POST["password"]);

    }
    if (empty($username_err) && empty($password_err)) {
        $sql="SELECT id,username,password FROM users Where username=?";
        if ($stmt=mysqli_prepare($link,$sql)) {
           
            $param_username=$username;
            mysqli_stmt_bind_param($stmt,"s",$param_username);
            
           

            if(mysqli_stmt_execute($stmt)){
                //var_dump($param_username);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt)==1) {
                    mysqli_stmt_bind_result($stmt,$id,$username,$hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                       
                        if(password_verify($password,$hashed_password)){
                            //echo 'XXXXXXXXXXXXXXXXXXX'.__LINE__.PHP_EOL;
                            $_SESSION["loggedin"]=true;
                            $_SESSION["id"]=$id;
                            $_SESSION["username"]=$username;
                            //echo "hello";
                            header("Location: welcom.php");
                            exit();

                        }else {
                            $login_err="Invalid username or password";
                            echo$login_err;
                            echo "a";
                        }

                    }
                    
                    
                } 
                $login_err="Invalid username or password";
                echo$login_err;
               
            }else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>