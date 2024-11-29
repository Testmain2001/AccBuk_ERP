<?php
    include('config.php');

    $stock=$utilObj->getMultipleRow("stock_ledger","id not in(select product from ledger_gst_history) ");

    foreach($stock as $ledger) {

        $arrValue1=array('id'=>uniqid(),'product'=>$ledger['id'],'ClientID'=>$_SESSION['Client_Id'],'fromdate'=>date('Y-m-d',strtotime($ledger['Created'])),'igst'=>$ledger['igst'],'cgst'=>$ledger['cgst'],'sgst'=>$ledger['sgst'],'Created'=>$ledger['Created'],'LastEdited'=>$ledger['LastEdited'],'type'=>'stock_ledger' );

        $insertedId=$utilObj->insertRecord('ledger_gst_history', $arrValue1);
    }

?>