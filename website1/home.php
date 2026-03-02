<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Home Page</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container mt-5 text-center">
		<h1 class="mb-4">Welcome to Our Website</h1>
        <p class="lead">This is a simple home page.</p>
        <p class="text-success">
		
		<?php if (isset($_SESSION['users_first_name'])): ?>
			<h4>Hello, <?php echo htmlspecialchars($_SESSION['users_first_name']); ?>!</h4>
			<a href="dashboard.php" class="btn btn-success mt-3">Go to Dashboard</a>
			<a href="logout.php" class="btn btn-danger mt-3">Logout</a>
			
		<?php else: ?>
			<a href="login.php" class="btn btn-primary me-2">Login</a>
		<?php endif; ?>

	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>
