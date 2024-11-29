<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$id=uniqid();
				
				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'name'=>$_REQUEST['name'],'parent_voucher'=>$_REQUEST['parent_voucher'],'numbering'=>$_REQUEST['numbering'],'numbering_digit'=>$_REQUEST['numbering_digit'],'numbering_code'=>$_REQUEST['numbering_code'],'codewidth'=>$_REQUEST['codewidth'],'prefix_label'=>$_REQUEST['prefix_label'],'decleration'=>$_REQUEST['decleration'],'narration'=>$_REQUEST['narration'],'printing_settings'=>$_REQUEST['printing_settings'],'scan'=>$_REQUEST['scan'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));

				$insertedId=$utilObj->insertRecord('voucher_type', $arrValue);
				
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';

			break;


			case "update":
				
				// echo $_REQUEST['LastEdited'];	//	Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				} 
					
				$arrValue=array('name'=>$_REQUEST['name'],'parent_voucher'=>$_REQUEST['parent_voucher'],'numbering'=>$_REQUEST['numbering'],'numbering_digit'=>$_REQUEST['numbering_digit'],'numbering_code'=>$_REQUEST['numbering_code'],'codewidth'=>$_REQUEST['codewidth'],'prefix_label'=>$_REQUEST['prefix_label'],'decleration'=>$_REQUEST['decleration'],'narration'=>$_REQUEST['narration'],'printing_settings'=>$_REQUEST['printing_settings'],'scan'=>$_REQUEST['scan'],'LastEdited'=>date('Y-m-d H:i:s'));
				
				// print_r($arrValue);
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('voucher_type', $strWhere, $arrValue);
				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! ';

			break;	

	
			case"delete":

				$pids=explode(",",$_REQUEST['id']);

				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('voucher_type', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! ';

			break;
			
		}	
	}
?>
