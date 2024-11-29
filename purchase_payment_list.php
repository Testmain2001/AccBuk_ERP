<?php 
	include 'header.php';
	$task=$_REQUEST['PTask'];

	if($task==''){ $task='makepayment';}
	if($_REQUEST['Task']=='view')
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
	$loc ='ALL';

	if($_REQUEST['Task']=='filter')
	{
		$loc=$_REQUEST['location'];
	}
	else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''||$_REQUEST['Task']=='')
	{
		$_SESSION['FromDate']=date('Y-m-01');
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}
?>

<div class="container-xxl flex-grow-1 container-p-y ">
<style>
.taxtbl td{
	padding:5px;
}
</style>
            
<div class="row">     
	<div class="col-md-3">       
		<h4 class="fw-bold mb-4" style="padding-top:2px;">Bank Payment</h4>
	</div>
	<div class="col-md-2">
		<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#makepaymentModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="makepayment"><i class="fas fa-plus-circle fa-lg"></i></button>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
		<thead>
			<tr>
				<th><span class="icon icon-triangle-ns"></span> Vendor Name </th>								
				<th><span class="icon icon-triangle-ns"></span> Grand Total </th>
				<th><span class="icon icon-triangle-ns"></span> Paid Amount </th>
				<th><span class="icon icon-triangle-ns"></span> Pending Amount </th>
				<th><span class="icon icon-triangle-ns"></span> Status</th>
				<th >Action</th> 
			</tr>
		</thead>
   
	<tbody>
	<?php
	$i=0;
								if($_REQUEST['Task']=='')
								{
									//$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger inner join purchase_invoice on purchase_invoice.supplier=account_ledger.id where 1 group by account_ledger.id ");
									$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger  where account_ledger.group_name='18' AND id in( select supplier from  purchase_payment where 1)  group by account_ledger.id ");
									
								}
								elseif($_REQUEST['Task']!='filter')
								{
									$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger inner join purchase_invoice on purchase_invoice.supplier=account_ledger.id where 1 group by account_ledger.id where  account_ledger.id='".$_REQUEST['supplier']."' ");
								}
								
								while($info=mysqli_fetch_array($data))
								{
									// var_dump($info);
									$i++;
									
									$grandtotalsum=$utilObj->getSum("purchase_invoice","supplier='".$info['id']."' ","grandtotal");
									$vendr=$utilObj->getSingleRow("account_ledger","id='".$info['id']."' ");														
									// $getdebit=$utilObj->getSum("purchase_payment","PID='".$vendr['id']."' $cndloc ","debit_amt");
                                    $getsum=$utilObj->getSum("purchase_payment","supplier='".$vendr['id']."'","amt_pay"); 
                                   	// $cheque=$utilObj->getSum("purchase_payment","PID='".$vendr['id']."' $cndloc","cheque_amt");	
                                    $sum=($getsum);
									
									if($vendr['opening_balance']!='')
									{
										$sum=$sum+$vendr['opening_balance'];
									}else
									{
										$grandtotalsum=$grandtotalsum+$vendr['opening_balance'];
									}
									 
									 $total_pending=$grandtotalsum-$sum; 
									if(round($total_pending,2)=='-0')
									{
										$total_pending='0';
									}
									
									
                                    if($total_pending==0){$status="Completed";}else{$status="Pending";}
                                           
	 								echo "<tr class='even'> <td class='controls'><a href='#'>".$vendr['name']."</a> </td>";
									echo "<td >".round($grandtotalsum,2) . "</td>"; 
                                    echo "<td >".round($sum,2) . "</td>";
                                    echo "<td >".round($total_pending,2) . "</td>"; 	
                                    echo "<td >".$status. "</td>"; 									
									echo "<td><a ata-content='Make Payment' title='Make Payment' class='popovers' data-placement='top'  data-trigger='hover' $dasable  href='purchase_payment_list.php?supplier=".$vendr['id']."&PTask=makepayment' >
			                	            <i class='fa fa-money' style='color: crimson;'></i></a>";
											
									echo "<a ata-content='History' title='History' class='popovers' data-placement='top' data-trigger='hover' $dasable href='purchase_history.php?supplier=".$vendr['id']."&Task=history' >
			                	            <i class='fa fa-book' ></i></a></td></tr>";    
									 		
									echo "</td></tr>";
									
								
								}
								 ?>
	   </table>
  </div>
</div>




<?php 
include("form/purchase_payment_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>


window.onload=function(){
	$("#date").flatpickr({
		dateFormat: "d-m-Y"
	});
	$("#date1").flatpickr({
		dateFormat: "d-m-Y"
	});
}

<?php 
	if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' || $_REQUEST['PTask']=='makepayment'){?>	
	window.onload=function(){
		
		document.getElementById("makepayment").click();
		$("#makepayment").val("Show List");

		// alert("hiii");

		purchasetable();
		// checkbilltype();
		validate();
		
	};  
<?php } ?>



<?php
/* if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		    deletedata("<?php echo $_REQUEST['id'];?>");
		 }
		else
		{
			window.location="purchase_invoice_list.php";
		}
  
};
<?php }  */?>	


function mysubmit(a)
{
	// return _isValidpopup(a);
	return savedata();	
}

function remove_urldata()
{	 
	window.location="purchase_payment_list.php";
} 
 
function savedata() {
   
	var PTask = $("#PTask").val();
	
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var id = $("#id").val();
	var cnt = $("#cnt").val();
	
	
 	var recordnumber = $("#recordnumber").val();
 	var voucher_type = $("#voucher_type").val();
	var supplier = $("#supplier").val();
	// var type = $("#type").val();
	
    var totalvalue = $("#totalvalue").val();
	
    var date = $("#date").val();
    var mode = $("#mode").val();
    var bank_ledger = $("#bank_ledger").val();
    var balance = $("#balance").val();
    var cheque_no = $("#cheque_no").val();
    var amt_pay = $("#amt_pay").val();
    var narration = $("#narration").val();
	var totalvalue = $("#totalvalue").val();
	
    // var bank_array=[];
	// var bank1_array=[];
	// var purchaseid_array=[];

	var type_array=[];
	var billno_array=[];
	var invodate_array=[];
	var totalinvo_array=[];
	var pendingamt_array=[];
	var payamt_array=[];
	
	for(var i=1;i<=cnt;i++) {

		var type = $("#type_"+i).val();
		var billno = $("#billno_"+i).val();
		var invodate = $("#invodate_"+i).val();
		var totalinvo = $("#totalinvo_"+i).val();
		var pendingamt = $("#pendingamt_"+i).val();
		var payamt = $("#payamt_"+i).val();
			
		type_array.push(type);
		billno_array.push(billno);
		invodate_array.push(invodate);
		totalinvo_array.push(totalinvo);
		pendingamt_array.push(pendingamt);
		payamt_array.push(payamt);
	}
	
	jQuery.ajax({url:'handler/purchase_payment_form.php', type:'POST',
		data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,recordnumber:recordnumber,voucher_type:voucher_type,date:date,supplier:supplier,totalvalue:totalvalue,mode:mode,bank_ledger:bank_ledger,balance:balance,cheque_no:cheque_no,amt_pay:amt_pay,narration:narration,type_array:type_array,billno_array:billno_array,invodate_array:invodate_array,totalinvo_array:totalinvo_array,pendingamt_array:pendingamt_array,payamt_array:payamt_array,totalvalue:totalvalue },
		success:function(data) {

			if(data!="") {

				// alert(data);
				alert("Record added successfully..!");
				window.location='purchase_payment_list.php';
			} else {

				alert('error in handler');
			}
		}
	});	
}


</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
