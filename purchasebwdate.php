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

$sql = "select prm.productName, sum(quantity), prm.unit from tbl_product_master as prm left join (tbl_purchase as tp inner join tbl_purchase_master as pm on tp.purchaseMasterId = pm.id) on prm.id = tp.productId where pm.purchaseInvoiceDate between $1 and $2 group by(prm.id)";

if(!empty($_POST))
{
	$datefrom = $_POST['datefrom'];
	$dateupto = $_POST['dateupto'];
	
	$df = strtotime($datefrom);
	$df_day = date('Y-m-d',$df);
	
	$du = strtotime($dateupto);
	$du_day = date('Y-m-d',$du);
}
else
{
	$df_day = " ";
	$du_day = " ";
	$datefrom =" ";
	$dateupto = " ";
}

$result = $db->GetResultSet($sql,array($df_day,$du_day));
?>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="right_col" role="main" style="width=100%">
	<div class="x_panel">
		<div class="x_title">
			<h2>purchase on Duration</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
	<form method="post" class="from form-resonsive">
	<label>From : </label>
	<input type="date" name="datefrom" value="<?php echo $df_day; ?>" class="form-control"><br><br>
	<label>Upto : </label>
	<input type="date" name="dateupto" value="<?php echo $du_day; ?>" class="form-control"><br><br>
	<button type="submit" class="btn btn-success">Submit</button>
</form>

<br>
<?php 
if(!empty($_POST))
{
	?>
<table class="table table-responsive">
	<thead>
	<tr>
		<th>Product Name</th>
		<th>Unit</th>
		<th>Total Purchase</th>
		
	</tr>
	</thead>
	<tbody>
	<?php
	while($row = mysqli_fetch_array($result))
	{
	?>
		<tr>
			<td><?php echo $row['productName']; ?></td>
			<td><?php echo $row['unit']; ?></td>
			<td><?php echo $row['sum(quantity)']; ?></td>
			
		</tr>
	<?php
	}
	?>
	</tbody>
	</table>
<?php
}
?>
		</div>
	</div>
</div>