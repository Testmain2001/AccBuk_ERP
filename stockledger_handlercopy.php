<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
					$id=uniqid();
					
					$arrValue=array('id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'sale_invoicing'=>$_REQUEST['sale_invoicing'],'unit'=>$_REQUEST['unit'],'alt_unit'=>$_REQUEST['alt_unit'],'unit_qty'=>$_REQUEST['unit_qty'],'altunit_qty'=>$_REQUEST['altunit_qty'],'batch_maintainance'=>$_REQUEST['batch_maintainance'],'bill_of_material'=>$_REQUEST['bill_of_material'],'cost_tracking'=>$_REQUEST['cost_tracking'],'costing_method'=>$_REQUEST['costing_method'],'consumed'=>$_REQUEST['consumed'],'new_mfg'=>$_REQUEST['new_mfg'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('stock_ledger', $arrValue);
					
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
					
					$arrValue=array('name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'sale_invoicing'=>$_REQUEST['sale_invoicing'],'unit'=>$_REQUEST['unit'],'alt_unit'=>$_REQUEST['alt_unit'],'unit_qty'=>$_REQUEST['unit_qty'],'altunit_qty'=>$_REQUEST['altunit_qty'],'batch_maintainance'=>$_REQUEST['batch_maintainance'],'bill_of_material'=>$_REQUEST['bill_of_material'],'cost_tracking'=>$_REQUEST['cost_tracking'],'costing_method'=>$_REQUEST['costing_method'],'consumed'=>$_REQUEST['consumed'],'new_mfg'=>$_REQUEST['new_mfg'],'LastEdited'=>date('Y-m-d H:i:s'));
					$strWhere="id='".$_REQUEST['id']."'  ";
					$Updaterec=$utilObj->updateRecord('stock_ledger', $strWhere, $arrValue);
					
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('stock_ledger', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
