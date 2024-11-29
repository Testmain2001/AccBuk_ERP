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
	unset($_SESSION['FromDate']);
	unset($_SESSION['ToDate']);
	// unset($_SESSION['cname']);

	if($_REQUEST['Task']=='filter')
	{
		$from=$_REQUEST['FromDate'];
		$Date1=date('Y-m-d',strtotime($from));
		
		$to=$_REQUEST['ToDate'];
		$Date=date('Y-m-d',strtotime($to));
		
		$_SESSION['FromDate']=date($Date1);
		$_SESSION['ToDate']=date($Date);
		$inputfrom=date('d-m-Y',strtotime($from));
		$inputto=date('d-m-Y',strtotime($to));
		// $_SESSION['cname']=$_REQUEST['cname'];
	}
	else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='')
	{
		$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}

?>

<div class="container-xxl flex-grow-1 container-p-y ">

    <div class="row">
		<div class="col-md-3">
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">Cost Tracking Report</h4>
		</div>
	</div>
    
    <div class="row">

        <div class="col-md-3 ">
            <label  class="form-label">FromDate</label>
            <input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom; ?>" />
        </div>

        <div class="col-md-3 ">
            <label  class="form-label">ToDate</label>
            <input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto; ?>">
        </div>

        <div class="col-md-3" style="padding-top:25px;">
            <input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">

            <table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
                <thead>
                    <tr>
                        <th style="text-align:center;">Sr No</th>
                        <th style="text-align:center;">Product</th>
                        <th style="text-align:center;">Batch Name</th>
                        <th style="text-align:center;">Batch Rate</th>
                        <th style="text-align:center;">Quantity</th>
                        <th style="text-align:center;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=0;
                        
                        if($_REQUEST['Task']=='filter') {
                            $cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."' ";
                        } else {
                            $cnd=" date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."' ";
                        }
                        
                        $batch = $utilObj->getMultipleRow("purchase_batch", "$cnd AND (type='production_in' OR type='packaging_in') ");

                        foreach($batch as $info) {

                        $product=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");
                        $i++;
                    ?>
                    <tr>
                        <input type="hidden" name="batchid_<?php echo $i; ?>" id="batchid_<?php echo $i; ?>" value="<?php echo $info['parent_id']; ?>">

                        <input type="hidden" name="batchtype_<?php echo $i; ?>" id="batchtype_<?php echo $i; ?>" value="<?php echo $info['type']; ?>">

                        <td style="text-align:center;"><?php echo $i; ?></td>
                        <td style="text-align:center;">
                            <?php echo $product['name']; ?>
                            
                        </td>
                        <td style="text-align:center;">
                            <?php echo $info['batchname']; ?>
                        </td>
                        <td style="text-align:center;">
                            <?php echo $info['bat_rate']; ?>
                        </td>
                        <td style="text-align:center;">
                            <?php echo $info['batqty']; ?>
                        </td>
                        <td style="text-align:center;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="costreportdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#costreport">Batch</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<div class="modal fade" style = "max-width=40%;" id="costreport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="costtrack">
    
        </div>
    </div>
</div>

<script>
    
    window.onload=function(){
        $("#fromdate").flatpickr({
            dateFormat: "d-m-Y"
        });
        $("#todate").flatpickr({
            dateFormat: "d-m-Y"
        });
    }

    function Search() {

        var fromdate=$('#fromdate').val();
        var todate=$('#todate').val();

        window.location="cost_tracking_report.php?FromDate="+fromdate+"&ToDate="+todate+"&Task=filter";

    }

    function costreportdata(i) {
								                      
        var batchid =$("#batchid_"+i).val();
        var batchtype =$("#batchtype_"+i).val();

        jQuery.ajax({
            url: 'get_ajax_values.php',
            type: 'POST',
            data: { Type: 'costreportdata', batchid:batchid, batchtype:batchtype },
            success: function (data) {
                $('#costtrack').html(data);
                $('#costreport').modal('show');
        
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

</script>

<?php 
	include("footer.php");
?>