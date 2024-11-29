<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
    {
        switch($_REQUEST['PTask'])
        {
            case "Add":
                
                $id='1';

                $arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'pan_no'=>$_REQUEST['pan_no'],'tan_no'=>$_REQUEST['tan_no'],'gstin'=>$_REQUEST['gstin'],'lut_no'=>$_REQUEST['lut_no'],'cin_no'=>$_REQUEST['cin_no'],'pf_no'=>$_REQUEST['pf_no'],'esic_no'=>$_REQUEST['esic_no'],'pro_tax_no'=>$_REQUEST['pro_tax_no'] );
				print_r($arrValue);
                
				$insertedId=$utilObj->insertRecord('statutory_master',$arrValue);

            break;

            case "update":

                $arrValue=array('LastEdited'=>date('Y-m-d H:i:s'),'pan_no'=>$_REQUEST['pan_no'],'tan_no'=>$_REQUEST['tan_no'],'gstin'=>$_REQUEST['gstin'],'lut_no'=>$_REQUEST['lut_no'],'cin_no'=>$_REQUEST['cin_no'],'pf_no'=>$_REQUEST['pf_no'],'esic_no'=>$_REQUEST['esic_no'],'pro_tax_no'=>$_REQUEST['pro_tax_no'] );
                print_r($arrValue);

                $strWhere="id='".$_REQUEST['id']."' ";
                $Updaterec=$utilObj->updateRecord('statutory_master', $strWhere, $arrValue);

            break;

            case"delete":



            break;
        }
    }
?>