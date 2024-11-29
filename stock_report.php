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
<?php
	$user=$utilObj->getSingleRow("employee","id ='".$_SESSION['Ck_User_id']."' ");
	
	// $loc=$utilObj->getMultipleRow("location","1");
	
?>

<div class="container-xxl flex-grow-1 container-p-y ">
    
    <div class="row">     
		<div class="col-md-3">       
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Report </h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">

		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
				<!-- <div class="col-md-3 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 

				<div class="col-md-3 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div> -->

				<div class="col-md-3">
					<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
						<?php

							$place = explode(",",$user['multiloc']);
							foreach($place as $pid) {

								$data=$utilObj->getMultipleRow("location","id = '".$pid."' group by id");

								foreach($data as $info) {

									$loc=$utilObj->getSingleRow("location","1 ");

									if($info['id']==$_REQUEST['location']){ echo $select="selected"; } else { echo $select=""; }
									echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
								}  
							}
						?>
					</select>
				</div>

				<div class="col-md-3">
					<label class="form-label" >Ledger: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="stkgrp" name="stkgrp" class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['stkgrp']=="All"){ echo "selected"; }else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("stock_group","1 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['stkgrp']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>

				<div class="col-md-2" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success btn-sm" value="Search" style="margin-top: 2px;" />
				</div>
			</div>
		</form>
	</div>

	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='2%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
						<th width='5%'>Date</th>
						<th width='10%'>Product</th>
						<th width='8%'>Closing Stock</th>
						<th width='8%'>Rate</th>
						<th width='10%'>Amount</th>
					</tr>
				</thead>
	
				<tbody>
				<?php
					$i=0;

					if($_REQUEST['Task']=='filter' && $_REQUEST['location']!="" && $_REQUEST['stkgrp']!="All") {

						$cnd="under_group='".$_REQUEST['stkgrp']."' ";
					} elseif($_REQUEST['stkgrp']=='All' && $_REQUEST['Task']=='filter') {

						$cnd="1";
					} else {

						$cnd="1";
					}
					
					$location = $_REQUEST['location'];
					$data=$utilObj->getMultipleRow("stock_ledger","$cnd");

					foreach($data as $res) {
						if($res['under_group']==$_REQUEST['stkgrp'] || $_REQUEST['stkgrp']=='All') {

						$i++;
						$firstDateOfMonth = date("Y-m-01");
						$currentDate = date("Y-m-d");
						$href= 'stock_statement_report.php?FromDate='.$firstDateOfMonth.'&ToDate='.$currentDate.'&ledger='.$res['id'].'&PTask=view';

						// stock_statement_report.php?FromDate=$firstDateOfMonth&ToDate=26-10-2024&ledger=6667e24042d28&Task=filter

						$tostock = getlocationstock('',$res['id'], date('Y-m-d'), $location);

						$recrate=mysqli_query($GLOBALS['con'],"SELECT rate FROM ( SELECT product, rate, MAX(LastEdited) AS latest_LastEdited FROM ( SELECT product, rate, LastEdited FROM grn_details UNION ALL SELECT product, rate, LastEdited FROM purchase_invoice_details UNION ALL SELECT product, rate, LastEdited FROM production UNION ALL SELECT product, rate, LastEdited FROM packaging ) AS combined_tables WHERE product = '".$res['id']."' GROUP BY product, rate ORDER BY latest_LastEdited DESC ) AS subquery LIMIT 1 ");

						$rrate=mysqli_fetch_array($recrate);
						$rate = $rrate['rate'];

						$amt = $tostock*$rate;

				?>
					<tr>
						<td  class=" controls" >
							<input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $res['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?>
						</td>
						
						<td><?php echo date('d-m-Y'); ?></td>
						<td><a href="<?php echo $href; ?>" target="_blank"><?php echo $res['name']; ?></a></td>
						<td><?php echo $tostock; ?></td>
						<td><?php echo $rate; ?></td>
						<td><?php echo $amt; ?></td>
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

	function Search() {

		var location=$('#location').val();
		var stkgrp=$('#stkgrp').val();

		window.location="stock_report.php?location="+location+"&stkgrp="+stkgrp+"&Task=filter";
	}

	// "&location="+location+
</script>

<!-- Footer -->
<?php 
	include("footer.php");
?>