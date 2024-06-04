<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM signup WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Start session
            $_SESSION['username'] = $username;
            // Close statement and connection
            $stmt->close();
            $conn->close();
            // Display success message and redirect after 5 seconds
            echo "<html>
                    <head>
                        <title>Login Successful</title>
                        <script type='text/javascript'>
                            function redirectToIndex() {
                                setTimeout(function() {
                                    window.location.href = 'weather.html';
                                }, 3000); // 3000 milliseconds = 3 seconds
                            }
                        </script>
                    </head>
                    <body onload='redirectToIndex()'>
                        <div style='text-align: center; margin-top: 50px;'>
                            <h2>Login successful. Welcome, " . htmlspecialchars($username) . "!</h2>
                            
                        </div>
                    </body>
                  </html>";
            exit;
        } else {
            $error_message = " Invalid password (Try Again)";
        }
    } else {
        $error_message = " (No user found with that username)";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-bg">
    <div class="form-container">
        <form id="login-form" method="POST" action="login.php">
            <h2 class="form-title">Weather App</h2>
            <label for="login-username">Username</label>
            <input type="text" id="login-username" name="username" required>
            <div id="username-error" class="error-message">
                
            </div>
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password" required>
            <?php echo isset($error_message) ? htmlspecialchars($error_message) : ''; ?>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
</body>
</html>
