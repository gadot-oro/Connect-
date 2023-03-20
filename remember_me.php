<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "backend";

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? $_POST['remember'] : '';

    // Query the database
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set a "Remember me" cookie if checked
            if ($remember == 'on') {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_me', $token, time() + 60 * 60 * 24 * 7); // Expires in 1 week
                // Store the token in the database
                $query = "UPDATE users SET remember_token='$token' WHERE id={$user['id']}";
                $conn->query($query);
            }
            // Set a session variable and redirect to homepage
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit();
        } else {
            $error_msg = "Invalid email or password.";
        }
    } else {
        $error_msg = "Invalid email or password.";
    }
}

// Check if there is a "Remember me" cookie set
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    // Query the database to find the user with the matching token
    $query = "SELECT * FROM users WHERE remember_token='$token' LIMIT 1";
    $result = $conn->query($query);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Set a session variable and redirect to homepage
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    }
}
?>
