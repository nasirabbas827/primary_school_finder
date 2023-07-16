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

// Function to fetch all faculty members from the database
function getFaculty($conn) {
    $query = "SELECT * FROM faculty";
    $result = mysqli_query($conn, $query);

    $faculty = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $faculty[] = $row;
        }
    } else {
        echo "Error fetching faculty members: " . mysqli_error($conn);
    }

    return $faculty;
}

// Fetch all schools
$schools = getSchools($conn);

// Fetch all faculty members
$faculty = getFaculty($conn);

// Handle form submission for adding a course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $school_id = $_POST['school_id'];
    $subject_name = $_POST['subject_name'];
    $subject_name = trim($subject_name); // Remove leading/trailing whitespaces
    $subject_name = htmlspecialchars($subject_name); // Convert special characters to HTML entities
    
    // Perform further validation if needed
    
    $faculty_id = $_POST['faculty_id'];
    $book_name = $_POST['book_name'];
    $author_name = $_POST['author_name'];
    $publisher = $_POST['publisher'];
    $course_fee = $_POST['course_fee'];
    $course_duration = $_POST['course_duration'];
    
    // Insert the course into the database
    $query = "INSERT INTO course (school_id, subject_name, faculty_id, book_name, author_name, publisher, course_fee, course_duration) 
              VALUES ('$school_id', '$subject_name', '$faculty_id', '$book_name', '$author_name', '$publisher', '$course_fee', '$course_duration')";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Course added successfully.";
    } else {
        echo "Error adding course: " . mysqli_error($conn);
    }
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<style>
            body {
        background-color: #f8f9fa;
    }
</style>
</head>

<body>
    <div class="container">
        <!-- HTML form for adding a course -->
        <form method="POST" action="" class="mt-4">
            <div class="mb-3">
                <label for="school_id" class="form-label">Select School:</label>
                <select name="school_id" id="school_id" class="form-select" required>
                    <?php foreach ($schools as $school) { ?>
                        <option value="<?php echo $school['school_id']; ?>"><?php echo $school['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="faculty_id" class="form-label">Select Faculty:</label>
                <select name="faculty_id" id="faculty_id" class="form-select" required>
                    <?php foreach ($faculty as $member) { ?>
                        <option value="<?php echo $member['faculty_id']; ?>"><?php echo $member['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="book_name" class="form-label">Book Name:</label>
                <input type="text" name="book_name" id="book_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="author_name" class="form-label">Author Name:</label>
                <input type="text" name="author_name" id="author_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Publisher:</label>
                <input type="text" name="publisher" id="publisher" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="course_fee" class="form-label">Course Fee:</label>
                <input type="number" name="course_fee" id="course_fee" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="course_duration" class="form-label">Course Duration:</label>
                <input type="text" name="course_duration" id="course_duration" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Course</button>
            <p class="mr-3 mt-3 btn btn-success" >Click <a class="text-white" href="update_courses.php">Here To Manage Courses</a></p>

        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
