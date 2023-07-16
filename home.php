<?php
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Function to fetch all cities from the database
function getCities($conn) {
    $query = "SELECT * FROM cities";
    $result = mysqli_query($conn, $query);

    $cities = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
    } else {
        echo "Error fetching cities: " . mysqli_error($conn);
    }

    return $cities;
}

// Function to fetch all schools from the database
function getSchools($conn, $cityID = null) {
    $query = "SELECT * FROM schools";
    if ($cityID) {
        $query .= " WHERE city_id = $cityID";
    }
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

// Function to fetch all courses from the database
function getCourses($conn) {
    $query = "SELECT c.*, s.name AS school_name, f.name AS faculty_name
              FROM course c
              INNER JOIN schools s ON c.school_id = s.school_id
              INNER JOIN faculty f ON c.faculty_id = f.faculty_id";
    $result = mysqli_query($conn, $query);

    $courses = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    } else {
        echo "Error fetching courses: " . mysqli_error($conn);
    }

    return $courses;
}

// Fetch all cities
$cities = getCities($conn);

// Fetch all schools
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_form'])) {
    $cityID = $_POST['cityID'];
    $schools = getSchools($conn, $cityID);
} else {
    $schools = getSchools($conn);
}

// Fetch all faculty members
$faculty = getFaculty($conn);

// Fetch all courses
$courses = getCourses($conn);

// Handle apply online form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_form'])) {
    $studentPicture = $_FILES['studentPicture']['name'];
    $studentPictureTemp = $_FILES['studentPicture']['tmp_name'];
    $studentName = $_POST['studentName'];
    $studentCNIC = $_POST['studentCNIC'];
    $studentFatherName = $_POST['studentFatherName'];
    $studentFatherCNIC = $_POST['studentFatherCNIC'];
    $studentMotherName = $_POST['studentMotherName'];
    $studentAddress = $_POST['studentAddress'];
    $studentContactNumber = $_POST['studentContactNumber'];
    $studentDomicile = $_FILES['studentDomicile']['name'];
    $studentDomicileTemp = $_FILES['studentDomicile']['tmp_name'];
    $schoolID = $_POST['schoolID'];
    $cityID = $_POST['cityID'];

    // Move uploaded files to a designated folder
    $studentPicturePath = './uploads/' . $studentPicture;
    move_uploaded_file($studentPictureTemp, $studentPicturePath);

    $studentDomicilePath = './uploads/' . $studentDomicile;
    move_uploaded_file($studentDomicileTemp, $studentDomicilePath);

    // Insert form values into the admission form table
    $insertQuery = "INSERT INTO registration_form (user_id, student_picture, student_name, student_cnic, student_father_name, student_father_cnic, student_mother_name, student_address, student_contact_number, student_domicile, school_id, city_id, register_status)
                    VALUES ('$user_id', '$studentPicture', '$studentName', '$studentCNIC', '$studentFatherName', '$studentFatherCNIC', '$studentMotherName', '$studentAddress', '$studentContactNumber', '$studentDomicile', '$schoolID', '$cityID', 'Pending')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        $successMessage = "Registration form submitted successfully. Your registration is currently pending.";
    } else {
        $errorMessage = "Error submitting registration form: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schools, Faculty, and Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
    body {
        background-color: #f8f9fa;
    }
</style>

</head>

<body>

    <?php include('navbar.php') ?>

    <div class="container">
        <?php if (isset($successMessage)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <?php if (isset($errorMessage)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <h2 class="mt-3 mb-3 text-center">Search School By City</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="cityID">Select City:</label>
                <select class="form-control" id="cityID" name="cityID">
                    <option value="">-- Select City --</option>
                    <?php foreach ($cities as $city) { ?>
                        <option value="<?php echo $city['id']; ?>"><?php echo $city['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="search_form">Search</button>
        </form>
        <h2 class="mt-3 mb-3 text-center">Our Schools</h2>
        <div class="row">
            <?php foreach ($schools as $school) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="./admin/school_images/<?php echo $school['picture']; ?>" class="card-img-top" alt="School Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $school['name']; ?></h5>
                            <p class="card-text">Contact Info: <?php echo $school['contact_info']; ?></p>
                            <p class="card-text">Address: <?php echo $school['address']; ?></p>
                            <p class="card-text">Fee: <?php echo $school['fee']; ?></p>
                            <p class="card-text">Uniform: <?php echo $school['uniform']; ?></p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal-<?php echo $school['school_id']; ?>">Apply Online</button>
                        </div>
                    </div>
                </div>

                <!-- Apply Online Modal -->
                <div class="modal fade" id="applyModal-<?php echo $school['school_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel-<?php echo $school['school_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="applyModalLabel-<?php echo $school['school_id']; ?>">Apply Online</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="studentPicture-<?php echo $school['school_id']; ?>">Student Picture</label>
                                        <input type="file" class="form-control-file" id="studentPicture-<?php echo $school['school_id']; ?>" name="studentPicture" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentName-<?php echo $school['school_id']; ?>">Student Name</label>
                                        <input type="text" class="form-control" id="studentName-<?php echo $school['school_id']; ?>" name="studentName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentCNIC-<?php echo $school['school_id']; ?>">Student CNIC</label>
                                        <input type="text" class="form-control" id="studentCNIC-<?php echo $school['school_id']; ?>" name="studentCNIC" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentFatherName-<?php echo $school['school_id']; ?>">Student Father's Name</label>
                                        <input type="text" class="form-control" id="studentFatherName-<?php echo $school['school_id']; ?>" name="studentFatherName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentFatherCNIC-<?php echo $school['school_id']; ?>">Student Father's CNIC</label>
                                        <input type="text" class="form-control" id="studentFatherCNIC-<?php echo $school['school_id']; ?>" name="studentFatherCNIC" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentMotherName-<?php echo $school['school_id']; ?>">Student Mother's Name</label>
                                        <input type="text" class="form-control" id="studentMotherName-<?php echo $school['school_id']; ?>" name="studentMotherName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentAddress-<?php echo $school['school_id']; ?>">Address</label>
                                        <input type="text" class="form-control" id="studentAddress-<?php echo $school['school_id']; ?>" name="studentAddress" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentContactNumber-<?php echo $school['school_id']; ?>">Contact Number</label>
                                        <input type="text" class="form-control" id="studentContactNumber-<?php echo $school['school_id']; ?>" name="studentContactNumber" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="studentDomicile-<?php echo $school['school_id']; ?>">Domicile PDF File</label>
                                        <input type="file" class="form-control-file" id="studentDomicile-<?php echo $school['school_id']; ?>" name="studentDomicile" required>
                                    </div>
                                    <input type="hidden" name="schoolID" value="<?php echo $school['school_id']; ?>">
                                    <input type="hidden" name="cityID" value="<?php echo $school['city_id']; ?>">
                                    <button type="submit" class="btn btn-primary" name="apply_form">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h2 class="mt-3 mb-3 text-center">Our Faculty Members</h2>
        <div class="row">
            <?php foreach ($faculty as $member) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="./admin/faculty_images/<?php echo $member['picture']; ?>" class="card-img-top" alt="Faculty Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $member['name']; ?></h5>
                            <p class="card-text">Qualification: <?php echo $member['qualification']; ?></p>
                            <p class="card-text">Experience: <?php echo $member['experience']; ?></p>
                            <p class="card-text">Contact Info: <?php echo $member['contact_info']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h2 class="mt-3 mb-3 text-center">Our Courses</h2>
        <div class="row">
            <?php foreach ($courses as $course) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $course['subject_name']; ?></h5>
                            <p class="card-text">School: <?php echo $course['school_name']; ?></p>
                            <p class="card-text">Faculty: <?php echo $course['faculty_name']; ?></p>
                            <p class="card-text">Book Name: <?php echo $course['book_name']; ?></p>
                            <p class="card-text">Author Name: <?php echo $course['author_name']; ?></p>
                            <p class="card-text">Publisher: <?php echo $course['publisher']; ?></p>
                            <p class="card-text">Course Fee: <?php echo $course['course_fee']; ?></p>
                            <p class="card-text">Course Duration: <?php echo $course['course_duration']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
