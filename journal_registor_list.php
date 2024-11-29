<?php 
include("header.php");
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
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Journal Registor </h4>
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
					<select id="account_ledger" name="account_ledger"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['account_ledger']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","1 group by id"); 
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
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
		  <th width='3%'><input type='checkbox' value='0' id='select_all' onclick="select_all();" />&nbsp Sr.No.</th>
		  <th width='10%'>Date</th>
		   <th width='10%'>Particular</th>
          <th width='10%'>Account Ledger</th>
          <th width='10%'>Debit Amount</th>
          <th width='10%'>Credit Amount</th>
          <th width='10%'>User</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			if($_REQUEST['Task']=='filter'&& $_REQUEST['account_ledger']!="All"&&$_REQUEST['account_ledger']!=""){
				$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND account='".$_REQUEST['account_ledger']."'";
			}else{
				$cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
			}
			
			$data=$utilObj->getMultipleRow("journal_entry","$cnd");
			foreach($data as $info){
				
					$href= 'journal_entry_list.php?parent_id='.$info['parent_id'].'&PTask=view';
					/* $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
					if($d1>0){
						$dis="disabled";
					}else{
						$dis="";
					} */
					$customer=$utilObj->getSingleRow("account_ledger","id='".$info['account']."'");
		
		?>
		<tr>
			<td  class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/>&nbsp&nbsp<?php echo $i; ?></td> 
			<td> <?php echo $info['date']; ?> </td>
			<td><a href="<?php echo $href; ?>"><?php echo $info['recordnumber']; ?></td>
			<td><?php echo $customer['name']; ?></td>
			<td><?php echo $info['debit_amount']; ?></td>
			<td><?php echo $info['credit_amount']; ?></td>
			<?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
			<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
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
	var account_ledger=$('#account_ledger').val();
	window.location="journal_registor_list.php?FromDate="+fromdate+"&ToDate="+todate+"&account_ledger="+account_ledger+"&Task=filter";
	
}

</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
