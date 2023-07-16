<?php
// Assuming you have established a database connection
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

// Function to fetch all schools from the database
function getSchools($conn) {
    $query = "SELECT schools.school_id, schools.name, schools.picture, schools.contact_info, schools.address, schools.fee, schools.uniform, cities.name AS city_name FROM schools INNER JOIN cities ON schools.city_id = cities.id";
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

// Handle delete school request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_school'])) {
    $school_id = $_POST['school_id'];

    // Delete the school from the database
    $delete_query = "DELETE FROM schools WHERE school_id = '$school_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        echo "School deleted successfully.";
        // Refresh the page after deletion
        header("Refresh:0");
    } else {
        echo "Error deleting school: " . mysqli_error($conn);
    }
}
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schools</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">

<style>
            body {
        background-color: #f8f9fa;
    }
</style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-4 mb-3" >Manage Schools</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>School ID</th>
                    <th>Name</th>
                    <th>Picture</th>
                    <th>Contact Info</th>
                    <th>Address</th>
                    <th>Fee</th>
                    <th>Uniform</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schools as $school) { ?>
                    <tr>
                        <td><?php echo $school['school_id']; ?></td>
                        <td><?php echo $school['name']; ?></td>
                        <td><img src="school_images/<?php echo $school['picture']; ?>" alt="School Picture" width="100" height="100"></td>
                        <td><?php echo $school['contact_info']; ?></td>
                        <td><?php echo $school['address']; ?></td>
                        <td><?php echo $school['fee']; ?></td>
                        <td><?php echo $school['uniform']; ?></td>
                        <td><?php echo $school['city_name']; ?></td>
                        <td>
                            <a href="edit_school.php?school_id=<?php echo $school['school_id']; ?>" class="btn btn-primary">Edit</a>
                            <form method="POST" action="">
                                <input type="hidden" name="school_id" value="<?php echo $school['school_id']; ?>">
                                <button type="submit" name="delete_school" class="mt-3 btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
