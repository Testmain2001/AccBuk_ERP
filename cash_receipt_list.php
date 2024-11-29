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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Cash Receipt</h4>
		</div>
		<div class="col-md-2">
			<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#makepaymentModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="makepayment">Make Payment</button>
		</div>
	</div>

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
						//$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger inner join sale_invoice on sale_invoice.customer=account_ledger.id where 1 group by account_ledger.id ");
						$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger  where account_ledger.group_name='14' AND id in( select customer from cash_receipt where 1) group by account_ledger.id ");
					}
					elseif($_REQUEST['Task']!='filter')
					{
						$data = mysqli_query($GLOBALS['con'],"SELECT account_ledger.id as id FROM account_ledger inner join sale_invoice on sale_invoice.customer=account_ledger.id where 1 group by account_ledger.id where  account_ledger.id='".$_REQUEST['customer']."' ");
					}
					
					while($info=mysqli_fetch_array($data))
					{
						//var_dump($info);
						$i++;
						
						$grandtotalsum=$utilObj->getSum("sale_invoice","customer='".$info['id']."' ","grandtotal");
						$vendr=$utilObj->getSingleRow("account_ledger","id='".$info['id']."' ");														
						// $getdebit=$utilObj->getSum("purchase_payment","PID='".$vendr['id']."' $cndloc ","debit_amt");
						$getsum=$utilObj->getSum("cash_receipt","customer='".$vendr['id']."'","amt_pay"); 
						// $cheque=$utilObj->getSum("purchase_payment","PID='".$vendr['id']."' $cndloc","cheque_amt");	
						$sum=($getsum);
						
						if($vendr['opening_balance']!='')
						{
							$sum=$sum;//$vendr['opening_balance'] it is in 1/0 ie.yes/no
						}else
						{
							$grandtotalsum=$grandtotalsum;//$vendr['opening_balance']
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
						echo "<td><a ata-content='Make Payment' title='Make Payment' class='popovers' data-placement='top'  data-trigger='hover' $dasable  href='cash_receipt_list.php?customer=".$vendr['id']."&PTask=makepayment' >
								<i class='fa fa-money' style='color: crimson;'></i></a>";
								
						echo "<a ata-content='History' title='History' class='popovers' data-placement='top' data-trigger='hover' $dasable href='cash_receipt_history.php?customer=".$vendr['id']."&Task=history' >
								<i class='fa fa-book' ></i></a></td></tr>";    
								
						echo "</td></tr>";
						
					
					}
				?>
			</table>
		</div>
	</div>

	<?php 
		include("form/cash_receipt_form.php");
	?>

</div>
			

<script>

	<?php 
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' || $_REQUEST['PTask']=='makepayment') {
	?>	
		window.onload=function(){
			
			document.getElementById("makepayment").click();
			$("#makepayment").val("Show List"); 
			saletable();
			checkbilltype();
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
		return _isValidpopup(a);	
	}

	function remove_urldata()
	{	 
		window.location="cash_receipt_list.php";
	} 
	
	function savedata()
	{
		//if there are syntax error file not submited
		var PTask = $("#PTask").val();
		
		var table = $("#table").val();
		var LastEdited = $("#LastEdited").val();
		var id = $("#id").val();
		var cnt = $("#cnt").val();
		
		var recordnumber = $("#recordnumber").val();
		var voucher_type = $("#voucher_type").val();
		var customer = $("#customer").val();
		var location = $("#location").val();
		var type=$("#type").val();
		var totalvalue = $("#totalvalue").val();
		
		var date = $("#date").val();
		var mode = $("#mode").val();
		var bankid = $("#bankid").val();
		var balance = $("#balance").val();
		var cheque_no = $("#cheque_no").val();
		var amt_pay = $("#amt_pay").val();
		var narration = $("#narration").val();
		
	
		
		var bank_array=[];
		var bank1_array=[];
		var saleid_array=[];
		
		for(var i=1;i<=cnt;i++)
		{
			var bank = $("#bank"+i).val();	
			var bank1 = $("#bank1"+i).val();
			var saleid =$("#saleid"+i).val();
				
			bank_array.push(bank);
			bank1_array.push(bank1);
			saleid_array.push(saleid);
		} 
		
		jQuery.ajax({url:'handler/cash_receipt_form.php', type:'POST',
			data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt:cnt,recordnumber:recordnumber,voucher_type:voucher_type,date:date,location:location,customer:customer,type:type,totalvalue:totalvalue,mode:mode,bankid:bankid,balance:balance,cheque_no:cheque_no,amt_pay:amt_pay,narration:narration,bank_array:bank_array,bank1_array:bank1_array,saleid_array:saleid_array},
			success:function(data)
			{	
			// alert(data);
				if(data!="")
				{	
					//alert(data);	
					window.location='cash_receipt_list.php?';
				}else{
					alert('error in handler');
				}
			}
		});
	}


</script>

<?php 
	include("footer.php");
?>
