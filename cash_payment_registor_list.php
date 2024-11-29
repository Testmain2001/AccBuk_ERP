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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Payment Registor </h4>
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
					<select id="supplier" name="supplier"  class="required form-select select2" data-allow-clear="true">
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
		  <th width='10%'>Date</th>
		   <th width='10%'>Particular</th>
          <th width='10%'>Supplier</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			if($_REQUEST['Task']=='filter'&& $_REQUEST['supplier']!="All"&&$_REQUEST['supplier']!=""){
				  $cnd="paymentdate>='".$_SESSION['FromDate']."'AND paymentdate<='".$_SESSION['ToDate']."'AND supplier='".$_REQUEST['supplier']."'";
			}/* else if($_REQUEST['Task']=='filter'&& $_REQUEST['supplier']=="All"){
				$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
			} */else{
				 $cnd=" paymentdate>='".$_SESSION['FromDate']."' AND paymentdate<='".$_SESSION['ToDate']."'";
			}
			
			$data=$utilObj->getMultipleRow("purchase_payment"," $cnd AND payment_method='cash'");
			foreach($data as $info){
				
					$href= 'purchase_payment_list.php?id='.$info['id'].'&PTask=view';
					/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}else{
						$dis="";
					} */
					$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
		
		?>
		<tr>
		<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
		<td> <?php echo $info['paymentdate']; ?> </td>
		<td><a href="<?php echo $href; ?>"><?php echo $info['recordnumber']; ?></td>
		<td><?php echo $supplier['name']; ?></td>
		</tr>
		<?php 
		$i=$i+1;
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
	window.location="cash_payment_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&supplier="+supplier+"&Task=filter";
	

}



</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
