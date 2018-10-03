<?php
session_start();
$conn = mysql_connect("localhost","root","");
$username = $_POST['username'];
$password = $_POST['password'];
if($conn)
{
	if(mysql_select_db("inventory3",$conn))
	{
		$sql = "select * from tbl_user_master where userName = '$username'";
		$result = mysql_query($sql,$conn);
		$row = mysql_fetch_array($result);
		if($row['userPassword'] === $password)
		{
			$_SESSION['id'] = $row['id']; 
			$_SESSION['userName'] = $row['userName']; 
			header("location:index.php?id=".$row['id']);
		}
		else
			header("location:userlogin.php?wronguser=1");
	}
}
?>
