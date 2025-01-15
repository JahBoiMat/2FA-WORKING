<?php
session_start();
include('dbcon.php');

// Check if user is logged in
if (isset($_SESSION['auth_user'])) {
    // Get the email of the authenticated user
    $user_email = $_SESSION['auth_user']['email'];

    // Update the verify_status to 0 in the database
    $update_status_query = "UPDATE users SET verify_status = 0 WHERE email = '$user_email'";
    $update_status_query_run = mysqli_query($con, $update_status_query);

    if (!$update_status_query_run) {
        // Log an error message if the query fails (optional for debugging)
        error_log("Failed to update verify_status for user: $user_email");
    }
}

// Destroy session and redirect to login page
session_destroy();
unset($_SESSION['authenticated']);
unset($_SESSION['auth_user']);
$_SESSION['status'] = "You have been logged out successfully!";
header("Location: login.php");
exit(0);
?>
