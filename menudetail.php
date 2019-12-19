<?php
	require_once "header.php";
	require_once "connect.php";
	$_SESSION['currentpage'] = "menudetail";

	$formfield['fforderid'] = $_POST['orderid'];
	$formfield['ffprodid'] = $_POST['prodid'];
	$formfield['fforderitemprice'] = $_POST['orderitemprice'];
	
	$sqlselectc = "SELECT * from menutype";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();

	if (isset($_POST['OIEnter']))
	{
		
		$sqlinsert = 'INSERT INTO ticketdetail (ticketkey, menuitemkey, ticketdetailprice) VALUES (:bvorderid, :bvprodid, :bvorderitemprice)';

			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sqlinsert);
			//Binds our associative array variables to the bound
			//variables in the sql statement
			$stmtinsert->bindvalue(':bvorderid', $formfield['fforderid']);
			$stmtinsert->bindvalue(':bvprodid', $formfield['ffprodid']);
			$stmtinsert->bindvalue(':bvorderitemprice', $formfield['fforderitemprice']);

			//Runs the insert statement and query
			$stmtinsert->execute();

		$sqlupdate = 'UPDATE menuitem SET  menuitemcount = menuitemcount - 1 WHERE menuitemkey = :bvitemname';
			$stmtinsert = $db->prepare($sqlupdate);

			$stmtinsert->bindvalue(':bvitemname', $formfield['ffprodid']);

			$stmtinsert->execute();
	}

	if (isset($_POST['DeleteItem']))
	{
		$sqldelete = 'DELETE FROM ticketdetail 
					WHERE ticketdetailkey = :bvorderitemid';
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':bvorderitemid', $_POST['orderitemid']);
		$stmtdelete->execute();

		$sql = 'SELECT menuitemkey FROM ticketdetail WHERE ticketdetailkey = :bvitemkey';
		$resulti = $db->prepare($sql);
		$resulti->bindvalue(':bvitemkey', $_POST['orderitemid']);
		$resulti->execute();
		$row = $resulti->fetch();
		$itemkey = $row['menuitemkey'];

		$sqlupdateoi = 'UPDATE menuitem 
					SET menuitemcount = menuitemcount + 1
					WHERE menuitemkey = :bvorderitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);
		$stmtupdateoi->bindvalue(':bvorderitemid', $itemkey);
		$stmtupdateoi->execute();
	}
	
	if (isset($_POST['UpdateItem']))
	{
		$sqlupdateoi = 'Update ticketdetail 
					set ticketdetailnote = :bvitemnotes
					WHERE ticketdetailkey = :bvorderitemid';
		$stmtupdateoi = $db->prepare($sqlupdateoi);
		$stmtupdateoi->bindvalue(':bvorderitemid', $_POST['orderitemid']);
		$stmtupdateoi->bindvalue(':bvitemnotes', $_POST['newnote']);
		$stmtupdateoi->execute();
	}
	
	$sqlselecto = "SELECT ticketdetail.*, menuitem.menuitemname
			FROM ticketdetail, menuitem
			WHERE menuitem.menuitemkey = ticketdetail.menuitemkey
			AND ticketdetail.ticketkey = :bvorderid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':bvorderid', $formfield['fforderid']);
	$resulto->execute();
	
	//if ($visible == 1)
	
?>
<link rel="stylesheet" href="styles/menu.css">
<body>

	<fieldset><legend align="center">Enter Items for Order Number <?php echo $formfield['fforderid'] ;?> </legend>
		<form action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		
		<table border>
			<?php
				echo '<tr>';
				while ($rowc = $resultc->fetch() )
				{
					echo '<td class="break"><table>';
					$sqlselectp = "SELECT * from menuitem where menutypekey = :bvprodcat";
					$resultp = $db->prepare($sqlselectp);
					$resultp->bindValue(':bvprodcat', $rowc['menutypekey']);
					$resultp->execute();
					while ($rowp = $resultp->fetch() )
					{
						$checkinv = $rowp['menuitemcount'];
						if($checkinv > 0)
						{
							echo '<tr><td>';
							echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
							echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
							echo '<input type = "hidden" name = "prodid" value = "'. $rowp['menuitemkey'] .'">';
							echo '<input type = "hidden" name = "orderitemprice" value = "'. $rowp['menuitemprice'] .'">';
							echo '<p>'. $rowp['menuitemdesc'] . '</p>';
							echo '<input type="submit" style="width:225px;" name="OIEnter" value="'. $rowp['menuitemname'] . ' - $' 
								. $rowp['menuitemprice'] .'">';
								echo '<p></p>';
							echo '</form>';
						
							echo '</td></tr>';
						}
					}
					echo '</table></td>';	
				}
				echo '</tr>';
			?>
		</table>
	</fieldset>
	<br><br>
	<table>
		<tr>
		<td>
		<table border>
			<tr>
				<th>Item</th>
				<th>Price</th>
				<th>Notes</th>
				<th></th>
				<th></th>

			</tr>
			<?php
				$ordertotal = 0;
				while ($rowo = $resulto->fetch() )
				{
				$ordertotal = $ordertotal + $rowo['ticketdetailprice'];
					
				echo '<tr><td>' . $rowo['menuitemname'] . '</td><td>' . $rowo['ticketdetailprice'] . '</td>';
				echo '<td>' . $rowo['ticketdetailnote'] . '</td><td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
				echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['ticketdetailkey'] .'">';
				echo '<input type="submit" name="NoteEntry" value="Update">';
				echo '</form></td><td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
				echo '<input type = "hidden" name = "orderitemid" value = "'. $rowo['ticketdetailkey'] .'">';
				echo '<input type="submit" name="DeleteItem" value="Delete">';
				echo '</form></td></tr>';
				}
			?>
		<tr>
			<th>Total:</th>
			<th><?php echo $ordertotal; ?></th>
		</tr>
		</table>
		<?php
			if (isset($_POST['NoteEntry']))
			{
			$sqlselectoi = "SELECT ticketdetail.*, menuitem.menuitemname 
				from ticketdetail, menuitem
				WHERE menuitem.menuitemkey = ticketdetail.menuitemkey
				AND ticketdetail.ticketkey = :bvorderid
				AND ticketdetail.ticketdetailKey = :bvorderitemid";
			$resultoi = $db->prepare($sqlselectoi);
			$resultoi->bindValue(':bvorderid', $formfield['fforderid']);
			$resultoi->bindvalue(':bvorderitemid', $_POST['orderitemid']);
			$resultoi->execute();
			$rowoi = $resultoi->fetch();
			
			echo '</td><td>';
			echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
			echo '<table>';
			echo '<tr><td>Note: <input type = "text" name = "newnote" value = "'. $rowoi['ticketdetailnote'] . '"></td></tr>';
			echo '<tr><td>';
			echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
			echo '<input type = "hidden" name = "orderitemid" value = "'. $rowoi['ticketdetailkey'] .'">';
			echo '<input type="submit" name="UpdateItem" value="Update Item"></td></tr></table>';
			}
			?>
		
		</td></tr>
	</table>
	<br><br>
<?php
	echo '<form action = "menufinish.php" method = "post">';
	echo '<input type = "hidden" name = "orderid" value = "'. $formfield['fforderid'] .'">';
	echo '<input type="submit" name="CompleteOrder" value="Complete Order">';
	echo '<br/><br/><br/>';
	echo '</form>';

//visible
include_once 'footer.php';
?>