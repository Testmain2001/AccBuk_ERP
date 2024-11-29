<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
					
					$id=uniqid();
					
					
					$arrValue=array('id'=>$id,'name'=>$_REQUEST['name'],'ClientID'=>$_SESSION['Client_Id'],'under_group'=>$_REQUEST['under_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'pos_check'=>$_REQUEST['pos_check'],'accour_led_id'=>$_REQUEST['accour_led_id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('location', $arrValue);
					
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
					
					$arrValue=array('name'=>$_REQUEST['name'],'ClientID'=>$_SESSION['Client_Id'],'under_group'=>$_REQUEST['under_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'pos_check'=>$_REQUEST['pos_check'],'accour_led_id'=>$_REQUEST['accour_led_id'],'LastEdited'=>date('Y-m-d H:i:s'));
					$strWhere="id='".$_REQUEST['id']."'  ";
					$Updaterec=$utilObj->updateRecord('location', $strWhere, $arrValue);
					//var_dump($arrValue);die();
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('location', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
