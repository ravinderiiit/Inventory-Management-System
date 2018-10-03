<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>


<?php include "header.php";
include "disable_session_purchase.php";
?>
<style>
.row{ margin-bottom:10px;}
</style>
 <script type="text/javascript">
	
	$(document).ready(function() {   
	addValidator();             
	$("#FORMNAME1").validate({
	rules: { 
	productId:{
		required:true, 
		},
	sellPrice:{
		required:true, 
		},
	quantity:{
		required:true, 
		},
	total:{
		required:true, 
		},
	messages: {
	productId:"",
	sellPrice:"",
	quantity:"",
	total:"",
	},
	});                
	});
	function noBack() {
	if(history.length>0) {
	//alert('Please use navigational links (Inbox, Cancel) instead of the browser back button.');
	history.go(+1)
	}
}
	</script>
<body onLoad="JavaScript:noBack();">
<!-- page content -->
<?php
$sql1 = "select * from tbl_product_master";
$result1 = $db->GetResultSet($sql1,array());

?>

<?php
	if(!isset($_SESSION['grandtotal']) && !isset($_GET['del']))
		$_SESSION['grandtotal'] = 0;
	
	if(!empty($_POST['sellInvoiceNo']))
	{ 
		$sellInvoiceNo = $_POST['sellInvoiceNo'];
		$_SESSION['sell_invoice'] = $sellInvoiceNo;
	}
	else
	{
		
		$sellInvoiceNo = " ";
	}

	if(!empty($_POST['sellInvoiceDate']))
	{
		$sellInvoiceDate = $_POST['sellInvoiceDate'];
		$_SESSION['sell_invoiceDate'] = $sellInvoiceDate;
	}
	else
	$sellInvoiceDate = " ";

	if(!empty($_POST['customerName']))
	{
		$customerName = $_POST['customerName'];
		$_SESSION['sell_customerName'] = $customerName;
	}
	else
	$customerName = " ";

if(!empty($_POST['customerMobile']))
{
		$customerMobile = $_POST['customerMobile'];
		$_SESSION['sell_customerMobile'] = $customerMobile;
}
	else
	$customerMobile = " ";


if(isset($_GET['del']))
{
	$sql = "select * from temporary_sell where productId=$1 and invoiceNo=$2";
	$result = $db->GetResultSet($sql,array($_GET['pid'],$_GET['invoice']));
	$row = mysqli_fetch_array($result);
	$_SESSION['grandtotal'] = $_SESSION['grandtotal'] - $row['total'];
	$deleted = $db->Delete("temporary_sell","productId=$1 and invoiceNo=$2",array($_GET['pid'],$_GET['invoice']));
	$customerMobile = $_SESSION['sell_customerMobile'];
	$customerName = $_SESSION['sell_customerName'];
	$sellInvoiceDate = $_SESSION['sell_invoiceDate'];
	$sellInvoiceNo = $_SESSION['sell_invoice'];
}


//add to temporary table
if(!empty($_POST['productId']))
{	
$data = array();
$productId = $_POST['productId'];

//check for stock
$sql3 = "select sum(tp.quantity) as sum_purchase, pm.unit from tbl_purchase as tp inner join tbl_product_master as pm on tp.productId = pm.id where productId=$1";
$result3 = $db->GetResultSet($sql3,array($productId));
$row3 = mysqli_fetch_array($result3);

$sql4 = "select sum(quantity) as sum_sell from tbl_sell where productId=$1";
$result4 = $db->GetResultSet($sql4,array($productId));
$row4 = mysqli_fetch_array($result4);

$sql5 = "select sum(quantity) as sum_temp from temporary_sell where productId=$1";
$result5 = $db->GetResultSet($sql5,array($productId));
$row5 = mysqli_fetch_array($result5);

$stock_avail = $row3['sum_purchase'] - $row4['sum_sell'] - $row5['sum_temp'];
//end checking stock

if($stock_avail < $_POST['quantity'])
	$_SESSION['quantity_error'] =1;
else
{	
unset($_SESSION['quantity_error']);
$data['productId'] = $productId;
$sql_sell = "select sellPrice from tbl_sell_price WHERE productId=$1 order by id desc LIMIT 1";
$result = $db->GetResultSet($sql_sell,array($productId));
$row = mysqli_fetch_array($result);

$quantity =  $_POST['quantity'];
$total = $row['sellPrice'] * $quantity;
$_SESSION['grandtotal'] = $_SESSION['grandtotal'] + $total;
$invoiceNo = $_SESSION['sell_invoice'];


$data['quantity'] = $quantity;
$data['total'] = $total;
$data['invoiceNo'] = $invoiceNo;

$data['sellPrice'] = $row['sellPrice'];
$result = $db->Insert("temporary_sell",$data);
}
}
?>

<div class="right_col" role="main">

          <div class="x_panel">
                 <div class="x_title">
                    <h2>Sale Details</h2>
                    <a href="sell_list.php"><div class="btn btn-primary pull-right">Back</div></a>
					<a href="sellvia.php"><div class="btn btn-success pull-right">Save</div></a>
					
				</div>
				<div class="x_content">
				<form class="form-horizontal" name="FORMNAME1" id="FORMNAME1" method="post" action="sell_alter.php">
					 <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>Invoice No</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
						<?php
						
						$sqlinvoice = "select max(sellInvoiceNo) as maximum from tbl_sell_master order by id desc LIMIT 1";
						$resultinvoice = $db->GetResultSet($sqlinvoice,array());
						$row = mysqli_fetch_array($resultinvoice);
						$sellInvoiceNo = $row['maximum']+1;
						
						?>
                          <input type="text" class="form-control" required readonly name="sellInvoiceNo" id="sellInvoiceNo" <?=$db->disableit?> value="<?php echo $sellInvoiceNo; ?>" />
                        </div>
						
						<div class="col-md-3 col-sm-9 col-xs-12"><label>Invoice Date</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <input type="date" class="form-control" required name="sellInvoiceDate" id="sellInvoiceDate" <?=$db->disableit?> value="<?php echo $sellInvoiceDate; ?>" />
                        </div>
						
						
                      </div>
					  <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>customer Name</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="customerName" id="customerName" <?=$db->disableit?> value="<?php echo $customerName; ?>" />
                        </div>
						
						<div class="col-md-3 col-sm-9 col-xs-12"><label>customer Mobile</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <input type="text" class="form-control" required  name="customerMobile" id="customerMobile" <?=$db->disableit?> value="<?php echo $customerMobile; ?>" />
                        </div>	
                      </div>
					  
					  <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>GRAND TOTAL</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="grandtotal" id="grandtotal" <?=$db->disableit?> value="<?php echo $_SESSION['grandtotal']; ?>" />
                        </div>
                       
                      </div>
					  <br><br>
						  
						  
						  <div class="form-group">
							<label for="productId" class="control-label col-md-1 col-sm-3 col-xs-12">Product</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							<select name="productId" id="productId" required class="form-control" <?=$db->disableit?> value="">
							<?php
							while($row1 = mysqli_fetch_array($result1))
							{
							?>
							<option value="<?php echo $row1['id']; ?>"><?php echo $row1['productName']; ?></option>
							<?php
							}
							?>
							</select>
							</div>
						  </div>
						  
						  <div class="form-group">
							<label for="quantity" class="control-label col-md-1 col-sm-3 col-xs-12">Quantity</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							  <input type="text" class="form-control" required  name="quantity" id="quantity" <?=$db->disableit?> value="" />
							  <?php if(isset($_SESSION['quantity_error']))echo "<font color=red>".$stock_avail.$row3['unit']." available</font>"; ?>
							</div>
						  </div>
						  
						   <div class="form-group">
							  <div class="col-md-1 col-sm-9 col-xs-12">&nbsp;</div>
							   <div class="col-md-2 col-sm-9 col-xs-12">
							   <input type="submit" name="btn_submit" value="Add" class="btn btn-success"/>
						   </div>
						   </div>
				</form>
                  </div>
			</div>
			<!--show item added-->
			<div class="x_panel">
				<?php
					if(isset($_SESSION['sell_invoice']))
					{
					?>
					
				  <div class="x_content" style="font-size:15px;padding-left:15px;">
					<?php
						$invoice = $_SESSION['sell_invoice'];
						$sql4 = "select * from temporary_sell as tp inner join tbl_product_master as pm on pm.id = tp.productId where tp.invoiceNo=$1 order by tp.id DESC;";
						$result4 = $db->GetResultSet($sql4,array($invoice));
					?>
					<table class="table table-responsive">
						<thead>
							<th>Product Name</th>
							<th>sell Price</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>Total</th>
						</thead>
						<tbody>
						<?php 
						while($row4 = mysqli_fetch_array($result4))
						{
						?>
						<tr>
						<td><?php echo $row4['productName']; ?></td>
						<td><?php echo $row4['sellPrice']; ?></td>
						<td><?php echo $row4['quantity']; ?></td>
						<td><?php echo $row4['unit']; ?></td>
						<td><?php echo $row4['quantity'] * $row4['sellPrice']; ?></td>
						<td><a href = "sell_alter.php?del=1&pid=<?php echo $row4['productId']; ?>&invoice=<?php echo $_SESSION['sell_invoice']; ?>"><font color="red">Delete</font></a></td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
					<div class="clearfix"></div>
				  </div>
				  <?php
					}
					?>
			</div>
<!-- /page content -->

       
</div>
</body>