<?php
session_start();
include('dbcon.php');

if(isset($_POST['register-btn']))
{
    $name = $_POST['name'];
    $name = $_POST['phone'];
    $name = $_POST['email'];
    $name = $_POST['password'];

    //Email existr check thing
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Eemail ID Aldready Exists";
        header("Location: register.php");
    }
    else
    {
        //Insert User / Register Userdata
    }
}
?>