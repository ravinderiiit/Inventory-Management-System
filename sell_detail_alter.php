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
	sellInvoiceNo:{
		required:true, 
		},
	sellInvoiceDate:{
		required:true, 
		},	
	},
	customerName:{
		required:true, 
		},
	customerMobile:{
		required:true, 
		},
	messages: {
	sellInvoiceNo:"",
	sellInvoiceDate:"",
	customerName:"",
	customerMobile:"",
	},
	});                
	});
	</script>
<!-- page content -->
<?php
$uid = $_GET['uid'];
$sql = "select * from tbl_sell as tp inner join tbl_sell_master as pm on tp.sellMasterId = pm.id where pm.id=$1";
$result = $db->GetResultSet($sql,array($uid));
$row = mysqli_fetch_array($result);
?>
<div class="right_col" role="main">
          <div class="x_panel">
                  <div class="x_title">
                    <h2>sell Details</h2>
                    <a href="sell_list.php?cmd=Clear"><div class="btn btn-primary pull-right">Back</div></a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php $db->Execute()?>
                      <form class="form-horizontal" name="FORMNAME1" id="FORMNAME1" method="post">
                       <div class="form-group">
                        <label for="sellInvoiceNo" class="control-label col-md-1 col-sm-3 col-xs-12">Invoice Number</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="sellInvoiceNo" id="sellInvoiceNo" <?=$db->disableit?> value="<?php echo $row["sellInvoiceNo"]; ?>" />
                        </div>
                      </div>
                 
                      <div class="form-group">
                        <label for="sellInvoiceDate" class="control-label col-md-1 col-sm-3 col-xs-12">Invoice Date</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="date" class="form-control" required  name="sellInvoiceDate" id="sellInvoiceDate" <?=$db->disableit?> value="<?php echo $row["sellInvoiceDate"];
						  ?>" />
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label for="customerName" class="control-label col-md-1 col-sm-3 col-xs-12">customer Name</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="customerName" id="customerName" <?=$db->disableit?> value="<?php echo $row["customerName"]; ?>" />
                        </div>
                      </div>
                       
					   <div class="form-group">
                        <label for="customerMobile" class="control-label col-md-1 col-sm-3 col-xs-12">customer Mobile</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="customerMobile" id="customerMobile" <?=$db->disableit?> value="<?php echo $row["customerMobile"]; ?>" />
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
						$uid = $_GET['uid'];
						$sql4 = "select * from tbl_sell as tp inner join tbl_product_master as pm on pm.id = tp.productId where tp.sellMasterId=$1";
						$result4 = $db->GetResultSet($sql4,array($uid));
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
						<!--<td><a href = "sell_alter.php?del=1&pid=<?php echo $row4['productId']; ?>&invoice=<?php echo $_SESSION['sell_invoice']; ?>"><font color="red">Delete</font></a></td>-->
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