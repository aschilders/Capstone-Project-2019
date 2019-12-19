<?php
	session_start();
	require_once 'header.php';

	if ($_SESSION['signedin'] == 1) {
		if (preg_match('/......1................................./', $_SESSION['permission'])) {
?>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="#">Tickets</a></li>
	<li class="breadcrumb-item active">Select</li>
</ol>
<div class="card">
	<div class="card-header">Select Tickets</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="selectordersTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Date</th>
						<th>Time</th>
						<th>Type</th>
						<th>Customer</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sqlselectt = "SELECT * FROM tickets
												 INNER JOIN customer ON tickets.customerkey = customer.customerkey
												 ORDER BY ticketkey ASC";
					$result = $db->prepare($sqlselectt);
					$result->execute();
					while ( $row = $result-> fetch() ) {
						echo '<tr><td>' . $row['ticketdate'] . '</td><td> ' . $row['tickettime'] .
						'</td><td> ' . $row['tickettype'] . '</td><td> ' . $row['customeremail'] . '</td>
						<td>
							<form name="selectticketform" method="post" action="selectticketdetails.php">
								<input type="hidden" name="ticketkey" value="' . $row['ticketkey'] . '"/>
								<input type="submit" name="selectticketsubmit" value="Select"/>
							</form>
						</td>';
					}
					echo '</tr>';
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready( function () {
    $('#selectordersTable').DataTable();
} );
</script>
<?php
} else {
	echo '<p>You do not have permission to view this page</p>';
}
} else {
	echo '<p>You are not signed in. Click <a href="signin.php">here</a> to sign in.</p>';
}
	require_once 'footer.php';
?>
