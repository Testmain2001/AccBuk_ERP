<?php
if( $_REQUEST['PTask']=='view'){
	$disabled="disabled";
}else{
	$disabled="";
}

 ?>
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="makepaymentModal" tabindex="-1" aria-hidden="true" style="width:100% !IMPORTANT;">
<?php
 
	//echo $_REQUEST['PTask'];
	if( $_REQUEST['PTask']=='makepayment') {									
		$supplierid=$_REQUEST['supplier'];								
		$grandtotalsum=$utilObj->getSum("purchase_invoice","supplier='".$supplierid."'","grandtotal");
		//$getgtot=$utilObj->getSum("rejected_details","vendor='".$vendr1["id"]."'","GrandTotal");
		$vendr1=$utilObj->getSingleRow("account_ledger","id='".$id."' ");
		$getsum=$utilObj->getSum("cash_payment","supplier='".$supplierid."'","amt_pay"); 
		// $cheque=$utilObj->getSum("cash_payment","PID='".$supplierid."'","cheque_amt");	
		$sum=($getsum);
		$grandtotalsum=$grandtotalsum+$vendr1['opening_balance'];
		$total_pending=$grandtotalsum -$sum; 	
		
		$paymentdate=date('d-m-Y');
		
		
		$date=date('d-m-Y');            // For Reset Numbering
		$curr_year = date('Y',strtotime($date));
		if(strtotime($date)>strtotime($curr_year."-03-31")){
			$prev_year = $curr_year;
			$curr_year = $curr_year + 1;
		}else{
			$curr_year = $curr_year;
			$prev_year = $curr_year-1;
		}
		$fdate = date($prev_year."-04-01");
		$tdate = date($curr_year."-03-31");
	
	} else if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {
		$id=$_REQUEST['id'];
		$payment_record=mysqli_query($GLOBALS['con'],"Select * from cash_payment WHERE id ='".$id."'");
		$payment1=mysqli_fetch_array($payment_record);
		$date=$payment1['paymentdate'];
		$supplierid=$payment1['supplier'];;
		$paymentdate=date('d-m-Y',strtotime($payment1['paymentdate']));
		$invno=$payment1['recordnumber'];
		$vendr1=$utilObj->getSingleRow("account_ledger","id='".$payment1['supplier']."' ");
		
		
		
	} else {
		$paymentdate=date('d-m-Y');
		$date=date('d-m-Y'); // For Reset Numbering
		$curr_year = date('Y',strtotime($date));
		if(strtotime($date)>strtotime($curr_year."-03-31")){
			$prev_year = $curr_year;
			$curr_year = $curr_year + 1;
		}else{
			$curr_year = $curr_year;
			$prev_year = $curr_year-1;
		}
		$fdate = date($prev_year."-04-01");
		$tdate = date($curr_year."-03-31");

	}
							
?>
	<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
				<h3 class="role-title">Payment</h3>
				
				</div>
				<!-- Add role form -->
				
				<form id="" data-parsley-validate class="row g-3" action="../cash_payment_list.php"  method="post" data-rel="myForm">
					
					<input type='hidden' name="PTask" id="PTask" value="<?php echo $task;?>">					  
					<input type='hidden' name="id" id="id" value="<?php echo $_REQUEST['id'];?>">
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $payment1['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "cash_payment"; ?>"/>
						
					<!------------------------------------------------------------------------------------>
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"    <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_purpay_code();">
							<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=6 group by id"); 
								foreach($data as $info){
									if($info["id"]==$payment1['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>
						
					<div class="col-md-4">
						<label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $invno;?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Payment  Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="supplier" name="supplier"  onChange="purchasetable();checkbilltype(); "  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
								foreach($data as $info){
									if($info["id"]==$supplierid){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>

					<div class="col-md-4" id="bill_type">
					<?php
						if($_REQUEST['PTask']=='update') {
					?>
						<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();purchasetable();" style="width:100%"  <?php echo $disabled;?> name="type" id="type">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					<?php } ?>
					</div>

					<div id="pending" class="col-md-12 col-sm-4 col-xs-12"> 
						
					</div>
					<!----------------------------------------------------------------------------->
					<div class=" text-nowrap" style=""><!--table-responsive-->

						<table id="pmttble" class="table  table-bordered table-hover  table-sm "  style="width:100%; margin-right: 60px;">
						<thead>
							<tr>
								<th style="width:10%">Issue Date</th>
								<th style="width:15%"><span class="icon icon-triangle-ns"></span>Bank ledger</th>
								<th style="width:10%"><span class="icon icon-triangle-ns"></span>Balance</th>
								<th style="width:15%"><span class="icon icon-triangle-ns"></span>Amount</th>
								<th style="width:10%"><span class="icon icon-triangle-ns"></span>Method</th> 
								<!-- <th style="width:10%"><span class="icon icon-triangle-ns"></span>Cheque No / Trasaction ID</th> -->
								<th style="width:15%"><span class="icon icon-triangle-ns"></span> Narration</th>
							</tr>
						</thead>
						<tbody>
							<tr>

							<td style="width:10%">
								<input type="text" class="form-control flatpickr" id="date1" name="date1" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
							</td>

							<td style="width:15%">

								<div class="" id="Ac">
									<select id="bank_ledger" name="bank_ledger" class="required form-select select2" onchange="get_balance(this.value);" data-placeholder="Select Account No." style="width:100%"  <?php echo $disabled;?> <?php echo $readonly;?> >
										<option ></option>				 
										<?php 	
											$Account=$utilObj->getMultipleRow("account_ledger","group_name='8' group by name");    
											foreach($Account as $a_rec){                
												if($payment1['bank_ledger']==$a_rec['id']) $select='selected'; else $select='';
												echo  '<option value="'.$a_rec["id"].'" '.$select.'>'.$a_rec["name"].'</option>';
											}
										?>			
									</select>
								</div>
								
							</td>			  

							<td style="width:10%">
								<div class="">
									<input type="text" name='balance' readonly value="<?php echo $payment1["balance"];?>" class=" form-control col-md-7 col-xs-12"  id="balance" <?php echo $disabled;?>>
								</div>
							</td>

							<td style="width:15%">
								<input type="text" name='amt_pay'  value="<?php echo $payment1['amt_pay'];?>" onKeyUp="" onBlur="" class="required form-control col-md-7 col-xs-12" <?php echo $read ;?> <?php echo $disabled;?>  id="amt_pay">
							</td>

							<td style="width:10%">
								<div class="">
									<input type="text" class=" form-control col-md-7 col-xs-12" name="mode" id="mode" value="Cash" readonly>
									
								</div>
							</td>

							<!-- <td style="width:10%">
								<div class="" >
									<input type="text" name='cheque_no' value="<?php echo $payment1["cheque_no"];?>" class=" form-control col-md-7 col-xs-12"  id="cheque_no" <?php echo $disabled;?> >
								</div>
							</td> -->

							<td style="width:15%">
							<input type="text" name='narration'  value="<?php echo $payment1['narration'];?>" class="form-control col-md-7 col-xs-12" id="narration" <?php echo $disabled;?>> 
							</td>
						</tr>
						</tbody>
						</table>
					</div>		
						
			
					<div class="col-12 text-center">
						<?php 
						if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''|| $_REQUEST['PTask']=='makepayment'){?>	
							<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
						<?php } ?>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>

	function validate()
	{	
		var val = $("#type").val();

		if(val=='Advanced') {
			
			$("#order").hide();
			$("#pending").hide();
			$('#bill').removeClass("required");
			$('#amt_pay').prop('readonly', false);
			$('#amt_pay').addClass("required");
			
		} else {

			$("#order").show();
			$("#pending").show();
			$('#bill').addClass("required");
			$('#amt_pay').prop('readonly', true);
			// $('#amt_pay').addClass("required");
		}
	}

	function purchasetable()
	{			
		// alert('purchase');
		var val = $("#supplier").val();
		var type = $("#type").val();
		var PTask = $("#PTask").val();
		var id = $("#id").val();

		// alert(id);
		// alert(val);
		// alert(type);
		// alert(PTask);
		
		if(type=="PO"){
			jQuery.ajax({url:'get_ajax_values.php',
				type:'POST',
				data: { Type:'purchasetable1',cust:val,PTask:PTask,id:id,type:type},
				
				success:function(data)
				{
					// alert(data);
					$('#pending').html(data);

				}
			});
		}
	}

	<?php ?>

	function gettotaltable() 
	{ 
		var count=$("#cnt").val();	
		
		var total = 0;
		for(var i=1; i<=count;i++)
		{
				
			if ($('#checkbox'+i).is(':checked')) {
				//alert("Hiiii");
				if(document.getElementById("bank"+i)){
					var get1 = $("#bank"+i).val();		if(get1==''){ get1=0; }
					
					// var get2 = $("#bank1"+i).val();		if(get2==''){ get2=0; }

					// var get=parseFloat(get1)+parseFloat(get2);
					var get=parseFloat(get1);
					
					if(get=='' || get=='undefined'){ get=0; }

					total += parseFloat(get);
				}
				
				
			}
			
		}
		//alert(total);
		$("#totalvalue").val(total.toFixed(2));
		$("#amt_pay").val(total.toFixed(2));
		
	}

	function getinputbox(rid,id)
	{
		//alert(id)
		
		if ($('#checkbox'+rid).is(':checked')) {

			// alert("Hiiii");
			$("#checkboxshow"+rid).css('display', 'block');
			// $("#discount"+rid).css('display', 'block');
			
			$("#checkboxshow"+rid).html('<input type="text" class=" form-control required"  placeholder="Enter Amount" name="bank'+rid+'" id="bank'+rid+'" onkeyup="gettotaltable();getinputvalues('+rid+','+id+');" "onblur=gettotaltable();getinputvalues('+rid+','+id+');">');

			// $("#discount"+rid).html('<input type="text" class=" form-control required"  placeholder="Enter Discount" name="bank1'+rid+'" id="bank1'+rid+'" onkeyup="gettotaltable();getinputvalues('+rid+','+id+');" "onblur=gettotaltable();getinputvalues('+rid+','+id+');">');			
			
		} else {

			$("#checkboxshow"+rid).css('display', 'none');
			// $("#discount"+rid).css('display', 'none');

			$("#checkboxshow"+rid).html('');
			// $("#discount"+rid).html('');
		}
			
	}

	function getinputvalues(rid,id)
	{
		//alert(id);
		
		if ($('#checkbox'+rid).is(':checked')) {
			var get1 = $("#bank"+rid).val();
			if(get1==''){
				get1 = 0;
			}
			var get2 = $("#bank1"+rid).val();
			if(get2==''){
				get2 = 0;
			}
			var get = parseFloat(get1)+parseFloat(get2);
			var balance = (id-get);
			//alert(id+"hii"+get);

			if(get>id) {
				alert("Amount should not be Greater than payble amount");
				$("#bank"+rid).val('').trigger('change');
				$("#bank1"+rid).val('').trigger('change');
				var balance=0;
				$("#checkboxvalue"+rid).html('<input type="text" class="form-control" value="'+balance+'" readonly> ');
			} else {
				
			}
			
			if(get!="") {
				$("#checkboxvalue"+rid).html('<input type="text" class="form-control" value="'+balance+'" readonly> ');
			}
		} else {
			$("#checkboxvalue"+rid).html('');
		}
	}

	function getchequeno()
	{
		
			var type=$('#mode').val();
			if(type=="cash")
		{	
			$('#cheque_no').prop('readonly', true);
			var totalvalue = $("#totalvalue").val();
			
			//$('#cheque_no').removeAttr("readonly");
			$('#cheque_no').removeClass("required");
			$("#cheque_no").removeClass('number');
			// $("#amt_pay").val(totalvalue);
		}	
		else
		{
			var totalvalue = $("#totalvalue").val();
			// $('#account_no').removeAttr("readonly");
			$('#cheque_no').removeAttr("readonly");
			
			$('#cheque_no').addClass("required");
			// $("#cheque_no").addClass('number');
			// $("#amt_pay").val(totalvalue);
		}

	
	}

	function check_type()
	{
		var mode=$('#mode').val();
		// alert(mode);
			
		jQuery.ajax({url:'get_ajax_values.php',
			type:'POST',
			data: { Type:'cashmethod',mode:mode},	
			success:function(data)
			{				
				$('#Ac').html(data);
			}
		});	
	}

	function get_purpay_code() {

		// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
		// $result=mysqli_fetch_array($getinvno);
		// $grn_no=$result['pono']+1;

		var voucher_type = $("#voucher_type").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_purpay_code1',voucher_type:voucher_type },
			success:function(data)
			{	
				//alert(data);
				$("#recordnumber").val(data);	
				// $(this).next().focus();
			}
		});

	}

	function checkbilltype() {

		var id = $("#id").val();
		// var PTask = $("#PTask").val();
		var value = $("#supplier").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'checkbilltype2',value:value,id:id },
			success:function(data)
			{	
				//alert(data);
				$("#bill_type").html(data);
			}
		});

	}

	function get_balance(value) {

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_balance',value:value},
			success:function(data)
			{	
				// alert(data);
				$("#balance").val(data);
			}
		});

	}

</script>

             



