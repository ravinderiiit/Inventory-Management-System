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
	productName:{
		required:true, 
		},
	unit:{
		required:true, 
		},	
	},
	messages: {
	productName:"",
	unit:"",
	},
	});                
	});
	</script>
	<?php 
		$sql = "select * from tbl_product_master where id=$1";
		$result = $db->GetResultSet($sql,array(isset($_GET['uid'])?$_GET['uid']:""));
		$row = mysqli_fetch_array($result);
	?>
<!-- page content -->
<div class="right_col" role="main">
          <div class="x_panel">
                  <div class="x_title">
                    <h2>Product Details</h2>
                    <a href="product_list.php?cmd=Clear"><div class="btn btn-primary pull-right">Back</div></a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php $db->Execute()?>
                      <form class="form-horizontal" name="FORMNAME1" id="FORMNAME1" method="post">
                       <div class="form-group">
                        <label for="productName" class="control-label col-md-1 col-sm-3 col-xs-12">Product Name</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="productName" id="productName" <?=$db->disableit?> value="<?php echo $row['productName']; ?>" />
                        </div>
                      </div>
                 
                      <div class="form-group">
                        <label for="unit" class="control-label col-md-1 col-sm-3 col-xs-12">Unit</label>
                        <div class="col-md-4 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" required  name="unit" id="unit" <?=$db->disableit?> value="<?php echo $row['unit']; ?>" />
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
              </div>
<!-- /page content -->

       
      </div>
    </div>
  </body>
</html>