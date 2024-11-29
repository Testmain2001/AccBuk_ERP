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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Receipt Registor </h4>
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
			<div class="col-md-3">
				<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="location" name="location" onchange="" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
									<?php 
										echo '<option value="">Select Location</option>';
										$record=$utilObj->getMultipleRow("location","1");
										foreach($record as $e_rec)
										{
											if($_REQUEST['location']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
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
		  <th >Particular</th>
          <th >Type</th>
          <th >Customer</th>
          <th >Location</th>
          <th >Voucher Type</th>
          <th >Payment Method </th>
          <th >Account </th>
          <th >Received Amount</th>
		  <th>User</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			if($_REQUEST['Task']=='filter'&& $_REQUEST['customer']!="All"&&$_REQUEST['customer']!=""&& $_REQUEST['location']!="" ){
				$cnd="receiptdate>='".$_SESSION['FromDate']."'AND receiptdate<='".$_SESSION['ToDate']."'AND customer='".$_REQUEST['customer']."'AND location='".$_REQUEST['location']."'";
			}else if($_REQUEST['Task']=='filter'&& $_REQUEST['customer']=="All"&&$_REQUEST['location']!=""){
				$cnd="receiptdate>='".$_SESSION['FromDate']."'AND receiptdate<='".$_SESSION['ToDate']."'AND location='".$_REQUEST['location']."'";
			}else{
				$cnd="receiptdate>='".$_SESSION['FromDate']."' AND receiptdate<='".$_SESSION['ToDate']."'";
			}
			
			$data=$utilObj->getMultipleRow("sale_receipt"," $cnd");
			foreach($data as $info){
				    $i++;
					$href= 'sale_receipt_list.php?id='.$info['id'].'&PTask=view';
					/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}else{
						$dis="";
					} */
					$customer=$utilObj->getSingleRow("account_ledger","id='".$info['customer']."'");
					$location=$utilObj->getSingleRow("location","id='".$info['location']."'");
					$account=$utilObj->getSingleRow("account_ledger","id='".$info['bankid']."'");
					$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
		
		?>
		<tr>
			<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <?php echo date('d-m-Y',strtotime($info['receiptdate'])); ?> </td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><a href="<?php echo $href; ?>"><?php echo $info['recordnumber']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['Type']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $customer['name']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $location['name']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['payment_method']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $account['name']; ?></td>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $info['amt_pay']; ?></td>
			<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
		    <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
		</tr>
		<?php 
		
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
	var customer=$('#customer').val();
	var location=$('#location').val();
	window.location="receipt_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&customer="+customer+"&location="+location+"&Task=filter";
	
}


</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
