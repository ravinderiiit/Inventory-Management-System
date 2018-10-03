<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>



<?php include "header.php";
include "disable_session_sell.php";
include "disable_session_purchase.php";

$sql = "select * from stock_purchase as sp left join stock_sell as ss on sp.pproduct = ss.sproduct";
$result = $db->GetResultSet($sql,array());
?>
<?php
	include("css/design.html");
?>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<div class="right_col" role="main">
	<div class="x_panel">
		<div class="x_title">
			<h2>Current Stock</h2>
			<a href="index.php"><div class="btn btn-primary pull-right">Back</div></a>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<body>
				<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-9">
				<table class="table table-responsive">
					<thead>
					<tr>
						<th>Product Name</th>
						<th>Total Purchase</th>
						<th>Total Sell</th>
						<th>Current Stock</th>
					</tr>
					</thead>
					<?php
					while($row = mysqli_fetch_array($result))
					{
						$stock = $row['purchase_quantity'] - $row['sell_quantity'];
					?>
						<tr>
							<td><?php echo $row['pproduct']; ?></td>
							<td><?php if($row['purchase_quantity']>0)echo $row['purchase_quantity']; else echo 0;?></td>
							<td><?php if($row['sell_quantity']>0)echo $row['sell_quantity']; else echo 0;?></td>
							<td><?php echo $stock; ?></td>
						</tr>
					<?php
					}
					?>
				</table>
				</div>
				</div>

				</div>
				</div>
</body>
		</div>
	</div>
</div>
