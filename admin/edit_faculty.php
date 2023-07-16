<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $name = trim($name); // Remove leading/trailing whitespaces
    $name = htmlspecialchars($name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $contact_info = $_POST['contact_info'];
    
    // Update the faculty member in the database
    $query = "UPDATE faculty SET name = '$name', qualification = '$qualification', experience = '$experience', contact_info = '$contact_info' WHERE faculty_id = '$faculty_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Faculty member updated successfully.";
    } else {
        echo "Error updating faculty member: " . mysqli_error($conn);
    }
}

// Retrieve the faculty ID from the query string parameter
if (isset($_GET['faculty_id'])) {
    $faculty_id = $_GET['faculty_id'];
    
    // Fetch the faculty member details from the database
    $query = "SELECT * FROM faculty WHERE faculty_id = '$faculty_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $faculty_member = mysqli_fetch_assoc($result);
    } else {
        echo "Faculty member not found.";
        exit();
    }
} else {
    echo "Faculty ID not specified.";
    exit();
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Faculty Member</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        label {
            margin: 10px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4">Edit Faculty Member</h2>
        <form method="POST" action="">
            <input type="hidden" name="faculty_id" value="<?php echo $faculty_member['faculty_id']; ?>">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo $faculty_member['name']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="qualification">Qualification:</label>
                <input type="text" name="qualification" id="qualification" value="<?php echo $faculty_member['qualification']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="experience">Experience:</label>
                <input type="text" name="experience" id="experience" value="<?php echo $faculty_member['experience']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" name="contact_info" id="contact_info" value="<?php echo $faculty_member['contact_info']; ?>" class="form-control" required>
            </div>

            <button type="submit" class="mt-4 btn btn-primary">Update Faculty</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
