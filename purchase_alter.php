<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>

<?php include "header.php";
include "disable_session_sell.php";
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
	purchasePrice:{
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
	purchasePrice:"",
	quantity:"",
	total:"",
	},
	});                
	});
	</script>
<!-- page content -->
<?php
$sql1 = "select * from tbl_product_master";
$result1 = $db->GetResultSet($sql1,array());
?>

<?php
	if(!empty($_POST['purchaseInvoiceNo']))
	{
		$purchaseInvoiceNo = $_POST['purchaseInvoiceNo'];
		$_SESSION['purchase_invoice'] = $purchaseInvoiceNo;
	}
	else
	$purchaseInvoiceNo = " ";

	if(!empty($_POST['purchaseInvoiceDate']))
	{
		$purchaseInvoiceDate = $_POST['purchaseInvoiceDate'];
		$_SESSION['purchase_invoiceDate'] = $purchaseInvoiceDate;
	}
	else
	$purchaseInvoiceDate = " ";

	if(!empty($_POST['supplierName']))
	{
		$supplierName = $_POST['supplierName'];
		$_SESSION['purchase_supplierName'] = $supplierName;
	}
	else
	$supplierName = " ";

if(!empty($_POST['supplierMobile']))
{
		$supplierMobile = $_POST['supplierMobile'];
		$_SESSION['purchase_supplierMobile'] = $supplierMobile;
}
	else
	$supplierMobile = " ";

if(!isset($_SESSION['pgrandtotal']))
	$_SESSION['pgrandtotal'] = 0;


if(isset($_GET['del']))
{
	$sql = "select * from temporary_purchase where productId=$1 and invoiceNo=$2";
	$result = $db->GetResultSet($sql,array($_GET['pid'],$_GET['invoice']));
	$row = mysqli_fetch_array($result);
	$_SESSION['pgrandtotal'] = $_SESSION['pgrandtotal'] - $row['total'];
	$deleted = $db->Delete("temporary_purchase","productId=$1 and invoiceNo=$2",array($_GET['pid'],$_GET['invoice']));
	$supplierMobile = $_SESSION['purchase_supplierMobile'];
	$supplierName = $_SESSION['purchase_supplierName'];
	$purchaseInvoiceDate = $_SESSION['purchase_invoiceDate'];
	$purchaseInvoiceNo = $_SESSION['purchase_invoice'];
}

//add to temporary table
if(!empty($_POST['productId']))
{
$data = array();
$productId = $_POST['productId'];
$purchasePrice = $_POST['purchasePrice'];
$sellPrice = $_POST['sellprice'];
$quantity =  $_POST['quantity'];
$total = $purchasePrice * $quantity;
$invoiceNo = $_SESSION['purchase_invoice'];

$data['productId'] = $productId;
$data['purchasePrice'] = $purchasePrice;
$data['sellPrice'] = $sellPrice;
$data['quantity'] = $quantity;
$data['total'] = $total;
$_SESSION['pgrandtotal'] = $_SESSION['pgrandtotal'] + $total;
$data['invoiceNo'] = $invoiceNo;
$result = $db->Insert("temporary_purchase",$data);
}
?>


<div class="right_col" role="main">

          <div class="x_panel">
                 <div class="x_title">
                    <h2>Purchase Details</h2>
                    <a href="purchase_list.php"><div class="btn btn-primary pull-right">Back</div></a>
					<a href="addpurchasedetails.php"><div class="btn btn-success pull-right">Save</div></a>
					
				</div>
				<div class="x_content">
				<form class="form-horizontal" name="FORMNAME1" id="FORMNAME1" method="post" action="purchase_alter.php">
					 <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>Invoice No</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="purchaseInvoiceNo" id="purchaseInvoiceNo" <?=$db->disableit?> value="<?php echo $purchaseInvoiceNo; ?>" />
                        </div>
						
						<div class="col-md-3 col-sm-9 col-xs-12"><label>Invoice Date</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <input type="date" class="form-control" required  name="purchaseInvoiceDate" id="purchaseInvoiceDate" <?=$db->disableit?> value="<?php echo $purchaseInvoiceDate; ?>" />
                        </div>
						
						
                      </div>
					  <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>Supplier Name</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="supplierName" id="supplierName" <?=$db->disableit?> value="<?php echo $supplierName; ?>" />
                        </div>
						
						<div class="col-md-3 col-sm-9 col-xs-12"><label>Supplier Mobile</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <input type="text" class="form-control" required  name="supplierMobile" id="supplierMobile" <?=$db->disableit?> value="<?php echo $supplierMobile; ?>" />
                        </div>	
                      </div>
					  <div class="row">
					  <div class="col-md-3 col-sm-9 col-xs-12"><label>GRAND TOTAL</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="grandtotal" id="grandtotal" <?=$db->disableit?> value="<?php echo $_SESSION['pgrandtotal']; ?>" />
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
							<label for="purchasePrice" class="control-label col-md-1 col-sm-3 col-xs-12">purchase Price</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							  <input type="text" class="form-control" required  name="purchasePrice" id="purchasePrice" <?=$db->disableit?> value="" />
							</div>
						  </div>
						  
						  <div class="form-group">
							<label for="quantity" class="control-label col-md-1 col-sm-3 col-xs-12">Quantity</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							  <input type="text" class="form-control" required  name="quantity" id="quantity" <?=$db->disableit?> value="" />
							</div>
						  </div>
						  
						  <div class="form-group">
							<label for="sellprice" class="control-label col-md-1 col-sm-3 col-xs-12">Sell Price</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							  <input type="text" class="form-control" required  name="sellprice" id="quantity" <?=$db->disableit?> value="" />
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
					if(isset($_SESSION['purchase_invoice']))
					{
					?>
					
				  <div class="x_content" style="font-size:15px;padding-left:15px;">
					<?php
						$invoice = $_SESSION['purchase_invoice'];
						$sql4 = "select * from temporary_purchase as tp inner join tbl_product_master as pm on pm.id = tp.productId where tp.invoiceNo=$1 order by tp.id DESC;";
						$result4 = $db->GetResultSet($sql4,array($invoice));
					?>
					<table class="table table-responsive">
						<thead>
							<th>Product Name</th>
							<th>Purchase Price</th>
							<th>Qty</th>
							<th>Unit</th>
							<th>Sell price</th>
							<th>Total</th>
						</thead>
						<tbody>
						<?php 
						while($row4 = mysqli_fetch_array($result4))
						{
						?>
						<tr>
						<td><?php echo $row4['productName']; ?></td>
						<td><?php echo $row4['purchasePrice']; ?></td>
						<td><?php echo $row4['quantity']; ?></td>
						<td><?php echo $row4['unit']; ?></td>
						<td><?php echo $row4['sellPrice']; ?></td>
						<td><?php echo $row4['quantity'] * $row4['purchasePrice']; ?></td>
						<td><a href = "purchase_alter.php?del=1&pid=<?php echo $row4['productId']; ?>&invoice=<?php echo $_SESSION['purchase_invoice']; ?>"><font color="red">Delete</font></a></td>
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