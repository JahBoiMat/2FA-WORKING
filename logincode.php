<?php
session_start();
include('dbcon.php');
include('code.php'); // Ensure the `send_2fa_code()` function is accessible here

if (isset($_POST['login_now_btn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $con->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $login_query_run = $stmt->get_result();

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);

            // Validate the password
            if (password_verify($password, $row['password'])) {
                if ($row['verify_status'] == "1") {
                    // Generate 6-digit 2FA code
                    $twofa_code = rand(100000, 999999);
                    $stmt = $con->prepare("UPDATE users SET twofa_code = ? WHERE email = ?");
                    $stmt->bind_param("ss", $twofa_code, $email);
                    $stmt->execute();

                    // Send 2FA code email
                    send_2fa_code($row['name'], $row['email'], $twofa_code);

                    // Store temporary session data
                    $_SESSION['auth_temp_email'] = $row['email'];
                    header("Location: 2fa.php"); // Redirect to 2FA input page
                    exit(0);
                } else {
                    $_SESSION['status'] = "Email not verified. Please verify your email before logging in.";
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                $_SESSION['status'] = "Invalid Email or Password.";
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "No account found with this Email.";
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "All fields must be filled in.";
        header("Location: login.php");
        exit(0);
    }
}
?>
