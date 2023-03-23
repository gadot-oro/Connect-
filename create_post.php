<?php
// Start session
session_start();
include 'include/connect.php';

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
if (!isset($_SESSION['id'])) {
    header("Location: home.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the session
    $user_id = $_SESSION['id'];
    
    // Get the form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Check if an image has been uploaded
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image = $_FILES['image'];
        $image_path = 'story_image/' . $image['name'];
        move_uploaded_file($image['tmp_name'], $image_path);
    } else {
        $image_path = null;
    }
    
    // Connect to the database
    require_once "include/connect.php";


    // Insert the post into the database
    $stmt = $conn->prepare("INSERT INTO stories (user_id, story_title, story_content, story_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $content, $image_path);
    $stmt->execute();
    $stmt->close();
    


    // Query the stories table for the user's posts
$sql = "SELECT * FROM stories WHERE user_id = $user_id ORDER BY story_created_at DESC";
$result = $conn->query($sql);

// Loop through the results and display the posts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<h2>" . $row['story_title'] . "</h2>";
        echo "<p>" . $row['story_content'] . "</p>";
        if ($row['story_image']) {
            echo "<img src='" . $row['story_image'] . "' alt='Post Image'>";
        }
        echo "</div>";
    }
} else {
    echo "No posts found.";
}

    // Redirect to the homepage
    // header("Location: home.php");
    // exit();
}

?>

Display the form for creating a new post
<!-- <form method="POST" action="home.php" enctype="multipart/form-data"> -->
<form method="POST" enctype="multipart/form-data">
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>

  <label for="content">Content:</label>
  <textarea name="content" id="content" required></textarea>

  <label for="image">Image (optional):</label>
  <input type="file" name="image" id="image">

  <button type="submit">Submit</button>
</form>

<a href="home.php">back home</a>




  <!-- post story -->
  <?php
// // First, connect to the database
// // include 'include/connect.php';

// // Get the user ID from the session
// $user_id = $_SESSION['id'];

// // Query the stories table for the user's posts with user name and created time
// $sql = "SELECT s.*, u.name, u.profile_picture, s.story_created_at 
//         FROM stories s 
//         JOIN users u ON s.user_id = u.id 
//         WHERE s.user_id = $user_id 
//         ORDER BY s.story_created_at DESC";
// $result = $conn->query($sql);

// // Loop through the results and display the posts
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         if ($row['profile_picture']) {
//             echo "<img src='" . $row['profile_picture'] . "' alt='Profile Picture'>";
//         }

//         echo "<div class='post'>";
//         echo "<h2>" . $row['story_title'] . "</h2>";
//         echo "<p>" . $row['story_content'] . "</p>";
//         echo "<p>Posted by " . $row['name'] . " on " . $row['story_created_at'] . "</p>";
//         if ($row['story_image']) {
//             echo "<img src='" . $row['story_image'] . "' alt='Post Image'>";
//         }
//         echo "</div>";
//     }
// } else {
//     echo "No posts found.";
// }

// // Close the database connection
// // $conn->close();
?>
