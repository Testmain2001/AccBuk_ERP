
<!-- Add Role Modal -->
<div class="modal fade form-validate" id="makepaymentModal" tabindex="-1" aria-hidden="true" style="width:100% !IMPORTANT;">
	<?php
 
		
							if( $_REQUEST['PTask']=='makepayment')
							{									
							    $customerid=$_REQUEST['customer'];								
								 $grandtotalsum=$utilObj->getSum("sale_invoice","customer='".$customerid."'","grandtotal");
								 //$getgtot=$utilObj->getSum("rejected_details","vendor='".$vendr1["id"]."'","GrandTotal");
									$vendr1=$utilObj->getSingleRow("account_ledger","id='".$id."' ");
                                    $getsum=$utilObj->getSum("sale_receipt","customer='".$customerid."'","amt_pay"); 
                                   // $cheque=$utilObj->getSum("purchase_payment","PID='".$supplierid."'","cheque_amt");	
                                    $sum=($getsum);
									$grandtotalsum=$grandtotalsum+$vendr1['opening_balance'];
									$total_pending=$grandtotalsum - $sum; 	
								
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
										
										
										
								// $getinvno=mysqli_query($GLOBALS['con'],"Select max(	recordnumber) as pono from sale_receipt WHERE ClientID ='".$_SESSION['Client_Id']."' AND receiptdate>='".$fdate."' AND receiptdate<='".$tdate."'  ");
								// 		$row=mysqli_fetch_array($getinvno);
								// 		$invno=$row['pono']+1;  
							
							
							} 
							else if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
							{
								$id=$_REQUEST['id'];
								$payment_record=mysqli_query($GLOBALS['con'],"Select * from sale_receipt WHERE id ='".$id."'");
								$payment1=mysqli_fetch_array($payment_record);
								$date=$payment1['receiptdate'];
								$customerid=$payment1['customer'];
								$locationid=$payment1['location'];
								$receiptdate=date('d-m-Y',strtotime($payment1['receiptdate']));
								$invno=$payment1['recordnumber'];
								$vendr1=$utilObj->getSingleRow("account_ledger","id='".$payment1['customer']."' ");
								if($_REQUEST['PTask']=='view' ){
									$readonly="readonly";
									$disabled="disabled";
								}else{
									$readonly="";
									$disabled="";
								}
							  
								
							}
							else
							{
									 $receiptdate=date('d-m-Y');
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
		
		
									// $getinvno=mysqli_query($GLOBALS['con'],"Select max(recordnumber) as pono from sale_receipt WHERE ClientID ='".$_SESSION['Client_Id']."' AND receiptdate>='".$fdate."' AND receiptdate<='".$tdate."' ");
									// $row=mysqli_fetch_array($getinvno);
									// $invno=$row['pono']+1;  
							}
							
?>
  	<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>

				<div class="text-center mb-4">
			  		<h3 class="role-title">Receipt</h3>
				</div>
			
			  	<form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">

					<input type='hidden' name="PTask" id="PTask" value="<?php echo $task;?>">					  
					<input type='hidden' name="id" id="id" value="<?php echo $_REQUEST['id'];?>">
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $payment1['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "sale_receipt";?>"/>
						
					<div class="col-md-4">
						<label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="voucher_type" name="voucher_type"  <?php echo $readonly;?>   <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_sale_rec();">
							<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("voucher_type","parent_voucher=7 group by id"); 
								foreach($data as $info){
									if($info["id"]==$payment1['voucher_type']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>
						
					<div class="col-md-4">
						<label class="form-label">Record No  <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="recordnumber" class="required form-control"  <?php echo $readonly;?> placeholder="invoiceno No." name="recordnumber" value="<?php echo $invno;?>"/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Payment  Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div>

					<div class="col-md-4">
						<label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="customer" name="customer"  onChange="saletable();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
							<option value="">Select</option>
							<?php
								$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
								foreach($data as $info){
									if($info["id"]==$payment1['customer']){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div>

					<!-- <div class="col-md-4" id="bill_type">
					<?php
						if($_REQUEST['PTask']=='update') {
					?>
						<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();saletable();" style="width:100%"  <?php echo $readonly;?> 	<?php echo $disabled;?> name="type" id="type">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>
						</select>
					<?php } ?>
					</div> -->


					<!-- <div class="col-md-4">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" onchange="saletable();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php 
								echo '<option value="">Select Location</option>';
								$record=$utilObj->getMultipleRow("location","1");
								foreach($record as $e_rec)
								{
									if($locationid==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?>
						</select>
					</div> -->
						
					<!-- <div class="col-md-4">
						<label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="customer" name="customer"  onChange="saletable(); "  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
							<?php	
								$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
								foreach($data as $info){
									if($info["id"]==$customerid){echo $select="selected";}else{echo $select="";}
									echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
								}  
							?>
						</select>
					</div> -->
					

					<!-- ------------------------------------------------------------------------- -->
					<div id="pending" class="col-md-12 col-sm-4 col-xs-12"> 
						
					</div>
					<!-- ------------------------------------------------------------------------- -->

					<div class="text-nowrap" style="">

						<table id="pmttble" class="table  table-bordered table-hover  table-sm "  style="width:100%; margin-right: 60px;">
							<tbody>
								<tr>
									<!-- <th style="width:10%">Date</th>
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Payment Method</th>
									<th style="width:20%"><span class="icon icon-triangle-ns"></span>Account No.</th>
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Balance</th>
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Cheque No / Trasaction ID </th> 
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Amount</th>
									<th style="width:15%"><span class="icon icon-triangle-ns"></span>Narration</th> -->

									<th style="width:10%">Issue Date</th>
									<th style="width:15%"><span class="icon icon-triangle-ns"></span>Bank ledger</th>
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Balance</th>
									<th style="width:15%"><span class="icon icon-triangle-ns"></span>Amount</th>
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Method</th> 
									<th style="width:10%"><span class="icon icon-triangle-ns"></span>Cheque No / Trasaction ID</th>
									<th style="width:15%"><span class="icon icon-triangle-ns"></span> Narration</th>
								</tr>

								<tr>
									<td style="width:10%">
										<input type="text" class="form-control flatpickr" id="date1" name="date1" required value="<?php echo $date; ?>" <?php echo $disabled; ?>/>
									</td>

									<td style="width:10%">

										<div class="" id="Ac">
											<select id="bankid" name="bankid" class="required form-select select2" onchange="" data-placeholder="Select Account No." style="width:100%"  <?php echo $disabled;?> <?php echo $readonly;?> >
												<option></option>				 
												<?php
													$Account=$utilObj->getMultipleRow("account_ledger","group_name='7'  group by name");   
													foreach($Account as $a_rec) {
														if($payment1['bankid']==$a_rec['id']) $select='selected'; else $select='';
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
										<input type="text" name='amt_pay'  value="<?php echo $payment1['amt_pay'];?>" onKeyUp="" onBlur="" class="required form-control col-md-7 col-xs-12" <?php echo $readonly ;?>  id="amt_pay">
									</td>

									<td style="width:10%">
										
										<div class="">
											<select name="mode" id="mode" class="required form-select select2" onChange="getchequeno();" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> >
												<option></option>
												<option value="E-Payment" <?php if($payment1["payment_method"]=='E-Payment') echo $select='selected'; else $select='';?>>E-Payment</option>					
												<option value="DD" <?php if($payment1["payment_method"]=='DD') echo $select='selected'; else $select='';?>>DD</option>					
												<option value="UPI" <?php if($payment1["payment_method"]=='UPI') echo $select='selected'; else $select='';?>>UPI</option>
												<option value="cheque" <?php if($payment1["payment_method"]=='cheque') echo $select='selected'; else $select='';?>>Cheque</option>
											</select>
										</div>
									</td>

									<td style="width:10%">
										<div class="">
											<input type="text" name='cheque_no' readonly value="<?php echo $payment1["cheque_no"];?>" class=" form-control col-md-7 col-xs-12"  id="cheque_no" <?php echo $disabled;?>>
										</div>
									</td>

									<td style="width:15%">
										<input type="text" name='narration'  value="<?php echo $payment1['narration'];?>" class="form-control col-md-7 col-xs-12" id="narration" <?php echo $disabled;?>> 
									</td>
								</tr>
							</tbody>
						</table>
					</div>
			
					<div class="col-12 text-center">
						<?php 
							if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''|| $_REQUEST['PTask']=='makepayment'){
						?>	
							<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
						<?php } ?>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
			  		</div>
				</form>
			</div>
    	</div>
  	</div>
</div>
<!--/ Add Role Modal -->
<script>

	window.onload=function(){
		$("#date").flatpickr({
			dateFormat: "d-m-Y"
		});
		$("#date1").flatpickr({
			dateFormat: "d-m-Y"
		});
	}

	function checkbilltype1() {

		var id = $("#id").val();
		// var PTask = $("#PTask").val();
		var value = $("#customer").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'checkbilltype1',value:value,id:id },
			success:function(data)
			{	
				//alert(data);
				$("#bill_type").html(data);
			}
		});

	}

	function get_sale_rec() {
		
		// $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(ClientID) AS pono from voucher_type");
		// $result=mysqli_fetch_array($getinvno);
		// $grn_no=$result['pono']+1;

		var voucher_type = $("#voucher_type").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_sale_rec',voucher_type:voucher_type},
			success:function(data)
			{	
				//alert(data);
				$("#recordnumber").val(data);
				// $(this).next().focus();
			}
		});
	}

</script>
<script>

	// function validate()
	// {
	// 	var val = $("#type").val();

	// 	if(val=='Advanced')
	// 	{
			
	// 		$("#order").hide();
	// 		$("#pending").hide();
	// 		$('#bill').removeClass("required");
	// 		$('#amt_pay').prop('readonly', false);
	// 		$('#amt_pay').addClass("required");
			
	// 	}
	// 	else
	// 	{
	// 		$("#order").show();
	// 		$("#pending").show();
	// 		$('#bill').addClass("required");
	// 		$('#amt_pay').prop('readonly', true);
	// 		//$('#amt_pay').addClass("required");
	// 	}
	// }

	function saletable()
	{			

		var cust = $("#customer").val();
		// var location = $("#location").val();
		var type = $("#type").val();
		var PTask = $("#PTask").val();
		var id = $("#id").val();
		
		// if(type=="PO"){
		jQuery.ajax({url:'get_ajax_values.php',
			type:'POST',
			data: { Type:'saletable',cust:cust,PTask:PTask,id:id,type:type },
			success:function(data)
			{
				//alert(data);
				$('#pending').html(data);

			}
		});
		// }
	}


	function gettotaltable() 
	{ 
		var count=$("#cnt").val();	
		
		var total = 0;
		for(var i=1; i<=count;i++)
		{
				
			if ($('#checkbox'+i).is(':checked')) {
				// alert("Hiiii");
				if(document.getElementById("bank"+i)){
					var get1 = $("#bank"+i).val();		if(get1==''){ get1=0; }
					
					// var get2 = $("#bank1"+i).val();		if(get2==''){ get2=0; }
					// var get=parseFloat(get1)+parseFloat(get2);

					var get=parseFloat(get1);
					
					if(get=='' || get=='undefined'){get=0;}
					// alert(get);
					total += parseFloat(get);
				}
				
			}
			
		}
		// alert(total);
		$("#totalvalue").val(total.toFixed(2));
		$("#amt_pay").val(total.toFixed(2));
		
	}

	function getinputbox(rid,id)
	{
		if ($('#checkbox'+rid).is(':checked')) {
			
			$("#checkboxshow"+rid).css('display', 'block');

			$("#checkboxshow"+rid).html('<input type="text" class=" form-control required"  placeholder="Enter Amount" name="bank'+rid+'" id="bank'+rid+'" onkeyup="gettotaltable();getinputvalues('+rid+','+id+');" "onblur=gettotaltable();getinputvalues('+rid+','+id+');">');

			// $("#discount"+rid).html('<input type="text" class=" form-control required"  placeholder="Enter Discount" name="bank1'+rid+'" id="bank1'+rid+'" onkeyup="gettotaltable();getinputvalues('+rid+','+id+');" "onblur=gettotaltable();getinputvalues('+rid+','+id+');">');	
			
		}else
		{
			$("#checkboxshow"+rid).css('display', 'none');

			$("#checkboxshow"+rid).html('');
			// $("#discount"+rid).html('');
		}
			
	}

	function getinputvalues(rid,id)
	{
		// alert(id);
		
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
			// alert(id+"hii"+get);

			if(get>id)
			{
				alert("Amount should not be Greater than payble amount");
				$("#bank"+rid).val('').trigger('change');
				$("#bank1"+rid).val('').trigger('change');
				var balance=0;
				$("#checkboxvalue"+rid).html('<input type="text" class="form-control" value="'+balance+'" readonly> ');
			}else
			{
				
			}
			
			if(get!="")
			{
				$("#checkboxvalue"+rid).html('<input type="text" class="form-control" value="'+balance+'" readonly> ');
			}
		}else
		{
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
			//$('#account_no').removeAttr("readonly");
			$('#cheque_no').removeAttr("readonly");
			
			$('#cheque_no').addClass("required");
			//$("#cheque_no").addClass('number');
			//$("#amt_pay").val(totalvalue);
		}

	
	} 

	function check_type()
	{
		var mode=$('#mode').val();
		//alert(mode);
			
		jQuery.ajax({url:'get_ajax_values.php',
			type:'POST',
			data: { Type:'cashmethod',mode:mode},			
			success:function(data)
			{				
				$('#Ac').html(data);
			}
		});	
	}

	function get_bill(this_id) {

		var cust = $("#customer").val();
		var recordnumber = $("#recordnumber").val();

		var id=this_id.split("_");
        id=id[1];

		var val = $("#type_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_bill', val:val, cust:cust, id:id, recordnumber:recordnumber },
			success:function(data) {
				
				// alert(data);
				$("#voucher_"+id).html(data);
			}
		});
	}

	function delete_row_adjust(rwcnt)
	{
		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cnt").val();	
		if(count>1)
		{
			var r=confirm("Are you sure!");
			if (r==true)
			{		
				
				$("#row_"+rwcnt).remove();
					
				for(var k=rwcnt; k<=count; k++)
				{
					var newId=k-1;
					
					jQuery("#row_"+k).attr('id','row_'+newId);
					
					jQuery("#idd_"+k).attr('name','idd_'+newId);
					jQuery("#idd_"+k).attr('id','idd_'+newId);
					jQuery("#idd_"+newId).html(newId); 
					
					jQuery("#product_"+k).attr('name','product_'+newId);
					jQuery("#product_"+k).attr('id','product_'+newId);
					
					jQuery("#unit_"+k).attr('name','unit_'+newId);
					jQuery("#unit_"+k).attr('id','unit_'+newId);
					
					jQuery("#qty_"+k).attr('name','qty_'+newId);
					jQuery("#qty_"+k).attr('id','qty_'+newId);
					
					jQuery("#rate_"+k).attr('name','rate_'+newId);
					jQuery("#rate_"+k).attr('id','rate_'+newId);
					
					jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
					
				}
				jQuery("#cnt").val(parseFloat(count-1)); 
			}
		}
		else 
		{
			alert("Can't remove row Atleast one row is required");
			return false;
		}	 
	}		  		  
			  
	function addRow(tableID) 
	{ 
		var count=$("#cnt").val();	
		var state=$("#state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:0%;text-align:center;'>"+i+"</td>";
	   
		cell1 += "<td style='width:7%' ><select name='type_"+i+"' class='required select2 form-select'  id='type_"+i+"' onchange='get_bill(this.id);' style=''>\
			<option value=''>Select Type</option>\
			<option value='Advanced'>New Reference</option>\
			<option value='PO'>Against Bill</option>\
		</select></td>";

		cell1 += "<td style='width:10%'><div id='voucher_"+i+"'></div></td>";

		cell1 += "<td style='width:4%'><input name='invodate_"+i+"' id='invodate_"+i+"' readonly class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:8%'><input name='totalinvo_"+i+"' readonly id='totalinvo_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='pendingamt_"+i+"' readonly id='pendingamt_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='payamt_"+i+"' id='payamt_"+i+"' class='form-control required tdalign' type='text' onkeyup='gettotalamt(this.id);' />\
		</td>";
		
		cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row_adjust(this.id);'></i></td>";



		$("#myTable").append(cell1);
		$("#cnt").val(i);
		// $("#particulars_"+i).select2();
		// $(".select2").select2();
		 
	}

	function getinvo_info(this_id) {

		var cust = $("#customer").val();

		var id=this_id.split("_");
        id=id[1];

		var billno = $("#billno_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getinvo_info',billno:billno, cust:cust},
			success:function(data)
			{
				var bdid=data.split("#");
                var meas=bdid[0].split(",");
				alert(bdid[0]);
                jQuery("#invodate_"+id).val(bdid[0]);
                jQuery("#totalinvo_"+id).val(bdid[1]);
                jQuery("#pendingamt_"+id).val(bdid[2]);
			}
		});

	}

	function gettotalamt(this_id) {

		var id=this_id.split("_");
        id=id[1];

		var totaltaxable = 0;

		$("[id^='payamt_']").each(function() {
			var quant = parseFloat($(this).val()) || 0;
			// Convert the value to a number, default to 0 if not a valid number
			totaltaxable += quant;
		});

		$("#totalvalue").val(totaltaxable.toFixed(2));
		$("#amt_pay").val(totaltaxable.toFixed(2));
	}

</script>

             



