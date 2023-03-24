<?php
require_once 'include/connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the story ID and reaction type from the POST data
  $storyId = $_POST['story_id'];
  $reactionType = $_POST['reaction_type'];

  // Get the user ID from the session data
  $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

  if (!$userId) {
    die("Error: User not logged in.");
  }

  // Check if the user has already reacted to this story
  $query = "SELECT id FROM reactions WHERE user_id = $userId AND story_id = $storyId";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // User has already reacted to this story, so just display the current like/dislike counts
    $query = "SELECT COUNT(*) as count FROM reactions WHERE story_id = $storyId AND reaction_type = 'like'";
    $result = $conn->query($query);
    $likeCount = ($result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;

    $query = "SELECT COUNT(*) as count FROM reactions WHERE story_id = $storyId AND reaction_type = 'dislike'";
    $result = $conn->query($query);
    $dislikeCount = ($result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;

    if ($reactionType == 'like') {
      echo "$likeCount\n";
    } else {
      echo "$dislikeCount\n";
    }
    exit();
  }

  // Insert the reaction into the database
  $query = "INSERT INTO reactions (user_id, story_id, reaction_type) VALUES ($userId, $storyId, '$reactionType')";
  $result = $conn->query($query);

  if (!$result) {
    die("Error inserting reaction: " . $conn->error);
  }

  // Get the like and dislike counts for the story
  $query = "SELECT COUNT(*) as count FROM reactions WHERE story_id = $storyId AND reaction_type = 'like'";
  $result = $conn->query($query);
  $likeCount = ($result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;

  $query = "SELECT COUNT(*) as count FROM reactions WHERE story_id = $storyId AND reaction_type = 'dislike'";
  $result = $conn->query($query);
  $dislikeCount = ($result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;

  // Return the updated like or dislike count
  if ($reactionType == 'like') {
    echo "$likeCount\n";
  } else {
    echo "$dislikeCount\n";
  }
}
?>





