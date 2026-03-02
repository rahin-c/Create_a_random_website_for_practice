<?php 
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
        <body>
    <div class="container mt-5">
        <h1 class="text-center">Welcome to the Dashboard</h1>
        <p class="text-center">Hello, <?php echo htmlspecialchars($_SESSION['users_first_name']); ?>!</p>
    </div>
    <class="container mt-5">
        <p class="text-center"><a href="home.php" class="btn btn-primary">Home</a></p>
        <h2 class="text-center">User Information</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars(string: $_SESSION['users_first_name']); ?></td>
                    <td><?php echo htmlspecialchars(string: $_SESSION['users_last_name']); ?></td>
                    <td><?php echo htmlspecialchars(string: $_SESSION['users_email']); ?></td>
                </tr>
            </tbody>
        </table>
        <p class="text-center"><a href="logout.php" class="btn btn-danger">Logout</a></p> 
        <p class="text-center"><a href="login.php" class="btn btn-primary">Login</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    </body>
</html>