<?php
session_start();
include('dbcon.php');

if (isset($_POST['login_now_btn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = trim(mysqli_real_escape_string($con, $_POST['email']));
        $password = trim(mysqli_real_escape_string($con, $_POST['password']));

        // Query to check if user exists by email
        $login_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);

            // Verify password
            if (password_verify($password, $row['password'])) {
                // Check if email is verified
                if ($row['verify_status'] == "1") {
                    // Set session and redirect
                    $_SESSION['authenticated'] = TRUE;
                    $_SESSION['auth_user'] = [
                        'username' => $row['name'],
                        'phone' => $row['phone'],
                        'email' => $row['email'],
                    ];
                    $_SESSION['status'] = "You are now logged in!";
                    header("Location: dashboard.php");
                    exit(0);
                } else {
                    // Email not verified
                    $_SESSION['status'] = "Email not verified. Please check your inbox to verify your email.";
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                // Incorrect password
                $_SESSION['status'] = "Invalid Password";
                header("Location: login.php");
                exit(0);
            }
        } else {
            // User not found
            $_SESSION['status'] = "Invalid Email";
            header("Location: login.php");
            exit(0);
        }
    } else {
        // Empty fields
        $_SESSION['status'] = "All fields must be filled in.";
        header("Location: login.php");
        exit(0);
    }
}
?>
