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
	//unset($_SESSION['cname']);
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
		//$_SESSION['cname']=$_REQUEST['cname'];

		
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
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Pending Requisition Report </h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">

		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
				<div class="col-md-3 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 

				<div class="col-md-3 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div>

				<!-- <div class="col-md-3">
					<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['location']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("location","1 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['location']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div> -->

				<div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
		</form>
	</div>

    <!-------------------------------- Invoice List Table --------------------------------->

	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
		
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
						<th width='3%'>Date</th>
						<th width='3%'>Requisition No</th>
						<th width='3%'>By</th>
						<th width='3%'>Product</th>
						<th width='3%'>Unit</th>
						<th width='3%'>Quantity</th>
						<th width='3%'>Pending Quantity</th>
						<th width='3%'>Status</th>
						<!-- <th width='3%'>User</th> -->
					</tr>
				</thead>
	
				<tbody>
				<?php
					$i=0;
					$k=0;

					// if($_REQUEST['Task']=='filter' && $_REQUEST['location']!="All" && $_REQUEST['location']!="") {

					// 	$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND location='".$_REQUEST['location']."'";
					// } else {
						
					// 	$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					// }

					if($_REQUEST['Task']=='filter') {

						$cnd="Created>='".$_SESSION['FromDate']."'AND Created<='".$_SESSION['ToDate']."' ";
					} else {

						$cnd="Created>='".$_SESSION['FromDate']."' AND Created<='".$_SESSION['ToDate']."'";
					}
					
					// $data=$utilObj->getMultipleRow("purchase_requisition","$cnd");
					$data=$utilObj->getMultipleRow("purchase_requisition_details","$cnd");

					foreach($data as $info){
						
						$i++;$j=0;
						$href= 'purchase_requisition_list.php?id='.$info['parent_id'].'&PTask=view';
						
						$data1=$utilObj->getSingleRow("purchase_requisition","id='".$info['parent_id']."' ");
						$data2=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");

						$j++;

						$k++;
				?>
					<tr>
						<td class=" controls" ><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['parent_id']; ?>'/>&nbsp&nbsp<?php echo $k; ?></td> 
						<td class="" > <?php echo date('d-m-Y',strtotime($data1['date'])); ?> </td>
						<td class="" > <?php echo $data1['record_no']; ?></td>
						<td class="" > <?php echo $data1['requisition_by']; ?></td>
						<td><?php echo $data2['name']; ?></td>
						<td><?php echo $info['unit']; ?></td>
						<td><?php echo $info['qty']; ?></td>
						<td><?php echo $info['rm_qty']; ?></td>
						<td><?php if ($data1['rm_qty'] == 0) {
							?> Completed <?php
						} else {
							?> Pending <?php
						}
						?></td>
						<?php $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
						<!-- <td ><?php echo $username['name']; ?></td> -->
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
                    <tr>
                        <td colspan='8' style="text-align:right;">
                            Create PO :
                        </td>
                        <td style="padding-right:120px;">
                            <button class="btn btn-primary mr-2 btn-sm" data-bs-target="#porequi" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new_<?php echo $i; ?>" onclick="updateledger();"><i class="fas fa-plus-circle fa-lg"></i></button>
                        </td>
                    </tr>
                </tfoot>
			</table>
		</div>
	</div>

	<div class="modal fade" id="gen_po" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
            <div class="modal-content" id="po_gen" >
                
            </div>
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
		var location=$('#location').val();

		// window.location="pending_requisition_report.php?FromDate="+fromdate+"&ToDate="+todate+"&location="+location+"&Task=filter";
		window.location="pending_requisition_report.php?FromDate="+fromdate+"&ToDate="+todate+"&Task=filter";
	}

	function updateledger() {

        var sid='';

        $('input[type="checkbox"]').each(function() {
            	
            if(this.checked==true && this.value!='on')
            {
                
                sid +=this.value+",";
            }
        });

        // alert(sid);

        if(sid=='') {

            alert('Please Select Atleast 1 record!!!!');
            location.reload();
        } else {

            sendrequi(sid);
        }

    }
	
    function sendrequi(sid) {

        var PTask = "add";

        jQuery.ajax({ url: 'get_ajax_values.php', type: 'POST',
            data: { Type: 'generate_po', sid:sid,PTask:PTask },
            success: function (data) {

                $('#po_gen').html(data);
                $('#gen_po').modal('show');
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