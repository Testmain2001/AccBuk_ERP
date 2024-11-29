<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
					
					$id=uniqid();
					// $sample= "grn";
					
					$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'currency_symbol'=>$_REQUEST['currency_symbol'],'formal_name'=>$_REQUEST['formal_name'],'decimal_places'=>$_REQUEST['decimal_places'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('currency', $arrValue);
					
					
					if($insertedId)
					echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				} 
				
					$arrValue=array('updateduser'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],
					'currency_symbol'=>$_REQUEST['currency_symbol'],'formal_name'=>$_REQUEST['formal_name'],'decimal_places'=>$_REQUEST['decimal_places'],'LastEdited'=>date('Y-m-d H:i:s'));
					$strWhere="id='".$_REQUEST['id']."'";
					$Updaterec=$utilObj->updateRecord('currency', $strWhere, $arrValue);
					
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('currency', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
