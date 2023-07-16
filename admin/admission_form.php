<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

// Function to fetch all registration forms from the database
function getRegistrationForms($conn) {
    $query = "SELECT rf.*, s.name AS school_name, c.name AS city_name, u.username 
              FROM registration_form rf
              INNER JOIN users u ON rf.user_id = u.id
              INNER JOIN schools s ON rf.school_id = s.school_id
              INNER JOIN cities c ON rf.city_id = c.id";
    $result = mysqli_query($conn, $query);

    $registrationForms = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $registrationForms[] = $row;
        }
    } else {
        echo "Error fetching registration forms: " . mysqli_error($conn);
    }

    return $registrationForms;
}

// Fetch all registration forms
$registrationForms = getRegistrationForms($conn);

// Handle form submission for updating the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $formId = $_POST['form_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $updateQuery = "UPDATE registration_form SET register_status = '$status' WHERE form_id = '$formId'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<style>
            body {
        background-color: #f8f9fa;
    }
</style>
</head>

<body>

    <?php include('adminnavbar.php') ?>

    <div class="container">
        <h2 class="text-center mt-4 mb-3" >Registration Forms</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Form ID</th>
                        <th>Username</th>
                        <th>School Name</th>
                        <th>City Name</th>
                        <th>Student Picture</th>
                        <th>Student Name</th>
                        <th>Student CNIC</th>
                        <th>Student Father's Name</th>
                        <th>Student Father's CNIC</th>
                        <th>Student Mother's Name</th>
                        <th>Student Address</th>
                        <th>Student Contact Number</th>
                        <th>Student Domicile</th>
                        <th>Register Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrationForms as $form) { ?>
                        <tr>
                            <td><?php echo $form['form_id']; ?></td>
                            <td><?php echo $form['username']; ?></td>
                            <td><?php echo $form['school_name']; ?></td>
                            <td><?php echo $form['city_name']; ?></td>
                            <td>
                                <img src="../uploads/<?php echo $form['student_picture']; ?>" alt="Student Picture" width="100">
                            </td>
                            <td><?php echo $form['student_name']; ?></td>
                            <td><?php echo $form['student_cnic']; ?></td>
                            <td><?php echo $form['student_father_name']; ?></td>
                            <td><?php echo $form['student_father_cnic']; ?></td>
                            <td><?php echo $form['student_mother_name']; ?></td>
                            <td><?php echo $form['student_address']; ?></td>
                            <td><?php echo $form['student_contact_number']; ?></td>
                            <td>
                                <a href="admission_form.php?download_domicile=true&form_id=<?php echo $form['form_id']; ?>">Download Domicile</a>
                            </td>
                            <td><?php echo $form['register_status']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="form_id" value="<?php echo $form['form_id']; ?>">
                                    <select name="status" class="form-control">
                                        <option value="Pending" <?php if ($form['register_status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Approved" <?php if ($form['register_status'] === 'Approved') echo 'selected'; ?>>Approved</option>
                                        <option value="Rejected" <?php if ($form['register_status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
