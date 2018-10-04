<?php

class EmployeeCore extends Database
{
	function Execute()
	{
		 if(strtolower($this->PAGEFILENAME)=="product_list")
		 {
		 	$this->EmployeeList();
		 }

		 if(strtolower($this->PAGEFILENAME)=="employee_alter")
		 {
		 	$this->EmployeeAlter();
		 }
		 
	}
	
	function EmployeeList()
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
			$whereClause.=" and (name like $2 or city like $2 or address like $2)";
			$args[1]='%'.$key_word.'%';		
			$_SESSION[$this->PAGEFILENAME.'_where']=$whereClause;
			$_SESSION[$this->PAGEFILENAME.'_args']=$args;
		}

		if(isset($_SESSION[$this->PAGEFILENAME.'_where']))
		{
			$whereClause=$_SESSION[$this->PAGEFILENAME.'_where'];
			$args=$_SESSION[$this->PAGEFILENAME.'_args'];
		}
		/*echo $this->GetDataList("tbl_employee","tbl_employee", "id,name as 'Employee Name',mobile_no as 'Mobile No.',City,Address,Salary,joining_date as 'Joining Date'",$whereClause,$args, "id desc","employee_alter", "employee_alter");*/
		
		echo $this->GetDataList("tbl_product_master","tbl_product_master", "id,productName as 'Prodcut Name',unit as 'Unit'",$whereClause,$args, "id desc","employee_alter", "employee_alter");
	}

	function EmployeeAlter()
	{
		$this->CheckURL("tbl_product_master");
		if(isset($_POST["btn_submit"]))
		{
			if($this->ButtonOperation=="View")
                header("location:product_list.php");

            $data=array(
                "productName"=>$_POST["name"],
                "unit"=>$_POST["unit"]
            );

            if($this->ButtonOperation=="New")
            {
                $data["entry_date"]=$this->SysDateTime();
                echo $last_id=$this->Insert("tbl_employee", $data);
                if($last_id>0)
                    header("location:product_list.php");
            }

            if($this->ButtonOperation=="Edit")
            {
				//Using MD5 URL String Value
				$rows_affected=$this->Update("tbl_employee", $data,'md5(id)=$1',array($_REQUEST["uid"]));

				//Using Plain ID
				//$rows_affected=$this->Update("tbl_employee", $data,'id=$1',array($this->TempRs["id"]));
                header("location:product_list.php");
            }
        }
	}
}

?>