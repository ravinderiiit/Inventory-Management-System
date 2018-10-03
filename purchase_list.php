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
<!-- page content -->
<div class="right_col" role="main">
          <div class="x_panel">
                  <div class="x_title">
                    <h2>Purchase List</h2>
                    <a href="purchase_alter.php"><div class="btn btn-primary pull-right">Add New</div></a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php
                  //$emp->Error("Unable to connect to the database.");
                  //$emp->Message("Connection established successfully.");
				  	$db->Execute();
				  ?>
                  </div>
                </div>
        </div>
<!-- /page content -->
      </div>
    </div>
  </body>
</html>