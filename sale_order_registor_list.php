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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Order Registor </h4>
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
					<label class="form-label" >Customer: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['customer']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['customer']){echo $select="selected";}else{echo $select="";}
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
		<div class="card-datatable table-responsive pt-0">
		
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
				<tr>
						<th width='2%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> &nbsp; Sr. NO</th>
						<th width='2%'>Date</th>
						<th width='12%'>Particular</th>
						<th width='10%'>Voucher Type</th>
						<th width='5%'>Voucher No</th>
						<th width='5%'>Ass. Value</th>
						<th width='5%'>CGST AMT</th>
						<th width='5%'>SGST AMT</th>
						<th width='5%'>IGST AMT</th>
						<th width='5%'>User</th>
					</tr>
				</thead>
			
				<tbody>
				<?php

					$i=0;
					if($_REQUEST['Task']=='filter'&& $_REQUEST['customer']!="All"&&$_REQUEST['customer']!=""){
						$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND customer='".$_REQUEST['customer']."'";
					}/* else if($_REQUEST['Task']=='filter'&& $_REQUEST['customer']=="All"){
						$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					} */else{
						$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					}
					
					$data=$utilObj->getMultipleRow("sale_order"," $cnd");
					foreach($data as $info) {

						$i++;$j=0;
						$href= 'sale_order_list.php?id='.$info['id'].'&PTask=view';
						//$d1=$rows=$utilObj->getCount("delivery_challan","saleorder_no ='".$info['id']."'");

						if($d1>0) {
							
							$dis="disabled";
						} else {

							$dis="";
						}

						$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
						$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
						//$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");

						$data1=$utilObj->getMultipleRow("sale_order_details","parent_id='".$info['id']."'");
						foreach($data1 as $info1)
						{
							$j++;
							$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
							if($j==1){
								$rowspan=Count($data1);
								$hidetd="";
							}else{
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
							<a href="<?php echo $href; ?>"><?php echo $customer['name']; ?></a>
						</td>

						<td>
							<?php echo $voucher['name']; ?>
						</td>

						<td>
							<?php echo $info['saleino_code']; ?>
						</td>

						<td>
							<?php echo $info['grandtotal']; ?>
						</td>

						<td>
							<?php echo $info['cgstamt']; ?>
						</td>
						
						<td>
							<?php echo $info['sgstamt']; ?>
						</td>

						<td>
							<?php echo $info['igstamt']; ?>
						</td>

						<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
							<?php echo $username['name']; ?>
						</td>
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

	var fromdate=$('#fromdate').val();
	var todate=$('#todate').val();
	var customer=$('#customer').val();
	window.location="sale_order_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&customer="+customer+"&Task=filter";

}


</script>


<!-- Footer -->
<?php 
	include("footer.php");
?>
