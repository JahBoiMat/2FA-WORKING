<?php
session_start();
include('dbcon.php');

if (isset($_POST['verify_2fa_btn'])) {
    $twofa_code = mysqli_real_escape_string($con, $_POST['twofa_code']);
    $email = $_SESSION['auth_temp_email']; // Temporary email stored during login process

    $check_2fa_query = "SELECT * FROM users WHERE email='$email' AND twofa_code='$twofa_code' LIMIT 1";
    $check_2fa_query_run = mysqli_query($con, $check_2fa_query);

    if (mysqli_num_rows($check_2fa_query_run) > 0) {
        // Clear the 2FA code from database
        $clear_2fa_query = "UPDATE users SET twofa_code=NULL WHERE email='$email'";
        mysqli_query($con, $clear_2fa_query);

        // Set session for successful login
        $row = mysqli_fetch_array($check_2fa_query_run);
        $_SESSION['authenticated'] = TRUE;
        $_SESSION['auth_user'] = [
            'username' => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
        ];
        $_SESSION['status'] = "Login Successful!";
        unset($_SESSION['auth_temp_email']);
        header("Location: dashboard.php");
        exit(0);
    } else {
        $_SESSION['status'] = "Invalid 2FA code. Please try again.";
        header("Location: 2fa.php");
        exit(0);
    }
}
?>
