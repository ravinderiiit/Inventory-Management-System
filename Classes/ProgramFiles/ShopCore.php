<?php
class ShopCore extends Database
{
	function Execute()
	{
			//product
		 if(strtolower($this->PAGEFILENAME)=="product_list")
		 {
		 	$this->ProductList();
		 }

		 if(strtolower($this->PAGEFILENAME)=="product_alter")
		 {
		 	$this->ProductAlter();
		 }
		 //purchases
		 if(strtolower($this->PAGEFILENAME)=="purchase_list")
		 {
		 	$this->PurchaseList();
		 }
		 //sell
		 if(strtolower($this->PAGEFILENAME)=="sell_list")
		 {
		 	$this->SellList();
		 }
		 if(strtolower($this->PAGEFILENAME)=="sell_detail_alter")
		 {
		 	$this->SellDetailAlter();
		 }
		 if(strtolower($this->PAGEFILENAME)=="purchase_detail_alter")
		 {
		 	$this->PurchaseDetailAlter();
		 }
		 
	}
	
	function ProductList()
	{
		$whereClause="status = $1";
		$args[0]=1;

		if(isset($_REQUEST["cmd"]))
		{
			unset($_SESSION[$this->PAGEFILENAME.'_where']);
			unset($_SESSION[$this->PAGEFILENAME.'_args']);
		}


	/*	if(isset($_REQUEST["btnSearch"]))
		{
			$key_word=$_REQUEST["txt_search"];
			$whereClause.=" and (name like $2 or city like $2 or address like $2)";
			$args[1]='%'.$key_word.'%';		
			$_SESSION[$this->PAGEFILENAME.'_where']=$whereClause;
			$_SESSION[$this->PAGEFILENAME.'_args']=$args;
		}*/

	/*	if(isset($_SESSION[$this->PAGEFILENAME.'_where']))
		{
			$whereClause=$_SESSION[$this->PAGEFILENAME.'_where'];
			$args=$_SESSION[$this->PAGEFILENAME.'_args'];
		}*/
		echo $this->GetDataList("tbl_product_master","tbl_product_master", "id,productName as 'Prodcut Name',unit as 'Unit'",$whereClause,$args, "id desc","product_alter", "product_alter");
	}
	
	
	
	function PurchaseList()
	{
		$whereClause="status = $1";
		$args[0]=1;

		if(isset($_REQUEST["cmd"]))
		{
			unset($_SESSION[$this->PAGEFILENAME.'_where']);
			unset($_SESSION[$this->PAGEFILENAME.'_args']);
		}


	if(isset($_REQUEST["btnSearch"]))
		{
			$key_word=$_REQUEST["txt_search"];
			$whereClause.=" and (purchaseInvoiceNo like $2 or purchaseInvoiceDate like $2 or supplierName like $2 or supplierMobile like $2)";
			$args[1]='%'.$key_word.'%';		
			$_SESSION[$this->PAGEFILENAME.'_where']=$whereClause;
			$_SESSION[$this->PAGEFILENAME.'_args']=$args;
		}

		if(isset($_SESSION[$this->PAGEFILENAME.'_where']))
		{
			$whereClause=$_SESSION[$this->PAGEFILENAME.'_where'];
			$args=$_SESSION[$this->PAGEFILENAME.'_args'];
		}
		echo $this->GetDataList("tbl_purchase_master","tbl_purchase_master", "id,purchaseInvoiceNo as 'Invoice No',purchaseInvoiceDate as 'Invoice Date',supplierName as 'Supplier',supplierMobile as 'Mobile',grandTotal as 'Grand Total'",$whereClause,$args, "id desc","purchase_detail_alter", "purchase_list");
	}
	function SellList()
	{
		$whereClause="status = $1";
		$args[0]=1;

		if(isset($_REQUEST["cmd"]))
		{
			unset($_SESSION[$this->PAGEFILENAME.'_where']);
			unset($_SESSION[$this->PAGEFILENAME.'_args']);
		}


		if(isset($_REQUEST["btnSearch"]))
		{
			$key_word=$_REQUEST["txt_search"];
			$whereClause.=" and (sellInvoiceNo like $2 or sellInvoiceDate like $2 or customerName like $2 or customerMobile like $2)";
			$args[1]='%'.$key_word.'%';		
			$_SESSION[$this->PAGEFILENAME.'_where']=$whereClause;
			$_SESSION[$this->PAGEFILENAME.'_args']=$args;
		}

		if(isset($_SESSION[$this->PAGEFILENAME.'_where']))
		{
			$whereClause=$_SESSION[$this->PAGEFILENAME.'_where'];
			$args=$_SESSION[$this->PAGEFILENAME.'_args'];
		}
		echo $this->GetDataList("tbl_sell_master","tbl_sell_master", "id,sellInvoiceNo as 'Invoice No',sellInvoiceDate as 'Invoice Date',customerName as 'Customer',customerMobile as 'Mobile',grandTotal as 'Grand Total'",$whereClause,$args, "id desc","sell_detail_alter", "sell_list");
	}
	function ProductAlter()
	{
		$this->CheckURL("tbl_product_master");
		if(isset($_POST["btn_submit"]))
		{
			if($this->ButtonOperation=="View")
                header("location:product_list.php");

            $data=array(
                "productName"=>$_POST["productName"],
                "unit"=>$_POST["unit"]
            );

            if($this->ButtonOperation=="New")
            {
                echo $last_id=$this->Insert("tbl_product_master", $data);
                if($last_id>0)
                    header("location:product_list.php");
            }

            if($this->ButtonOperation=="Edit")
            {
				//Using MD5 URL String Value
				$rows_affected=$this->Update("tbl_product_master", $data,'id=$1',array($_REQUEST["uid"]));

				//Using Plain ID
				//$rows_affected=$this->Update("tbl_employee", $data,'id=$1',array($this->TempRs["id"]));
                header("location:product_list.php");
            }
        }
	}
	
	function PurchaseDetailAlter()
	{
		$this->CheckURL("tbl_purchase_master");
		if(isset($_POST["btn_submit"]))
		{
			if($this->ButtonOperation=="View")
                header("location:purchase_list.php");

            $data=array(
                "purchaseInvoiceNo"=>$_POST["purchaseInvoiceNo"],
                "purchaseInvoiceDate"=>$_POST["purchaseInvoiceDate"],
				"supplierName"=>$_POST["supplierName"],
				"supplierMobile"=>$_POST["supplierMobile"],
				"grandTotal"=>$_POST["grandTotal"]
            );

            if($this->ButtonOperation=="New")
            {
                echo $last_id=$this->Insert("tbl_purchase_master", $data);
                if($last_id>0)
				{
					$_SESSION['purchase_id'] = $last_id;
                    header("location:purchase_alter.php");
				}
            }

            if($this->ButtonOperation=="Edit")
            {
				//Using MD5 URL String Value
				$rows_affected=$this->Update("tbl_purchase_master", $data,'md5(id)=$1',array($_REQUEST["uid"]));

				//Using Plain ID
				//$rows_affected=$this->Update("tbl_employee", $data,'id=$1',array($this->TempRs["id"]));
                header("location:purchase_list.php");
            }
        }
	}
	function SellDetailAlter()
	{
		$this->CheckURL("tbl_sell_master");
		if(isset($_POST["btn_submit"]))
		{
			if($this->ButtonOperation=="View")
                header("location:sell_list.php");

            $data=array(
                "sellInvoiceNo"=>$_POST["sellInvoiceNo"],
                "sellInvoiceDate"=>$_POST["sellInvoiceDate"],
				"customerName"=>$_POST["customerName"],
				"customerMobile"=>$_POST["customerMobile"],
				"grandTotal"=>$_POST["grandTotal"]
            );

            if($this->ButtonOperation=="New")
            {
                echo $last_id=$this->Insert("tbl_sell_master", $data);
                if($last_id>0)
				{
					$_SESSION['sell_id'] = $last_id;
                    header("location:sell_alter.php");
				}
            }

            if($this->ButtonOperation=="Edit")
            {
				//Using MD5 URL String Value
				$rows_affected=$this->Update("tbl_sell_master", $data,'md5(id)=$1',array($_REQUEST["uid"]));

				//Using Plain ID
				//$rows_affected=$this->Update("tbl_employee", $data,'id=$1',array($this->TempRs["id"]));
                header("location:sell_list.php");
            }
        }
	}
}

?>