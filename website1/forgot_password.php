<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sms_db');

$step = isset($_SESSION['reset_email']) ? 'change' : 'forgot';
$error = '';
$success = '';

if (isset($_POST['submit_email'])) {
    $email = trim($_POST['email']);
    if (empty($email)) {
        $error = "Please enter your email.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE users_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            $step = 'change';
        } else {
            $error = "No user found with that email.";
        }
    }
}

if (isset($_POST['change_password'])) {
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if (empty($new_pass) || empty($confirm_pass)) {
        $error = "Please fill in all fields.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "Passwords do not match.";
    } else {
        $email = $_SESSION['reset_email'];
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET users_password = ? WHERE users_email = ?");
        $stmt->bind_param("ss", $hashed_pass, $email);

        if ($stmt->execute()) {
            unset($_SESSION['reset_email']);
            $success = "Password changed successfully <br><br> <a href='login.php' class='btn btn-primary'>Click here to login</a>";
            $step = 'forgot'; // Go back to email form
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center"><?php echo ($step === 'forgot') ? 'Forgot Password' : 'Change Password'; ?></h3>
    <div class="col-md-6 offset-md-3 bg-light p-4 rounded">

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($step === 'forgot'): ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="email">Enter your email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" value="<?php if (isset($_POST['submit_email'])) { echo htmlspecialchars($email); } ?>">
                </div>
                <button type="submit" name="submit_email" class="btn btn-primary">Submit</button>
            </form>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" name="new_pass" class="form-control" placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm new password">
                </div>
                <button type="submit" name="change_password" class="btn btn-success">Change Password</button>
            </form>
        <?php endif; ?>

        <?php if ($step === 'change'): ?>
            <div class="mt-3">
                <a href="?cancel=1" class="btn btn-sm btn-secondary">Cancel</a>
            </div>
        <?php endif; ?>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>

<?php
// Cancel password change and go back to email input
if (isset($_GET['cancel'])) {
    unset($_SESSION['reset_email']);
    header("Location: password_reset.php");
    exit();
}
?>
