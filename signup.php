<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare and bind statements
    $stmt_signup = $conn->prepare("INSERT INTO signup (name, username, password) VALUES (?, ?, ?)");
    $stmt_signup->bind_param("sss", $name, $username, $password);

    $stmt_login = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
    $stmt_login->bind_param("ss", $username, $password);

    // Execute statements and check for errors
    if ($stmt_signup->execute() && $stmt_login->execute()) {
        // Display success message and redirect after 5 seconds
        echo "<html>
                <head>
                    <title>Signup Successful</title>
                    <script type='text/javascript'>
                        function redirectToLogin() {
                            setTimeout(function() {
                                window.location.href = 'index.html';
                            }, 3000); // 3000 milliseconds = 3 seconds
                        }
                    </script>
                </head>
                <body onload='redirectToLogin()'>
                    <div style='text-align: center; margin-top: 50px;'>
                        <h2>Signup successful. Welcome, " . htmlspecialchars($name) . "!</h2>
                        
                    </div>
                </body>
              </html>";
        // Close statements and connection
        $stmt_signup->close();
        $stmt_login->close();
        $conn->close();
        exit;
    } else {
        $error_message = "Error: " . htmlspecialchars($stmt_signup->error) . "<br>" . htmlspecialchars($stmt_login->error);
    }

    // Close statements and connection
    $stmt_signup->close();
    $stmt_login->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App - Signup</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="signup-bg">
    <div class="form-container">
        <form id="signup-form" method="POST" action="signup.php">
            <h2 class="form-title">Weather App</h2>
            <label for="signup-name">Name</label>
            <input type="text" id="signup-name" name="name" required>
            <label for="signup-username">Username</label>
            <input type="text" id="signup-username" name="username" required>
            <label for="signup-password">Password</label>
            <input type="password" id="signup-password" name="password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div id="error-message" class="error-message">
            <?php echo isset($error_message) ? htmlspecialchars($error_message) : ''; ?>
        </div>
        <p>Already have an account? <a href="login.html">Login</a></p>
    </div>
</body>
</html>
