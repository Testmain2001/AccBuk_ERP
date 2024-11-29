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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">GRN  Registor </h4>
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
					<label class="form-label" >Supplier: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['supplier']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['supplier']){echo $select="selected";}else{echo $select="";}
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
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
		  <th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
		  <th>Date</th>
          <th>Particular</th>
          <th>Type</th>
		  <th>Supplier</th>
          <th>Location</th>
          <th>Voucher Type</th>
		  <th>Product</th>
          <th>Unit</th>
          <th>Quantity</th>
		   <th>User</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			if($_REQUEST['Task']=='filter'&& $_REQUEST['supplier']!="All"&&$_REQUEST['supplier']!=""){
				  $cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND supplier='".$_REQUEST['supplier']."'";
			}else{
				 $cnd=" date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
			}
			
			$data=$utilObj->getMultipleRow("grn"," $cnd");
			foreach($data as $info){
				 $i++;$j=0;
					$href= 'GRN_list.php?id='.$info['id'].'&PTask=view';					

					//$location1=$utilObj->getSingleRow("location","id='".$info['location']."'");
					$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
					$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
					$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
					$data1=$utilObj->getMultipleRow("grn_details","parent_id='".$info['id']."'");
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
						<td width='3%'  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' class='checkboxes' <?php //echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp; <?php echo $i; ?></td> 
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['grn_no']; ?></a> </td>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['type']; ?></td>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $supplier['name']; ?></td>
		                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td>
		                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
						<td><?php echo $product['name']; ?></td>
						<td><?php echo $info1['unit']; ?></td>
						<td><?php echo $info1['qty']; ?></td>
						<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
						<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
		</tr>
		<?php 
		   }
		} ?>
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
	var supplier=$('#supplier').val();
	window.location="GRN_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&supplier="+supplier+"&Task=filter";
}



</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
