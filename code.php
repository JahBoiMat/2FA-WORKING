<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token)
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'navnesenn29@gmail.com';                     //SMTP username
    $mail->Password   = 'filw njqd kjze qxkr';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('navnesenn29@gmail.com',$name);
    $mail->addAddress($email,$name);     //Add a recipient               //Name is optional
    $mail->addReplyTo('navnesenn29@gmail.com', 'Contact');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = '2FA Verification Code';
    $email_template = "
    <h2>You have registered with Merk Inc</h2>
    <h5>Please verify your email address to Login with the below link</h5>
    <br></br>
    <a href='http://localhost/2FA%20Login/verify-email.php?token=$verify_token'> Click Me</a>
    ";
    $mail->Body = $email_template;

    $mail->send();
    echo 'Message has been sent';
}


if(isset($_POST['register-btn']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //Email existr check thing
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Email ID Aldready Exists";
        header("Location: register.php");
    }
    else
    {
        //Insert User / Register Userdata
        $query = "INSERT INTO users (name,phone,email,password,verify_token) VALUES ('$name','$phone','$email','$hashed_password','$verify_token')";
        $query_run = mysqli_query($con, $query);

        if($query_run)
        {
            sendemail_verify("$name","$email","$verify_token");
            $_SESSION['status'] = "Registration Successfull! Please check your inbox to verify your Email.";
            header("Location: register.php");
        }
        else
        {
            $_SESSION['status'] = "An unexpected error occurred, Registration failed.";
            header("Location: register.php");
        }
    }
}
?>