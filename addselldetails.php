<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>


<?php
include "header.php";
$result = $db->GetResultSet("select * from temporary_sell where invoiceNo=$1",array($_SESSION['sell_invoice']));

$data1 = array();
$data1['sellInvoiceNo'] = $_SESSION['sell_invoice'];
$data1['sellInvoiceDate'] = $_SESSION['sell_invoiceDate'];
$data1['customerName'] = $_SESSION['sell_customerName'];
$data1['customerMobile'] = $_SESSION['sell_customerMobile'];
$data1['grandtotal'] = $_SESSION['grandtotal'] - $_POST['discount'];
$result1 = $db->Insert("tbl_sell_master",$data1);


while($row = mysqli_fetch_array($result))
{
	$productId = $row['productId'];
	$sellPrice = $row['sellPrice'];
	$quantity = $row['quantity'];
	$total = $row['total'];
	
	$data = array();
	$data['productId'] = $productId;
	$data['sellPrice'] = $sellPrice;
	$data['quantity'] = $quantity;
	$data['total'] = $total;
	$data['sellMasterId'] = $result1;
	
	$result2 = $db->Insert("tbl_sell",$data);
}
//delete temporary table
$id = $_SESSION['sell_invoice'];
$deleted = $db->Delete("temporary_sell","invoiceNo=$1",array($id));

unset($_SESSION['sell_invoice']);
unset($_SESSION['sell_invoiceDate']);
unset($_SESSION['sell_customerName']);
unset($_SESSION['sell_customerMobile']);
unset($_SESSION['grandtotal']);
unset($_SESSION['discount']);


header("location:sell_list.php");

?>