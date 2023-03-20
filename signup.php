<?php
require_once "include/connect.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/flowbite.min.css" rel="stylesheet" />
	<script>
		// On page load or when changing themes, best to add inline in `head` to avoid FOUC
		if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
			document.documentElement.classList.add('dark');
		} else {
			document.documentElement.classList.remove('dark')
		}
	</script>
</head>

<body class="bg-gray-50 dark:bg-gray-700">


	<!-- sign up -->
	<section class="bg-gray-50 dark:bg-gray-700">
		<!-- <section> -->
		<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
			<!-- <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
				<img class="w-8 h-8 mr-2" src="#" alt="logo">
				
			</a> -->
			<div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
				<div class="p-6 space-y-4 md:space-y-6 sm:p-8">
					<h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
						Create Account
					</h1>
					<form class="space-y-4 md:space-y-6" method="POST" action="signup.php">
						<div>
							<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full
								Name</label>
							<input type="name" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Gadisa Ahmed" required="">
						</div>
						<div>
							<label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
								email</label>
							<input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="gadisa@bit.nl" required="">
						</div>
						<div>
							<label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
							<input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
						</div>
						<div class="flex items-start">
							<div class="flex items-center h-5">
								<input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" required="">
							</div>
							<div class="ml-3 text-sm">
								<label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-primary-600 hover:underline dark:text-primary-500" href="terms_conditions.php">Terms and Conditions</a></label>
							</div>
						</div>
						<button type="submit" class="w-full text-gray-100  hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create
							an account</button>
						<p class="text-sm font-light text-gray-500 dark:text-gray-400">
							Already have an account? <a href="index.php" class="font-medium text-red-600  hover:text-green-700 dark:text-primary-500">Login</a>
						</p>
					</form>
				</div>
			</div>
		</div>
	</section>

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

		// If there are no errors, check if the email already exists in the database
		// If there are no errors, insert the user data into the database
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

			if (mysqli_num_rows($email_result) > 0
			) {
				// Use JavaScript to display error message
				echo "<script>
				setTimeout(function() {
					alert(`Account already exist! \nGo to login page!`);
					window.location.href = 'index.php';
				}, 200);
			 </script>";
			} else {
				// Hash the password
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);

				// Insert user data into the database
				$insert_user_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

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
	?>
	

</body>

</html>