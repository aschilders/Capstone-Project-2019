<?php
$pagetitle = "Login Confirmation";
require_once 'header.php';
require_once 'connect.php';

if(isset($_SESSION['loginid']))
{
    echo "<p class='error'>You are already logged in.</p>";
    include_once 'footer.php';
    exit();
}

$showform = 1;
$errormsg = '';

if(isset ($_POST['submit'])) {
	
	$formfield['ffemail'] = strtolower(trim($_POST['email']));
	$formfield['ffpassword'] = trim($_POST['password']);
	
	if(empty($formfield['ffemail'])) { $errormsg .= '<p>EMAIL IS MISSING</p>';}
	if(empty($formfield['ffpassword'])) { $errormsg .= '<p>PASSWORD IS MISSING</p>';}
	
	if($errormsg != '') {
		echo "<p>THERE ARE ERRORS</p>" . $errormsg;
	}
	else
	{
		try
		{
			$sql = 'SELECT * FROM userinfo WHERE dbemail = :bvemail';
			$s = $db->prepare($sql);
			$s->bindValue(':bvemail', $formfield['ffemail']);
			$s->execute();
			$count = $s->rowCount();
		}
		catch (PDOException $e)
		{
			echo "ERROR!!!" . $e->getMessage();
			exit();
		}
		
		if($count < 1)
		{
			echo '<p>The email or password is incorrect</p>';
		}
		else 
		{
			$row = $s->fetch();
			$confirmeduname = $row['dbemail'];
			$confirmedpw = $row['dbpassword'];
			
			if (password_verify($formfield['ffpassword'], $confirmedpw))
			{
				$_SESSION['loginid']= $row['this'];
                $_SESSION['loginname'] = $row['dbfullname'];
				$_SESSION['loginpermit'] = $row['dbuserpermit'];
				$showform = 0;
				echo "<br>";
                echo "Logged In Successfully";
				echo "<br><br>";
				echo '<a href = "' . $_SESSION['currentpage'] . '.php">Continue</a>';
				echo "<br>";
			} 
			else
			{
				echo '<p>The emails or password is incorrect</p>';
			}
		}
	}
}
if($showform == 1)
{
?>

<p>You are not logged in.  Please log in</p>

<form name = "loginForm" id = "loginForm" method = "post" action = "login.php">
	<table>
		<tr>
			<td>Email</td>
			<td><input type="text" name="email" id = "email" required></td>
		</tr><tr>	
			<td>Password</td>	
			<td><input type="password" name="password" id = "password" required></td>
		</tr><tr>	
			<td>Submit:</td>
			<td><input type ="submit" name= "submit" value = "submit"></td>
		</tr>
	</table>
</form>
<?php
}
include_once 'footer.php';
?>