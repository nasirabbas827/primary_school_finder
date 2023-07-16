<?php
session_start();
$_SESSION['adminlogin']=false;
$conn=mysqli_connect('localhost','root','','primary_db');
if($conn){
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $uemail=$_POST['uemail'];
        $upassword=$_POST['upassword'];
        $admin_login_query="SELECT * FROM `admin` WHERE email = '$uemail' AND password = '$upassword'";
        $admin_run_login=mysqli_query($conn,$admin_login_query);
        $row_run=mysqli_num_rows($admin_run_login);
        if($row_run==1){
            $_SESSION['uemail']=$uemail;
            $_SESSION['adminlogin']=true;
            header('location:admin_home.php');
        }else {
            echo '<div class="alert alert-danger alert-dismissible" role="alert" id="liveAlert">Wrong
  <strong> Username or Password!</strong> Try Again!!<button type = "button" class="btn-close" data-bs-dismiss = "alert" aria-label = "Close" ></button >
</div > ';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Login</title>
    <!--All The Attachments -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
        }
        .navbar-brand span {
            font-weight: normal;
        }
        .form-text {
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .data-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0,0,0,0.2);
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: bold;
            font-size: 20px;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="../index.php">School Finding <span>System</span></a>
    </div>
</nav>

<section class="data mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 align-self-center">
                <form method="post" class="data-form" autocomplete="off">
                    <h1 class="text-center">Admin Login</h1>
                    <label class="form-text" for="uemail">Username</label>
                    <input class="form-control" type="text" name="uemail" id="uemail" required placeholder="Username">
                    <label class="form-text" for="upassword">Password</label>
                    <input class="form-control" type="password" name="upassword" id="upassword" required placeholder="Password">
                    <div class="text-center mt-4">
                        <button class="btn btn-success" id="submit_btn">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<!--Bootstrap JS File-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>



