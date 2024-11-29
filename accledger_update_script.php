<?php
    include('config.php');

    $stock=$utilObj->getMultipleRow("account_ledger_test","1 AND actgrp='' ");

    foreach($stock as $ledger) {

        $getdata=$utilObj->getSingleRow2("group_master_test","id='".$ledger['group_name']."' ");

        $strWhere="id= '".$ledger['id']."' ";
        $arrValue=array('actgrp'=>$getdata['act_group']);

        $Updaterec=$utilObj->updateRecord('account_ledger_test', $strWhere, $arrValue); 

    }

    if($Updaterec) 
    echo 'Record has been Updated Sucessfully!';

?>