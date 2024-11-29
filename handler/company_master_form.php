<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
    {
        switch($_REQUEST['PTask'])
        {
            case "Add":
                
                $id=uniqid();

                $arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'name'=>$_REQUEST['name'],'mailing_name'=>$_REQUEST['mailing_name'],'email'=>$_REQUEST['email'],'mobile_no'=>$_REQUEST['mobile_no'],'alt_mobile_no'=>$_REQUEST['alt_mobile_no'],'acc_period'=>$_REQUEST['acc_period'],'currency_symbol'=>$_REQUEST['currency_symbol'],'acc_decimal'=>$_REQUEST['acc_decimal'],'address'=>$_REQUEST['address'],'state'=>$_REQUEST['state'],'state_code'=>$_REQUEST['state_code'],'pin_code'=>$_REQUEST['pin_code'] );
				print_r($arrValue);
                
				$insertedId=$utilObj->insertRecord('company_master',$arrValue);

            break;

            case "update":

                $arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'name'=>$_REQUEST['name'],'mailing_name'=>$_REQUEST['mailing_name'],'email'=>$_REQUEST['email'],'mobile_no'=>$_REQUEST['mobile_no'],'alt_mobile_no'=>$_REQUEST['alt_mobile_no'],'acc_period'=>$_REQUEST['acc_period'],'currency_symbol'=>$_REQUEST['currency_symbol'],'acc_decimal'=>$_REQUEST['acc_decimal'],'address'=>$_REQUEST['address'],'state'=>$_REQUEST['state'],'state_code'=>$_REQUEST['state_code'],'pin_code'=>$_REQUEST['pin_code'] );
                print_r($arrValue);

                $strWhere="id='".$_REQUEST['id']."' ";
                $Updaterec=$utilObj->updateRecord('company_master', $strWhere, $arrValue);

            break;

            case "delete":	
				
				$pids=explode(",",$_REQUEST['id']);
				foreach($pids as $pid)
				{
					$strWhere="id='".$pid."' ";
					$Deleterec=$utilObj->deleteRecord('company_master', $strWhere);
				}
				
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;
        }
    }
?>