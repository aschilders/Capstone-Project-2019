<?php
	include_once "header.php";
	require_once "connect.php";
	$_SESSION['currentpage'] = "updateuser";
?>
<head>
		<link type="text/css" href="styles/register.css" rel="stylesheet">
	</head>
	<?php
	
$errormsg = "";

		$showform = 1;
		$sqlselect = 'SELECT * from customer where customerkey = :bvcustid';
		$result = $db->prepare($sqlselect);
		$result->bindValue(':bvcustid', $_SESSION['customerkey']);
		$result->execute();
		$row = $result->fetch(); 
		
		
		if( isset($_POST['thesubmit']) )
		{	
			
			$hasfirst = true;
			$haslast = true;
			$hasaddress = true;
			$hascity = true;
			$hasstate = true;
			$haszip = true;
			$haspass = true;
			$haspass2 = true;
			$hasphone = true;
			

			//Data Cleansing
			
			$formfield['fffirstname'] = trim($_POST['XXfirstname']);
			$formfield['fflastname'] = trim($_POST['XXlastname']);
			$formfield['ffaddress'] = trim($_POST['XXaddress']);
			$formfield['ffcity'] = trim($_POST['XXcity']);
			$formfield['ffstate'] = trim($_POST['XXstate']);
			$formfield['ffzip'] = trim($_POST['XXzip']);
			$formfield['ffpass'] = trim($_POST['XXpassword']);
			$formfield['ffpass2'] = trim($_POST['XXpassword2']);
			$formfield['ffphone'] = trim(strtolower($_POST['XXphone']));
		
		
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			
			if(empty($formfield['fffirstname']))
			{
				$errormsg = "<p> First name empty.</p>";
				$hasfirst = false;
			}
			if(empty($formfield['fflastname']))
			{
				$errormsg = "<p> Last name empty.</p>";
				$haslast = false;
			}
			if(empty($formfield['ffaddress']))
			{
				$errormsg = "<p> Address is empty.</p>";
				$hasaddress = false;
			}
			if(empty($formfield['ffcity']))
			{
				$errormsg = "<p> City is empty.</p>";
				$hascity = false;
			}
			if(empty($formfield['ffstate']))
			{
				$errormsg = "<p> State is empty.</p>";
				$hasstate = false;
			}
			if(empty($formfield['ffzip']))
			{
				$errormsg = "<p> Zip is empty.</p>";
				$haszip = false;
			}
			if(empty($formfield['ffpass']))
			{
				$errormsg = "<p> Password is Empty.</p>";
				$haspass = false;
			}
			if(empty($formfield['ffpass2']))
			{
				$errormsg = "<p> Passowrd is Empty</p>";
				$haspass2 = false;
			}		
			if(empty($formfield['ffphone']))
			{
				$errormsg = "<p> Phone empty.</p>";
				$hasphone = false;
			}
			
			//CHECK FOR MATCHING PASSWORDS
			if($formfield['ffpass'] != $formfield['ffpass2'])
			{
				$errormsg .= "<p>Your passwords do not match.</p>";
			}
			
			
			
			if($errormsg != "")
			{
				echo $errormsg;

			}
			else
			{
				$options = [
					'cost' => 12,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
						];
					$encpass = password_hash($formfield['ffpass2'], PASSWORD_BCRYPT, $options);
				try
				{
					
					//enter data into database
					$sqlinsert = 'update customer set
								  customerfirstname = :bvfirstname,
								  customerlastname = :bvlastname,
								  customeraddress = :bvaddress,
								  customercity = :bvcity,
								  customerstate = :bvstate,
								  customerzip = :bvzip,
								  customerphone = :bvphone,
								  customerpassword = :bvpass
								  where customerkey = :bvcustid';
								  
					$stmtinsert = $db->prepare($sqlinsert);
					
					$stmtinsert->bindvalue(':bvfirstname', $formfield['fffirstname']);
					$stmtinsert->bindvalue(':bvlastname', $formfield['fflastname']);
					$stmtinsert->bindvalue(':bvaddress', $formfield['ffaddress']);
					$stmtinsert->bindvalue(':bvcity', $formfield['ffcity']);
					$stmtinsert->bindvalue(':bvstate', $formfield['ffstate']);
					$stmtinsert->bindvalue(':bvzip', $formfield['ffzip']);
					$stmtinsert->bindvalue(':bvpass', $encpass);
					$stmtinsert->bindvalue(':bvphone', $formfield['ffphone']);
					$stmtinsert->bindvalue(':bvcustid', $_SESSION['customerkey']);
					$stmtinsert->execute();
					echo "<div class='success'><p style=\"text-align:center;\">There are no errors.  Thank you.</p></div>";
				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit
	if(isset($_SESSION['userid']))
    {
    ?>
    <p>You are already logged in. <a href="logout.php">Sign Out</a></p>
    <?php
    }
    else 
    {
    ?>
	<div class="container">
	<div class="signin-container">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
				<table  class="form-signin">
					<tr>
						<th><label class="label"  for="XXfirstname">First Name<?php if($hasfirst == false){echo '';} ?></label></th>
						<td><input type="text" name="XXfirstname" id="XXfirstname" value="<?php echo $row['customerfirstname']; ?>"/></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXlastname">Last Name<?php if($haslast == false){echo '';} ?></label></th>
						<td><input type="text" name="XXlastname" id="XXlastname" value="<?php echo $row['customerlastname']; ?>"/></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXaddress">Address<?php if($hasaddress == false){echo '';} ?></label></th>
						<td><input type="text" name="XXaddress" id="XXaddress" value="<?php echo $row['customeraddress']; ?>" /></td>
					</tr>
					<tr>
						<th><label  class="label" for="XXcity">City<?php if($hascity == false){echo '';} ?></label></th>
						<td><input type="text" name="XXcity" id="XXcity" value="<?php echo $row['customercity']; ?>" /></td>
					</tr>
					<tr>
					<th><label class="label"  for="XXstate">State<?php if($hasstate == false){echo '';} ?></label></th>
					<td><select name="XXstate" id="XXstate">
							<option value="AL" <?php if( $row['customerstate'] == "AL" ){echo ' selected';}?>>AL</option>
							<option value="AK" <?php if( $row['customerstate'] == "AK" ){echo ' selected';}?>>AK</option>
							<option value="AZ" <?php if( $row['customerstate'] == "AZ" ){echo ' selected';}?>>AZ</option>
							<option value="AR" <?php if( $row['customerstate'] == "AR" ){echo ' selected';}?>>AR</option>
							<option value="CA" <?php if( $row['customerstate'] == "CA" ){echo ' selected';}?>>CA</option>
							<option value="CO" <?php if( $row['customerstate'] == "CO" ){echo ' selected';}?>>CO</option>
							<option value="CT" <?php if( $row['customerstate'] == "CT" ){echo ' selected';}?>>CT</option>
							<option value="DE" <?php if( $row['customerstate'] == "DE" ){echo ' selected';}?>>DE</option>
							<option value="FL" <?php if( $row['customerstate'] == "FL" ){echo ' selected';}?>>FL</option>
							<option value="GA" <?php if( $row['customerstate'] == "GA" ){echo ' selected';}?>>GA</option>
							<option value="HI" <?php if( $row['customerstate'] == "HI" ){echo ' selected';}?>>HI</option>
							<option value="ID" <?php if( $row['customerstate'] == "ID" ){echo ' selected';}?>>ID</option>
							<option value="IL" <?php if( $row['customerstate'] == "IL" ){echo ' selected';}?>>IL</option>
							<option value="IN" <?php if( $row['customerstate'] == "IN" ){echo ' selected';}?>>IN</option>
							<option value="IA" <?php if( $row['customerstate'] == "IA" ){echo ' selected';}?>>IA</option>
							<option value="KS" <?php if( $row['customerstate'] == "KS" ){echo ' selected';}?>>KS</option>
							<option value="KY" <?php if( $row['customerstate'] == "KY" ){echo ' selected';}?>>KY</option>
							<option value="LA" <?php if( $row['customerstate'] == "LA" ){echo ' selected';}?>>LA</option>
							<option value="ME" <?php if( $row['customerstate'] == "ME" ){echo ' selected';}?>>ME</option>
							<option value="MD" <?php if( $row['customerstate'] == "MD" ){echo ' selected';}?>>MD</option>
							<option value="MA" <?php if( $row['customerstate'] == "MA" ){echo ' selected';}?>>MA</option>
							<option value="MI" <?php if( $row['customerstate'] == "MI" ){echo ' selected';}?>>MI</option>
							<option value="MN" <?php if( $row['customerstate'] == "MN" ){echo ' selected';}?>>MN</option>
							<option value="MS" <?php if( $row['customerstate'] == "MS" ){echo ' selected';}?>>MS</option>
							<option value="MO" <?php if( $row['customerstate'] == "MO" ){echo ' selected';}?>>MO</option>
							<option value="MT" <?php if( $row['customerstate'] == "MT" ){echo ' selected';}?>>MT</option>
							<option value="NE" <?php if( $row['customerstate'] == "NE" ){echo ' selected';}?>>NE</option>
							<option value="NV" <?php if( $row['customerstate'] == "NV" ){echo ' selected';}?>>NV</option>
							<option value="NH" <?php if( $row['customerstate'] == "NH" ){echo ' selected';}?>>NH</option>
							<option value="NJ" <?php if( $row['customerstate'] == "NJ" ){echo ' selected';}?>>NJ</option>
							<option value="NM" <?php if( $row['customerstate'] == "NM" ){echo ' selected';}?>>NM</option>
							<option value="NY" <?php if( $row['customerstate'] == "NY" ){echo ' selected';}?>>NY</option>
							<option value="NC" <?php if( $row['customerstate'] == "NC" ){echo ' selected';}?>>NC</option>
							<option value="ND" <?php if( $row['customerstate'] == "ND" ){echo ' selected';}?>>ND</option>
							<option value="OH" <?php if( $row['customerstate'] == "OH" ){echo ' selected';}?>>OH</option>
							<option value="OK" <?php if( $row['customerstate'] == "OK" ){echo ' selected';}?>>OK</option>
							<option value="OR" <?php if( $row['customerstate'] == "OR" ){echo ' selected';}?>>OR</option>
							<option value="PA" <?php if( $row['customerstate'] == "PA" ){echo ' selected';}?>>PA</option>
							<option value="RI" <?php if( $row['customerstate'] == "RI" ){echo ' selected';}?>>RI</option>
							<option value="SC" <?php if( $row['customerstate'] == "SC" ){echo ' selected';}?>>SC</option>
							<option value="SD" <?php if( $row['customerstate'] == "SD" ){echo ' selected';}?>>SD</option>
							<option value="TN" <?php if( $row['customerstate'] == "TN" ){echo ' selected';}?>>TN</option>
							<option value="TX" <?php if( $row['customerstate'] == "TX" ){echo ' selected';}?>>TX</option>
							<option value="UT" <?php if( $row['customerstate'] == "UT" ){echo ' selected';}?>>UT</option>
							<option value="VT" <?php if( $row['customerstate'] == "VT" ){echo ' selected';}?>>VT</option>
							<option value="VA" <?php if( $row['customerstate'] == "VA" ){echo ' selected';}?>>VA</option>
							<option value="WA" <?php if( $row['customerstate'] == "WA" ){echo ' selected';}?>>WA</option>
							<option value="WV" <?php if( $row['customerstate'] == "WV" ){echo ' selected';}?>>WV</option>
							<option value="WI" <?php if( $row['customerstate'] == "WI" ){echo ' selected';}?>>WI</option>
							<option value="WY" <?php if( $row['customerstate'] == "WY" ){echo ' selected';}?>>WY</option>
						</select>
					</td>
					</tr>
					<tr>
						<th><label class="label"  for="XXzip">Zip<?php if($haszip == false){echo '';} ?></label></th>
						<td><input type="text" name="XXzip" id="XXcity" value="<?php echo $row['customerzip']; ?>" /></td>
					</tr>
					<tr>
						<th><label class="label" for="XXpassword">Password<?php if($haspass == false){echo '';} ?></label></th>
						<td><input type="password" name="XXpassword" id="XXpassword" value="" /></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXpassword2">Confirm Password<?php if($haspass2 == false){echo '';} ?></label></th>
						<td><input type="password" name="XXpassword2" id="XXpassword2" value="" /></td>
					</tr>
					<tr>
						<th><label class="label"  for="XXphone">Phone<?php if($hasphone == false){echo '';} ?></label></th>
						<td><input type="text" name="XXphone" id="XXphone" value="<?php echo $row['customerphone']; ?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><input style="margin-top: 12px;" type="submit" name="thesubmit" value="Update" id="submit"/></td>
					</tr>
				</table>
		</form>
	</div>
	</div>
	<?php
	}
	include_once "footer.php";
	?>