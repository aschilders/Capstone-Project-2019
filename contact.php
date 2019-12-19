<?php
include_once 'header.php';
	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 1;
	//DATABASE CONNECTION
	require_once "connect.php";
		
		$locationkey = 3;
		
		if (!empty($_SESSION['customerlocation'])) {
			$locationkey = $_SESSION['customerlocation'];
		}
		
		$sqlselectc = "SELECT * from locations WHERE locationkey=:bvlocationkey";
		$resultc = $db->prepare($sqlselectc);
		$resultc->bindvalue('bvlocationkey', $locationkey);
		$resultc->execute();
		$rowc = $resultc->fetch();
	
		if( isset($_POST['XXsubmit']) )
		{

			$formfield['XXFirst'] = trim($_POST['XXFirst']);
			$formfield['XXLast'] = trim(strtolower($_POST['XXLast']));
			$formfield['XXEmail'] = trim($_POST['XXEmail']);
			$formfield['XXPhone'] = $_POST['XXPhone'];
			$formfield['XXComment'] = trim($_POST['XXComment']);
		

			if(empty($formfield['XXFirst'])){$errormsg .= "<p>Your first name is empty.</p>";}
			if(empty($formfield['XXLast'])){$errormsg .= "<p>Your last name is empty.</p>";}
			if(empty($formfield['XXEmail'])){$errormsg .= "<p>Your e-mail is empty.</p>";}

			if($errormsg != "")
			{
				echo "<div class='error'><p>THERE ARE ERRORS!</p></div>";
				echo $errormsg;
			}
			else
			{
				try
				{
					$sqlinsert = 'INSERT INTO customercontact (customerfirstname, customerlastname, customeremail, customerphone, customercomments)
					VALUES (:thefirst, :thelast, :theemail, :thephone, :thecomments)';
					$stmtinsert = $db->prepare($sqlinsert);
					$stmtinsert->bindvalue(':thefirst', $_POST['XXFirst']);
					$stmtinsert->bindvalue(':thelast', $_POST['XXLast']);
					$stmtinsert->bindvalue(':theemail', $_POST['XXEmail']);
					$stmtinsert->bindvalue(':thephone', $_POST['XXPhone']);
					$stmtinsert->bindvalue(':thecomments', $_POST['XXComment']);
					$stmtinsert->execute();
					echo "<div class='success'><p>Message has been sent.</p></div>";
				}
				catch(PDOException $e)
				{
					echo 'ERROR!!!' .$e->getMessage();
					exit();
				}
			}
			
		}//if isset submit

?>
  <div id="contacttitle">
	<hr/>
	<img alt="Contact" src="images/contact.png">
	<hr/>
  </div>
  <div id="contactcontainer">
	<div id="contactleft">
		<div id="contactaddress">
			<img alt="Contact" src="images/addressicon.png">
			<div id="contacthourstext" style="padding-left:3px;">
			<p style="padding-bottom:5px;font-weight:bold;font-size:1.2em;color:#F88D2B;font-style:normal;">Location</p>
			<p style="padding-bottom:5px;font-weight:bold;"><?php echo $rowc['locationaddress']; ?></p>
			<p style="padding-bottom:5px;font-weight:bold;"><?php echo $rowc['locationcity']; echo ', '; echo $rowc['locationstate']; echo ' '; echo $rowc['locationzip'];?></p>
			</div>
		</div>
		<div id="contactphone" style="padding-left:11px;">
			<img alt="Contact" src="images/phoneicon.png">
			<div id="contacthourstext">
			<p style="padding-bottom:5px;font-weight:bold;font-size:1.2em;color:#F88D2B;font-style:normal;">Phone</p>
			<p style="padding-bottom:5px;font-weight:bold;"><?php echo $rowc['locationphone']; ?></p>
			<br/>
			</div>
		</div>
		<div id="contacthours">
			<img alt="Contact" src="images/clockicon.png">
			<div id="contacthourstext" style="width:300px;">
			<p style="padding-bottom:5px;font-weight:bold;font-size:1.2em;color:#F88D2B;font-style:normal;">Hours</p>
			<p style="padding-bottom:5px;font-weight:bold;">Mon. - Sun. : <?php echo date("g:i a", strtotime($rowc['locationopen'])); echo ' - '; echo date("g:i a", strtotime($rowc['locationclose']));?></p>
			<br/>
			</div>
		</div>
		<div id="map">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3315.584732157781!2d-79.00302908479162!3d33.7972203806762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89006d21180d160d%3A0xa08bd7c8eaec5100!2s201+Graduate+Rd%2C+Conway%2C+SC+29526!5e0!3m2!1sen!2sus!4v1554315479725!5m2!1sen!2sus" width="525" height="324" frameborder="0" style="border:0" allowfullscreen></iframe>
		</div>
	</div>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="XXform">
	<div id="contactright">
		<div id="sendus">
			<img alt="Contact" src="images/sendus.png">
		</div>
		<div id="first">
			<label for="XXFirst">First Name*</label><br/><br/>
			<input type="text" name="XXFirst" id="XXFirst" value="<?php if( isset($formfield['fffirst'])){echo $formfield['fffirst'];}?>"/>
		</div>
		<div id="last">
			<label for="XXLast">Last Name*</label><br/><br/>
			<input type="text" name="XXLast" id="XXLast" value="<?php if( isset($formfield['fflast'])){echo $formfield['fflast'];}?>"/>
		</div>
		<div id="email">
			<label for="XXEmail">Email*</label><br/><br/>
			<input type="text" name="XXEmail" id="XXEmail" value="<?php if( isset($formfield['ffemail'])){echo $formfield['ffemail'];}?>"/>
		</div>
		<div id="phone">
			<label for="XXPhone">Phone</label><br/><br/>
			<input type="text" name="XXPhone" id="XXPhone" value="<?php if( isset($formfield['ffphone'])){echo $formfield['ffphone'];}?>"/>
		</div>
		<div id="message">
			<label for="XXComment">Message</label><br/><br/>
			<textarea type="text" cols="30" rows="10" style="width:95%;height:100%;resize:none;" name="XXComment" id="XXComment" value="<?php if( isset($formfield['ffcomment'])){echo $formfield['ffcomment'];}?>"></textarea>
		</div>
		<div id="send">
			<input type="image" src="images/send.png" id="send" alt="send"  name="XXsubmit" value="SELECT" />
		</div>
	</div>
	</form>
  </div>
<?php
include_once 'footer.php';
?>

