<?php
include('config.php');

// Check if the user is already logged in
if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
  header("location: home.php"); // Redirect to home page if already logged in
  exit();
}
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Management System</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
    body {
        background-color: #f8f9fa;
    }
</style>

</head>

<body>
  <!-- Navbar -->
<?php include 'navbar.php' ?>
  
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="images/slider (1).jpg" alt="First slide" height="700px">
            <div class="carousel-caption d-none d-md-block heading">
                <h2>Welcome to ABC School</h2>
                <h4>Where Learning is Fun!</h4>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="images/slider (2).jpg" alt="Second slide" height="700px">
            <div class="carousel-caption d-none d-md-block heading">
                <h2>Join Our Team of Expert Teachers</h2>
                <h4>We Are Hiring Now!</h4>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="images/slider (3).jpg" alt="Third slide" height="700px">
            <div class="carousel-caption d-none d-md-block heading">
                <h2>Discover Our Exciting Extracurricular Activities</h2>
                <h4>From Sports to Arts, We Have it All</h4>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

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

  

<footer class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <p>&copy; 2023 Online Primary School Finder. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="#" class="me-3">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>

<script
src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
crossorigin="anonymous"
></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
crossorigin="anonymous"
></script>
<script
src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
crossorigin="anonymous"
></script>
</body>
</html>
