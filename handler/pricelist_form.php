<?php

include '../config.php';
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
			
				$cnt=$_REQUEST['cnt'];
				
				$id=uniqid();
				for($i=1;$i<=$cnt;$i++)
				{
					$cnt1=$_REQUEST['cnt'.$i];
					for($j=1;$j<=$cnt1;$j++)
					{				
						$id2=uniqid();
						
						$arrValue=array('common_id'=>$id,'id'=>$id2,'ClientID'=>$_SESSION['Client_Id'],'stock_gruop'=>$_REQUEST['stock_gruop'],'cat_group'=>$_REQUEST['cat_group'],
						'price_level'=>$_REQUEST['price_level'],'applicable_date'=>date('Y-m-d',strtotime($_REQUEST['applicable_date'])),
						'particulars'=>$_REQUEST['particulars_'.$i],'from_qty'=>$_REQUEST['from_qty_'.$i.'_'.$j],
						'less_qty'=>$_REQUEST['less_qty_'.$i.'_'.$j],
						'rate'=>$_REQUEST['rate_'.$i.'_'.$j],'discount'=>$_REQUEST['discount_'.$i.'_'.$j],
						'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
						// var_dump($arrValue);
						$insertedId=$utilObj->insertRecord('pricelist', $arrValue);
						
					}
				}		
					if($insertedId)
					$Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				 
					$strWhere="common_id='".$_REQUEST['id']."' ";
					$Deleterec=$utilObj->deleteRecord('pricelist', $strWhere);  
					 
					$id=$_REQUEST['id'];	
					$cnt=$_REQUEST['cnt'];
					for($i=1;$i<=$cnt;$i++)
					{
						$cnt1=$_REQUEST['cnt'.$i];
						for($j=1;$j<=$cnt1;$j++)
						{				
							$id2=uniqid();
							$arrValue=array('common_id'=>$id,'id'=>$id2,'ClientID'=>$_SESSION['Client_Id'],'stock_gruop'=>$_REQUEST['stock_gruop'],
							'price_level'=>$_REQUEST['price_level'],'applicable_date'=>date('Y-m-d',strtotime($_REQUEST['applicable_date'])),
							'particulars'=>$_REQUEST['particulars_'.$i],'from_qty'=>$_REQUEST['from_qty_'.$i.'_'.$j],
							'less_qty'=>$_REQUEST['less_qty_'.$i.'_'.$j],
							'rate'=>$_REQUEST['rate_'.$i.'_'.$j],'discount'=>$_REQUEST['discount_'.$i.'_'.$j],
							'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
							$insertedId=$utilObj->insertRecord('pricelist', $arrValue);
							//var_dump($arrValue);
						}
					}
					if($Updaterec) 
				    $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
			case "delete":	
				
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="common_id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('pricelist', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;
			
		}
//	echo "<script>window.top.location='pricelist_form.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
		
	}
?>
