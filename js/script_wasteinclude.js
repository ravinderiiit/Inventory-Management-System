

//*****************Waste payment collection*********************
function payable(fy,appid)
{
	 document.getElementById("payableamt").innerHTML="<h1><center><font color='#0000CC'>Loading.....</font></center></h1>";	 
	 document.getElementById("save").disabled=true;
	// $("#showamount").html("Loading...");
	

		var myKeyVals = { app_id : appid, fy : fy}
		var saveData = $.ajax({ type: 'POST', url: "get_payable_amt.php", data: myKeyVals, dataType: "text",
			  success: function(resultData)
			  {     
			       document.getElementById("save").disabled=false;
			        $("#payableamt").html(resultData); 				 
			  }
		});
		      saveData.error(function() { /*alert("Something went wrong");*/
		 });
			  

}
//*****************End Waste payment  collection*********************




function taxcalculate(fy,appid,str,tax)
{
	//alert("hello");
	// document.getElementById("showamount"+str).innerHTML="<h4><center><font color='#0000CC'>Loading.....</font></center></h4>";	 
	 document.getElementById("save").disabled=true;
	// $("#showamount").html("Loading...");
	

		var myKeyVals = { app_id : appid, fy : fy, str : str,tax : tax}
		var saveData = $.ajax({ type: 'POST', url: "support/get_taxnew.php", data: myKeyVals, dataType: "text",
			  success: function(resultData)
			  {    
			       
			        $("#showamount"+str).html(resultData); 				 
			  }
		});
		     // saveData.error(function() { /*alert("Something went wrong");*/
//		 });
			  

}
//*

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}