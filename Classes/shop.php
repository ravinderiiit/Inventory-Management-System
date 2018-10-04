<?php
	include("Database/class_database.php");
	class Shop extends Database
	{
		function StoreProduct()
		{
			$data = array();
			$pname = $_POST['pname'];
			$unit = $_POST['unit'];
			
			$data['productName'] = $pname;
			$data['unit'] = $unit;
			
			return $data;
		}
		
		function StorePurchase()
		{
			$data = array();
			$invoiceno = $_POST['invoiceno'];
			$invoicedate = $_POST['invoicedate'];
			$product = $_POST['product'];
			$sname = $_POST['sname'];
			$smobile = $_POST['smobile'];
			$pprice = $_POST['pprice'];
			$quantity =  $_POST['quantity'];
			$total = $pprice * $quantity;;
			
			$data['purchaseInvoiceNo'] = $invoiceno;
			$data['purchaseInvoiceDate'] = $invoicedate;
			$data['productId'] = $product;
			$data['supplierName'] = $sname;
			$data['supplierMobile'] = $smobile;
			$data['purchasePrice'] = $pprice;
			$data['quantity'] = $quantity;
			$data['total'] = $total;
			return $data;
		}
		function StoreSell()
		{
			$data = array();
			$invoiceno = $_POST['invoiceno'];
			$invoicedate = $_POST['invoicedate'];
			$product = $_POST['product'];
			$cname = $_POST['cname'];
			$cmobile = $_POST['cmobile'];
			$sprice = $_POST['sprice'];
			$quantity =  $_POST['quantity'];
			$total = $sprice * $quantity;
			
			$data['sellInvoiceNo'] = $invoiceno;
			$data['sellInvoiceDate'] = $invoicedate;
			$data['productId'] = $product;
			$data['customerName'] = $cname;
			$data['customerMobile'] = $cmobile;
			$data['sellPrice'] = $sprice;
			$data['quantity'] = $quantity;
			$data['total'] = $total;
			return $data;
		}
	}
?>