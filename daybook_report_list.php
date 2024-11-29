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
//$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
$_SESSION['FromDate']=$_SESSION['ToDate']=date("Y-m-d");
 //$inputfrom=date("01-m-Y");
 $inputfrom=$inputto=date("d-m-Y");
}
?>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-4">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Day Book Reports </h4>
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
					<label class="form-label" >Account Ledger: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="account" name="account"   class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
					<option value="All" <?php if($_REQUEST['account']=="All"){ echo "selected";}else{ echo "";} ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","1 group by name"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['account']){echo $select="selected";}else{echo $select="";}
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
    
   <table id="examplepaginghide"  style="background:#e8e9ed;" class="table table-striped table-bordered dataTable dt-responsive " cellspacing="0" width="100%">
								<thead>
								<tr>
								<th rowspan='2'>Sr.No.</th>
								<th rowspan='2'>Date</th>	
							    <th rowspan='2'>Particular</th> 
							    <th rowspan='2'>Acount</th> 
								<th colspan="2">Amount</th>
								<th rowspan='2'>Location</th>							    
								<th rowspan='2'>User</th>
								</tr>
								<tr>
								<td>credit</td>
								<td>debit</td>
								</tr>
								</thead><tbody>
								<?php
								$j=1;
								if($_REQUEST['account']!='All'){
									$account=$_REQUEST['account'];
								}else{
									
								}
								if(!empty($_REQUEST['Task'])){
                                //purchase invoice --------------------------------------------------------------------
								$data=$utilObj->getMultipleRow("purchase_invoice"," date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."' AND supplier Like '%".$account."%'");
								foreach($data as $row)
								{
									$Material_record_array_detail[$j]['date']=$row['date'];
									$Material_record_array_detail[$j]['transaction']='Purchase - Invoice No.<a href="purchase_invoice_list.php?id='.$row['id'].'&PTask=view" target="_blank"> '.$row['invoicenumber'];
									
									$account_ledger=$utilObj->getSingleRow("account_ledger","id ='".$row['supplier']."'");
								    $Material_record_array_detail[$j]['account']=$account_ledger['name'];
									
								    $Material_record_array_detail[$j]['credit_amount']='';
								    $Material_record_array_detail[$j]['debit_amount']=$row['grandtotal'];
									
									$location=$utilObj->getSingleRow("location","id ='".$row['location']."'");
								    $Material_record_array_detail[$j]['location']=$location['name'];
									
									$username=$utilObj->getSingleRow("employee","id ='".$row['user']."'");
									$Material_record_array_detail[$j]['user']=$username['name'];
									$j++;
								}
								
								//purchase return--------------------------------------------------------------------
								$data=$utilObj->getMultipleRow("purchase_return","date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."' AND supplier Like '%".$account."%'");
								
								foreach($data as $row)
								{
									$Material_record_array_detail[$j]['date']=$row['date'];
									$Material_record_array_detail[$j]['transaction']='Purchase Return - Record No. <a href="purchase_return_list.php?id='.$row['id'].'&PTask=view" target="_blank"> '.$row['recordnumber'];
									
									$account_ledger=$utilObj->getSingleRow("account_ledger","id ='".$row['supplier']."'");
								    $Material_record_array_detail[$j]['account']=$account_ledger['name'];
									
								    $Material_record_array_detail[$j]['credit_amount']=$row['grandtotal'];
								    $Material_record_array_detail[$j]['debit_amount']='';
									
									$location=$utilObj->getSingleRow("location","id ='".$row['location']."'");
								    $Material_record_array_detail[$j]['location']=$location['name'];
									
									$username=$utilObj->getSingleRow("employee","id ='".$row['user']."'");
									$Material_record_array_detail[$j]['user']=$username['name'];
									$j++;
								}

								//purchase payment--------------------------------------------------------------------
								$data=$utilObj->getMultipleRow("purchase_payment","	paymentdate >='".$_SESSION['FromDate']."' AND 	paymentdate <='".$_SESSION['ToDate']."' AND supplier Like '%".$account."%'");
								
								foreach($data as $row)
								{
									$Material_record_array_detail[$j]['date']=$row['paymentdate'];
									$Material_record_array_detail[$j]['transaction']='Purchase Payment - Record No. <a href="purchase_return_list.php?id='.$row['id'].'&PTask=view" target="_blank"> '.$row['recordnumber'];
									
									$account_ledger=$utilObj->getSingleRow("account_ledger","id ='".$row['supplier']."'");
								    $Material_record_array_detail[$j]['account']=$account_ledger['name'];
									
								    $Material_record_array_detail[$j]['credit_amount']='';
								    $Material_record_array_detail[$j]['debit_amount']=$row['amt_pay'];
									
									$location=$utilObj->getSingleRow("location","id ='".$row['location']."'");
								    $Material_record_array_detail[$j]['location']=$location['name'];
									
									$username=$utilObj->getSingleRow("employee","id ='".$row['user']."'");
									$Material_record_array_detail[$j]['user']=$username['name'];
									$j++;
								}
								
								//Sale invoice--------------------------------------------------------------------
								$data=$utilObj->getMultipleRow("sale_invoice","date >='".$_SESSION['FromDate']."' AND date <='".$_SESSION['ToDate']."' AND customer Like '%".$account."%'");
								foreach($data as $row)
								{
									$Material_record_array_detail[$j]['date']=$row['date'];
									$Material_record_array_detail[$j]['transaction']='Sale- Invoice No. <a href="purchase_invoice_list.php?id='.$row['id'].'&PTask=view" target="_blank"> '.$row['sale_invoiceno'];
									
									$account_ledger=$utilObj->getSingleRow("account_ledger","id ='".$row['customer']."'");
								    $Material_record_array_detail[$j]['account']=$account_ledger['name'];
									
								    $Material_record_array_detail[$j]['credit_amount']='';
								    $Material_record_array_detail[$j]['debit_amount']=$row['grandtotal'];
									
									$location=$utilObj->getSingleRow("location","id ='".$row['location']."'");
								    $Material_record_array_detail[$j]['location']=$location['name'];
									
									$username=$utilObj->getSingleRow("employee","id ='".$row['user']."'");
									$Material_record_array_detail[$j]['user']=$username['name'];
									$j++;
								}
								
							    //sale Return--------------------------------------------------------------------
								$data=$utilObj->getMultipleRow("sale_return","date >='".$_SESSION['FromDate']."' AND date <='".$_SESSION['ToDate']."' AND customer Like '%".$account."%'");
								foreach($data as $row)
								{
									$Material_record_array_detail[$j]['date']=$row['date'];
									$Material_record_array_detail[$j]['transaction']='Sale Return - Record No. <a href="purchase_return_list.php?id='.$row['id'].'&PTask=view" target="_blank"> '.$row['recordnumber'];
									
									$account_ledger=$utilObj->getSingleRow("account_ledger","id ='".$row['customer']."'");
								    $Material_record_array_detail[$j]['account']=$account_ledger['name'];
									
								   $Material_record_array_detail[$j]['credit_amount']=$row['grandtotal'];
								    $Material_record_array_detail[$j]['debit_amount']='';
									
									$location=$utilObj->getSingleRow("location","id ='".$row['location']."'");
								    $Material_record_array_detail[$j]['location']=$location['name'];
									
									$username=$utilObj->getSingleRow("employee","id ='".$row['user']."'");
									$Material_record_array_detail[$j]['user']=$username['name'];
									$j++;
								}		
//=============================================================================================================================================			
							/* foreach ($Material_record_array_detail as $key => $row) {
						
							$dates[$key]  = $row['date'];
							}
							print_r($dates);
							array_multisort($dates, SORT_ASC, $Material_record_array_detail);	 */											
								
							$c=1;$val=0;$pval=0;$cr=0;$pcr=0;
							for($i=1;$i<$j;$i++)
							{
							
								echo "<tr class='even'>";
								echo "<td style='width:2%;'>$c</td>";
								echo "<td style='width:8%;'>".date('d-m-Y',strtotime($Material_record_array_detail[$i]['date']))." </td>";
								echo "<td>".$Material_record_array_detail[$i]['transaction']." </td>";
								echo "<td>".$Material_record_array_detail[$i]['account']." </td>";
								echo "<td>".$Material_record_array_detail[$i]['credit_amount']." </td>";
								echo "<td>".$Material_record_array_detail[$i]['debit_amount']." </td>";
								echo "<td>".$Material_record_array_detail[$i]['location']." </td>";
								echo "<td>".$Material_record_array_detail[$i]['user']." </td>";
							
								echo"</tr>";
								$c++;
								
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
	var account=$('#account').val();
	window.location="daybook_report_list.php?FromDate="+fromdate+"&ToDate="+todate+"&account="+account+"&Task=filter";
}



</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
