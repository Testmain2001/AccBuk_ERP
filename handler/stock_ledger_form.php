<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$id=uniqid();

				$arrValue=array('id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'],'cat_id'=>$_REQUEST['cat_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'sale_invoicing'=>$_REQUEST['sale_invoicing'],'unit'=>$_REQUEST['unit'],'alt_unit'=>$_REQUEST['alt_unit'],'unit_qty'=>$_REQUEST['unit_qty'],'altunit_qty'=>$_REQUEST['altunit_qty'],'batch_maintainance'=>$_REQUEST['batch_maintainance'],'bill_of_material'=>$_REQUEST['bill_of_material'],'cost_tracking'=>$_REQUEST['cost_tracking'],'costing_method'=>$_REQUEST['costing_method'],'consumed'=>$_REQUEST['consumed'],'new_mfg'=>$_REQUEST['new_mfg'],'description'=>$_REQUEST['description'],'hsn_sac'=>$_REQUEST['hsn_sac'],'non_gst'=>$_REQUEST['non_gst'],'cal_type'=>$_REQUEST['cal_type'],'taxability'=>$_REQUEST['taxability'],'rev_charge'=>$_REQUEST['rev_charge'],'ineligible_input'=>$_REQUEST['ineligible_input'],'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'cess'=>$_REQUEST['cess'],'sale_local'=>$_REQUEST['sale_local'],'purchase_local'=>$_REQUEST['purchase_local'],'sale_outstate'=>$_REQUEST['sale_outstate'],'purchase_outstate'=>$_REQUEST['purchase_outstate'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'reorderlvl'=>$_REQUEST['reorderlvl'],'mfg_maintainance'=>$_REQUEST['mfg_maintainance'],'exp_maintainance'=>$_REQUEST['exp_maintainance'] );

				$insertedId=$utilObj->insertRecord('stock_ledger', $arrValue);

				$type = "stock_ledger";
					
				$arrValue1 = array('id'=>uniqid(),'product'=>$id,'ClientID'=>$_SESSION['Client_Id'],'fromdate'=>date('Y-m-d'),'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$type );

				$insertedId=$utilObj->insertRecord('ledger_gst_history', $arrValue1);
					
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully!';

			break;


			case "update":
				 
				// echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				}

				$rows=$utilObj->getSingleRow("ledger_gst_history","product ='".$_REQUEST['id']."' ");

				if($rows['todate']==NULL) {

					$todate = date('Y-m-d',strtotime("-1 days"));

					$strWhere="product='".$_REQUEST['id']."' ";

					$arr = array('todate'=>$todate);
					$Updaterec1=$utilObj->updateRecord('ledger_gst_history', $strWhere, $arr);
					 
					$arrValue1=array('id'=>uniqid(),'product'=>$_REQUEST['id'],'ClientID'=>$_SESSION['Client_Id'],'fromdate'=>date('Y-m-d'),'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$rows['type'] );

					$insertedId=$utilObj->insertRecord('ledger_gst_history', $arrValue1);
				}

				$arrValue=array('name'=>$_REQUEST['name'],'under_group'=>$_REQUEST['under_group'], 'cat_id'=>$_REQUEST['cat_group'],'negative_stk_block'=>$_REQUEST['negative_stk_block'],'sale_invoicing'=>$_REQUEST['sale_invoicing'],'unit'=>$_REQUEST['unit'],'alt_unit'=>$_REQUEST['alt_unit'],'unit_qty'=>$_REQUEST['unit_qty'],'altunit_qty'=>$_REQUEST['altunit_qty'],'batch_maintainance'=>$_REQUEST['batch_maintainance'],'bill_of_material'=>$_REQUEST['bill_of_material'],'cost_tracking'=>$_REQUEST['cost_tracking'],'costing_method'=>$_REQUEST['costing_method'],'consumed'=>$_REQUEST['consumed'],'new_mfg'=>$_REQUEST['new_mfg'],'description'=>$_REQUEST['description'],'hsn_sac'=>$_REQUEST['hsn_sac'],'non_gst'=>$_REQUEST['non_gst'],'cal_type'=>$_REQUEST['cal_type'],'taxability'=>$_REQUEST['taxability'],'rev_charge'=>$_REQUEST['rev_charge'],'ineligible_input'=>$_REQUEST['ineligible_input'],'igst'=>$_REQUEST['igst'],'cgst'=>$_REQUEST['cgst'],'sgst'=>$_REQUEST['sgst'],'cess'=>$_REQUEST['cess'],'sale_local'=>$_REQUEST['sale_local'],'purchase_local'=>$_REQUEST['purchase_local'],'sale_outstate'=>$_REQUEST['sale_outstate'],'purchase_outstate'=>$_REQUEST['purchase_outstate'],'LastEdited'=>date('Y-m-d H:i:s'),'reorderlvl'=>$_REQUEST['reorderlvl'],'mfg_maintainance'=>$_REQUEST['mfg_maintainance'],'exp_maintainance'=>$_REQUEST['exp_maintainance'] );
				
				$strWhere="id='".$_REQUEST['id']."'  ";
				$Updaterec=$utilObj->updateRecord('stock_ledger', $strWhere, $arrValue);
				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully!';

			break;	

	
			case"delete":

				$pids=explode(",",$_REQUEST['id']);

				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('stock_ledger', $strWhere);

					$strWhere1="product='".$pid."' ";
					$Deleterec1=$utilObj->deleteRecord('ledger_gst_history', $strWhere1);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 

			break;


			
		}	
	}
?>
