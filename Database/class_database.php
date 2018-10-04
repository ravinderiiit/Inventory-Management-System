<?php
  class Database
  {
	  public $con;
	  protected $connectionString = "";
	  protected $lastError="";
	  protected $lastArgs="";
	  protected $lastQuery;
	  protected $ADMIN_PAGE_LIMIT;
	  protected $FRONT_PAGE_LIMIT;
	  protected $default_error_msg;
	  public $URLFILENAME;
	  public $ButtonOperation;
	  public $disableit = "";
	  public $TempRs;
	  public $USERTYPE = 0;
	  public $USER;
	  public $LOGINID;
	  public $SCHOOLID;
	  public $USERNAME;
	  public $FINANCIALYR;
	  public $EMPLOYEE;
	  public $PAGEFILENAME;
	  public $ALTERPAGENAME;
	  public $ButtonCaption;
	   public $db_name;
	  //protected String obj;
	  //protected String linkColor = "#018aa7";
	  public $EPOCH;
	 /* public $SOURCE_ROOT = "";
	  public $IMAGE_PATH = "";*/

	  public $err_no ,$err_str ,$err_file , $err_line;

	  function __construct($host,$username,$password,$db_name)
	  { 
		  //ini_set("display_errors", 0);
		  set_error_handler(array($this, 'ErrorHandler'));
		  date_default_timezone_set('Asia/Calcutta');
		  //set_exception_handler(array($this, 'ExceptionHandler'));
		  try
		  {
			  $EPOCH= mktime(0,0,0,1,1,1970);
			  $this->default_error_msg= "Some error has been occured please contact to Admin";
			  $this->ADMIN_PAGE_LIMIT = 10;
			  $this->FRONT_PAGE_LIMIT = 20;
			  $this->host     = $host;
			  $this->user     = $username;
			  $this->password = $password;
			  $this->db_name   = $db_name;
			  $this->con=mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
			  
			  
			  if (isset($_SESSION["USER"]))
			  {
				  $this->USER = $_SESSION["USER"];
				  $this->USERTYPE =$this->USER["user_type_id"];
				  $this->DEPTTYPE =$this->USER["department_id"];

				  if(isset($_SESSION["login_name"]))
				  {
				   $this->LOGINID = $_SESSION["login_name"];
				  }

				  if(isset($_SESSION["user_name"]))
				  {
				    $this->USERNAME = $_SESSION["user_name"];
				  }

				  if(isset($_SESSION["school_id"]))
				  {
				   $this->SCHOOLID = $_SESSION["school_id"];
				  }

				  if(isset($_SESSION["financial_yr"]))
				  {
				    $this->FINANCIALYR = $_SESSION["financial_yr"];
				  }

			  }
			  $exepage= explode('_list', basename($_SERVER['PHP_SELF']));
			  $this->ALTERPAGENAME=$exepage[0]."_alter";
			  $uri = $_SERVER['PHP_SELF'];
			  $url_arr = explode('/',$uri);
			  $this->URLFILENAME = $url_arr[count($url_arr)-1];
			  $file_arr=explode('.',$this->URLFILENAME);
			  $this->PAGEFILENAME=$file_arr[0];
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("101", $this->default_error_msg, $ex);
		  }
	  }

	  /*
		  Show error message with error number in case of any exceptions
		  @param errorNO - Custom Error No.
		  @param message - Message to be displayed
		  @param exception - Object of Catched Exception
	  */
	  function Notify($errorNO=null, $message=null,$exception=null)
	  {
		  echo "<div class='alert alert-danger'><a class='close' data-dismiss='alert' style='float:right; cursor:default; font-weight:bold'>X</a>  <h4>Warning : </h4>Error No:" . $errorNO . " : " . $message . "<br><br></div>";
		  if(isset($exception))
			  $this->lastError=$exception->getMessage();
	  }

	  function Error($msg=null)
	  {
		  echo "<div class='alert alert-danger' style='margin:0px;'><a class='close' data-dismiss='alert' style='float:right; cursor:default; font-weight:bold'>x</a>  " . $msg . "</div>";
	  }
	  function Message($msg=null)
	  {
		  echo "<div class='alert alert-info'><a class='close' data-dismiss='alert' style='float:right; cursor:default; font-weight:bold'>x</a> " . $msg . "</div>";
	  }
			
		
		
		

	  function ErrorHandler($errno ,$errst ,$errfile , $errline)
	  {

		  $this->err_no=$errno;
		  $this->err_str=$errst;
		  $this->err_file=$errfile;
		  $this->err_line=$errline;
		  $error="<b>Error ($errno)</b>: $errst : $errfile ($errline)";
		  //echo $error;
		  $this->lastError=$error;
		  if(strpos(strtolower($errst),"violates foreign key constraint")> 0 && strpos(strtolower($this->lastQuery),"elete")>0)
		  	$this->Error("Record already in use. It can not be deleted.");
		  else
		  	$this->Error($error);

		  //throw new Exception($errst);
	  }
   
   function IND_money_format($money){
			$dec=explode('.',$money);
			$money=$dec[0];
			$len = strlen($money);
			$m = '';
			$money = strrev($money);
			for($i=0;$i<$len;$i++){
				if(( $i==3 || ($i>3 && ($i-1)%2==0))&& $i!=$len){
				$m .=',';
				}
				$m .=$money[$i];
				}
				if($dec[1]<>'')
				{
					if(strlen($dec[1])==1)
					{
						$dcont=$dec[1].'0';
					}
					else
					{
						$dcont=substr($dec[1],0,2);
					}
				}
				else
				{
				$dcont='00';
				}
			return strrev($m).'.'.$dcont;
		
		}

   

	  function ExceptionHandler($exception)
	  {
		  echo "Uncaught exception: " , $exception->getMessage(), "\n";
	  }
	 static function ms_query_params__callback( $at ) 
	 {
			global $pg_query_params__parameters;
			return $pg_query_params__parameters[ $at[1]-1 ];
	}
	function mysqli_query_params( $link,$query, $parameters )
	{

			global $pg_query_params__parameters;
			foreach( $parameters as $k=>$v )
					$parameters[$k] = ( is_int( $v ) ? $v : "'".mysqli_real_escape_string($this->con, $v )."'" );
			$pg_query_params__parameters = $parameters;
			//echo preg_replace_callback( '/\$([0-9]+)/', array('Database','ms_query_params__callback'), $query );
			return mysqli_query( $link, preg_replace_callback( '/\$([0-9]+)/', array('Database','ms_query_params__callback'), $query ) );

	}
	 function mysqli_query_params_old($link,$query, $array)
    {
        $query_parsed = $query;
       
        for ($a = 0, $b = sizeof($array); $a < $b; $a++)
        {
            if ( is_numeric($array[$a]) )
            {
                $query_parsed = str_replace(('$'.($a+1)), str_replace("'","''", mysqli_real_escape_string($this->con, $array[$a])), $query_parsed );
            }
            else
            {
                $query_parsed = str_replace(('$'.($a+1)), "'".str_replace("'","''", mysqli_real_escape_string($this->con, $array[$a]))."'", $query_parsed );
            }
        }
      // echo $query_parsed;
	  $r=mysqli_query($link,$query_parsed);
	  if($r)
	  {
		  return $r;
	  }
	  else{
		  
		  echo ' mysqli Error : '.mysqli_error($link);
	  }
	  
    } 

	  function GetResultSet($query,$params)
	  {
		  $result=null;
		  try
		  { 
			  $this->lastQuery=$query;
			  $this->lastArgs=$params;
			  $result= $this->mysqli_query_params($this->con,$query,$params);
			  
			 
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("102",$this->default_error_msg, $ex);
		  }
		  //var_dump($result);
		  return $result;
	  }

	  function ExecuteBatch($query)
	  {
		  $result=null;
		  try
		  {
			  $this->lastQuery=$query;
			  $this->lastArgs=array();
			   $result= mysqli_query($this->con,$query);
			  
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("118",$this->default_error_msg, $ex);
		  }
		  return $result;
	  }

	  function GetLastQueryPreview()
	  {
		  $qry=$this->lastQuery;
		  if(is_array($this->lastArgs))
		  {
			$len=count($this->lastArgs);
		  	for($i=1;$i<=$len;$i++)
			{
				$qry=str_replace('$'.$i,"'".$this->lastArgs[$i-1]."'",$qry);
			}
		  }
		  return $qry;
	  }
		
		function mysqli_fetch_all($result)
		{
			$data=array();
			while($Row=mysqli_fetch_assoc($result)){
				 $data[] = $Row; //echo print_r($Row);
			}
			mysqli_free_result($result);
			return $data;
		}
	  function GetRecord($query , $params)
	  { 
		  $record=null;
		  try
		  {
	 //echo $query;
			   $result=$this->GetResultSet($query, $params); 
			//echo $this->getlastquerypreview();
 			  if(mysqli_num_rows($result)>0)
			  {
				//$record=$this->mysqli_fetch_all($result);
				  $record=mysqli_fetch_assoc($result);
				  
			  }
			  mysqli_free_result($result);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("103",$this->default_error_msg, $ex);
		  }
		  return $record;
	  }

	  function GetArray($query,$params)
	  {
		  $array=null;
		  try
		  { //echo $query,$params;
			  $result=$this->GetResultSet($query, $params);
//echo $this->getlastquerypreview();
			  if(mysqli_num_rows($result)>0)
			  {
				  $array=mysqli_fetch_row($result);
			  }
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("104",$this->default_error_msg, $ex);
		  }
		  return $array;
	  }

	  function GetValue($query , $params)
	  {
		  $value=null;
		  try
		  {
			  $array=$this->GetArray($query, $params);
			  if(isset($array))
			  {
				  $value=$array[0];
			  }
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("105",$this->default_error_msg, $ex);
		  }
		  return $value;
	  }

	  function GetMin($column, $tableName, $whereClause=NULL, $whereParam=NULL)
	  {
		  $min=null;
		  try
		  {
			  $query = "select min($column) from ".$tableName;
			  if(isset($whereClause) && $whereClause!="")
			  {
				  $query.=" where ".$whereClause;
			  }
			  $min=$this->GetValue($query, $whereParam);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("106",$this->default_error_msg, $ex);
		  }
		  return $min;
	  }

	  function GetMax($column, $tableName, $whereClause=NULL, $whereParam=NULL)
	  {
		  $max=null;
		  try
		  {
			  $query = "select max($column) from ".$tableName;
			  if(isset($whereClause) && $whereClause!="")
			  {
				  $query.=" where ".$whereClause;
			  }
			  $max=$this->GetValue($query, $whereParam);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("107",$this->default_error_msg, $ex);
		  }
		  return $max;
	  }

	  function GetAvg($column, $tableName, $whereClause=NULL, $whereParam=NULL)
	  {
		  $avg=null;
		  try
		  {
			  $query = "select avg($column) from ".$tableName;
			  if(isset($whereClause) && $whereClause!="")
			  {
				  $query.=" where ".$whereClause;
			  }
			  $avg=$this->GetValue($query, $whereParam);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("108",$this->default_error_msg, $ex);
		  }
		  return $avg;
	  }

	  function GetSum($column, $tableName, $whereClause=NULL, $whereParam=NULL)
	  {
		  $sum=null;
		  try
		  {
			  $query = "select sum($column) from ".$tableName;
			  if(isset($whereClause) && $whereClause!="")
			  {
				  $query.=" where ".$whereClause;
			  }
			  $sum=$this->GetValue($query, $whereParam);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("109",$this->default_error_msg, $ex);
		  }
		  return $sum;
	  }

	  function GetCount($column, $tableName, $whereClause=NULL, $whereParam=NULL)
	  {
		  $count=null;
		  try
		  {
			 $query = "select count($column) from ".$tableName;

			  if(isset($whereClause) && $whereClause!="")
			  {
				  $query.=" where ".$whereClause;
			  }
			  $count=$this->GetValue($query, $whereParam);
		  }
		  catch(Exception $ex)
		  {
			  $this->Notify("110",$this->default_error_msg, $ex);
		  }
		  return $count;
	  }

	  function Insert($tableName,array $data)
	  {
		  $lastInsertedId = 0;
		  try
		  {
			  $colNames = "";
			  $dataPlaceHolder = "";
			  $dataValues=array();
			  $index=1;
			  foreach ($data as $key=>$value)
			  {
				  $k = trim($key);
				  $colNames .= $k . ",";
				  $dataPlaceHolder .= '$'.$index.',';
				  $dataValues[$index-1]= $value;
				  $index++;
			  }
			  $colNames =substr($colNames,0,strlen($colNames)-1);
			  $dataPlaceHolder =substr($dataPlaceHolder,0,strlen($dataPlaceHolder)-1);
			  $query = "insert into " . $tableName . "(" . $colNames . ") values (" . $dataPlaceHolder . ");";
			  $this->GetResultSet($query, $dataValues);
			  $lastInsertedId =mysqli_insert_id($this->con); 
			  
		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("111",$this->default_error_msg, $ex);
		  }
		  return $lastInsertedId;
	  }
 	 
	  function Update($tableName, $data, $whereClause,$whereArgs)
	  {
		  $totalUpdated = 0;
		  try
		  {
			  $colNames = "";
			  $dataValues=array();
			  $index=count($whereArgs)+1;  // WhereClause will have 1,2,3...
			  foreach ($data as $key=>$value)
			  {
				  $k = trim($key);
				  $colNames .= $k . '=$'.$index.',';
				  $dataValues[$index-1]=$value;
				  $index++;
			  }
			  $colNames =substr($colNames,0,strlen($colNames)-1);
			  $query = "update " . $tableName . " set " . $colNames;
			  $log_where="";// Where clause for Update Logging
			  if(isset($whereClause) && $whereClause!="")
			  {
				 $query.=" where ".$whereClause;
				  $log_where=" where ".$whereClause;
				  $index=0;
				  foreach ($whereArgs as $arg)
				  {
					  $dataValues[$index++]=$arg;
				  }
			  }
			  ksort($dataValues);
			 
			  //Get Existing records before update
			  $existing_result=$this->GetResultSet("select * from ".$tableName.$log_where,$whereArgs);
			  $existing_data=$this->mysqli_fetch_all($existing_result);
			  $result=$this->GetResultSet($query,$dataValues);
			  $totalUpdated=mysqli_affected_rows($this->con);
			  if($totalUpdated>0)
			  {
				  //Create Update Log
				  //$this->LogUpdate($existing_data,$tableName,$data);
			  }
		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("112",$this->default_error_msg, $ex);
		  }
		  return $totalUpdated;
	  }

	  function LogUpdate($result,$table_Name,$data)
	  {
		foreach($result as $row)
		{
			$log_master=array();
			$log_master["table_name"]=$table_Name;
			$log_master["table_id"]=$row["id"];
			if(isset($this->USER["id"]))
			$log_master["entry_by"]=$this->USER["id"];
			$log_master["date_time"]=date("Y-m-d H:i:s");
			$log_master["ip_address"]=$_SERVER["REMOTE_ADDR"];
			$log_master["status"]=1;
			$log_id=$this->Insert("tbl_update_log_master",$log_master);
			$log_details=array();
			foreach($data as $key=>$value)
			{
				if($value!=$row[$key])
				{
					$log_details["update_id"]=$log_id;
					$log_details["column_name"]=$key;
					$log_details["old_value"]=$row[$key];
					$log_details["new_value"]=$value;
					$log_details["status"]=1;
					$log_det_id=$this->Insert("tbl_update_log_details",$log_details);
				}
			}
		}
	  }


	  function Delete($tableName, $whereClause,$whereArgs)
	  {
		  $totalAffected = 0;
		  try
		  {
			  $query = "delete from " . $tableName . " where " .  $whereClause;
			  $result=$this->GetResultSet($query,$whereArgs);
			  $totalAffected=mysqli_affected_rows($this->con);
		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("113",$this->default_error_msg, $ex);
		  }
		  return $totalAffected;
	  }

	  function Delete_Status($tableName, $whereClause,$whereArgs)
	  {
		  $totalAffected = 0;
		  try
		  {
			  $query = "update " . $tableName . " set status=0 where " .  $whereClause;
			  $result=$this->GetResultSet($query,$whereArgs);
			  $totalAffected=mysqli_affected_rows($this->con);
		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("114",$this->default_error_msg, $ex);
		  }
		  return $totalAffected;
	  }

	  function IntToString($value)
	  {
	  	  $value=intval($value);
		  $arr = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve");
		  $tens = array("Twen", "Thir", "Four", "Fif", "Six", "Seven", "Eigh", "Nine");
		  $htlc = array("Hundred", "Thousand", "Lakh", "Crore");

		  if ($value < 13)
			  return $arr[$value];
		  if ($value < 20)
			  return $tens[$value % 10 - 2] . "teen";
		  if ($value < 100 && $value % 10 != 0)
			  return $tens[($value / 10) - 2] . "ty " . $arr[$value % 10];
		  if ($value < 100 && $value % 10 == 0)
			  return $tens[($value / 10) - 2] . "ty ";
		  if ($value < 1000)
			  return $arr[$value / 100] . " " . $htlc[0] . " " . (($value % 100) > 0 ? $this->IntToString($value % 100) : "");
		  if ($value < 100000)
			  return $this->IntToString($value / 1000) . " " . $htlc[1] . " " . (($value % 1000) > 0 ? $this->IntToString($value % 1000) : "");
		  if ($value < 10000000)
			  return $this->IntToString($value / 100000) . " " . $htlc[2] . " " . (($value % 10000) > 0 ? $this->IntToString($value % 100000) : "");
		  else
			  return $this->IntToString($value / 10000000) . " " . $htlc[3] . " " . (($value % 1000000) > 0 ? $this->IntToString($value % 10000000) : "");
	  }

	  function MoneyToString($amount)
	  {
		  $val = "";
		  try
		  {
			  $arr = explode(".",number_format($amount,2));
			  $rupees = $arr[0];
			  $paise = $arr[1];
			  if ($rupees > 0)
			  {

				  $val = $this->IntToString($rupees) . " Rupees ";
			  }
			  if ($paise > 0)
			  {
				  if ($rupees > 0)
					  $val .= "and ";
				  $val .= $this->IntToString($paise) . " Paise";
			  }

		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("115",$this->default_error_msg, $ex);
		  }
		  return $val;
	  }

	  function GetCurrentFinancialYear()
	  {
		  $year=intval(date("Y"));
		  $month=intval(date("m"));
		  if ($month >= 1 && $month <= 3)
		  {
			  return ($year - 1) . "-" . ($year);
		  }
		  else
		  {
			  return ($year) . "-" . ($year+1);
		  }
	  }

	  function GetLastQuery()
	  {
		 return $this->lastQuery;
	  }

	  function GetLastError()
	  {
		 return $this->lastError;
	  }

	  function InvalidURL()
	  {
		  header("Location:invalidurl.php");
		  exit;
	  }

	  function CheckUrl($tableName)
	  { 
		  try
		  {
			  $this->TempRs =array();
			  $parameter = $_SERVER['QUERY_STRING'];
			  if (strlen(trim($parameter)) > 0)
			  {
				  $param = explode("=",$parameter);
				  if ($param[0] != ("uid"))
				  {
					 $this->InvalidURL();
				  }
				  if ((@$_REQUEST["wid"] != "c81e728d9d4c2f636f067f89cc14862c") && (@$_REQUEST["wid"] != "eccbc87e4b5ce2fe28308fd9f2a7baf3"))
				  {
					  $this->InvalidURL();
				  }
				  else
				  {
					  if ($_REQUEST["wid"] == "c81e728d9d4c2f636f067f89cc14862c")
					  {
						  $this->ButtonOperation = "Edit";
						  $this->ButtonCaption="Save";
					  }
					  if ($_REQUEST["wid"] == "eccbc87e4b5ce2fe28308fd9f2a7baf3")
					  {
						  $this->ButtonOperation = "View";
						  $this->ButtonCaption="Close";
						  $this->disableit = " disabled readonly style='border:none; background:#FFFFFF; outline-style:none;box-shadow:none;color:#000;resize:none; -moz-appearance:none; -webkit-appearance:npne; -ms-appearance:npne; -o-appearance:npne; appearance:npne'";
					  }
					  $sid = $_REQUEST["uid"];
					  $query='select * from '.$tableName.' where md5(id)=$1';
					  $this->TempRs=$this->GetRecord($query,array($sid));
					  $this->TempRs=$this->TempRs;
				  }
			  }
			  else
			  { 
			      $meta =$this->mysql_meta_data($tableName); 
				  foreach($meta as $key=>$value)
				  $this->TempRs[$key]=""; 
				  $this->ButtonOperation = "New";
				  $this->ButtonCaption="Save";
				  //print_r($this->TempRs);
			  }
		  }
		  catch (Exception $ex)
		  {
			  $this->Notify("116",$this->default_error_msg, $ex);
		  }
	  }
	  function mysql_meta_data($tableName)
	  {
		 $query="SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = $1 AND TABLE_NAME = $2";
		
		$result = $this->GetResultSet($query, array($this->db_name, $tableName));
		$fields = array();
		while($row = mysqli_fetch_array($result)) {
		$fields[$row['COLUMN_NAME']] = $row;
    	}
		return $fields;
		
	  }

/*	function GetDataList($tableName, $viewName, $columns, $whereClaues, $whereArgs, $orderBy)
    {
        $start = 0;
        $limit = $this->FRONT_PAGE_LIMIT;
		if (isset($_REQUEST["page"]))
		{
			$page = intval($_REQUEST["page"]);
			$start = ($page - 1) * $limit;
		}
        $returnTable = "<table class='table table-striped table-hover'>\n";
        try
        {
            if (isset($_REQUEST["lst_delete"]))
                $this->Delete($tableName," md5(id)=$1 ",array($_REQUEST["del_id"]));
            $add = 1;
			$edit = 1;
			$view = 1;
			$delete = 1;
            $viewfile = $this->ALTERPAGENAME;
			$editfile = $this->ALTERPAGENAME;

            //String[] pName = URLFILENAME.Split('_');
            //String viewfile = pName[0] + "_alter", editfile = pName[0] + "_alter";
            //query = "select * from view_systemfilelist where file_name='" + URLFILENAME + "' and role_id=" + USERTYPE; // Need to modify query if working with user type also in constructor
            //SqlDataReader rs = new SqlCommand(query, con).ExecuteReader();
            //if (rs.Read())
            //{
            //    add = (int)rs["can_add"];
//                edit = (int)rs["can_edit"];
//                view = (int)rs["can_view"];
//                delete = (int)rs["can_delete"];
//                viewfile = (String)rs["view_file"];
//                editfile = (String)rs["edit_file"];
//            }
//            rs.Close();
            /*if (isset($whereClaues) && $whereClaues!="")
            {
                whereClaues = whereClaues.Trim().Length == 0 ? null : whereClaues;
            }
            if (orderBy != null)
            {
                orderBy = orderBy.Trim().Length == 0 ? null : orderBy;
            }
            $query = "select " . $columns . " from " . $viewName;
            if (isset($whereClaues) && $whereClaues!="")
            {
                $query .= " where " . $whereClaues;
            }
            if (isset($orderBy) && $orderBy!="")
            {
                $query .= " order by " . $orderBy;
            }
            else
            {
                $query .= " order by id asc ";
            }

            $query .= " limit ".$limit." offset " . $start;
			$result=$this->GetResultSet($query, $whereArgs);
            $cols = pg_num_fields($result);
            $returnTable .= "<tr style='font-weight:bold'>\n";
            $colArr = array();
            for ($i = 1; $i < $cols; $i++)
            {
                $colArr[$i] = pg_field_name($result,$i);
				$field_Name=$colArr[$i];
				if(stripos($colArr[$i],"_")!=false)
				{
					$field_arr=explode("_",$field_Name);
					$field_Name=$field_arr[1];
				}
                $returnTable .= "<td>" . $field_Name . "</td>\n";
            }

            if ($edit == 1)
            {
                $returnTable .= "<td>Edit</td>\n";
            }
            if ($view == 1)
            {
                $returnTable .= "<td>View</td>\n";
            }
            if ($delete == 1)
            {
                $returnTable .= "<td>Delete</td>\n";
            }
            $returnTable .= "</tr>\n";

            while ($row =pg_fetch_array($result))
            {
                $returnTable .= "<tr>\n";
                $deleteDetails = "<table style='width:90%;margin:auto'>";

                for ($i = 1; $i < $cols; $i++)
                {
                    $deleteDetails .= "<tr>";
					$field_Name=$colArr[$i];
					if(stripos($colArr[$i],"_")!=false)
					{
						$field_arr=explode("_",$field_Name);
						$field_Name=$field_arr[1];
					}
                    $deleteDetails .= "<td>" .$field_Name. "</td>\n<td>";
                    $colName = $colArr[$i];
                    $val = $row[$i];

                    if (rs[i] != DBNull.Value)
                    {
                        switch (colName.Split('_')[0].ToUpper())
                        {
                            case "INTTODATE":
                                val = IntToDateStr(rs[i].ToString());
                                break;

                        case "INTTOTIMEAMPM":
                            val = IntToTime12(rs.getString(i));
                            break;
                        case "INTTOTIME24":
                            val = IntToTime24(rs.getString(i));
                            break;
                        case "INTTODATETIMEAMPM":
                            val = IntToDateTime12(rs.getString(i));
                            break;
                        case "INTTODATETIME24":
                            val = IntToDateTime24(rs.getString(i));
                            break;

                            default:
                                val = rs[i].ToString();
                                break;

                        }



                    $returnTable .= "<td>" . $val . "</td>\n";
                    $deleteDetails .= $val . "</td>\n";
                    $deleteDetails .= "</tr>";

                }
                $deleteDetails .= "</table>";
                if ($edit == 1)
                {
                    $returnTable .= "<td width='40'><a href='" . $editfile. ".php?uid=" . md5($row[0]) . "&wid=c81e728d9d4c2f636f067f89cc14862c'><img src='" . IMAGE_PATH . "/e.png' alt='Edit'></a></td>\n";
                }
                if ($view == 1)
                {
                    $returnTable .= "<td width='40'><a href='" .$viewfile. ".php?uid=" . md5($row[0]) . "&wid=eccbc87e4b5ce2fe28308fd9f2a7baf3'><img src='" . IMAGE_PATH . "/v.png' alt='View'></a></td>\n";
                }
                if ($delete == 1)
                {
                    $returnTable .= "<td width='40'><a href='#myModal" .$row[0]. "' data-toggle='modal' ><img src='" . IMAGE_PATH . "/d.png' alt='Delete'></a>
                            <form class='contact' method='POST'>
                            <input type='hidden' name='del_id' value='" . md5($row[0]) . "'>
                           <div class='modal fade' id='myModal".$row[0]."' role='dialog'>
							<div class='modal-dialog'>
							  <!-- Modal content-->
							  <div class='modal-content'>
								<div class='modal-header'>
								  <button type='button' class='close' data-dismiss='modal'>&times;</button>
								  <h4 class='modal-title'>Confirmation Delete</h4>
								</div>
								<div class='modal-body'>
								  ".$deleteDetails."
								</div>
								<div class='modal-footer'>
								  <input type='Submit' class='btn  btn-danger' name='lst_delete' value='Delete'  id='submit'/>
								  <button type='button' class='btn btn-warning' data-dismiss='modal'>Close</button>
								</div>
							  </div>
							</div>
						</div>
                        </form>
                       </td>\n";
                }
                $returnTable .= "</tr>\n";
            }

            $returnTable .= "</table><br>";
            $paging= $this->GetPagination($viewName, $whereClaues,$whereArgs);
			$returnTable .=$paging;
        }
        catch (Exception $ex)
		{
			$this->Notify("117",$this->default_error_msg, $ex);
		}
        return $returnTable;
    }
*/

	function CurrentFilePermission()
	{
		$permission=array();
		$permission["create"]=0;
		$permission["read"]=0;
		$permission["update"]=0;
		$permission["delete"]=0;
		if(isset($this->USER))
		{
			$doc_root=$_SERVER["DOCUMENT_ROOT"];
			$script_name=$_SERVER["SCRIPT_FILENAME"];
			$file_name=substr($script_name,strlen($doc_root));
			$per_query="select * from sys_file_permission_details where file_name=$1 and type=$2 and ref_id=$3 and dept_id=$4 and status=1";
			$group_permission=$this->GetRecord($per_query,array($file_name,"USER_TYPE",$this->USER["user_type_id"],$this->USER["department_id"]));
			$user_permission=$this->GetRecord($per_query,array($file_name,"USER",$this->USER["id"],$this->USER["department_id"]));
			if(count($group_permission)>0)
			{
				$permission["create"] = $group_permission["_create"];
				$permission["update"] = $group_permission["_update"];
				$permission["read"]= $group_permission["_read"];
				$permission["delete"] = $group_permission["_delete"];
			}
			if(count($user_permission)>0)
			{
				$permission["create"] = $user_permission["_create"];
				$permission["update"] = $user_permission["_update"];
				$permission["read"] = $user_permission["_read"];
				$permission["delete"] = $user_permission["_delete"];
			}
		}
		return $permission;
	}

	function GetFilePermission($file_name)
	{
		$permission=array();
		$permission["create"]=0;
		$permission["read"]=0;
		$permission["update"]=0;
		$permission["delete"]=0;
		if(isset($this->USER))
		{
			$per_query="select * from sys_file_permission_details where file_name=$1 and type=$2 and ref_id=$3 and dept_id=$4 and status=1";
			$group_permission=$this->GetRecord($per_query,array($file_name,"USER_TYPE",$this->USER["user_type_id"],$this->USER["department_id"]));
			//if($file_name=="/HOME/hrms/admin/project/Payroll/employee_salary_details.php")
				//echo $this->GetLastQueryPreview();
			$user_permission=$this->GetRecord($per_query,array($file_name,"USER",$this->USER["id"],$this->USER["department_id"]));
			//echo $this->GetLastQueryPreview();
			if(count($group_permission)>0)
			{
				$permission["create"] = $group_permission["_create"];
				$permission["update"] = $group_permission["_update"];
				$permission["read"]= $group_permission["_read"];
				$permission["delete"] = $group_permission["_delete"];
			}
			if(count($user_permission)>0)
			{
				$permission["create"] = $user_permission["_create"];
				$permission["update"] = $user_permission["_update"];
				$permission["read"] = $user_permission["_read"];
				$permission["delete"] = $user_permission["_delete"];
			}
		}
		return $permission;
	}
	function GetDataList($tableName, $viewName, $columns, $whereClaues, $whereArgs, $orderBy, $viewfile, $editfile)
    {
        $start = 0;
        $limit = $this->ADMIN_PAGE_LIMIT;
		if (isset($_REQUEST["page"]))
		{
			$page = intval($_REQUEST["page"]);
			$start = ($page - 1) * $limit;
		}
        $returnTable = "<table class='table table-striped table-hover'>\n";

        try
        {
            if (isset($_REQUEST["lst_delete"]))
                $this->Delete($tableName," md5(id)=$1 ",array($_REQUEST["del_id"]));

			//$permission=$this->CurrentFilePermission();
			//Code for permisssion
			//$add = $permission["create"];
			//$edit =  $permission["update"];
			//$view = $permission["read"];
			//$delete = $permission["delete"];
			//Code for permisssion
			$add = 1;
			$edit =  1;
			$view = 1;
			$delete = 1;
            $query = "select " . $columns . " from " . $viewName;
            if (isset($whereClaues) && $whereClaues!="")
            {
                $query .= " where " . $whereClaues;
            }
			if(isset($_POST["ORDERCLAUSE"]))
			{
				$_SESSION["ORDERCLAUSE"]=$_POST["ORDERCLAUSE"];
				$_REQUEST["page"]=1;
				$start=0;
			}
			if(isset($_SESSION["ORDERCLAUSE"]))
			{
				$query .= ' order by '.$_SESSION["ORDERCLAUSE"];
				
				$ORDERCLAUSEDESCASCa="^";
				if(isset($_POST["ORDERCLAUSEDESCASC"])){$ORDERCLAUSEDESCASCa=$_POST["ORDERCLAUSEDESCASC"];}  
				if( $ORDERCLAUSEDESCASCa=="v") {$query .= ' Asc';  } 
				if( $ORDERCLAUSEDESCASCa=="^") {$query .= ' Desc'; } 
			}
            elseif(isset($orderBy) && $orderBy!="")
            {
                $query .= " order by " . $orderBy;
            }
            else
            {
                $query .= " order by id asc ";
            }

           $query .= " limit ".$limit." offset " . $start; 
			 //echo $query;
			$org_value = preg_replace('/"[^"]+"/', '', $columns);
			$org_value = str_replace('as', '', $org_value);
			$org_column=explode(',',$org_value ); 
			$result=$this->GetResultSet($query, $whereArgs);
			//echo $this->getlastquerypreview();
           	$cols = mysqli_num_fields($result);
            $returnTable .= "<tr style='font-weight:bold'>\n";
            $colArr = array();
            for ($i = 1; $i < $cols; $i++)
            {
				$properties=mysqli_fetch_field_direct($result,$i); //print_r($properties);
                $colArr[$i] = is_object($properties) ? $properties->name : null;
				$column_name=$org_column[$i];
				$field_Name=$colArr[$i];
				if(stripos($colArr[$i],"_")!=false)
				{
					$field_arr=explode("_",$field_Name);
					$field_Name=$field_arr[1];
				}
				$order_clause='';
				$order_clause.= "<form method=\"POST\" style='display:inline'>";
				if(isset($_POST["ORDERCLAUSE"])){$tempfieldname=$_POST["ORDERCLAUSE"];} else {$tempfieldname="";}
				if($column_name<>$tempfieldname){
					$order_clause .= '<input type="SUBMIT"  name="ORDERCLAUSEDESCASC" value="^" style="background:none; font-weight:bold; border:none; box-shadow:none" />';
				}
				 else { 
					if($_POST["ORDERCLAUSEDESCASC"]<>"^"){
						$order_clause .= '<input type="SUBMIT"  name="ORDERCLAUSEDESCASC" value="^" style="background:none; font-family:Webdings; font-weight:bold; border:none; box-shadow:none" />';
					}
					else {
						$order_clause .= ' <input type="SUBMIT"  name="ORDERCLAUSEDESCASC" value="v" style="background:none;font-weight:bold; border:none; box-shadow:none" />';
					}
				 }
				$order_clause .= '<input type="hidden" name="fieldvalue" value="'.($i+1).'" />
		<input type="hidden" name="ORDERCLAUSE" value="'. $column_name.'" />';
				$order_clause .= "</form>";
				
                $returnTable .= "<td>" . $field_Name . $order_clause."</td>\n";
            }

            if ($edit == 1)
            {
                $returnTable .= "<td>Edit</td>\n";
            }
            if ($view == 1)
            {
                $returnTable .= "<td>View</td>\n";
            }
            if ($delete == 1)
            {
                $returnTable .= "<td>Delete</td>\n";
            }
            $returnTable .= "</tr>\n";
			$list_count=$start;
			//echo $this->GetLastQueryPreview();
            while ($row =mysqli_fetch_array($result))
            { $list_count++;
             //   $returnTable .= "<tr><td>$list_count</td>\n";
                $deleteDetails = "<table style='width:90%;margin:auto'>";
				
                for ($i = 1; $i < $cols; $i++)
                { 
                    $deleteDetails .= "<tr>";
					$field_Name=$colArr[$i];
					if(stripos($colArr[$i],"_")!=false)
					{
						$field_arr=explode("_",$field_Name);
						$field_Name=$field_arr[1];
					}
					else{$field_arr[0]='';}
                    $deleteDetails .= "<td>" .$field_Name. "</td>\n<td>";
                    $colName = $colArr[$i];
                    $val = $row[$i];
					
					 if($field_arr[0]=="IntToDate")
					 	$val=$this->IntToDate($row[$i]);
					 elseif($field_arr[0]=="IntToTimeAMPM")
					  	$val=$this->IntToTime12($row[$i]);
					 elseif($field_arr[0]=="IntToTime24")
					  	$val=$this->IntToTime24($row[$i]);
					 elseif(($field_arr[0]=="Age"))
					 	$val=$this->dateDiff($this->IntToDate($row[$i]),date('d-m-Y'));
					
					

                    /*if (rs[i] != DBNull.Value)
                    {
                        switch (colName.Split('_')[0].ToUpper())
                        {
                            case "INTTODATE":
                                val = IntToDateStr(rs[i].ToString());
                                break;

                        case "INTTOTIMEAMPM":
                            val = IntToTime12(rs.getString(i));
                            break;
                        case "INTTOTIME24":
                            val = IntToTime24(rs.getString(i));
                            break;
                        case "INTTODATETIMEAMPM":
                            val = IntToDateTime12(rs.getString(i));
                            break;
                        case "INTTODATETIME24":
                            val = IntToDateTime24(rs.getString(i));
                            break;

                            default:
                                val = rs[i].ToString();
                                break;

                        }

                    }*/

                    $returnTable .= "<td>" . $val . "</td>\n";
                    $deleteDetails .= $val . "</td>\n";
                    $deleteDetails .= "</tr>";

                }
                $deleteDetails .= "</table>";
                if ($edit == 1)
                {
                    $returnTable .= "<td width='40'><a href='" . $editfile. ".php?uid=" . ($row[0]) . "&wid=c81e728d9d4c2f636f067f89cc14862c'><span class='btn btn-primary btn-xs'>&nbsp;Edit</span></a></td>\n";
                }
                if ($view == 1)
                {
                    $returnTable .= "<td width='40'><a href='" .$viewfile. ".php?uid=" . ($row[0]) . "&wid=eccbc87e4b5ce2fe28308fd9f2a7baf3'>
					<span class='btn btn-info btn-xs'>&nbsp;View</span>
					</a></td>\n";
                }
                if ($delete == 1)
                {
                    $returnTable .= "<td width='40'><a href='#myModal" .$row[0]. "' data-toggle='modal' >

					<span class='btn btn-danger btn-xs'>&nbsp;Delete</span>


					</a>
                            <form class='contact' method='POST'>
                            <input type='hidden' name='del_id' value='" . md5($row[0]) . "'>
                           <div class='modal fade' id='myModal".$row[0]."' role='dialog'>
							<div class='modal-dialog'>
							  <!-- Modal content-->
							  <div class='modal-content'>
								<div class='modal-header btn-danger'>
								  <button type='button' class='close' data-dismiss='modal'>&times;</button>
								  <h4 class='modal-title'>Confirmation Delete</h4>
								</div>
								<div class='modal-body'>
								  ".$deleteDetails."
								</div>
								<div class='modal-footer'>
								  <input type='Submit' class='btn  btn-danger' name='lst_delete' value='Delete'  id='submit'/>
								  <button type='button' class='btn btn-warning' data-dismiss='modal'>Close</button>
								</div>
							  </div>
							</div>
						</div>
                        </form>
                       </td>\n";
                }
                $returnTable .= "</tr>\n";
				
            }

            $returnTable .= "</table><br>";
            $paging= $this->GetPagination($viewName, $whereClaues,$whereArgs);
			$returnTable .=$paging;
        }
        catch (Exception $ex)
		{
			$this->Notify("117",$this->default_error_msg, $ex);
		}
        return $returnTable;
     }

	  function GetPagination($tableName, $whereCaluse,$whereArgs)
	  {
          
		  $pagination = "";
		  $limit = $this->ADMIN_PAGE_LIMIT;
		  $adjacents = 3;
		  $page = 0;
		  $counter = 0;
		  $totalpages = $this->GetCount("*",$tableName,$whereCaluse,$whereArgs);
		  //echo $totalpages;
		  //$targetpage = $this->URLFILENAME;
		  $targetpage='';
		  $request_page = explode('page',$_SERVER['REQUEST_URI']);
		   $temp_page=$request_page[0];
		  $temp_page_c= explode('?',$temp_page);
		  if(count($temp_page_c)>1)
		  {
			 if(substr($temp_page, -1)!='&')
		  		$targetpage=$temp_page.'&';
			 else 
			 	$targetpage=$temp_page;
		  }
		  else 
		  	$targetpage=$temp_page.'?';
		  
		  if (isset($_REQUEST["page"]))
		  {
			  $page = intval($_REQUEST["page"]);
		  }
		  if ($page == 0)
		  {
			  $page = 1;
		  }
		  $prev = $page - 1;
		  $next = $page + 1;
		  $lastpage = ceil($totalpages / $limit);
		  $lpm1 = $lastpage - 1;
		  if ($lastpage > 1)
		  {
				$pagination .= '<div class="pagination pagination-right"><ul>';
				if ($page > 1)
					$pagination.= "<li><a href=\"$targetpage"."page=$prev\">Previous</a></li>";
				else
					$pagination.= "<li class='disabled'><a href='#'>Previous </a></li>";
				if ($lastpage < 7 + ($adjacents * 2))
				{
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.='<li class="active"><a href="#"><span>'.$counter.'</span></a></li>';
						else
							$pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
					}
				}
				else if ($lastpage > 5 + ($adjacents * 2))
				{
					if ($page < 1 + ($adjacents * 2))
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
							else
								$pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
						}
						$pagination.= "<li class='disabled'><a href='#'>... </a></li>";
						$pagination.= "<li><a href=\"$targetpage"."page=$lpm1\">$lpm1</a></li>";
						$pagination.= "<li><a href=\"$targetpage"."page=$lastpage\">$lastpage</a></li>";
					}
					else if ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination.= "<li><a href=\"$targetpage"."page=1\">1</a></li>";
						$pagination.= "<li><a href=\"$targetpage"."page=2\">2</a></li>";

						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
							else
								$pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
						}
						$pagination.= "<li><a href=\"$targetpage"."page=$lpm1\">$lpm1</a></li>";
						$pagination.= "<li><a href=\"$targetpage"."page=$lastpage\">$lastpage</a></li>";
					}
					else
					{
						$pagination.= "<li><a href=\"$targetpage"."page=1\">1</a></li>";
						$pagination.= "<li><a href=\"$targetpage"."page=2\">2</a></li>";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination.='<li class="active"><a href="#">'.$counter.'  </a></li>';
							else
								$pagination.= "<li><a href=\"$targetpage"."page=$counter\">$counter</a></li>";
						}
					}
				}
				if ($page < $counter - 1)
					$pagination.= "<li><a href=\"$targetpage"."page=$next\">Next</a></li>";
				else
					$pagination.= "<li class='disabled'><a href='#'>Next</a></li>";
				$pagination.= "</ul></div> ";
			}
		  return $pagination;
	  }

	  function GenerateMenu($table_name="sys_menu_master")
	  { 
		  $menu_string="";
		  $site_name='http://'.$_SERVER['HTTP_HOST'];
		  try
		  {
			  $menu= $this->GetMenuFileArray("sys_menu_master");
			  $section=0;
			  $dashboard="";
			  foreach($menu as $item=>$i_value)
			  { 
				  if(strtolower($item)=="dashboard")
				  {
					     $dashboard.="
					  <!-- start -->\n
					  <a href='".$site_name.$i_value."' style='color:#FFFFFF; text-decoration:none; font-weight:bold'>\n
					  <div style='width:100%; text-align:left; height:45px; background:#dd0000; border-bottom:dotted #999999 1px; color:#fff; cursor:pointer; line-height:45px;'>\n
					  &nbsp;&nbsp;<img src='".IMAGE_PATH."/dash.png' width='20' />\n
					  &nbsp;&nbsp;".$item."</div></a>\n";

				  }
				  else
				  {
					  $menu_string.="<!-- Start ".$item." menu -->\n
					  <div class='accordion accordion-close' id='section".++$section."'>".$item."<span></span></div>\n
					  <div style='display: none;'>\n
					  <div class='content' >\n
					  <ul style='list-style:circle'>\n";
					  foreach($i_value as $menu_item=>$menu_link)
					  {
						  $menu_string.="\t\t\t\t\t\t<a href='".$site_name.$menu_link."' style='text-decoration:none;'><li>".$menu_item."<span></span></li> </a>\n";
					  }
					  $menu_string.="\t\t\t\t\t\t</ul>\n
					  </div>\n
					  </div>\n
					  <!-- End ".$item." menu -->\n";
				  }
			  }

			 $menu_string=$dashboard."<div class='collapse navbar-collapse' id='myNavbar' style='padding:0px; width:100%'>\n".$menu_string."</div>\n";
		  }
		  catch (Exception $ex)
		  {
			$this->Notify("119",$this->default_error_msg, $ex);
		  }
		  return $menu_string;
	  }

	  function GetMenuFileArray($table_name="sys_menu_master")
	  {
		  
		  $menu=array();
		  try
		  {
			  $result=$this->GetResultSet("select * from ".$table_name." where parent_menu_id=0 order by _order asc",array());
			  //echo $this->getlastquerypreview(); return;
			  while($row= mysqli_fetch_assoc($result))
			  {

				  if($row["file_name"]!="#")
				  {
				  	$permission=$this->GetFilePermission($row["file_name"]);
					if($permission["read"]==1)
					{
						$file_name=$row["file_name"];
						if($row["query_string"]!=null && $row["query_string"]!="")
							$file_name.="?".$row["query_string"];
						$menu[$row["menu_name"]]=$file_name;
					}
				  }
				  else
				  {
					  $child_res=$this->GetResultSet("select * from ".$table_name." where parent_menu_id=$1 order by _order asc",array($row["id"]));
					  $childs=array();
					  //echo $this->GetLastQueryPreview();
					  while($row_ch=mysqli_fetch_assoc($child_res))
					  { 
						  $permission=$this->GetFilePermission($row_ch["file_name"]);
						  if($permission["read"]==1)
						  {
								$file_name=$row_ch["file_name"];
								$file_name.="?mid=".md5($row_ch["id"]);
								if($row_ch["query_string"]!=null && $row_ch["query_string"]!="")
									$file_name.="&".$row_ch["query_string"];
								$childs[$row_ch["menu_name"]]=$file_name;
						  }
					  }
					  if(count($childs)>0)
					  {
						  $menu[$row["menu_name"]]=$childs;
					  }
				  }

			  }
		 }
		 catch (Exception $ex)
		 {
			$this->Notify("120",$this->default_error_msg, $ex);
	     }
		 return $menu;
	  }
	  
	  //***************************************************User Panel Menu generate**************************************************************************************
	  
	   function GenerateMenu1($table_name="sys_menu_master")
	  { 	
		  $menu_string="";
		  $site_name='http://'.$_SERVER['HTTP_HOST'];
		  try
		  {
		  	
			  $menu= $this->GetMenuFileArray1("sys_menu_master");
			  $section=0;
			  $dashboard="";
			  foreach($menu as $item=>$i_value)
			  {
				  if(strtolower($item)=="dashboard")
				  {
					     $dashboard.="
					  <!-- start -->\n
					  <a href='".$site_name.$i_value."' style='color:#FFFFFF; text-decoration:none; font-weight:bold'>\n
					  <div style='width:100%; text-align:left; height:45px; background:#dd0000; border-bottom:dotted #999999 1px; color:#fff; cursor:pointer; line-height:45px;'>\n
					  &nbsp;&nbsp;<img src='".IMAGE_PATH."/dash.png' width='20' />\n
					  &nbsp;&nbsp;".$item."</div></a>\n";

				  }
				  else
				  { 
				  	$file_name = $this->GetValue("select file_name from `view_sysmenu_master` where menu_name=$1 and status=1 ", array($item));
				  
					  $menu_string.="<!-- Start ".$item." menu -->\n
					  <li><a href='".$file_name."'><i class='fa fa-edit'></i>".$item."</a></li>\n
					
			
					
					  <!-- End ".$item." menu -->\n";
				  }
			  }

			// $menu_string=$dashboard."<div class='collapse navbar-collapse' id='myNavbar' style='padding:0px; width:100%'>\n".$menu_string."</div>\n";
		  }
		  catch (Exception $ex)
		  {
			$this->Notify("119",$this->default_error_msg, $ex);
		  }
		  return $menu_string;
	  }

	  function GetMenuFileArray1($table_name="sys_menu_master")
	  {
		  
		  $menu=array();
		  try
		  {
			  $result=$this->GetResultSet("select * from ".$table_name." where parent_menu_id=0 order by _order asc",array());
			  //echo $this->getlastquerypreview(); return;
			  while($row= mysqli_fetch_assoc($result))
			  {

				  if($row["file_name"]!="#")
				  {
				  	$permission=$this->GetFilePermission1($row["file_name"]);
					if($permission["read"]==1)
					{
						$file_name=$row["file_name"];
						if($row["query_string"]!=null && $row["query_string"]!="")
							$file_name.="?".$row["query_string"];
						$menu[$row["menu_name"]]=$file_name;
					}
				  }
				  else
				  {
					  $child_res=$this->GetResultSet("select * from ".$table_name." where parent_menu_id=$1 order by _order asc",array($row["id"]));
					  $childs=array();
					 //echo $this->GetLastQueryPreview();
					  while($row_ch=mysqli_fetch_assoc($child_res))
					  { 
						  $permission=$this->GetFilePermission1($row_ch["file_name"]);
						  if($permission["read"]==1)
						  {
								$file_name=$row_ch["file_name"];
								$file_name.="?mid=".md5($row_ch["id"]);
								if($row_ch["query_string"]!=null && $row_ch["query_string"]!="")
								$file_name.="&".$row_ch["query_string"];
								$childs[$row_ch["menu_name"]]=$file_name;
						  }
					  }
					  if(count($childs)>0)
					  {
						  $menu[$row["menu_name"]]=$childs;
						  
					  }
				  }

			  }
		 }
		 catch (Exception $ex)
		 {
			$this->Notify("120",$this->default_error_msg, $ex);
	     }
		 return $menu;
	  }
	  
	  
	  function GetFilePermission1($file_name)
	{
		$permission=array();
		$permission["create"]=0;
		$permission["read"]=0;
		$permission["update"]=0;
		$permission["delete"]=0;
		if(isset($this->USER))
		{
			$per_query="select * from sys_file_permission_details where file_name=$1 and type=$2 and ref_id=$3  and status=1 group by file_name";
			$group_permission=$this->GetRecord($per_query,array($file_name,"USER_TYPE",$this->USER["user_type_id"]));
			//if($file_name=="/HOME/hrms/admin/project/Payroll/employee_salary_details.php")
				//echo $this->GetLastQueryPreview();
			$user_permission=$this->GetRecord($per_query,array($file_name,"USER",$this->USER["id"],3));
			//echo $this->GetLastQueryPreview();
			if(count($group_permission)>0)
			{
				
				$permission["create"] = $group_permission["_create"];
				$permission["update"] = $group_permission["_update"];
				$permission["read"]= $group_permission["_read"];
				$permission["delete"] = $group_permission["_delete"];
			}
			if(count($user_permission)>0)
			{
				
				$permission["create"] = $user_permission["_create"];
				$permission["update"] = $user_permission["_update"];
				$permission["read"] = $user_permission["_read"];
				$permission["delete"] = $user_permission["_delete"];
			}
		}
		return $permission;
	}
	  
	  
	  
	  
	  
	  
	  //***************************************************end User Panel Menu generate**************************************************************************

	  function SysDate()
	  {
		 return date("Y-m-d");
	  }

	  function SysDateTime()
	  {
		 return date("Y-m-d H:i:s");
	  }
	  
	  	function IntToDate($para=null)
			{
				date_default_timezone_set('Asia/Calcutta');
		if(!$para==0)
		{
			return date("d-m-Y", $para);
		}	
	}
	function DateToInt($para=null)
	{	
		date_default_timezone_set('Asia/Calcutta');
		$date=explode("-", $para); 
		return mktime(0, 0, 0, ($date[1]), $date[0], $date[2]);
	}
	function TimeToInt24($para=null)
	{	 
		date_default_timezone_set('Asia/Calcutta');
 		$time=explode(":",$para);
		$hh=$time[0];
		$mm=$time[1];
 
		 return mktime($hh,$mm,0,0,0,0);
	}
	function IntToTime24($para=null)
	{
		date_default_timezone_set('Asia/Calcutta');
		return date('H:i',$para);
		
	}
	
	function ChageDateFormat($date_str,$current_fromat,$result_format)
	{
		return date_format(date_create_from_format($current_fromat,$date_str),$result_format);
	}

	function ChangeDateFormat($date_str,$current_fromat,$result_format)
	  {
		 return date_format(date_create_from_format($current_fromat,$date_str),$result_format);
	  }
	
	function addmonth($dt,$mm,$dd)
{
  

$date=explode("-", $dt); 
return (mktime(0, 0, 0, ($date[1]+$mm), ($date[0]+$dd), $date[2]));
}

	 function FyYearRows()
	{
		return $this->getRecord("select * from tbl_financial_year_master where financial_year=$1",array($this->GetCurrentFinancialYear()));
	}
  }



  ?>
