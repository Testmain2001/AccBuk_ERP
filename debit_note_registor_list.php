<?php 
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task=='') { $task='Add'; }

	if($_REQUEST['PTask']=='view') {

		$readonly="readonly";
		$disabled="disabled";
	} else {

		$readonly="";
		$disabled="";
	}

	unset($_SESSION['FromDate']);
	unset($_SESSION['ToDate']);
	// unset($_SESSION['cname']);

	if($_REQUEST['Task']=='filter') {

		$from=$_REQUEST['FromDate'];
		$Date1=date('Y-m-d',strtotime($from));
		
		$to=$_REQUEST['ToDate'];
		$Date=date('Y-m-d',strtotime($to));
		
		
		$_SESSION['FromDate']=date($Date1);
		$_SESSION['ToDate']=date($Date);
		$inputfrom=date('d-m-Y',strtotime($from));
		$inputto=date('d-m-Y',strtotime($to));
		// $_SESSION['cname']=$_REQUEST['cname'];

	} else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='') {

		$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}
?>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Debit Note Registor </h4>
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
		  		<div class="col-md-3">
					<label class="form-label" >Account <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"   class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
						<option value="All" <?php if($_REQUEST['account_ledger']=="All") { echo "selected"; }else { echo ""; } ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['account_ledger']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>
				<div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
		 </form>
	</div>


	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='1%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> &nbsp; Sr. NO</th>
						<th width='5%' >Date</th>
						<th width='15%' >Particular</th>
						<th width='5%' class="tdalign">Voucher Type</th>
						<th width='7%' class="tdalign">Voucher No</th>
						<th width='3%' class="tdalign">GrandTotal</th>
						<th width='5%' class="tdalign">Ass. Value</th>
						<th width='4%' class="tdalign">CGST AMT</th>
						<th width='4%' class="tdalign">SGST AMT</th>
						<th width='4%' class="tdalign">IGST AMT</th>
						<th width='4%' class="tdalign">User</th>
					</tr>
				</thead>
   
				<tbody>
				<?php
					$i=0;
					if($_REQUEST['Task']=='filter'&& $_REQUEST['account_ledger']!="All"&&$_REQUEST['supplier']!="") {

						$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND supplier='".$_REQUEST['supplier']."'";
					} else {

						$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					}
			
					$data=$utilObj->getMultipleRow("debitnote_acc"," $cnd");
					foreach($data as $info) {
							
						$i++;$j=0;

						$href='purchase_invoice_list.php?id='.$info['id'].'&PTask=view';
						
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
						$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");

						$data1=$utilObj->getMultipleRow("debitnote_acc_details","parent_id='".$info['id']."'");
						foreach($data1 as $info1)
						{
							$j++;
							
							$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
							if($j==1) {
								
								$rowspan=Count($data1);
								$hidetd="";
							} else {

								$rowspan=1;
								$hidetd="hidetd";
							}
		
				?>
					<tr>
					<td >
							<input type='checkbox' class='checkboxes' <?php //echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/>  &nbsp; <?php echo $i; ?>
						</td>

						<td >
							<?php echo date('d-m-Y',strtotime($info['date'])); ?>
						</td>

						<td >
							<a href="<?php echo $href; ?>"><?php echo $supplier['name']; ?></a>
						</td>

						<td>
							<?php echo $voucher['name']; ?>
						</td>

						<td>
							<?php echo $info['voucher_code']; ?>
						</td>

						<td class="tdalign" >
							<?php echo $info['grandtotal']; ?>
						</td>

						<td class="tdalign" >
							<?php echo $info1['service_subtotal']; ?>
						</td>

						<td class="tdalign" >
							<?php echo $info['cgst_amt']; ?>
						</td>
						
						<td class="tdalign" >
							<?php echo $info['sgst_amt']; ?>
						</td>

						<td class="tdalign" >
							<?php echo $info['igst_amt']; ?>
						</td>

						<?php $username=$utilObj->getSingleRow("employee","id='".$info['user']."' "); ?>
						<td class="<?php echo $hidetd; ?> tdalign" rowspan="<?php echo $rowspan; ?>">
							<?php echo $username['name']; ?>
						</td>

					</tr>
					<?php 
						// $i=$i+1;
					?>
					<?php } ?>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>
		  

<script>

	window.onload=function() {
		
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
		var supplier=$('#supplier').val();

		window.location="debit_note_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&supplier="+supplier+"&Task=filter";
	}


</script>


<?php 
	include("footer.php");
?>
