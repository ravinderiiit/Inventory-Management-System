<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory System</title>
   <?php include $_SERVER["DOCUMENT_ROOT"]."/template3/common/inc/include.php";
  	$menu_path=SOURCE_ROOT;
	?>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" >
    <a href="<?=SOURCE_ROOT?>/template3/index.php" class="site_title">
	&nbsp;<span>Inventory Management</span></a>
            </div>
            <div class="clearfix"></div>
            <div class="profile">
			<div class="profile_info">
			<span>Welcome,</span>
			<h2 style="font-size:12px;"><?php echo $_SESSION['userName']; ?></h2>
			</div>
			<div class="clr"></div>
			</div>
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>&nbsp;</h3>
                <ul class="nav side-menu">
                  <li class="active"><a href="<?=$menu_path?>/index.php"><i class="fa fa-home"></i> Dashboard </a>
                  
                  </li>
                  <li><a><i class="fa fa-edit"></i>Menu<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <li><a href="<?=$menu_path?>/product_list.php?cmd=Clear">Products</a></li>
					  <li><a href="<?=$menu_path?>/purchase_list.php?cmd=Clear">Purchases</a></li>
					  <li><a href="<?=$menu_path?>/sell_list.php?cmd=Clear">Sells</a></li>
					   <li><a href="<?=$menu_path?>/sellprice_alter.php?cmd=Clear">Update Price</a></li>
					  
                    </ul>
                  </li>
                  
                  <li><a><i class="fa fa-bar-chart-o"></i> Reports <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?=$menu_path?>/currentstock.php?cmd=Clear">Current Stock</a></li>
                      <li><a href="<?=$menu_path?>/ondate.php?cmd=Clear">Stock On Date</a></li>
					  <li><a href="<?=$menu_path?>/purchasebwdate.php?cmd=Clear">Purchase Between Dates</a></li>
					  <li><a href="<?=$menu_path?>/sellbwdate.php?cmd=Clear">Sell Between Dates</a></li>
                    </ul>
					
					
					<li><a href="<?=SOURCE_ROOT?>/dir/change_password.php"> <i class="fa fa-key"></i> Change Password</a></li>
                  </li>
               <!-- <li>
                <a>
                <i class="fa fa-clone"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                <li><a href="#">Level One</a>
                <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                <li class="sub_menu"><a href="#">Level Two</a>
                </li>
                <li><a href="#">Level Two</a>
                </li>
                <li><a href="#">Level Two</a>
                </li>
                </ul>
                </li>
                <li><a href="#">Level One</a>
                </li>
                </ul>
                </li>  -->             
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- top hdr -->
<div class="top_nav">
<div class="nav_menu" style="background:#0A4f8a">
<nav>
<div class="nav toggle" id="toggle">
<a id="menu_toggle"><i class="fa fa-bars" style="color:#ccc"></i></a> 
</div>

<h3 class="soft-tle-show" style="color:#FFFFFF">
Reporting Center &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<a href="?logout=ture"><i class="fa fa-power-off" style="color:#FFFFFF"></i></a>
</h3> 

<div class="col-md-4 col-sm-5 col-xs-12 search_top">
<?php
if($db->URLFILENAME == 'purchase_list.php' || $db->URLFILENAME == 'sell_list.php')
{
?>
<form method="post" action="<?=$db->URLFILENAME?>">
<div class="input-group">
<input type="text" class="form-control" placeholder="Search for..." name="txt_search">
<span class="input-group-btn">
<button class="btn btn-primary" type="submit" name="btnSearch" style="height:30px;margin-left:10px">Go!</button>
</span>
</div>
</form>
<?php
}
?>
</div>

<div class="col-md-2 col-sm-3 col-xs-12 pull-right" >
<ul class="nav navbar-nav navbar-right logoff">
<li>
<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false"  style="color:#fff; font-weight:inherit; font-size:13px;">
<span class="fa fa-user fnt-sze" style="color:#ffffff;"></span> <span style="color:#F6F7F7; ">Test User</span>
<span class="fa fa-angle-down"  style="color:#fff"></span>
</a>
<ul class="dropdown-menu dropdown-usermenu pull-right">
<li><a href="#">Change Password</a></li>
<li><a href="<?=SOURCE_ROOT?>/index.php">Dashboard</a></li>
<li><a href="userlogin.php?logout=1"><i class="fa fa-sign-out"></i> Log Out</a></li>
</ul>
</li>


</ul>
</div>
<div class="clearfix"></div>
</nav>
</div>
</div>
<!-- /top hdr -->
<?php include $_SERVER["DOCUMENT_ROOT"]."/template3/common/inc/include_js.php"?>
