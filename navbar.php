<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Online School Finder</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <?php
        // check if user is logged in
        if (isset($_SESSION["id"]) && isset($_SESSION["email"])) {
            ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="admin_form_status.php">Form Status</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="fee_voucher.php">Fees</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="upload_voucher.php">Upload Fees</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="update_profile.php"><?php echo $_SESSION["email"]; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        <?php
        } else {
            // user is not logged in, show register and login links in navbar
            echo '<li class="nav-item"><a class="nav-link" href="admin/adminlogin.php">Admin Login</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
            echo '<li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>
