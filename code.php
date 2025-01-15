<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Email verification function
function sendemail_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'navnesenn29@gmail.com';
        $mail->Password   = 'filw njqd kjze qxkr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('navnesenn29@gmail.com', $name);
        $mail->addAddress($email, $name);
        $mail->addReplyTo('navnesenn29@gmail.com', 'Contact');

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $email_template = "
        <h2>You have registered with Merk Inc</h2>
        <h5>An account was created using this address. If this was you, please verify your email by clicking the button below:</h5>
        <h5>If this was not you, please contact support at navnesenn29@gmail.com</h5>
        <br></br>
        <a href='http://localhost/2FA%20Login/verify-email.php?token=$verify_token'> Verify</a>
        ";
        $mail->Body = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// 2FA email function
function send_2fa_code($name, $email, $twofa_code)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'navnesenn29@gmail.com';
        $mail->Password   = 'filw njqd kjze qxkr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('navnesenn29@gmail.com', 'Merk Inc');
        $mail->addAddress($email, $name);
        $mail->addReplyTo('navnesenn29@gmail.com', 'Contact');

        $mail->isHTML(true);
        $mail->Subject = 'Your Two-Factor Authentication Code';
        $email_template = "
        <h2>Hi $name,</h2>
        <h4>Your Two-Factor Authentication code is:</h4>
        <h1>$twofa_code</h1>
        <h5>This code is one time use only, you cannot reuse this code.</h5>
        ";
        $mail->Body = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Existing account registration logic
if (isset($_POST['register-btn'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Email existence check
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email ID Already Exists";
        header("Location: register.php");
        exit();
    } else {
        // Insert user data into database
        $query = "INSERT INTO users (name, phone, email, password, verify_token) VALUES ('$name', '$phone', '$email', '$hashed_password', '$verify_token')";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            sendemail_verify("$name", "$email", "$verify_token");
            $_SESSION['status'] = "Registration Successful! Please check your inbox to verify your Email.";
            header("Location: register.php");
            exit();
        } else {
            $_SESSION['status'] = "An unexpected error occurred, Registration failed.";
            header("Location: register.php");
            exit();
        }
    }
}
?>
