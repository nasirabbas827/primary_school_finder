<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

// Function to fetch all schools from the database
function getSchools($conn) {
    $query = "SELECT * FROM schools";
    $result = mysqli_query($conn, $query);

    $schools = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $schools[] = $row;
        }
    } else {
        echo "Error fetching schools: " . mysqli_error($conn);
    }

    return $schools;
}

// Fetch all schools
$schools = getSchools($conn);

// Handle form submission for adding a faculty member
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $school_id = $_POST['school_id'];
    $name = $_POST['name'];
    $name = trim($name); // Remove leading/trailing whitespaces
    $name = htmlspecialchars($name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    
    // Upload faculty picture
    $picture = $_FILES['picture']['name'];
    $picture_tmp = $_FILES['picture']['tmp_name'];
    $picture_ext = pathinfo($picture, PATHINFO_EXTENSION);
    $picture_filename = uniqid('faculty_') . '.' . $picture_ext;
    $picture_destination = 'faculty_images/' . $picture_filename;
    move_uploaded_file($picture_tmp, $picture_destination);
    
    $contact_info = $_POST['contact_info'];
    
    // Insert the faculty member into the database
    $query = "INSERT INTO faculty (school_id, name, qualification, experience, picture, contact_info) 
              VALUES ('$school_id', '$name', '$qualification', '$experience', '$picture_filename', '$contact_info')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Faculty member added successfully.";
    } else {
        echo "Error adding faculty member: " . mysqli_error($conn);
    }
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<style>
            body {
        background-color: #f8f9fa;
    }
</style>
</head>

<body>
    <div class="container">
        <!-- HTML form for adding a faculty member -->
        <form method="POST" action="" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="school_id" class="form-label">Select School:</label>
                <select name="school_id" id="school_id" class="form-select" required>
                    <?php foreach ($schools as $school) { ?>
                        <option value="<?php echo $school['school_id']; ?>"><?php echo $school['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="qualification" class="form-label">Qualification:</label>
                <input type="text" name="qualification" id="qualification" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="experience" class="form-label">Experience:</label>
                <input type="text" name="experience" id="experience" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Picture:</label>
                <input type="file" name="picture" id="picture" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact_info" class="form-label">Contact Info:</label>
                <input type="text" name="contact_info" id="contact_info" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Faculty</button>
            <p class="mr-3 mt-3 btn btn-success" >Click <a class="text-white" href="update_faculty.php">Here To Manage Faculty</a></p>

        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
