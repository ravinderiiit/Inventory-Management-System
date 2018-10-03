<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>


<?php include "header.php";
include "disable_session_purchase.php";
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
$last_id = 0;
$sql1 = "select * from tbl_product_master";
$result1 = $db->GetResultSet($sql1,array());


if(!empty($_POST['price']) && !empty($_POST['productId']))
{
	$data['productId'] = $_POST['productId'];
	$data['sellPrice'] = $_POST['price'];
	
	$last_id = $db->Insert("tbl_sell_price",$data);
}




?>

<div class="right_col" role="main">
          <div class="x_panel">
                 <div class="x_title">
                    <h2>Add Sell Price</h2>
                    <a href="index.php"><div class="btn btn-primary pull-right">Back</div></a>
				</div>
				<div class="x_content">
				<?php if($last_id>0)echo "<font color=green>New Price Added Successfully</font>"; ?>
				<form class="form-horizontal" name="FORMNAME1" id="FORMNAME1" method="post" action="sellprice_alter.php">
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
							<label for="quantity" class="control-label col-md-1 col-sm-3 col-xs-12">New Price</label>
							<div class="col-md-4 col-sm-9 col-xs-12">
							  <input type="text" class="form-control" required  name="price" id="quantity" <?=$db->disableit?> value="" />
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
</div>
<!-- /page content -->

       
</div>
</body>