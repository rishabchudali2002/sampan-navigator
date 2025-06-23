<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('config.php');

// Remove any debug output before header calls (like var_dump)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the user exists and is active
    $query = "SELECT * FROM users WHERE email = '$email' AND is_active = 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // User exists, fetch user data
            $user = mysqli_fetch_assoc($result);
           
            

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Password is correct, initialize session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['is_admin'] = $user['is_admin']; // Store admin status

                // Redirect based on user role
                if ((int)$user['is_admin'] === 1) {
                    header('Location: dashboard.php');
                    exit();
                }else if ((int)$user['is_guide'] === 1) {
                    header('Location: guide_dashboard.php');
                    exit();
                } else {
                    header('Location: homepage.php');
                    exit();
                }
            } else {
                // Invalid password
                $error_message = " Invalid email or password. Please try again.";
            }
        } else {
            // No user found
            $error_message = " Invalid email or password. Please try again.";
        }
    } else {
        // Query error
        $error_message = " Error in query: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          background-color: #f4f7fc;
          margin: 0;
          padding: 0;
      }

      .login-container {
          width: 350px;
          margin: 50px auto;
          padding: 20px;
          background-color: white;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
          border-radius: 8px;
      }

      .login-container h2 {
          text-align: center;
          margin-bottom: 20px;
          color: #333;
      }

      .login-container label {
          font-weight: bold;
          color: #555;
      }

      .login-container input {
          width: 100%;
          padding: 10px;
          margin: 10px 0;
          border: 1px solid #ccc;
          border-radius: 4px;
          box-sizing: border-box;
      }

      .login-container button {
          width: 100%;
          padding: 12px;
          background-color: #4CAF50;
          color: white;
          border: none;
          border-radius: 4px;
          cursor: pointer;
      }

      .login-container button:hover {
          background-color: #45a049;
      }

      .login-container .error {
          color: red;
          text-align: center;
          margin-bottom: 10px;
      }

      .login-container .link {
          display: block;
          text-align: center;
          margin-top: 10px;
      }

      .login-container .link a {
          text-decoration: none;
          color: #4CAF50;
      }

      .login-container .link a:hover {
          text-decoration: underline;
      }
  </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    
    <!-- Display error message if login fails -->
    <?php if (isset($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">
        
        <button type="submit">Login</button>
    </form>

    <!-- Link to the registration page -->
    <p class="link">
        Don't have an account? <a href="register.php">Register here</a>
    </p>
</div>

</body>
</html>
