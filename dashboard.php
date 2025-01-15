<?php 
$page_title = "Dashboard";
include_once('authentication.php');
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="card">
                    <div class="card-header">
                        <h4>User Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h4>You must be logged in to access this page</h4>
                        <hr>
                        <h5>Welcome, <?= $_SESSION['auth_user']['username'];?></h5>
                        <h5>Email: <?=  $_SESSION['auth_user']['email'];?></h5>
                        <h5>Phone: <?= $_SESSION['auth_user']['phone'];?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>