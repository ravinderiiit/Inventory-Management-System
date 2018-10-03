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
<div class="right_col" role="main">
	<div class="x_panel">
		<div class="x_title">
			<h2>Welcome <?php echo $_SESSION['userName']; ?></h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<h1>INVENTORY MANAGEMENT SYSTEM</h1>
		</div>
	</div>
</div>