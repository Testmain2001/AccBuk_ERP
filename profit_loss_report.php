<?php 
    include("header.php");
    
?>

<div class="container-xxl flex-grow-1 container-p-y ">
    <div class="row">     
        <div class="col-md-3">       
            <h4 class="fw-bold mb-4" style="padding-top:2px;">Profit & Loss Report</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom:12px;">
        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th  colspan="3" style="text-align:center;">Debit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $data=$utilObj->getMultipleRow("group_master","parent_group='Primary' AND report_type='Debit' ");

                        foreach($data as $info) {
                    ?>
                    <tr>
                        <td style="width:100px;">
                            <?php echo $info['group_name']; ?>
                        </td>

                        <td style="width:50px;">

                        </td>

                        <td style="width:50px;">
                            
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="2">Grand Total</th>
                        <th>123456</th>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th  colspan="3" style="text-align:center;">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $data=$utilObj->getMultipleRow("group_master","parent_group='Primary' AND report_type='Crebit' ");

                        foreach($data as $info) {
                    ?>
                    <tr>
                        <td style="width:100px;">
                            <?php echo $info['group_name']; ?>
                        </td>
                        
                        <td style="width:50px;">

                        </td>

                        <td style="width:50px;">

                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="2">Grand Total</th>
                        <th>123456</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<?php 
include("footer.php");
?>