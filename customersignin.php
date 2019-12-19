<?php
	include_once "header.php";
	require_once "connect.php";
?>
<head>
	<link type="text/css" href="styles/main.css" rel="stylesheet">
</head>
	<?php if(isset($_SESSION['customeremail']))
	{
	echo '<p>You are already signed in';
	}
	else
	{
	?>
	<body>
		<div class="container">
			<div class="signin-container">
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<table class="form-signin">
						<tr>
							<td><label>E-Mail</label></td>
							<td><input name="username" type="text"/></td>
						</tr>
						<tr>
							<td><label>Password</label></td>
							<td><input name="password" type="password"/></td>
						</tr>
						<tr>
							<td colspan="2"><button name="signin-submit" style="margin-top: 12px;padding-top:8px;padding-bottom:8px;" class="signinbutton" type="submit">Sign In</button></td>
						</tr>
						<tr>
							<td colspan="2"><a style="margin-top: 2px;padding-top:4px;padding-bottom:4px;" href="registeruser.php" class="signinbutton">Register</button></td>
						</tr>
					</table>
				</form>
				<?php
}
?>

				<?php
				if (isset($_POST['signin-submit'])) {
					$formfield['ffusername'] = trim($_POST['username']);
					$formfield['ffpassword'] = trim($_POST['password']);

					try {
						$sql = 'SELECT * FROM customer WHERE customeremail = :bvusername';
						$s = $db->prepare($sql);
						$s->bindValue(':bvusername', $formfield['ffusername']);
						$s->execute();
						$count = $s->rowCount();
					} catch (PDOException $e) {
						echo $e->getMessage();
						exit();
					}

					if ($count < 1) {
						echo '<p>The email or password is incorrect.</p>';
					} else {
						$row = $s->fetch();
						$confirmeduk = $row['customeremail'];
						$confirmedpw = $row['customerpassword'];

						// Sign in successful
						if (password_verify($formfield['ffpassword'], $confirmedpw)) {
							// Create session variables
							$_SESSION['customeremail'] = $row['customeremail'];	//username
							$_SESSION['customerkey'] = $row['customerkey'];	//username
							$_SESSION['customerfirstname'] = $row['customerfirstname'];	//firstname
							$_SESSION['customerlocation'] = $row['locationkey'];

							// Redirect accordingly
							//header("Location: frontindex.php");
							//echo '<script>document.location.replace("frontindex.php");</script>';
							echo '<p>Login successful. Redirecting to order... <br />' . $_SESSION['customeremail'] . ' ' . $_SESSION['customerfirstname'] .'</p>';
							echo '<meta http-equiv="refresh" content="0;URL=' . $_SESSION['currentpage'] . '.php" />';

						} else {
							echo '<p>The email or password is incorrect.</p>';
						}
					}
				}
				?>

			</div>
		</div>
<?php
	include_once "footer.php";
?>
