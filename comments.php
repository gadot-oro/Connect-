<?php
// // Start session
// session_start();
// include 'include/connect.php';

// // Check if user is logged in
// if (!isset($_SESSION['id'])) {
//     header("Location: home.php");
//     exit();
// }

// // Check if the form has been submitted
// // Check if the form has been submitted
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Get the user ID and story ID from the form data
//     $user_id = $_SESSION['id'];
//     $story_id = $_POST['story_id'];
//     $comment_text = $_POST['comment_text'];

//     // Check if the story with the specified ID exists
//     $stmt = $conn->prepare("SELECT id FROM stories WHERE id = ?");
//     $stmt->bind_param("i", $story_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows == 0) {
//         // Story not found, redirect to home page
//         // header("Location: home.php");
//         // exit();
//     }

//     // Insert the comment into the database
//     $stmt = $conn->prepare("INSERT INTO comments (user_id, story_id, comment_text) VALUES (?, ?, ?)");
//     $stmt->bind_param("iis", $user_id, $story_id, $comment_text);
//     $stmt->execute();
//     $stmt->close();

//     // Redirect to the story page
//     header("Location: story.php?id=$story_id");
//     exit();
// }

// // ...


// // Get the story ID from the query string
// if (!isset($_GET['id'])) {
//     // header("Location: home.php");
//     // exit();
// }
// $story_id = $_GET['id'];

// // Query the stories table for the specified story
// $sql = "SELECT * FROM stories WHERE id = $story_id";
// $result = $conn->query($sql);

// // Check if the story exists
// if ($result->num_rows == 0) {
//     // header("Location: home.php");
//     // exit();
// }

// // Display the story
// $row = $result->fetch_assoc();
// echo "<div class='post'>";
// echo "<h2>" . $row['story_title'] . "</h2>";
// echo "<p>" . $row['story_content'] . "</p>";
// if ($row['story_image']) {
//     echo "<img src='" . $row['story_image'] . "' alt='Post Image'>";
// }
// echo "</div>";

// // Query the comments table for the specified story's comments
// $sql = "SELECT * FROM comments WHERE story_id = $story_id ORDER BY comment_created_at DESC";
// $result = $conn->query($sql);

// // Loop through the results and display the comments
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         echo "<div class='comment'>";
//         echo "<p>" . $row['comment_text'] . "</p>";
//         echo "</div>";
//     }
// } else {
//     echo "No comments found.";
// }

// // Display the form for creating a new comment
// echo "<form method='POST'>";
// echo "<input type='hidden' name='story_id' value='$story_id'>";
// echo "<label for='comment_text'>Comment:</label>";
// echo "<textarea name='comment_text' id='comment_text' required></textarea>";
// echo "<button type='submit'>Submit</button>";
// echo "</form>";

// echo "<a href='home.php'>back home</a>";
?>



<a href="home.php">back comment</a>