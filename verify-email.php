<?php
session_start();

include('dbcon.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $con->prepare("SELECT verify_token, verify_status FROM users WHERE verify_token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $verify_query_run = $stmt->get_result();

    // Check if the query returned rows
    if (mysqli_num_rows($verify_query_run) > 0) {
        $row = mysqli_fetch_array($verify_query_run);
        echo $row['verify_token'];

        if ($row['verify_status'] == "0") {
            $clicked_token = $row['verify_token'];
            $stmt = $con->prepare("UPDATE users SET verify_status = '1' WHERE verify_token = ?");
            $stmt->bind_param("s", $clicked_token);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION['status'] = "Your account has been verified successfully!";
                header('Location: login.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Verification Failed";
                header('Location: login.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Email Already Verified. Please Login";
            header('Location: login.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = "This Token does not Exist";
        header('Location: login.php');
        exit();
    }
} else {
    $_SESSION['status'] = "Not Allowed";
    header('Location: login.php');
    exit();
}
?>
