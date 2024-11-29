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
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Pending Delivery Registor </h4>
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
	<!-- Invoice List Table -->


	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
		
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
						<th >Date</th>
						<th >Delivery Challan No</th>
						<th >Customer</th>
						<th >Product</th>
						<th >Unit</th>
						<th >Quantity</th>
						<th >Pending Delivery Challan Qty</th>
						<th >User</th>
					</tr>
				</thead>
	
				<tbody>
					<?php
						$i=0;$k=0;
						if($_REQUEST['Task']=='filter' && $_REQUEST['customer']!="All" && $_REQUEST['customer']!=""){
							$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND customer='".$_REQUEST['customer']."'";
						}else{
							$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
						}
						
						$data=$utilObj->getMultipleRow("delivery_challan"," $cnd");//AND id not in(select delivery_challan_no from sale_invoice where 1)
						foreach($data as $info){
							$i++;$j=0;
							$href= 'delivery_challan_list.php?id='.$info['id'].'&PTask=view';
							/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
							if($d1>0){
								$dis="disabled";
							}else{
								$dis="";
							} */
							$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
							$data1=$utilObj->getMultipleRow("delivery_challan_details","parent_id='".$info['id']."'");
							foreach($data1 as $info1){
							$j++;$pending_qty=0;
							$product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
							$sale_invoice_details=$utilObj->getSingleRow("sale_invoice_details","product='".$info1['product']."' AND unit='".$info1['unit']."' AND parent_id in(select  id  from sale_invoice where  delivery_challan_no='".$info['id']."' AND  customer='".$info['customer']."'   )  ");
							
							//if($sale_invoice_details){
								$k++;
							//var_dump($delivery_challan_details);
							$pending_qty=$info1['qty']-$sale_invoice_details['qty'];
							if($j==1){
								$rowspan=Count($data1);
								$hidetd="";
								
							}else{
								$rowspan=1;
								$hidetd="hidetd";
							}  
							
							?>
							<tr>
								<td  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $k; ?></td> 
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <?php echo $info['date']; ?> </td>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><a href="<?php echo $href; ?>"><?php echo $info['challan_no']; ?></td>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $customer['name']; ?></td>
								<td><?php echo $product['name']; ?></td>
								<td><?php echo $product['unit']; ?></td>
								<td><?php echo $info1['qty']; ?></td>
								<td><?php echo $pending_qty; ?></td>
								<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
								<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
							</tr>
							<?php 
							//}
							} 
						}
					?>
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
	var customer=$('#customer').val();
	window.location="pending_delivery_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&customer="+customer+"&Task=filter";
}

</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
