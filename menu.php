<?php
	include_once 'header.php';
	$_SESSION['currentpage'] = "menu";
	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 1;
	//DATABASE CONNECTION
	require_once "connect.php";

	$sqllocations = "SELECT * from locations";
	$resultl = $db->prepare($sqllocations);
	$resultl->execute();

	try{
		$formfield['ffcu'] = $_POST['XXcu'];
		$sqlselect = 'SELECT * from customer WHERE customeremail = :bvcu';
		$result = $db->prepare($sqlselect);
		$result->bindValue(':bvcu', $_SESSION['customeremail']);
		$result->execute();
		$row = $result->fetch(); 
		}
		catch(PDOException $ex)
		{
		
		}
		if( isset($_POST['XXsubmit']) )
		{

			//Data Cleansing
			$formfield['ffcu'] = $row['customerkey'];
			$formfield['ffpt'] = trim($_POST['XXpt']);
			$formfield['fflo'] = trim($_POST['XXlo']);
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcu'])){$errormsg .= "<p>The customer is empty.</p>";}
			if(empty($formfield['ffpt'])){$errormsg .= "<p>The pickup type is empty.</p>";}
			if(empty($formfield['fflo'])){$errormsg .= "<p>Please choose a location.</p>";}
	
     		date_default_timezone_set('US/Eastern');
			$date = date('Y-m-d');
			$time = date('H:i:s');

			$quantity = 1;
			$costid = $formfield['ffit'];
			/*  ****************************************************************************
			DISPLAY ERRORS
			If we have concatenated the error message with details, then let the user know
			**************************************************************************** */
			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p>";
				echo $errormsg;
				echo "</div>";
			}
			else
			{
				try
				{



					//enter data into database
					$sqlinsert = 'INSERT INTO tickets (customerkey, ticketdate, tickettime, tickettype, locationkey)
								  VALUES (:bvcu, :bvdate, :bvtime, :bvpt, :bvlo)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcu', $formfield['ffcu']);
					$stmtinsert->bindvalue(':bvdate', $date);
					$stmtinsert->bindvalue(':bvtime', $time);
					$stmtinsert->bindvalue(':bvpt', $formfield['ffpt']);
					$stmtinsert->bindvalue(':bvlo', $formfield['fflo']);
					$stmtinsert->execute();
					//enter data into database

					$sqlmax = "SELECT MAX(ticketkey) AS maxid from tickets";
					$resultmax = $db->prepare($sqlmax);
					$resultmax->execute();
					$rowmax = $resultmax->fetch();
					$maxid = $rowmax["maxid"];	

					echo "Ticket Number: " . $maxid;
					echo '<br><br><form action="menudetail.php" method = "post">';
					echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">';
					echo '<input type="submit" name="thesubmit" value="Enter Ticket Items">';
					echo '<br/><br/><br/>';
					echo "</form>";

					$showform = 0;

				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
	}
	
	date_default_timezone_set('US/Eastern');
	$time2 = date('H:i:s');

	$sqltimes = "SELECT * FROM locations WHERE locationkey = 3";
	$resulttime = $db->prepare($sqltimes);
	$resulttime->execute();
	$rowtime = $resulttime->fetch();
	$opentime = $rowtime["locationopen"];
	$closetime = $rowtime["locationclose"];

	if($showform == 1){
	if($time2 > $opentime && $time2 < $closetime)
	{
		if(isset($_SESSION['customeremail']))
		{
	?>
	<link rel="stylesheet" href="styles/menu.css">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
			<fieldset><legend>Menu Order Entry</legend>
				<table border>
					<tr>
						<th><label for="XXpt">Pickup Type:</label></th>
						<td><select name="XXpt" id="XXpt">
						<option value = "">Enter a pickup type</option>
						<option value = "Pickup">Pick-up</option>
						<option value = "Delivery">Delivery</option>
						</select>
						</td>
					</tr>
					<tr>
						<th><label for="XXlo">Location:</label></th>
						<td><select name="XXlo" id="XXlo">
						<?php while ($rowl = $resultl->fetch() )
							{
							echo '<option value="'. $rowl['locationkey'] . '">' . $rowl['locationcity'] . ', ' . $rowl['locationstate'] . '</option>';
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" name="XXsubmit" value="Place Order" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
			<br><br>

		<?php 
		}
		else 
		{
			echo "<p style=\"text-align:center;\">Please <a href='customersignin.php'>Sign In</a></p>";
		}
	}
	else
	{
		echo "<p style=\"text-align:center;\">Sorry, but we are closed.</p>";
	}
	}
	
		include_once "footer.php"; ?>