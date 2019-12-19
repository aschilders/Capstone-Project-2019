<?php
include_once 'header.php';
$_SESSION['currentpage'] = "menu";

$pagetitle = "Menu";
$errormsg = "";
$showform = 1;
require_once 'connect.php';

//NECESSARY VARIABLES

$categories = 'SELECT * FROM menutype';
$resultc = $db->prepare($categories);
$resultc->execute();

$menuitems = 'SELECT * FROM menuitem';
$resultm = $db->prepare($menuitems);
$resultm->execute();

try{
		$formfield['ffcu'] = $_POST['XXcu'];
		$sqlselect = 'SELECT * from customer WHERE customerkey = :bvcu';
		$result = $db->prepare($sqlselect);
		$result->bindValue(':bvcu', $_SESSION['userid']);
		$result->execute();
		$row = $result->fetch(); 
		}
		catch(PDOException $ex)
		{
		
		}
		if( isset($_POST['XXsubmit']) )
		{
			echo '<p>The form was submitted.</p>';

			//Data Cleansing
			$formfield['ffcu'] = $_SESSION['customerusername'];
			$formfield['ffpt'] = trim($_POST['XXpt']);
			/*  ****************************************************************************
     		CHECK FOR EMPTY FIELDS
    		Complete the lines below for any REQIURED form fields only.
			Do not do for optional fields.
    		**************************************************************************** */
			if(empty($formfield['ffcu'])){$errormsg .= "<p>The customer is empty.</p>";}
			if(empty($formfield['ffpt'])){$errormsg .= "<p>The pickup type is empty.</p>";}
	
     		date_default_timezone_set('UTC');
			$date = date('Y-m-d');
			$time = date('h:i:s');

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
					$sqlinsert = 'INSERT INTO ticket (customerusername, ticketdate, tickettime, tickettype)
								  VALUES (:bvcu, :bvdate, :bvtime, :bvpt)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':bvcu', $formfield['ffcu']);
					$stmtinsert->bindvalue(':bvdate', $date);
					$stmtinsert->bindvalue(':bvtime', $time);
					$stmtinsert->bindvalue(':bvpt', $formfield['ffpt']);
					$stmtinsert->execute();
					//enter data into database

					$sqlmax = "SELECT MAX(ticketkey) AS maxid from ticket";
					$resultmax = $db->prepare($sqlmax);
					$resultmax->execute();
					$rowmax = $resultmax->fetch();
					$maxid = $rowmax["maxid"];	
					$maxid = $maxid + 1;

					echo "Ticket Number: " . $maxid;
					echo '<br><br><form action="menudetail.php" method = "post">';
					echo '<input type = "hidden" name = "orderid" value = "'. $maxid .'">';
					echo '<input type="submit" name="thesubmit" value="Enter Ticket Items">';
					echo "</form>";

				}//try
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}//else statement end
		}//if isset submit
		if(isset($_SESSION['customerusername']))
		{
	?>
	
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
						<td colspan="2"><input type="submit" name="XXsubmit" value="Place Order" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
	
			<br><br>

		<?php 
		}
?>

    <br><br>
	
  <br>
  
  

    <div class="container">
        <?php
        while ($rowc = $resultc->fetch()) {
			echo '';
			echo '<div id="contacttitle">';
			echo '<hr>';
			echo '<img alt="Contact" src="images/dividers.png">';
			echo '<hr>';
			echo ' </div>';
			echo '<h1 style="font-family: Baron-Neue" class="font-italic text-warning text-lowercase">' . $rowc['menutypename'] . '</h1><table border class="table text-white" style="font-family:AvenirLTStd-Roman">';
			
            while ($rowm = $resultm->fetch()) {
                if ($rowc['menutypekey'] == $rowm['menutypekey']) {
                    echo '<tr><td style="width:25%">' . $rowm['menuitemname'] . '</td><td style="width:65%">' . $rowm['menuitemdesc'] . '</td><td style="width:10%">$' . $rowm['menuitemprice'] . '</td></tr>';
                }
            }
            $resultm = $db->prepare($menuitems);
            $resultm->execute();
            echo '</table><br>';
        }
        ?>
    </div>

	
 
 <br>
 
 <br>
 <br>
 
 </body>
<?php

include_once 'footer.php';
?>