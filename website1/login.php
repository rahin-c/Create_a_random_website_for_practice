<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sms_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$unsuccessfulmsg = '';
$emailmsg = '';
$passmsg = '';

if (isset($_POST['submit'])) {
    $users_email = $_POST['users_email'];
    $users_password = $_POST['users_password'];

    // Validate email
    if (empty($users_email)) {
        $emailmsg = 'Enter an email.';
    }

    // Validate password
    if (empty($users_password)) {
        $passmsg = 'Enter your password.';
    }

    // If both fields are filled
    if (!empty($users_email) && !empty($users_password)) {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE users_email = ?");
        $stmt->bind_param("s", $users_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify the password
            if (password_verify($users_password, $row['users_password'])) {
                // Store user information in session
                $_SESSION['users_last_name'] = $row['users_last_name'];
                $_SESSION['users_first_name'] = $row['users_first_name'];
                header('location:dashboard.php');
                exit(); // Always exit after a header redirect
            } else {
                $unsuccessfulmsg = 'Wrong email or Password!';
            }
        } else {
            $unsuccessfulmsg = 'Wrong email or Password!';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="container" style="margin-top:50px">
            <h3 class="text-center">Login System</h3>
            <p class="text-center text-success">
                <?php if (!empty($_SESSION['signupmsg'])) { echo $_SESSION['signupmsg']; } ?>
            </p>
        </div>
        <div class="container" style="margin-top:50px">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <div class="container bg-light p-4">
                        <p class="text-danger"><?php echo $unsuccessfulmsg; ?> </p>
                        <form action="" method="POST">
                            <div class="mt-2 pb-2">
                                <label for="email">Email:</label>
                                <input type="email" name="users_email" class="form-control" placeholder="Enter your email" value="<?php if (isset($_POST['submit'])) { echo htmlspecialchars($users_email); } ?>">
                                <span class="text-danger"><?php echo $emailmsg; ?></span>
                            </div>
                            <div class="mt-1 pb-2">
                                <label for="password">Password:</label>
                                <input type="password" name="users_password" class="form-control" placeholder="Enter your password">
                                <span class="text-danger"><?php echo $passmsg; ?></span>
                            </div>
                            <div class="mt-1 pb-2">
                                <button name="submit" class="btn btn-success">Login</button>
                            </div>
                            <div class="mt-1 pb-2">
                                Not an account? <a href="signuppage.php" class="text-decoration-none">Sign Up</a>
                            </div>
                            <div class="mt-1 pb-2">
                                <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-+0n0xVWY1y5g5c5e5f5e5f5e
+0n0xVWY1y5g5c5e5f5f5f5f5f5f5f5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-+0n0xVWY1y5g5c5e5f5f5f5f5f5f5f5f5f5f5f5f5f5f5" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>