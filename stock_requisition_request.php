<?php 
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task==''){ $task='Add';}
	if($_REQUEST['PTask']=='view') {

		$readonly="readonly";
		$disabled="disabled";
	} else {

		$readonly="";
		$disabled="";
	}
?>

<div class="container-xxl flex-grow-1 container-p-y ">
	<div class="row">     
		<div class="col-md-3">       
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Requisition Request</h4>
		</div>
		<!-- <div class="col-md-2">
		<?php if((CheckCreateMenu())==1) { ?>
			<button type="button" class="add_new btn btn-primary btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>
		<?php if((CheckDeleteMenu())==1) { ?>
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		</div> -->
	</div>

	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp; Sr.No.</th>
						<th>Date</th>
						<th>Record NO.</th>
						<th>Requisition By</th>
						<th>Status</th>
						<?php if((CheckEditMenu())==1) { ?> <th>Actions</th> <?php } ?>
					</tr>
				</thead>
			
				<tbody>
				<?php
					$i=0;
					$data1=$utilObj->getMultipleRow("production_requisition","1");
					foreach($data1 as $info1)
					{
						$i++;
						$href = 'stock_transfer_list.php?id=' .$info1['id'].'&PTask=send';
						$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."' ");

						if($i==1){
							$rowspan=Count($data1);
							$hidetd="";
						} else {
							$rowspan=1;
							$hidetd="hidetd";
						}

						$stockdetails = $utilObj->getMultipleRow("production_requisition_details","parent_id='".$info1['id']."' ");

						foreach($stockdetails as $details) {

							$flagValue = $details['flag'];
							$flagArray[] = $flagValue;

							if (in_array(0, $flagArray) && in_array(1, $flagArray)) {

								$status = 'Partial Completion';
							} elseif (in_array(0, $flagArray)) {

								$status = 'Pending';
							} else {

								$status = 'Completed';
							}
						}

				?>
					<tr>
						<td width='5%' >
							<input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info1['id']; ?>'/>&nbsp <?php echo $i; ?>
						</td> 

						<td >
							<?php echo date('d-m-Y',strtotime($info1['date'])); ?>
						</td>

						<td >
							<a href="<?php echo $href; ?>"><?php echo $info1['record_no']; ?></a>
						</td>

						<td >
							<?php echo $info1['requisition_by']; ?>
						</td>

						<td>
							<?php echo $status; ?>
						</td>
						
						<td >
						<?php if($status != 'Completed') { ?>
							<a href=<?php echo $href; ?> ><i class="fas fa-arrow-alt-circle-right fa-lg"></i></a>
						<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>
          
<script>

	

</script>


<?php 
	include("footer.php");
?>
