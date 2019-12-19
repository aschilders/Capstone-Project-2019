<?php
	include_once 'header.php';
	//NECESSARY VARIABLES
	$errormsg = "";
	$showform = 1;
	//DATABASE CONNECTION
	require_once "connect.php";
	$_SESSION['currentpage'] = "menufinish";

	$formfield['fforderid'] = $_POST['orderid'];
	
	$sqlselecto = "SELECT ticketdetail.*, menuitem.menuitemname
			FROM ticketdetail, menuitem
			WHERE menuitem.menuitemkey = ticketdetail.menuitemkey
			AND ticketdetail.ticketkey = :bvorderid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvorderid', $formfield['fforderid']);
	$resulto->execute();
	
	$sqlselectt = "UPDATE tickets SET
			ticketcomplete = 1
			WHERE ticketkey = :bvorderid";
	$resultt = $db->prepare($sqlselectt);
	$resultt->bindValue(':bvorderid', $formfield['fforderid']);
	$resultt->execute();

	$ordertotal = 0;



	if(isset($_SESSION['customeremail']))
	{
?>
<h2>Your order has been submitted.  Thank you!</h2>
<table border>
		<tr>
			<th>Item</th>
			<th>Price</th>
			<th>Notes</th>
		</tr>
		<?php
		while ($rowo = $resulto->fetch() )
			{
			$ordertotal = $ordertotal + $rowo['ticketdetailprice'];
			
			echo '<tr><td>' . $rowo['menuitemname'] . '</td><td>$' . $rowo['ticketdetailprice'] . '</td>';
			echo '<td>' . $rowo['ticketdetailnote'] . '</td></tr>';
			}
		echo '<tr><th>Total</th>';
		echo '<th>$' . $ordertotal . '</th><td></td></tr>';
		?>
</table>
		<?php 
		}
		else {
		echo "<p>Please <a href='customersignin.php'>Sign In</a></p>";
	}
		include_once "footer.php"; ?>