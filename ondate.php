<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>



<?php
include "header.php";
include "disable_session_sell.php";
include "disable_session_purchase.php";
if(!empty($_POST))
{
$sqlpr = "select * from tbl_product_master";
$resultpr = $db->GetResultSet($sqlpr,array());

$dateupto = $_POST['dateupto'];
$day = strtotime($dateupto);
$day_converted = date('Y-m-d', $day);
}
else 
	$day_converted = " ";

?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="right_col" role="main">
	<div class="x_panel">
		<div class="x_title">
			<h2>Stock on Date</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="row">
<div class="col-md-1">
</div>
<div class="col-md-4">
<form method = "post">
<label>Enter Date :</label>
<input type="date" name="dateupto" value="<?php echo $day_converted; ?>" class="form-control"><br><br>
<button type="submit" class="btn btn-success">Submit</button> 
</form>
</div>
</div>

<?php
if(!empty($_POST))
{
?>
<div class="row">
<div class="col-md-1">
</div>
<div class="col-md-9">
<table class="table table-responsive">
	<thead>
	<tr>
		<th>Product Name</th>
		<th>Current Stock</th>
	</tr>
	</thead>
	<?php
	while($row = mysqli_fetch_array($resultpr))
	{
		$sqlp = "select prm.productName, sum(quantity) as purchase_sum from tbl_purchase as tp inner join tbl_purchase_master as pm on pm.id = tp.purchaseMasterId inner join tbl_product_master as prm on tp.productId = prm.id where pm.purchaseInvoiceDate<=$1 and prm.id=$2 group by(prm.id)";
		
		$sqls = "select pm.productName, sum(quantity) as sell_sum from tbl_sell as ts inner join tbl_sell_master as tsm on tsm.id = ts.sellMasterId inner join tbl_product_master as pm on ts.productId = pm.id where tsm.sellInvoiceDate<=$1 and pm.id=$2 group by(pm.id)";
		
		$resultp = $db->GetResultSet($sqlp,array($day_converted,$row['id']));
		$results = $db->GetResultSet($sqls,array($day_converted,$row['id']));
		
		$rowp = mysqli_fetch_array($resultp);
		$rows = mysqli_fetch_array($results);
		
		$stock = $rowp['purchase_sum'] - $rows['sell_sum'];
	?>
		<tr>
			<td><?php echo $row['productName']; ?></td>
			<td><?php echo $stock; ?></td>
		</tr>
	<?php
}
	?>
</table>
</div>
</div>
<?php
}
?>
		</div>
	</div>
</div>
