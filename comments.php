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




<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Validate form data
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    // Check if file is uploaded
    if (empty($_FILES['profile_picture']['name'])) {
        $errors[] = "Profile picture is required";
    }
    // If there are no errors, check if the email already exists in the database
    if (empty($errors)) {
        // Connect to the database
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "backend";
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // Check if the email already exists in the database
        $email_query = "SELECT * FROM users WHERE email='$email'";
        $email_result = mysqli_query($conn, $email_query);

        if (mysqli_num_rows($email_result) > 0) {
            // Use JavaScript to display error message
            echo "<script>
                    setTimeout(function() {
                        alert(`Account already exists! \nGo to login page!`);
                        window.location.href = 'index.php';
                    }, 200);
                 </script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Upload profile picture to server
            $profile_picture_name = $_FILES['profile_picture']['name'];
            $profile_picture_tmp_name = $_FILES['profile_picture']['tmp_name'];
            $profile_picture_type = $_FILES['profile_picture']['type'];
            $profile_picture_size = $_FILES['profile_picture']['size'];

            $upload_dir = 'uploads/';

            if (empty($errors)) {
                // Check if file type is valid
                $allowed_types = array('jpg', 'jpeg', 'png');
                $profile_picture_extension = pathinfo($profile_picture_name, PATHINFO_EXTENSION);
                if (!in_array($profile_picture_extension, $allowed_types)) {
                    $errors[] = "Invalid file type. Allowed types: jpg, jpeg, png";
                }

                // Check if file size is valid
                $max_size = 500000; // 500kb
                if ($profile_picture_size > $max_size) {
                    $errors[] = "File size exceeds maximum size. Maximum size: 500kb";
                }

                // Generate unique file name to prevent overwriting
                $new_file_name = uniqid('profile_', true) . "." . $profile_picture_extension;

                // Upload file to server
                if (move_uploaded_file($profile_picture_tmp_name, $upload_dir . $new_file_name)) {

                    // Insert user data into the database
                    $insert_user_query = "INSERT INTO users (name, email, password, profile_picture) VALUES ('$name', '$email', '$hashed_password', '$new_file_name')";

                    if (mysqli_query($conn, $insert_user_query)) {
                        // Use JavaScript to display success message and redirect to index.php after 200ms
                        echo "<script>
                                setTimeout(function() {
                                    alert('Account created successfully');
                                    window.location.href = 'index.php';
                                }, 200);
                             </script>";
                    } else {
                        $errors[] = "Error: " . $insert_user_query . "<br>" . mysqli_error($conn);
                    }
                } else {
                    $errors[] = "Error uploading file";
                }
            }

            mysqli_close($conn);
        }

        // Display errors
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }
}
?>