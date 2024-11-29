<?php
function concurrencycontrol($utilObj,$table,$date)
{
	$Date = strtotime($date);
	
	$id=$_REQUEST['id'];
	
	$tabledata=$utilObj->getSingleRow($table, "id ='".$id."' AND ClientID='".$_SESSION['Client_Id']."'");	
	//var_dump($tabledata);
	$LastEdited = strtotime($tabledata['LastEdited']);

	if($Date==$LastEdited)
	{
			return 0;	//Do Nothing
	}else
	{
			return 1;	//Break
	}
	
}

?>