<?php
include 'config.php';
if(!isset($_SESSION['adminlogin']) || $_SESSION['adminlogin'] === false){
    header('Location: adminlogin.php');
    exit();
}

// Function to delete a city from the database
function deleteCity($conn, $cityId) {
    $query = "DELETE FROM cities WHERE id = '$cityId'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Check if the delete button is clicked
if (isset($_GET['delete'])) {
    $cityId = $_GET['delete'];
    $deleted = deleteCity($conn, $cityId);
    if ($deleted) {
        echo "City deleted successfully.";
    } else {
        echo "Error deleting city: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted

    // Validate and sanitize the input
    $city = $_POST['city'];
    $city = trim($city); // Remove leading/trailing whitespaces
    $city = htmlspecialchars($city); // Convert special characters to HTML entities

    // Perform further validation if needed

    // Insert the city into the database
    $query = "INSERT INTO cities (name) VALUES ('$city')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "City added successfully.";
    } else {
        echo "Error adding city: " . mysqli_error($conn);
    }
}

// Retrieve all cities from the database
$query = "SELECT * FROM cities";
$result = mysqli_query($conn, $query);
$cities = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php include('adminnavbar.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
                body {
            background-color: #f8f9fa;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- HTML form for adding a city -->
        <form method="POST" action="" class="mt-4">
            <div class="mb-3">
                <label for="city" class="form-label">City:</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add City</button>
        </form>

        <hr>

        <!-- Display cities in a table -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>City ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?php echo $city['id']; ?></td>
                    <td><?php echo $city['name']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $city['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
