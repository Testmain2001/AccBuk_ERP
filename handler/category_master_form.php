<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
					
					$id=uniqid();
					
					
					$arrValue=array('id'=>$id,'cat_name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('stock_category_master', $arrValue);
					
					if($insertedId)
					echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				/* 
				//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				}  */
					
					$arrValue=array('cat_name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'],'LastEdited'=>date('Y-m-d H:i:s'));
					$strWhere="id='".$_REQUEST['id']."'  ";
					$Updaterec=$utilObj->updateRecord('stock_category_master', $strWhere, $arrValue);
					
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('stock_category_master', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
