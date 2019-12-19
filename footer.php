<!DOCTYPE html>
<html>
</div>
<?php

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

?>
<footer>
    <div id="left">
	<div id="nav">
		<a href='home.php'>Home |</a>
		<a href='menu2.php'>Menu |</a>
		<a href='menu.php'>Order |</a>
		<a href='about.php'>About |</a>
		<a href='contact.php'>Contact</a>
	</div>
	<div id="newsletter">
		<div id="newsimage">
		<img src="images/newsletter.png" alt="Sign up for our newsletter">
		</div>
		<div id="footerinput">
		<input type="text" id="submitfooter" style="width:70%;" placeholder="ex@example.com">
		<input type="image" src="images/arrow.png" id="arrow" alt="Submit">
		</div>
	</div>
	</div>
	<div id="logos">
		<p> Follow us on </p>
		<div class="social-media">
			<a href="https://www.facebook.com/ezcheezytruck/" target="_blank">
			<img src="images/FB.png" alt="Facebook">
			</a>
		</div>
		<div class="social-media">
			<a href="https://twitter.com/gohgtc?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor" target="_blank">
			<img src="images/Instagram.png" alt="Twitter">
			</a>
		</div>
		<div id="instagram">
			<a href="https://www.instagram.com/gohgtc/?hl=en" target="_blank">
			<img src="images/Twitter.png" alt="Instagram">
			</a>
		</div>
	</div>
	<div id="address">
		<p style="padding-bottom:5px;font-weight:bold;font-size:1.0em;">Mon. - Sun. : <?php echo date("g:i a", strtotime($rowc['locationopen'])); echo ' - '; echo date("g:i a", strtotime($rowc['locationclose']));?></p>
		<p style="padding-bottom:5px;font-weight:bold;font-size:1.0em;"><?php echo $rowc['locationaddress']; ?></p>
		<p style="padding-bottom:5px;font-weight:bold;font-size:1.0em;"><?php echo $rowc['locationcity']; echo ', '; echo $rowc['locationstate']; echo ' '; echo $rowc['locationzip'];?></p>
	</div>
</footer>
</div>
</center>
</body>
</html>