<?php 
session_start();
$page_title = "Two-Factor Authentication";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php 
                if (isset($_SESSION['status'])) {
                    ?>
                    <div class="alert alert-danger">
                        <h5><?= $_SESSION['status'] ?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']); 
                }
                ?>

                <div class="card shadow">
                    <div class="card-header">
                        <h5>Two Factor Authentication</h5>
                        <h4>Enter the 6 digit code that was sent to your email</h4>
                    </div>
                    <div class="card-body">

                        <form action="2facode.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Two-Factor Authentication Code</label>
                                <input type="text" name="twofa_code" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="verify_2fa_btn" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
