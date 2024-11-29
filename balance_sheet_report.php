<?php 
    include("header.php");
    $task=$_REQUEST['PTask'];
    if($task==''){ $task='Add';}
    if($_REQUEST['PTask']=='view')
    {
        $readonly="readonly";
        $disabled="disabled";
    }
    else
    {
        $readonly="";
        $disabled="";
    }
    
?>

<div class="container-xxl flex-grow-1 container-p-y ">
    <div class="row">     
        <div class="col-md-3">       
            <h4 class="fw-bold mb-4" style="padding-top:2px;">Balance Sheet Report</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom:12px;">
        <div class="col-md-6">
            <table class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:center;">Liability</th>
                    </tr>
                </thead>
                <tbody>
                    
                    
                    <tr class="accordion accordion-flush" id="accordionFlushExample_2">
                        <td style="width:250px;">
                            <h2 class="accordion-header" id="flush-headingOne_2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne_2" aria-expanded="false" aria-controls="flush-collapseOne_2">
                                    Current Liabilities
                                </button>
                            </h2>
                            <div id="flush-collapseOne_2" class="accordion-collapse collapse" aria-labelledby="flush-headingOne_2" data-bs-parent="#accordionFlushExample_2">
                                <table class="table table-striped" >
                                    <tbody>
                                        <?php
                                            $subgroup=$utilObj->getMultipleRow("group_master","parent_group='Current Liabilities' AND sub_report='Liability' ");

                                            $gtot='';
                                            foreach($subgroup as $sgrp) {

                                                $mate=$utilObj->getSingleRow("account_ledger","group_name='".$sgrp['id']."' ");

                                                $testmate=$utilObj->getMultipleRow("account_ledger","group_name='".$sgrp['id']."' ");
                                                
                                                foreach ($testmate as $tmate) {
                                                    
                                                    $mate1=$utilObj->getSum("purchase_invoice","supplier='".$tmate['id']."' ","grandtotal");

                                                    $mate2=$utilObj->getSum("purchase_return","supplier='".$tmate['id']."' ","grandtotal");

                                                    $stot1=$mate1+$mate2;

                                                    $gtot += $stot1;
                                                }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $sgrp['group_name']; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td style="width:250px;">
                            <div id="flush-collapseOne_2" class="accordion-collapse collapse" aria-labelledby="flush-headingOne_2" data-bs-parent="#accordionFlushExample_2">
                                <table class="table table-striped" >
                                    <tbody>
                                        <?php
                                            $subgroup=$utilObj->getMultipleRow("group_master","parent_group='Current Liabilities' AND sub_report='Liability' ");

                                            $gtot='';
                                            $stot='';
                                            foreach($subgroup as $sgrp) {

                                                $testmate=$utilObj->getMultipleRow("account_ledger","group_name='".$sgrp['id']."' ");
                                                
                                                foreach ($testmate as $tmate) {
                                                    
                                                    $mate1=$utilObj->getSum("purchase_invoice","supplier='".$tmate['id']."' ","grandtotal");

                                                    $mate2=$utilObj->getSum("purchase_return","supplier='".$tmate['id']."' ","grandtotal");

                                                    $mate3=$utilObj->getSum("purchase_invoice_service","igst_ledger='".$tmate['id']."' ","igst_amt");

                                                    $stot=$mate1+$mate2+$mate3;
                                                    print_r($stot);
                                                }
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $stot; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td style="width:250px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="col-md-6">
            <table class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th colspan="3" style="text-align:center;">Assets</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $group=$utilObj->getMultipleRow("group_master","parent_group='Primary' AND sub_report='Assets' ");
                        $j=0;
                        
                        foreach($group as $grp) {
                            $j++;
                            
                    ?>
                    <tr class="accordion accordion-flush" id="accordionFlushExample1_<?php echo $j; ?>">
                        <td style="width:250px;">
                            <h2 class="accordion-header" id="flush-headingOne1_<?php echo $j; ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne1_<?php echo $j; ?>" aria-expanded="false" aria-controls="flush-collapseOne1_<?php echo $j; ?>">
                                    <?php echo $grp['group_name']; ?>
                                </button>
                            </h2>
                            <div id="flush-collapseOne1_<?php echo $j; ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne1_<?php echo $j; ?>" data-bs-parent="#accordionFlushExample1_<?php echo $j; ?>">
                                <table class="table table-striped" >
                                    <tbody>
                                        <?php
                                            $subgroup=$utilObj->getMultipleRow("group_master","parent_group='".$grp['group_name']."' AND sub_report='Assets' ");

                                            $gtot='';
                                            foreach($subgroup as $sgrp) {

                                                $mate=$utilObj->getSingleRow("account_ledger","group_name='".$sgrp['id']."' ");
                                                $mate1=$utilObj->getSum("sale_invoice","customer='".$mate['id']."' ","grandtotal");
                                                $mate2=$utilObj->getSum("sale_return","customer='".$mate['id']."' ","grandtotal");
                                                $stot=$mate1+$mate2;

                                                $gtot += $stot;
                                        ?>
                                        <tr >
                                            <td>
                                                <?php echo $sgrp['group_name']; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        
                        <td style="width:250px;" class="tdalign">
                            <div id="flush-collapseOne1_<?php echo $j; ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne1_<?php echo $j; ?>" data-bs-parent="#accordionFlushExample1_<?php echo $j; ?>">
                                <table class="table table-striped" >
                                    <tbody>
                                        <?php
                                            $subgroup=$utilObj->getMultipleRow("group_master","parent_group='".$grp['group_name']."' AND sub_report='Assets' ");

                                            foreach($subgroup as $sgrp) {
                                                $mate=$utilObj->getSingleRow("account_ledger","group_name='".$sgrp['id']."' ");
                                                $mate1=$utilObj->getSum("sale_invoice","customer='".$mate['id']."' ","grandtotal");
                                                $mate2=$utilObj->getSum("sale_return","customer='".$mate['id']."' ","grandtotal");
                                                $stot=$mate1+$mate2;
                                        ?>
                                        <tr></tr>
                                        <tr>
                                            <td>
                                                <?php echo $stot; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>

                        <td style="vertical-align:top;" class="tdalign">
                            <?php echo $gtot; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<?php 
include("footer.php");
?>