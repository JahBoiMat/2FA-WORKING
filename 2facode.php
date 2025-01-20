<?php
session_start();
include('dbcon.php');

if (isset($_POST['verify_2fa_btn'])) {
    $twofa_code = $_POST['twofa_code'];
    $email = $_SESSION['auth_temp_email']; // Temporary email stored during login process

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND twofa_code = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $twofa_code);
    $stmt->execute();
    $check_2fa_query_run = $stmt->get_result();

    if (mysqli_num_rows($check_2fa_query_run) > 0) {
        // Clear the 2FA code from database
        $stmt = $con->prepare("UPDATE users SET twofa_code = NULL WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

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
