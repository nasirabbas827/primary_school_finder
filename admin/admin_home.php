<?php
// Include the configuration file
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}


// Function to get the total count from a table
function getTotalCount($conn, $table) {
    $query = "SELECT COUNT(*) AS total_count FROM $table";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total_count'];
    } else {
        return 0;
    }
}

// Fetch the counts from the respective tables
$totalUsers = getTotalCount($conn, 'users');
$totalSchools = getTotalCount($conn, 'schools');
$totalCities = getTotalCount($conn, 'cities');
$totalPendingRegistrations = getTotalCount($conn, 'registration_form WHERE register_status = "Pending"');
$totalFaculty = getTotalCount($conn, 'faculty');
$totalFeeVouchers = getTotalCount($conn, 'registration_form WHERE fee_voucher IS NOT NULL');
$totalCourses = getTotalCount($conn, 'course');

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional custom styling */
        body {
            background-color: #f8f9fa;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include('adminnavbar.php'); ?>

    <div class="container">
        <h2 class="text-center mt-3">Admin Dashboard</h2>

        <div class="card-container">
            <div class="card">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text"><?php echo $totalUsers; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Schools</h5>
                <p class="card-text"><?php echo $totalSchools; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Cities</h5>
                <p class="card-text"><?php echo $totalCities; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Pending Registrations</h5>
                <p class="card-text"><?php echo $totalPendingRegistrations; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Faculty</h5>
                <p class="card-text"><?php echo $totalFaculty; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Fee Vouchers</h5>
                <p class="card-text"><?php echo $totalFeeVouchers; ?></p>
            </div>
            <div class="card">
                <h5 class="card-title">Total Courses</h5>
                <p class="card-text"><?php echo $totalCourses; ?></p>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (at the end of the body for better performance) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
