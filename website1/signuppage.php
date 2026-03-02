<?php
	session_start();
	$conn = new mysqli('localhost', 'root', '', 'sms_db');

	if(isset($_POST['submit'])){
		$users_first_name  = $_POST['users_first_name'];
		$users_last_name   = $_POST['users_last_name'];
		$users_email       = $_POST['users_email'];
		$users_password    = $_POST['users_password'];
		$passwordagain     = $_POST['passwordagain'];
		
		$emptymsg1 = $emptymsg2 = $emptymsg3 = $emptymsg4 = $emptymsg5 = $pasmatchmsg = $emptymsg = '';

		if(empty($users_first_name)){
			$emptymsg1 = 'Write Firstname';
		}
		if(empty($users_last_name)){
			$emptymsg2 = 'Write Lastname';
		}
		if(empty($users_email)){
			$emptymsg3 = 'Write email';
		}
		if(empty($users_password)){
			$emptymsg4 = 'Write password';
		}
		if(empty($passwordagain)){
			$emptymsg5 = 'Write password Again';
		}

		if(!empty($users_first_name) && !empty($users_last_name) && !empty($users_email) && !empty($users_password) && !empty($passwordagain)){
			if($users_password !== $passwordagain){
				$pasmatchmsg = 'Password does not match!';
			}else{
				$pasmatchmsg = '';
				$hashed_password = password_hash($users_password, PASSWORD_DEFAULT);

				$stmt = $conn->prepare("INSERT INTO users (users_first_name, users_last_name, users_email, users_password) VALUES (?, ?, ?, ?)");
				$stmt->bind_param("ssss", $users_first_name, $users_last_name, $users_email, $hashed_password);

				if($stmt->execute()){
					$_SESSION['signupmsg'] = 'Sign Up Complete. Please Log in now.';
					header('Location: login.php');
					exit();
				}else{
					echo 'Data not inserted: ' . $stmt->error;
				}
			}
		}else{
			$emptymsg = 'Fill up all fields';
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <body>  
            <div class="container mt-5"></div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
	                        <h2 class="text-center">Sign Up</h2>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="border p-4 rounded shadow-sm">
                            <div class="text-danger text-center"><?php if(isset($_POST['submit'])){ echo $emptymsg; }?></div>
                            <div class="text-success text-center"><?php if(isset($_SESSION['signupmsg'])){ echo $_SESSION['signupmsg']; unset($_SESSION['signupmsg']); }?></div>    
<div class="mt-2 pb-2">
	<label for="firstname">First Name:</label>
	<input type="text" name="users_first_name" class="form-control" placeholder="Your First Name" value="<?php if(isset($_POST['submit'])){ echo htmlspecialchars($users_first_name); } ?>">
	<span class="text-danger"><?php if(isset($_POST['submit'])){ echo $emptymsg1; }?></span>
</div>
<div class="mt-2 pb-2">
	<label for="users_last_name">Last Name:</label>
	<input type="text" name="users_last_name" class="form-control" placeholder="Your Last Name" value="<?php if(isset($_POST['submit'])){ echo htmlspecialchars($users_last_name); } ?>">
	<span class="text-danger"><?php if(isset($_POST['submit'])){ echo $emptymsg2; }?></span>
</div>
<div class="mt-2 pb-2">
	<label for="email">Email:</label>
	<input type="email" name="users_email" class="form-control" placeholder="Enter your email" value="<?php if(isset($_POST['submit'])){ echo htmlspecialchars($users_email); } ?>">
	<span class="text-danger"><?php if(isset($_POST['submit'])){ echo $emptymsg3; }?></span>
</div>
<div class="mt-1 pb-2">
	<label for="password">Password:</label>
	<input type="password" name="users_password" class="form-control" placeholder="Enter New password">
	<span class="text-danger"><?php if(isset($_POST['submit'])){ echo $emptymsg4; }?></span>
</div>
<div class="mt-1 pb-2">
	<label for="passwordagain">Password Again:</label>
	<input type="password" name="passwordagain" class="form-control" placeholder="Enter password Again">
	<span class="text-danger"><?php if(isset($_POST['submit'])){ echo $emptymsg5 . ' ' . $pasmatchmsg; }?></span>
</div>
<div class="mt-1 pb-2">
	<button name="submit" class="btn btn-success">Sign Up</button>
</div>
	<div class="mt-1 pb-2">
	Already have an account? <a href="login.php" class="text-decoration-none">Login</a>
	</div>
                        </form>
                    </div>
                </div>
            </div>
			<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>			
        </body>
    </html> 