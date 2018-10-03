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
<script>
function noBack() {
	if(history.length>=0) {
	//alert('Please use navigational links (Inbox, Cancel) instead of the browser back button.');
	history.go(+1)
	}
}
</script>
<!-- page content -->
<body onLoad="JavaScript:noBack();">
<div class="right_col" role="main">

          <div class="x_panel">
                 <div class="x_title">
                    <h2>Sale Details</h2>
                    <a href="sell_alter.php"><div class="btn btn-primary pull-right">Back</div></a>
					
				</div>
				<div class="x_content">
					 <div class="row">
					  <div class="col-md-2 col-sm-9 col-xs-12"><label>Invoice No</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <?php echo $_SESSION['sell_invoice']; ?>
                        </div>
					</div>
						<div class="row">
						<div class="col-md-2 col-sm-9 col-xs-12"><label>Invoice Date</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <?php echo $_SESSION['sell_invoiceDate']; ?>
                        </div>
                      </div>
					  <div class="row">
					  <div class="col-md-2 col-sm-9 col-xs-12"><label>customer Name</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                          <?php echo $_SESSION['sell_customerName']; ?>
                        </div>
						</div>
						<div class="row">
						<div class="col-md-2 col-sm-9 col-xs-12"><label>customer Mobile</label></div>
                       
                        <div class="col-md-3 col-sm-9 col-xs-12">
                           <?php echo $_SESSION['sell_customerMobile']; ?>
                        </div>	
                      </div>
					  <form method="post" action="addselldetails.php">
					  <div class="row">
					  <div class="col-md-2 col-sm-9 col-xs-12"><label>GRAND TOTAL</label></div>
                      <div class="col-md-3 col-sm-9 col-xs-12">
                           <?php echo $_SESSION['grandtotal']; ?>
                      </div>
					  <div class="col-md-2 col-sm-9 col-xs-12"><label>DISCOUNT</label></div>
						<div class="col-md-2 col-sm-9 col-xs-10">
                          <input type="text" class="form-control" required  name="discount" id="discount" <?=$db->disableit?> value="0" />
                        </div>
						<div class="col-md-1 col-sm-9 col-xs-12">
						</div>
						<div class="col-md-2 col-sm-9 col-xs-12">
							   <input type="submit" name="btn_submit" value="Confirm" class="btn btn-success"/>
							</div>
							
                      </div>
					  </form>
						 
                </div>
			</div>
			<!--show item added-->
			<div class="x_panel">
				 <div class="x_title">
                    <h2>ITEMS ADDED</h2>
					
				</div>
				
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
						</tr>
						
						
					<div class="clearfix"></div>
				  </div>
				  <?php
					}
					?>
					</tbody>
					</table>
			</div>
<!-- /page content -->

       
</div>
</body>