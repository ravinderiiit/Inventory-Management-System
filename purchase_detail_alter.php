<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>


<?php include "header.php";
include "disable_session_sell.php";
include "disable_session_purchase.php";
?>

 <script type="text/javascript">
	
	$(document).ready(function() {   
	addValidator();             
	$("#FORMNAME1").validate({
	rules: { 
	purchaseInvoiceNo:{
		required:true, 
		},
	purchaseInvoiceDate:{
		required:true, 
		},	
	},
	supplierName:{
		required:true, 
		},
	supplierMobile:{
		required:true, 
		},
	messages: {
	purchaseInvoiceNo:"",
	purchaseInvoiceDate:"",
	supplierName:"",
	supplierMobile:"",
	},
	});                
	});
	</script>
<!-- page content -->
<?php
$uid = $_GET['uid'];
$sql = "select * from tbl_purchase as tp inner join tbl_purchase_master as pm on tp.purchaseMasterId = pm.id where pm.id=$1";
$result = $db->GetResultSet($sql,array($uid));
$row = mysqli_fetch_array($result);
?>
<div class="right_col" role="main">
          <div class="x_panel">
                  <div class="x_title">
                    <h2>purchase Details</h2>
                    <a href="purchase_list.php?cmd=Clear"><div class="btn btn-primary pull-right">Back</div></a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php $db->Execute()?>
                      <form class="form-horizontal" name="	" id="FORMNAME1" method="post">
                       <div class="form-group">
                        <label for="purchaseInvoiceNo" class="control-label col-md-1 col-sm-3 col-xs-12">Invoice Number</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="purchaseInvoiceNo" id="purchaseInvoiceNo" <?=$db->disableit?> value="<?php echo $row["purchaseInvoiceNo"];?>" />
                        </div>
                      </div>
                 
                      <div class="form-group">
                        <label for="purchaseInvoiceDate" class="control-label col-md-1 col-sm-3 col-xs-12">Invoice Date</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="date" class="form-control" required  name="purchaseInvoiceDate" id="purchaseInvoiceDate" <?=$db->disableit?> value="<?php echo $row["purchaseInvoiceDate"]; ?>" />
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label for="supplierName" class="control-label col-md-1 col-sm-3 col-xs-12">supplier Name</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="supplierName" id="supplierName" <?=$db->disableit?> value="<?php echo $row["supplierName"]; ?>" />
                        </div>
                      </div>
                       
					   <div class="form-group">
                        <label for="supplierMobile" class="control-label col-md-1 col-sm-3 col-xs-12">supplier Mobile</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="supplierMobile" id="supplierMobile" <?=$db->disableit?> value="<?php echo $row["supplierMobile"]; ?>" />
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label for="supplierMobile" class="control-label col-md-1 col-sm-3 col-xs-12">GrandTotal</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="grandTotal" id="grandtotal" <?=$db->disableit?> value="<?php echo $row["grandTotal"]; ?>" />
                        </div>
                      </div>
					  
					  
                       <div class="form-group">
                          <div class="col-md-1 col-sm-9 col-xs-12">&nbsp;</div>
                           <div class="col-md-2 col-sm-9 col-xs-12">
                           <input type="submit" name="btn_submit" value="<?=$db->ButtonCaption?>" class="btn btn-success"/>
                       </div>
                       </div>
                      </form>
                  </div>
                </div>
				
				
				<div class="x_panel">
				  <div class="x_content" style="font-size:15px;padding-left:15px;">
					<?php
						$sql4 = "select * from tbl_purchase as tp inner join tbl_product_master as pm on pm.id = tp.productId where tp.purchaseMasterId=$1";
						$result4 = $db->GetResultSet($sql4,array($uid));
					?>
					<table class="table table-responsive">
						<thead>
							<th>Product Name</th>
							<th>Purchase Price</th>
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
						<td><?php echo $row4['purchasePrice']; ?></td>
						<td><?php echo $row4['quantity']; ?></td>
						<td><?php echo $row4['unit']; ?></td>
						<td><?php echo $row4['quantity'] * $row4['purchasePrice']; ?></td>
						<!--<td><a href = "purchase_alter.php?del=1&pid=<?php echo $row4['productId']; ?>&invoice=<?php echo $_SESSION['purchase_invoice']; ?>"><font color="red">Delete</font></a></td> -->
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
					<div class="clearfix"></div>
				  </div> 
			</div>
</div>
<!-- /page content -->

       
      </div>
    </div>
  </body>
</html>