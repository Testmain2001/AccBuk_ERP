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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Payables Registor Invoicewise</h4>
		</div>
	</div>
	<div class="row" style="margin-bottom:12px;">
		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
				<div class="col-md-2 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 
				<div class="col-md-2 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div>
				<div class="col-md-3">
					<label class="form-label" >Supplier: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"   class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
						<option value="All" <?php if($_REQUEST['supplier']=="All"){ echo "selected";}else{ echo ""; } ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['supplier']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>

				<div class="col-md-2">
					<label class="form-label" >Method: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="method" name="method" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
					<option value="">Select Method</option>
					<option value="All" <?php if($_REQUEST["method"]=='All') echo $select='selected'; else $select='';?>>All</option>
					<option value="pending" <?php if($_REQUEST["method"]=='pending') echo $select='selected'; else $select='';?>>Pending</option>
					<option value="completed" <?php if($_REQUEST["method"]=='completed') echo $select='selected'; else $select='';?>>Completed</option>
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
						<th width='3%'><!--input type='checkbox' value='0' id='select_all' onclick="select_all();" /-->&nbsp Sr.No.</th>
						<th width='10%'>Date</th>
						<th width='10%'>Particular</th>
						<th width='10%'>Supplier</th>
						<th width='10%'>Purchase Invoice Amount</th>
						<th width='10%'>Paid Amount</th>
						<th width='10%'>Payable</th>
					</tr>
				</thead>
			
				<tbody>
				<?php
					$i=0;
					if($_REQUEST['Task']=='filter'&& $_REQUEST['supplier']!="All"&&$_REQUEST['supplier']!="") {

						$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."'AND supplier='".$_REQUEST['supplier']."'";
					} else {

						$cnd=" date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
					}
					
					$data=$utilObj->getMultipleRow("purchase_invoice","$cnd");

					foreach($data as $info) {

						if($_REQUEST['Task']=='filter') {

						$i++;
						// var_dump($info);
						$href= 'purchase_invoice_list.php?id='.$info['id'].'&PTask=view';

						$supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
					
						// $pp_details=$utilObj->getSingleRow("purchase_payment_details","purchaseid='".$info['id']."' AND parent_id in (select  id from  purchase_payment where supplier='".$info['supplier']."' )");

						$pp_details=$utilObj->getSum("purchase_payment_details","parent_id in(select id from purchase_payment where supplier='".$info['supplier']."') AND purchaseid='".$info['id']."' ","amount");

						$pp_details_cash=$utilObj->getSum("cash_payment_details","parent_id in(select id from cash_payment where supplier='".$info['supplier']."') AND purchaseid='".$info['id']."' ","amount");

						$receivable= $info['grandtotal'] - ($pp_details+$pp_details_cash);

						// echo "<pre>";
						// echo $receivable;

						if($pp_details==0 || $pp_details=='') {

							$payamt = 0;
						} else {

							$payamt = $pp_details;
						}
						
						if($_REQUEST['method']=='pending' && $receivable!=0) {
				?>
						<tr>
							<td  class='controls'>
								<?php echo $i; ?>
							</td> 
							<td> <?php echo $info['date']; ?> </td>
							<td><a href="<?php echo $href; ?>" target="_blank"><?php echo $info['pur_invoice_code']; ?></td>
							<td><?php echo $supplier['name']; ?></td>
							<td><?php echo $info['grandtotal']; ?></td>
							<td><?php echo $payamt; ?></td>
							<td><?php echo $receivable; ?></td>
						</tr>
					<?php } elseif($_REQUEST['method']=='completed' && $receivable==0) { ?>

						<tr>
							<td  class='controls'>
								<?php echo $i; ?>
							</td> 
							<td> <?php echo $info['date']; ?> </td>
							<td><a href="<?php echo $href; ?>" target="_blank"><?php echo $info['pur_invoice_code']; ?></td>
							<td><?php echo $supplier['name']; ?></td>
							<td><?php echo $info['grandtotal']; ?></td>
							<td><?php echo $payamt; ?></td>
							<td><?php echo $receivable; ?></td>
						</tr>
					<?php } elseif($_REQUEST['method']=='All') { ?>

						<tr>
							<td  class='controls'>
								<?php echo $i; ?>
							</td> 
							<td> <?php echo $info['date']; ?> </td>
							<td><a href="<?php echo $href; ?>" target="_blank"><?php echo $info['pur_invoice_code']; ?></td>
							<td><?php echo $supplier['name']; ?></td>
							<td><?php echo $info['grandtotal']; ?></td>
							<td><?php echo $payamt; ?></td>
							<td><?php echo $receivable; ?></td>
						</tr>
					<?php } ?>
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
		var supplier=$('#supplier').val();
		var method=$('#method').val();

		window.location="payables_report_list.php?FromDate="+fromdate+"&ToDate="+todate+"&supplier="+supplier+"&method="+method+"&Task=filter";
	}

	/* function select_all(){	
	
		//select all checkboxes
		$("#select_all").change(function(){  //"select all" change

			var status = this.checked; // "select all" checked status
			$('.checkboxes').each(function(){ //iterate all listed checkbox items
				if(this.disabled==false)
				{
					this.checked = status; //change ".checkbox" checked status
					//alert(this.disabled);
				}
			});
		});

		//uncheck "select all", if one of the listed checkbox item is unchecked
		$('.checkboxes').change(function(){ //".checkbox" change

			if(this.checked == false){ //if this item is unchecked
				$("#select_all")[0].checked = false; //change "select all" checked status to false
			}
		});

	} */
</script>


<?php 
	include("footer.php");
?>
