<?php 
session_start();
error_reporting(0);
if(!isset($_SESSION['id']))
	header("location:userlogin.php?login=1");
?>


<?php
include "header.php";
$result = $db->GetResultSet("select * from temporary_purchase where invoiceNo=$1",array($_SESSION['purchase_invoice']));

$data1 = array();
$data1['purchaseInvoiceNo'] = $_SESSION['purchase_invoice'];
$data1['purchaseInvoiceDate'] = $_SESSION['purchase_invoiceDate'];
$data1['supplierName'] = $_SESSION['purchase_supplierName'];
$data1['supplierMobile'] = $_SESSION['purchase_supplierMobile'];
$data1['grandtotal'] = $_SESSION['pgrandtotal'];
$result1 = $db->Insert("tbl_purchase_master",$data1);


while($row = mysqli_fetch_array($result))
{
	$productId = $row['productId'];
	$purchasePrice = $row['purchasePrice'];
	$sellPrice = $row['sellPrice'];
	$quantity = $row['quantity'];
	$total = $row['total'];
	
	$data = array();
	$data['productId'] = $productId;
	$data['purchasePrice'] = $purchasePrice;
	$datas['sellPrice'] = $sellPrice;
	$data['quantity'] = $quantity;
	$data['total'] = $total;
	$data['purchaseMasterId'] = $result1;
	
	$datas['productId'] = $productId;
	$datas['sellPrice'] = $sellPrice;
	
	$result2 = $db->Insert("tbl_purchase",$data);
	$result2 = $db->Insert("tbl_sell_price",$datas);
}
$id = $_SESSION['purchase_invoice'];
$deleted = $db->Delete("temporary_purchase","invoiceNo=$1",array($id));

//session unset
unset($_SESSION['purchase_invoice']);
unset($_SESSION['purchase_invoiceDate']);
unset($_SESSION['purchase_supplierName']);
unset($_SESSION['purchase_supplierMobile']);
header("location:purchase_list.php");
?>