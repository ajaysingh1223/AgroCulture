<!DOCTYPE html>
<html>
<head>
	<title>Change Password</title>
	<link rel="stylesheet" type="text/css" href="first.css">
</head>
<body>
	<div class="container">
		<form action="profileView.php" method="post">
			<h2>Change Password</h2>
			<?php if (isset($_GET['error'])) { ?>
				<p class="error"><?php echo $_GET['error']; ?></p>
			<?php } ?>

			<?php if (isset($_GET['success'])) { ?>
				<p class="success"><?php echo $_GET['success']; ?></p>
			<?php } ?>

			<label for="old_password">Old Password</label>
			<input type="password" id="old_password" name="old_password" required>

			<label for="new_password">New Password</label>
			<input type="password" id="new_password" name="new_password" required>

			<label for="confirm_password">Confirm New Password</label>
			<input type="password" id="confirm_password" name="confirm_password" required>

			<button type="submit">Change Password</button>
		</form>
	</div>
</body>
</html>
