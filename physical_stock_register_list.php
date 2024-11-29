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
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Physical Stock Registor </h4>
		</div>
		
	</div>
	<div class="row" style="margin-bottom:12px;">

		<form id="" class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">

				<div class="col-md-2 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 
				<div class="col-md-2">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div>
				
				<div class="col-md-3">
					<label class="form-label" >Product <span class="required required_lbl" style="color:red;">*</span></label>
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
					<select id="location" name="location"  onchange="get_locationwise_productstock_forphysical();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("location","1 ");
							foreach($record as $e_rec)
							{
								if($_REQUEST['location']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
					</select>
				</div>
				<div class="col-md-2" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
		 </form>
	</div>
	<!-- Invoice List Table -->


	<div class="card">
		<div class="card-datatable table-responsive pt-0">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
					<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
					<th width='10%'>Date</th>
					<th width="10%">Record No.</th>
					<th width='10%'>location</th>
					<th width='10%'>Product</th>
					<th width='10%'>Physical Stock</th>
					</tr>
				</thead>
		
				<tbody>
				<?php
					$i=1;
					if($_REQUEST['Task']=='filter'&& $_REQUEST['product']!=""&&$_REQUEST['location']!=""){
						$cnd="product='".$_REQUEST['product']."' AND parent_id in ( select id  from physical_stock where  location='".$_REQUEST['location']."' AND date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."')";
					
					}elseif($_REQUEST['Task']=='filter'&& $_REQUEST['product']!=""&&$_REQUEST['location']==""){
						$cnd="product='".$_REQUEST['product']."' AND parent_id in ( select id  from physical_stock where   date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."')";
					
					}else if($_REQUEST['Task']=='filter'&& $_REQUEST['location']!="" && $_REQUEST['product']==""){
						$cnd="parent_id in ( select id  from physical_stock where  location='".$_REQUEST['location']."' AND date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."')";
					}else{
						$cnd=" parent_id in ( select id  from physical_stock where   date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."')";
					
					}
					
					$data=$utilObj->getMultipleRow("physical_stock_details"," $cnd ");
					foreach($data as $info){
						
					$href= 'physical_stock_list.php?id='.$info['id'].'&PTask=view';
					/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}else{
						$dis="";
					} */
					// $customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
					$physical_stock=$utilObj->getSingleRow("physical_stock","id='".$info['parent_id']."'");
					$location=$utilObj->getSingleRow("location","id='".$physical_stock['location']."'");
					$stock_ledger=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."'");
					
				?>
					<tr>
						<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
						<td> <?php echo $physical_stock['date']; ?> </td>
						<td><a href="<?php echo $href; ?>"><?php echo $physical_stock['record_no']; ?></a> </td>
						<td> <?php echo $location['name']; ?> </td>
						<td> <?php echo $stock_ledger['name']; ?></td>
						<td><?php echo $info['physicalstock']; ?></td>
					</tr>
					<?php 
						$i=$i+1;
					?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!--/ Content -->
		  

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
		var fromdate=$('#fromdate').val();
		var todate=$('#todate').val();
		var product=$('#product').val();
		var location=$('#location').val();
		window.location="physical_stock_register_list.php?FromDate="+fromdate+"&ToDate="+todate+"&product="+product+"&location="+location+"&Task=filter";
	}

</script>

<?php 
	include("footer.php");
?>
