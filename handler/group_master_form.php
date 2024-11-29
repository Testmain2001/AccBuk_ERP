<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
					
					$id=uniqid();
					$rows=$utilObj->getSingleRow("group_master","group_name ='".$_REQUEST['parent_group']."'"); 
					
					$arrValue=array('id'=>$id,'parent_id'=>$_REQUEST['parent_id'],'group_name'=>$_REQUEST['name'],'parent_group'=>$_REQUEST['parent_group'],'report'=>$rows['report'],'sub_report'=>$rows['sub_report'],'report_type'=>$rows['report_type'],'flag'=>1,'act_group'=>$_REQUEST['act_group'] );
					 $insertedId=$utilObj->insertRecord('group_master', $arrValue);
					// echo $id."##".$_REQUEST['name'];
					
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
			
				$rows=$utilObj->getSingleRow("group_master","id ='".$_REQUEST['parent_group']."'"); 
				
				$arrValue=array('parent_id'=>$_REQUEST['parent_id'],'group_name'=>$_REQUEST['name'],'parent_group'=>$_REQUEST['parent_group'],
				'report'=>$rows['report'],'sub_report'=>$rows['sub_report'],'report_type'=>$rows['report_type'],'flag'=>1,'act_group'=>$_REQUEST['act_group']);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('group_master', $strWhere, $arrValue);
				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully!';
			
			break;	

	
			case"delete":

				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('group_master', $strWhere);
				}
			
				echo $Msg='Record has been Deleted Sucessfully!'; 

			break;


			
		}	
	}
?>
