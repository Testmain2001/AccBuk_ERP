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
<?php
    $loc=$utilObj->getMultipleRow("location","1");
?>

<div class="container-xxl flex-grow-1 container-p-y ">
    
    <div class="row">
		<div class="col-md-3">
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">Batchwise Stock Report</h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">

		<form id="" class=" form-horizontal" method="get" data-rel="myForm">
			<div class="row">
                <div class="col-md-3">
					<label class="form-label">Product<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="product" name="product" class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
						<?php
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($_REQUEST['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
					</select>
				</div>
                
				<div class="col-md-3">
					<label class="form-label">location<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("location","1");
							foreach($record as $e_rec)
							{
								if($_REQUEST['location']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
					</select>
				</div>
				<div class="col-md-2" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" class="btn btn-success" value="Search" />
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
						<th width='10%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
						<!-- <th width='10%'>Date</th> -->
						<th width='15%'>Batch Name</th>
						<th width='15%'>Batch Rate</th>
						<th width='15%'>Batch Stock</th>
					</tr>
				</thead>
	
				<tbody>
				<?php
					$k=0;
					
					if($_REQUEST['Task']=='filter' && $_REQUEST['location']!="All" && $_REQUEST['location']!=""){
						$cnd="location='".$_REQUEST['location']."' AND product='".$_REQUEST['product']."' ";
					}
					else {
						$cnd="1";
					}

					$data=$utilObj->getMultipleRow("purchase_batch","$cnd AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='physical_batch_in' OR type='production_in' OR type='packaging_in' )");

					foreach($data as $res) {

						if($res['product']==$_REQUEST['product']) {
						$k++;

						$totalstock = getbatchstock($res['id'],$_REQUEST['product'], date('Y-m-d'), $_REQUEST['location']);

				?>
					<tr>
						<td  class=" controls" ><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $res['id']; ?>'/>&nbsp&nbsp<?php echo $k; ?></td>
						<!-- <td><?php echo date('d-m-Y'); ?></td> -->
						<td><?php echo $res['batchname']; ?></td>
						<td><?php echo $res['bat_rate']; ?></td>
						<td><?php echo $totalstock; ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
				</tbody>
			</table>
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

function Search(){
	// var fromdate=$('#fromdate').val();
	// var todate=$('#todate').val();

	var product=$('#product').val();
	var location=$('#location').val();
	window.location="batch_stock_report.php?location="+location+"&product="+product+"&Task=filter";
}
// "&location="+location+
</script>

<!-- Footer -->
<?php 
	include("footer.php");
?>