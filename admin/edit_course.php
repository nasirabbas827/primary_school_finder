<?php
// Assuming you have established a database connection
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    
    // Validate and sanitize the input
    $course_id = $_POST['course_id'];
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
    
    // Update the course in the database
    $query = "UPDATE course SET subject_name = '$subject_name', faculty_id = '$faculty_id', book_name = '$book_name', 
              author_name = '$author_name', publisher = '$publisher', course_fee = '$course_fee', course_duration = '$course_duration' 
              WHERE course_id = '$course_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Course updated successfully.";
    } else {
        echo "Error updating course: " . mysqli_error($conn);
    }
}

// Retrieve the course ID from the query string parameter
if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    
    // Fetch the course details from the database
    $query = "SELECT * FROM course WHERE course_id = '$course_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "Course not found.";
        exit();
    }
} else {
    echo "Course ID not specified.";
    exit();
}

// Fetch all faculty members
$query = "SELECT * FROM faculty";
$result = mysqli_query($conn, $query);

$faculty = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $faculty[] = $row;
    }
} else {
    echo "Error fetching faculty members: " . mysqli_error($conn);
    exit();
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
        }

        label {
            margin-top: 10px;
        }

        button[type="submit"] {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Edit Course</h2>
        <form method="POST" action="">
            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">

            <div class="form-group">
                <label for="subject_name">Subject Name:</label>
                <input type="text" name="subject_name" id="subject_name" value="<?php echo $course['subject_name']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="faculty_id">Select Faculty:</label>
                <select name="faculty_id" id="faculty_id" class="form-control" required>
                    <?php foreach ($faculty as $member) { ?>
                        <option value="<?php echo $member['faculty_id']; ?>" <?php echo ($course['faculty_id'] == $member['faculty_id']) ? 'selected' : ''; ?>>
                            <?php echo $member['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="book_name">Book Name:</label>
                <input type="text" name="book_name" id="book_name" value="<?php echo $course['book_name']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="author_name">Author Name:</label>
                <input type="text" name="author_name" id="author_name" value="<?php echo $course['author_name']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="publisher">Publisher:</label>
                <input type="text" name="publisher" id="publisher" value="<?php echo $course['publisher']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="course_fee">Course Fee:</label>
                <input type="text" name="course_fee" id="course_fee" value="<?php echo $course['course_fee']; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="course_duration">Course Duration:</label>
                <input type="text" name="course_duration" id="course_duration" value="<?php echo $course['course_duration']; ?>" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
