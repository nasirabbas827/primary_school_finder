<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

// Function to fetch all faculty members from the database
function getFaculty($conn) {
    $query = "SELECT f.*, s.name AS school_name FROM faculty f INNER JOIN schools s ON f.school_id = s.school_id";
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

// Fetch all faculty members
$faculty = getFaculty($conn);

// Handle delete faculty member request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_faculty'])) {
    $faculty_id = $_POST['faculty_id'];

    // Delete the faculty member from the database
    $delete_query = "DELETE FROM faculty WHERE faculty_id = '$faculty_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "Faculty member deleted successfully.";
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting faculty member: " . mysqli_error($conn);
    }
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Members</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<style>
            body {
        background-color: #f8f9fa;
    }
</style>
</head>

<body>
    <div class="container">
        <h2 class="mt-4 mb-3 text-center" >Faculty Members</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Faculty ID</th>
                    <th>Name</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Picture</th>
                    <th>Contact Info</th>
                    <th>School</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faculty as $member) { ?>
                    <tr>
                        <td><?php echo $member['faculty_id']; ?></td>
                        <td><?php echo $member['name']; ?></td>
                        <td><?php echo $member['qualification']; ?></td>
                        <td><?php echo $member['experience']; ?></td>
                        <td><img src="faculty_images/<?php echo $member['picture']; ?>" alt="Faculty Picture" width="100" height="100"></td>
                        <td><?php echo $member['contact_info']; ?></td>
                        <td><?php echo $member['school_name']; ?></td>
                        <td>
                            <a href="edit_faculty.php?faculty_id=<?php echo $member['faculty_id']; ?>" class="btn btn-primary">Edit</a>
                            <form method="POST" action="">
                                <input type="hidden" name="faculty_id" value="<?php echo $member['faculty_id']; ?>">
                                <button type="submit" name="delete_faculty" class="mt-3 btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
