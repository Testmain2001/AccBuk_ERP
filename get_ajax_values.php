<?php include 'config.php'; 
$utilObj=new util();
$type=$_REQUEST['Type'];
switch($type)
{
	// ---------------------- USE IN- Stock Journal(1) ----------------------
	case'get_qty_from_purchaseinvoice':
		$purchase_invoice=$utilObj->getSingleRow("purchase_invoice_details"," product ='".$_REQUEST['product']."' AND unit ='".$_REQUEST['unit']."'  AND  parent_id in (select id from purchase_invoice where location ='".$_REQUEST['location']."') order by LastEdited desc");
		echo $purchase_invoice['qty'];
	break;

	// ---------------------- USE IN Physical Stock,Stock Journal ----------------------
	case'get_product_stock':

		// echo getstock($_REQUEST['product'],$_REQUEST['unit'],date('Y-m-d'),'',$_REQUEST['location']);
		echo getlocationstock('',$_REQUEST['product'],date('Y-m-d'),$_REQUEST['location']);

	break;

	// ---------------------- USE IN -Physical Stock(2) ----------------------
	case'get_locationwise_productstock_forphysical':

		$loaction=$_REQUEST['location'];
		$common_id = $_REQUEST['ad'];

		if($_REQUEST['PTask']=='view') {

			$readonly="readonly";
			$disabled="disabled";
		}
		else {

			$readonly="";
			$disabled="";
		}
	?>
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<th style="width:10%;text-align:center;">Stock</th>
					<th style="width:10%;text-align:center;">Physical Stock </th>
					<th style="width:10%;text-align:center;">Add Stock</th>
					<th style="width:10%;text-align:center;">Less Stock</th>
					<th style="width:10%;text-align:center;">Batch</th>
				<?php if($_REQUEST['PTask']!='view') { ?>

					<th style="width:2%;text-align:center;"></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ 
				    $record5=$utilObj->getMultipleRow("physical_stock_details"," parent_id='".$_REQUEST['id']."' order by id  ASC");
				} else {
					$record5[0]['id']=1;
				}

				foreach($record5 as $row_demo)
				{

					$i++;
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td  style="width: 20%;">
					<?php 

                        $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view') {
					?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
					<?php } else { ?>

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_stock(this.id);get_unit(this.id);check_physicalbatch(this.id);" style="width:100%;">	
							<option value=""></option>
							<?php
								$record=$utilObj->getMultipleRow("stock_ledger","1 group by name ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					<?php } ?>
					</td>
					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>

					<td style="width: 10%;">
					<?php 
						$tostock = getlocationstock('',$row_demo['product'],date('Y-m-d'),$loaction);
					?>
					 	<input type="text"  id="stock_<?php echo $i;?>"  <?php echo $readonly;?> readonly class="form-control number"  name="stock_<?php echo $i; ?>" value="<?php echo $tostock; ?>"/>
					</td>

					<td style="width: 10%;">
						<input type="text" id="physicalstock_<?php echo $i;?>" class=" form-control number" onkeyup="add_less_stock(this.id);"   <?php echo $readonly;?> name="physicalstock_<?php echo $i;?>" value="<?php echo $row_demo['physicalstock']; ?>"/>
					</td> 
					 

					<td style="width: 10%;">
						<input type="text" id="addstock_<?php echo $i;?>" readonly class=" form-control required"   <?php echo $readonly;?> name="addstock_<?php echo $i;?>" value="<?php echo $row_demo['addstock'];?>"/>
					</td>
					 
					<td style="width: 10%;">
						<input type="text" id="lessstock_<?php echo $i;?>"  readonly class=" form-control required"    <?php echo $readonly;?> name="lessstock_<?php echo $i;?>" value="<?php echo $row_demo['lessstock'];?>"/>
					</td>

					<td style="width: 10%;text-align:center;">
						<?php if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') { ?>

							<?php 
								$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
							?>
								<div id='divbatch_<?php echo $i; ?>'>
									<!-- <button type="button" class="btn btn-primary" onClick="check_qty(<?php echo $i; ?>)" >Edit Batch</button> -->
									<button type="button" class="btn btn-light" onclick="physical_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#physicalbatch">
										<i class="fas fa-box fa-lg" style="color: #000000;"></i>
									</button>
								</div>
						<?php } else { ?>

							<div id="divbatch_<?php echo $i; ?>">

							</div>
						<?php } ?>
					</td>
					<?php if($_REQUEST['PTask']!='view'){?>
						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
					 
				</tr>
			<?php } ?>
					
			</tbody>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
		</table>
		<br>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:right;">
				<td>
					<?php
						if( $_REQUEST['PTask']!='view') { ?>
		
						<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					<?php } ?>
				</td>			
			</tr>
		</table>

		<div class="modal fade" style = "max-width=40%; " id="physicalbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="phybatch">
					
				</div>
			</div>
		</div>

		<script>

			function check_physicalbatch(id){
			
				var id=id.split("_");
				id=id[1];
				var product = $("#product_"+id).val();
				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_physicalbatch',id:id,product:product},
					success:function(data)
					{	
						$("#divbatch_"+id).html(data);	
						$(this).next().focus();
					}
				});	

			}

			function physical_batchdata(i) {
								                      
				var qty =$("#physicalstock_"+i).val();
				var stock =$("#stock_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				// var ad = $("#ad").val();
				var id = $("#id").val();
				var location =$("#location").val();
				var product =$("#product_"+i).val();
				var addstock =$("#addstock_"+i).val();
				var lessstock =$("#lessstock_"+i).val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'physical_batchdata', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,addstock:addstock,lessstock:lessstock },
					success: function (data) {

						$('#phybatch').html(data);
						$('#physicalbatch').modal('show');
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

		</script>

	<?php
	break;

	// ---------------------------- Physical Batch Check ----------------------------
	case 'check_physicalbatch':
	
		$i = $_REQUEST['id'];
		$mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "'");
	?>

		<div id='divbatch_<?php echo $i; ?>'>

			<button type="button" class="btn btn-light" onclick="physical_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#physicalbatch">
				<i class="fas fa-box fa-lg" style="color: #000000;"></i>
			</button>

		</div>

	<?php
	break;

	// ---------------------------- Physical Stock Batch  ----------------------------

	case 'physical_batchdata':
	
		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$qty = $_REQUEST['qty'];
		$stock = $_REQUEST['stock'];
		$addstock = $_REQUEST['addstock'];
		$lessstock = $_REQUEST['lessstock'];

		$i=$_REQUEST['i'];
		$c=0;
		$totalstock=0;
	?>

		<div id="phybatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<table class = "table border-top" >
							
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
						<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="stock" id="stock" value="<?php echo $stock; ?>">
						<input type="hidden" name="addstock" id="addstock" value="<?php echo $addstock; ?>">
						<input type="hidden" name="lessstock" id="lessstock" value="<?php echo $lessstock; ?>">

						<p>
							Total Stock : <?php echo $stock; ?> &nbsp;&nbsp;
							Physical Stock : <?php echo $qty; ?> &nbsp;&nbsp;
							Add Stock : <?php echo $addstock; ?> &nbsp;&nbsp;
							Remove Stock : <?php echo $lessstock; ?> &nbsp;&nbsp;
						</p>

						<thead>
							<tr>
								<th>Batch name</th>
								<th>Batch Stock</th>
								<th>Physical Stock</th>
								<th>Add Stock</th>
								<th>Remove Stock</th>
							</tr>
						</thead>
						<tbody>
						<?php

							if($PTask != 'update' && $_REQUEST['PTask']!='view'){

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND flag='0' AND location='".$location."' AND purchase_batch='' ");
							} else {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."' ");
							}
							$productsum = $utilObj->getSum("purchase_batch", "product='" . $product_id . "'", "batqty");
							$sumqty=0;
							foreach ($product as $info) {
								
								if($PTask != 'update' && $_REQUEST['PTask']!='view') {

									$totalstock = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
								} else {
									
									$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $location);

									$quantity = $totalstock+$info['batqty'];

									if($quantity>$info['batchphysical']) {

										$badd = 0;
										$bremove = $quantity - $info['batchphysical'];
									} else {

										$badd = $quantity - $info['batchphysical'];
										$bremove = 0;
									}
								}

								if($PTask == 'update' || $_REQUEST['PTask']=='view') {

									$b_id = $info['purchase_batch'];
								} else {

									$b_id = $info['id'];
								}

								$c++;
						?>
							<tr id="row1_<?php echo $c; ?>">
								<input type="hidden" name="id[]" class="batch_id" value="<?php echo $b_id; ?>">
								<input type="hidden" id="batchrate_<?php echo $c; ?>" name="batchrate_<?php echo $c; ?>" class="batch_id" value="<?php echo $info['bat_rate']; ?>">
								<input type="hidden" id="batchid_<?php echo $c; ?>" name="batchid_<?php echo $c; ?>" class="batch_id" value="<?php echo $b_id; ?>">

								<td style="width:15%;">
									<input type="text" id="batchname1_<?php echo $c; ?>" class=" form-control number" name="batchname1_<?php echo $c; ?>" value="<?php echo $info['batchname']; ?>" readonly />
								</td>
							<?php 
								if($PTask == 'update' || $_REQUEST['PTask']=='view') {
									$total = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
									
									
									$sumqty+=$totalstock+$info['quantity'];?>
								<td style="width:15%;">
									<input readonly type="text" id="batqty_<?php echo $c; ?>" class=" form-control number" name="batqty_<?php echo $c; ?>" value="<?php echo $quantity;?>"/>
								
								</td>
								<td style="width:15%;">
									<input type="text" id="batchphysical_<?php echo $c; ?>" class="form-control number" onkeyup="get_diff(this.id);" name="batchphysical_<?php echo $c; ?>"  value="<?php echo $info['batchphysical']; ?>"/>
								</td>
								<td style="width:15%;">
									<input type="text" readonly id="batchadd_<?php echo $c; ?>" class="form-control number" name="batchadd_<?php echo $c; ?>"  value="<?php echo $badd; ?>"/>
								</td>
								<td>
									<input type="text" readonly id="batchremove_<?php echo $c; ?>" class="form-control number" name="batchremove_<?php echo $c; ?>"  value="<?php echo $bremove; ?>"/>
								</td>
							<?php } else { ?>
								<td style="width:15%;">
									<input readonly type="text" id="batqty_<?php echo $c; ?>" class=" form-control number" name="batqty_<?php echo $c; ?>" value="<?php echo $totalstock; ?>"/>
								</td>
								<td style="width:15%;">
									<input type="text" id="batchphysical_<?php echo $c; ?>" class="form-control number " onkeyup="get_diff(this.id);" name="batchphysical_<?php echo $c; ?>" value="" />
								</td>
								<td style="width:15%;">
									<input type="text" readonly id="batchadd_<?php echo $c; ?>" class="form-control number " name="batchadd_<?php echo $c; ?>" value="" />
								</td>
								<td style="width:15%;">
									<input type="text" readonly id="batchremove_<?php echo $c; ?>" class="form-control number " name="batchremove_<?php echo $c; ?>" value="" />
								</td>
							<?php } ?>
								
							</tr>
							<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $c; ?>">

						<?php } ?>
						</tbody>
						<!-- <td></td>
						<td></td>
						<td>
							Total Quantity : <input readonly class="form-control number" type="text" name="total_batch_remove" id="total_batch_remove" value="">
						</td>
						<td></td>
						<td></td> -->
					</table>
				</div>
			</div>
			
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="physicalstockbatch(<?php echo $i; ?>);" />
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#total_batch_remove').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();
			});

			function get_diff(this_id) {

				var did=this_id.split("_");
				rid=did[1];

				var physical_stock=jQuery("#batchphysical_"+rid).val(); 
				var stock=jQuery("#batqty_"+rid).val(); 
				
				if(parseFloat(physical_stock) < parseFloat(stock)) {

					var amt = parseFloat(stock) - parseFloat(physical_stock);
					$('#batchremove_'+rid).val(amt);
					$('#batchadd_'+rid).val(0);
				} else if(parseFloat(physical_stock) > parseFloat(stock)) {

					var amt = parseFloat( physical_stock) - parseFloat(stock);
					$('#batchadd_'+rid).val(amt);
					$('#batchremove_'+rid).val(0);
				} else if(parseFloat(physical_stock) == parseFloat(stock)) {

					$('#batchadd_'+rid).val(0);
					$('#batchremove_'+rid).val(0);
				}
			}

			function physicalstockbatch(mi) {

				var cnt1 = $("#cnt1").val();
				var product = $("#product_batch").val();
				var location = $("#location").val();
				var common_id = $("#ad").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "";
				var batchIds = [];

				$(".batch_id").each(function () {
					batchIds.push($(this).val());
				});

				// var batchadd_array=[];
				var batchid_array=[];
				var batchphysical_array=[];
				var batchname_array=[];
				var batchremove_array=[];
				var batchrate_array=[];
				
				for(var i=1;i<=cnt1;i++) {	

					var addstock = $("#batchadd_"+i).val();
					var lessstock = $("#batchremove_"+i).val();
					var batchremove = 0;

					if (addstock > 0) {

						type = "physical_batch_in";
						batchremove = $("#batchadd_"+i).val();
					} else {

						type = "physical_batch_out";
						batchremove = $("#batchremove_"+i).val();
					}

					// var batchremove = $("#batchremove_"+i).val();
					// var batchadd = $("#batchadd_"+i).val();
					var batchid = $("#batchid_"+i).val();
					var batchphysical = $("#batchphysical_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batchrate = $("#batchrate_"+i).val();

					// batchadd_array.push(batrate);
					batchid_array.push(batchid);
					batchphysical_array.push(batchphysical);
					batchname_array.push(batchname);
					batchremove_array.push(batchremove);
					batchrate_array.push(batchrate);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'physicalstockbatch',batchremove_array:batchremove_array,batchname_array:batchname_array,batchphysical_array:batchphysical_array,cnt1:cnt1,deliveryid:deliveryid,product:product,common_id:common_id,PTask:PTask,type:type,location:location,batchrate_array:batchrate_array,batchid_array:batchid_array },
					success: function (data) {
						$('#physicalbatch').modal('hide');
						// alert(data);
						console.log(data);
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}
		</script>
	<?php
	break;

	// ---------------------------- Physical Batch Handler ----------------------------
	case 'physicalstockbatch':
		
		if($_REQUEST['PTask']=='update') {

			$common=$_REQUEST['deliveryid'];
		} else {

			$common=$_REQUEST['common_id'];
		}

		// $arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['id'],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname'],'quantity'=>$_REQUEST['batchremove'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s") );

		$cnt2 = $_REQUEST['cnt1'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s"),'batchphysical'=>$_REQUEST['batchphysical_array'][$i],'bat_rate'=>$_REQUEST['batchrate_array'][$i],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'location'=>$_REQUEST['location'] );
			
			$insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);
			// echo $insertedId;
		}

		// $insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);

	break;

	// ============================= USE IN=Bank Transfer(1) =============================	
	case 'GetAccountBalance':

		echo getbalance($_REQUEST['account_id'],$_REQUEST['PayID'],date('Y-m-d',strtotime($_REQUEST['date'])),$utilObj);
	break;

	// ============================= UseIN = Bank Transfer,Purchase Payment =============================
	case 'cashmethod':
	
	?>
		<select id="bankid" name="bankid" class="required form-select select2" onchange="getBalance();" data-placeholder="Select Account No." style="width:100%"  <?php echo $disabled;?> <?php echo $readonly;?> >
                <option></option>				 
			<?php 
			if($_REQUEST['mode']=='cash'){ 
					$cnd_bank="group_name='8'"; 
				}
				else{ 
				    $cnd_bank="group_name='7' OR OR group_name='22'";
				}
			$Account=$utilObj->getMultipleRow("account_ledger"," $cnd_bank group by name"); 		
			foreach($Account as $a_rec){ 
			echo  '<option value="'.$a_rec["id"].'" '.$select.'>'.$a_rec["name"].' </option>';
			}
			?>				
		</select>
		
	<?php
	break;
	// ================================= USE IN = Dispatch (1) =========================================
	case 'get_saleinvoice_fordispatch':
         
		 $dispatch=$utilObj->getSingleRow("dispatch"," id='".$_REQUEST['id']."' ");
				?>	
				<label class="form-label"> Sale Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
				<div >
				   <?php if($_REQUEST['PTask']=='view' ){
					    $readonly="readonly";
				        $sale_invoice_no=$utilObj->getSingleRow("sale_invoice","id in (select sale_invoice_no from  dispatch where id ='".$_REQUEST['id']."')");
					?>
						<input type="hidden" id="sale_invoice_no" <?php echo $readonly;?> name="sale_invoice_no" value="<?php echo $sale_invoice_no['id'];?>"/>
						<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $sale_invoice_no['sale_invoiceno'];?>"/>
						
						<?php  }else{ ?>
					<select id="sale_invoice_no" name="sale_invoice_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange=" saleinvoice_fordispatch_rowtable();">
					 <option value=""> Select Sale Invoice No</option>
						<?php 
							$record=$utilObj->getMultipleRow("sale_invoice","customer ='".$_REQUEST['customer']."' AND location ='".$_REQUEST['location']."'group by sale_invoiceno");
							foreach($record as $e_rec){
								if($dispatch['sale_invoice_no']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sale_invoiceno"].'</option>';
							}
						?> 
					</select>
				   
				   <?php } ?>
				</div>
				<?php
	break;
//==============================USE IN = Dispatch (2)==================================== 
case 'saleinvoice_fordispatch_rowtable':	
         $location=$_REQUEST['location'];
	    $sale_invoice_no=$_REQUEST['sale_invoice_no'];
		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['customer']."' ");
        $state= $account_ledger['mail_state']; 
		if( $state==27) {

			$colspan=7;
		} else {

			$colspan=6;
		}
		
	    $dispatch=$utilObj->getSingleRow("dispatch"," id='".$_REQUEST['id']."' ");
	    //var_dump($purchase_return);
		if($_REQUEST['PTask']=='view'){
			 $readonly="readonly";
		}else{
			$readonly =" ";
		}
		 
		?>	
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<?php if( $state==27){ ?>
						<th style="width: 5%;text-align:center;">CGST </th>
						<th style="width: 5%;text-align:center;">SGST </th>
					<?php } else { ?>
						<th style="width: 5%;text-align:center;">IGST </th>
					<?php } ?>
					<th style="width:10%;text-align:center;"> Invoice Quantity<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;"> Remaining Invoice Quantity<span class="required required_lbl" style="color:red;">*</span></th>
					<th id="totalth" style="width: 8%;">Stock</th>
					<th id="totalth" style="width: 8%;">Dispatch Qty</th>
					<th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th>
						<?php if($_REQUEST['PTask']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
						<?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
			 
					if(($sale_invoice_no!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'))
					{  
						// echo "condi 2";
						$record5=$utilObj->getMultipleRow("sale_invoice_details","parent_id='".$sale_invoice_no."' order by id  ASC ");
					}
					else
					{ 
						$record5[0]['id']=1;					
					}   
					foreach($record5 as $row_demo)
					{ 
					//var_dump($row_demo);
					$dispatch_qtysum=$utilObj->getSum("dispatch_details"," product='".$row_demo['product']."'AND unit='".$row_demo['unit']."' AND parent_id in (select parent_id from dispatch where  sale_invoice_no='".$sale_invoice_no."'   )","qty");
					
						if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($sale_invoice_no==$dispatch['sale_invoice_no']))
					{ 
				       	// echo"update";
						$dispatch_details=$utilObj->getSingleRow("dispatch_details","parent_id='".$_REQUEST['id']."' AND product='".$row_demo['product']."'AND unit='".$row_demo['unit']."' AND parent_id in (select parent_id from dispatch where  sale_invoice_no='".$sale_invoice_no."'   )");
						$dispatchqty=$dispatch_details['qty'];
						
						$remainqty=$row_demo['qty']-$dispatch_qtysum;
				     }else{
						// echo "add";
						$dispatchqty=$remainqty=$row_demo['qty']-$dispatch_qtysum;
					 } 
					 $total=$row_demo['total'];
					 $i++;
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 20%;">
					    <?php 
							$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
					    ?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control"  readonly <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
						
					</td>
					<td style="width: 10%;">
					<div id='unitdiv_<?php echo $i;?>'>
						<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
					</div>
					</td>
					
						<?php if( $state==27){?>
					<td style="width: 5%;">
					 <input type="text" id="cgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
					 </td>
					 
					 <td style="width: 5%;">
					 <input type="text" id="sgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
					 </td>
						<?php }else{?>
					 <td style="width: 5%;">
					 <input type="text" id="igst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> readonly name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
					 </td>
					<?php }?>
					
				
					 <td style="width: 10%;">
					 <input type="text" id="invoiceqty_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> readonly name="invoiceqty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					 </td>

					 <td style="width: 10%;">
					 <input type="text" id="remainqty_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> readonly name="remainqty_<?php echo $i;?>" value="<?php echo $remainqty;?>"/>
					 </td>
					 

					 <td style="width: 10%;">
					 <?php 
                  	 $totalstock=getstock($row_demo['product'],$row_demo['unit'],date('Y-m-d'),$row_demo['parent_id'],$location); ?>
					 <input type="text"  id="stock_<?php echo $i;?>" class=" form-control number"  name="stock__<?php echo $i;?>" readonly  value="<?php echo $totalstock;?>"/>
					 </td>
					 
					 <td style="width: 10%;">
					 <input type="text" id="qty_<?php echo $i;?>" class=" form-control number"  onKeyUp="get_totalqty();stock_check();"  onBlur="get_totalqty();stock_check();"  <?php echo $readonly;?>  name="qty_<?php echo $i;?>" value="<?php echo $dispatchqty;?>"/>
					 </td>

					 <td style="width: 10%;">
					 <input type="text" id="total_<?php echo $i;?>" class="number form-control"   onkeyup="showgrandtotal();" onBlur="showgrandtotal();" readonly <?php echo $readonly;?> name="total_<?php echo $i;?>" value="<?php echo $total;?>"/>
					 </td>
					 
					<?php if($_REQUEST['Task']!='view'){?>
						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
				
				   
				<script>
				
				get_totalqty();
				</script>
			<?php
                   if($dispatchqty >$totalstock){
						$stock_chk++; 
					   }

			} ?>
					<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
			<tr>
			
				<td colspan="<?php echo $colspan; ?>" style="text-align:right;">
				Total Quantity
				</td>
				<td>
				 <input type="text" id="total_quantity" class="number form-control" readonly name="total_quantity" value="<?php  echo $total_quantity;?>"/>
				</td>
				<td>
				</td>
				<td>
				</td>
			</tr>
			</tfoot>
		</table>
		 <table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
                   <td>
						<?php 
					
						/* if(($_REQUEST['PTask']!='view' && $purchaseorder_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php  } */ ?> 
				</td>			
			</tr>
		</table> 
		  <div class="row text-center" >
		    <div id="submit_div" style="margin-bottom:10px;text-align:right;" class="col-md-6">
            <?php //echo "stock chk=".$stock_chk;
			if(($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='Add')&& $stock_chk<=0){?>	
				<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
			<?php }elseif($_REQUEST['PTask']!='view'){ ?>
			<span style="color:red;">Quantity Should Not Gratter Than Stock!!!  
			</span>
			<?php }?>
			</div>
			<div class="col-md-6" style="text-align:left;">
			
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
			
			</div>
		</div>
	<?php
	break;

	case 'get_bstock':
		$location = $_REQUEST['location'];
		$productid = $_REQUEST['product'];

		$fromstock = getlocationstock('',$productid, date('Y-m-d'), $location);
		echo $fromstock;
		
	break;

	// ---------------------- Use In - Stock Transfer(1) -------------------------------
	case 'get_locationwise_productstock':

		$loaction = $_REQUEST['location'];
		if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'reset') {

			$stock_transfer = $utilObj->getSingleRow("stock_transfer", "id ='" . $_REQUEST['id'] . "'");
		} else if ($_REQUEST['PTask'] == 'receive') {
			
			$stock_transfer = $utilObj->getSingleRow("stock_request", "id ='" . $_REQUEST['id'] . "'");
		}

		if ($_REQUEST['PTask'] == 'view') {
			$readonly = "readonly";
			$disabled = "disabled";
		} else {
			$readonly = "";
			$disabled = "";
		}
	?>

		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:1%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 7%;text-align:center;">Unit </th>
					<th style="width:10%;text-align:center;">From(availiable stock qty) </th>
					<?php
						if ($_REQUEST['PTask'] == 'receive' || $_REQUEST['PTask'] == 'reset' || $_REQUEST['PTask'] == 'send' || $stock_transfer['request_id'] != '') {
					?>
						<th style="width:10%;text-align:center;">Requisition Qty </th>
					<?php } ?>
					<th style="width:10%;text-align:center;">To(transfred stock qty)</th>
					<th style="width:10%;text-align:center;">To Location</th>
					<th style="width:3%;text-align:center;">Batch</th>
					<?php

						if ($_REQUEST['PTask'] == 'send') {
					?>
						<th style="width:15%;text-align:center;">Remark</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = 0;

				// if ($_REQUEST['PTask'] == 'view' || $_REQUEST['PTask'] == 'update') {
				// 	$record5 = $utilObj->getSingleRow("stock_transfer", "id ='" . $_REQUEST['id'] . "' ");
				// 	echo "hi_1111";
				// } else {
				// 	$record5 = $utilObj->getMultipleRow("stock_ledger", "1 order by id  ASC");
				// 	echo "hi_2222";
				// }

				if (($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view' || $_REQUEST['PTask'] == 'reset') && ($_REQUEST['location'] == $stock_transfer['location'])) {

					// echo "condi 1";
					$record5 = $utilObj->getMultipleRow("stock_transfer_details", "parent_id='" . $_REQUEST['id'] . "' order by id  ASC");
				} else if ($_REQUEST['PTask'] == 'receive') {

					$record5 = $utilObj->getMultipleRow("stock_request_details", "parent_id='" . $_REQUEST['id'] . "' order by id  ASC");
				} else if ($_REQUEST['PTask'] == 'send') {

					$record5 = $utilObj->getMultipleRow("production_requisition_details", "parent_id='" . $_REQUEST['id'] . "' AND flag='0' order by id  ASC");
				} else {

					// $record5 = $utilObj->getMultipleRow("stock_ledger", "1 order by id  ASC");
					$record5[0]['id']=1;
				}

				foreach ($record5 as $row_demo) {

					$i++;

					if (($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view') && ($_REQUEST['location'] == $stock_transfer['location'])) {

						$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
						$streq = $utilObj->getSingleRow("production_requisition_details", "parent_id='".$stock_transfer['request_id']."' AND product='".$row_demo['product']."' ");

						$reqqty = $streq['qty'];
						$tostock = $row_demo['tostock'];
						$reqid = $streq['id'];
						$reqflag = $streq['flag'];
						$productid = $row_demo['product'];
						$productnm = $product['name'];

					} else if ($_REQUEST['PTask'] == 'receive' || $_REQUEST['PTask'] == 'reset') {

						$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");

						$productid = $row_demo['product'];
						$productnm = $product['name'];
						$fromstock = getlocationstock('',$productid, date('Y-m-d'), $location);
					} else if ($_REQUEST['PTask'] == 'send') {

						$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
						$qty=$utilObj->getSum("stock_transfer_details","parent_id in(select id from stock_transfer where request_id='".$row_demo['parent_id']."') AND product='".$row_demo['product']."' ","tostock");

						$reqqty = $row_demo['qty'];
						$tostock = $row_demo['qty']-$qty;
						$reqid = $row_demo['id'];
						$reqflag = $row_demo['flag'];
						$productid = $row_demo['product'];
						$productnm = $product['name'];
						$fromstock = getlocationstock('',$productid, date('Y-m-d'), $location);
					} else {

						$product['batch_maintainance']=$row_demo['batch_maintainance'];
						$productid = $row_demo['id'];
						$productnm = $row_demo['name'];
					}

			?>	
				<input type="hidden" id="reqid_<?php echo $i; ?>"  <?php echo $readonly; ?> name="reqid_<?php echo $i; ?>" value="<?php echo $reqid; ?>"/>
				<tr id='row_<?php echo $i; ?>'>
					<td style="text-align:center;">
						<label  id="idd_<?php echo $i; ?>"   name="idd_<?php echo $i; ?>"><?php echo $i; ?> </label>
					</td>
					<td >
						<!-- <input type="hidden" id="product_<?php echo $i; ?>"  <?php echo $readonly; ?> name="product_<?php echo $i; ?>" value="<?php echo $productid; ?>"/>
						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly; ?>  value="<?php echo $productnm; ?>"/> -->

						<select id="product_<?php echo $i; ?>" name="product_<?php echo $i; ?>" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_bstock(this.id);" style="width:100%;">	
						<?php
							echo '<option value="">Select</option>';
							$record = $utilObj->getMultipleRow("stock_ledger", "1 ");
							foreach ($record as $e_rec) {
								if ($productid == $e_rec["id"]) echo $select = 'selected';
								else $select = '';
								echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["name"] . '</option>';
							}
						?>
						</select>
					</td>

					<td >
						<div id='unitdiv_<?php echo $i; ?>'>
							<input type="text" id="unit_<?php echo $i; ?>" class=" form-control required"  readonly <?php echo $readonly; ?> name="unit_<?php echo $i; ?>" value="<?php echo $row_demo['unit']; ?>"/>
						</div>
					</td>

					<td >
					<?php
						// $fromstock = getstock($productid, $row_demo['unit'], date('Y-m-d'), $_REQUEST['id'], $loaction);
						$fromstock = getlocationstock('',$productid, date('Y-m-d'), $loaction);
					?>
						<input type="text" id="fromstock_<?php echo $i; ?>" class="tdalign form-control number"  readonly <?php echo $readonly; ?> name="fromstock_<?php echo $i; ?>" value="<?php echo $fromstock; ?>"/>
					</td>
					<?php if ($_REQUEST['PTask'] == 'receive') { ?>
						<td >
							<input type="text" id="requestqty_<?php echo $i; ?>" class="tdalign form-control number"  readonly <?php echo $readonly; ?> name="requestqty_<?php echo $i; ?>" value="<?php echo $row_demo['tostock']; ?>" />
						</td>
					<?php } elseif ($_REQUEST['PTask'] == 'reset') { ?>
						<td >
							<input type="text" id="requestqty_<?php echo $i; ?>" class="tdalign form-control number"  	readonly <?php echo $readonly; ?> name="requestqty_<?php echo $i; ?>" value="<?php echo $row_demo['requested_qty']; ?>"/>
						</td> 
					<?php } elseif ($_REQUEST['PTask'] == 'send' || $reqid != '') { ?>
						<td >
							<input type="text" id="requestqty_<?php echo $i; ?>" class="tdalign form-control number"  	readonly <?php echo $readonly; ?> name="requestqty_<?php echo $i; ?>" value="<?php echo $reqqty; ?>"/>
						</td> 
					<?php } ?>

					<td >
						<?php
							// $tostock=getstock($row_demo['product'],$row_demo['unit'],date('Y-m-d'),$_REQUEST['id'],$loaction);
						?>
						<input type="text"  id="tostock_<?php echo $i; ?>" onchange="stock_check(this.id);" onKeyUp="stock_check(this.id);"  <?php echo $readonly; ?>  class="tdalign form-control"  name="tostock_<?php echo $i; ?>"   value="<?php echo $tostock; ?>"/>
					</td>

					<td >
						<select id="location_<?php echo $i; ?>" name="location_<?php echo $i; ?>"  <?php echo $disabled; ?> class="select2 form-select " data-allow-clear="true"  style="width:100%;">	
						<?php
							echo '<option value="">Select</option>';
							$record = $utilObj->getMultipleRow("location", "id!='" . $loaction . "' ");
							foreach ($record as $e_rec) {
								if ($row_demo['location'] == $e_rec["id"])
									echo $select = 'selected';
								else
									$select = '';
								echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["name"] . '</option>';
							}
						?>
						</select>
					</td>

					<td>
						<div id='divbatch_<?php echo $i; ?>' style="text-align:center;">
							<button type="button" class="btn btn-light btn-sm" onClick="check_qty(<?php echo $i; ?>)"><?php if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'reset') { ?> <i class="fas fa-box fa-lg" style="color: #000000;"></i> <?php } else { ?> <i class="fas fa-box fa-lg" style="color: #000000;"></i> <?php } ?></button>
						</div>
					</td>

					<?php
						if($_REQUEST['PTask'] == 'send') {
					?>
						<td style="text-align:center;">
							<!-- <div id='divremark_<?php echo $i; ?>' style="text-align:center;">
								<input type="text" id="reason_<?php echo $i; ?>" class="mt-1 form-control number" <?php echo $readonly; ?> name="reason_<?php echo $i; ?>" value="<?php echo $reqqty; ?>"/>
							</div> -->

							<input type="button" class="btn btn-sm btn-warning" name="remark_<?php echo $i; ?>" value="Remark" onclick="check_remark('remark_<?php echo $i; ?>', 'divremark_<?php echo $i; ?>');" id="remark_<?php echo $i; ?>" />

							<div class="input-group" id="divremark_<?php echo $i; ?>" style="display:none;">
								<input type="text" class="form-control number" placeholder="enter" name="reason_<?php echo $i; ?>" id="reason_<?php echo $i; ?>" value="">
								<div class="input-group-append">
									<button class="btn btn-sm btn-primary " type="button" onclick="save_reason(<?php echo $i; ?>);">
										<i class="fas fa-save fa-lg"></i>
									</button>
								</div>
							</div>
							<!-- <input type="hidden" name="remarkflag_<?php echo $i; ?>" id = "remarkflag_<?php echo $i; ?>" value="0"> -->
						</td>
					<?php }
						//elseif($_REQUEST['PTask'] == 'update') { ?>

						<!-- <td style="text-align:center;">
							<?php if($reqflag == '0') { ?>
								<input type="button" class="btn btn-sm btn-warning" name="remark_<?php echo $i; ?>" value="Remark" onclick="check_remark('remark_<?php echo $i; ?>', 'divremark_<?php echo $i; ?>');" id="remark_<?php echo $i; ?>" />
							<?php } else { ?>
								<div class="input-group" id="divremark_<?php echo $i; ?>" style="">
									<input type="text" class="form-control number" placeholder="enter" name="reason_<?php echo $i; ?>" id="reason_<?php echo $i; ?>" value="">
									<div class="input-group-append">
										<button class="btn btn-sm btn-primary " type="button" onclick="save_reason(<?php echo $i; ?>);">
											<i class="fas fa-save fa-lg"></i>
										</button>
									</div>
								</div>
							<?php } ?>
						</td> -->
					<?php //} ?>
				</tr>
			<?php } ?>
			</tbody>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
		</table>
		<br>
		<table>
			<tr style="">
				<td style="text-align:right;">
					<?php if ($_REQUEST['PTask'] != 'view') { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table>

		<div class="modal fade" style = "max-width=40%; " id="transferbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="transfersbatch">
					
				</div>
			</div>
		</div>

		<script>

			function save_reason(id) {

				var reason = $("#reason_"+id).val();
				var reqid = $("#reqid_"+id).val();
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'save_reason', id:id, reason:reason, reqid:reqid, product:product },
					success:function(data) {

						console.log("Success");
					}
				});
			}
		</script>

	<?php
	break;

	case 'save_reason':

		$arrValue = array('reason' => $_REQUEST['reason'],'flag'=>'1');

		$strWhere = "id='".$_REQUEST['reqid']."' ";
		$Updaterec = $utilObj->updateRecord('production_requisition_details', $strWhere, $arrValue);

	break;

	// ================================= Packaging BOM =================================
	case 'get_bom_pack':

		$product = $_REQUEST['product'];
	?>
		<label class="form-label">BOM<span class="required required_lbl" style="color:red;">*</span></label>
		<select id="bom" name="bom" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_billofmaterial_rowtable_forpackaging();" style="width:100%;">	
			<?php 
				echo '<option value="">Select</option>';
				$record=$utilObj->getMultipleRow("bill_of_material","product='".$product."' ");
				foreach($record as $e_rec)
				{
					if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["bom_name"] .'</option>';
				}
			?>
		</select>
	<?php
	break;

	// ================================= USE IN=Packaging(1) =================================
	case'get_billofmaterial_rowtable_forpackaging':
		/* echo $_REQUEST['product'];
		echo $_REQUEST['unit']; */

		$common_id = $_REQUEST['ad'];
		$location=$_REQUEST['location'];
		$bom = $_REQUEST['bom'];
		$id = $_REQUEST['id'];
		$bill_of_material=$utilObj->getSingleRow("bill_of_material","id ='".$bom."' ");
		$packaging=$utilObj->getSingleRow("packaging","product ='".$_REQUEST['product']."' AND unit ='".$_REQUEST['unit']."' ");
		//var_dump($bill_of_material);	
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
	?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/>
		<input type="hidden" id="mqty"  name="mqty" value="<?php echo $_REQUEST['qty']; ?>"/>
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%;text-align:center;">Product<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit</th>
					<th style="width:10%;text-align:center;">Required Quantity</th>
					<th style="width:10%;text-align:center;">Available Quantity</th>
					<th style="width:10%;text-align:center;">Used Quantity</th>
					<th style="width:10%;text-align:center;">Batch</th>
					<th style="width:10%;text-align:center;">Total</th>
						<?php if($_REQUEST['Task']!='view') { ?>
					<th style="width:2%;text-align:center;"></th>
						<?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($_REQUEST['qty']==$packaging['qty'])) { 

					// echo "condi 1";
					$record5=$utilObj->getMultipleRow("packaging_details","parent_id='".$_REQUEST['id']."' order by id  ASC");
				} 
				else if(($bill_of_material!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')) {  

					// echo "condi 2";
					$record5=$utilObj->getMultipleRow("bill_of_material_details","parent_id='".$bill_of_material['id']."' order by id  ASC ");
				} else { 

					$record5[0]['id']=1;
				}
				
				foreach($record5 as $row_demo) { 

					$i++;
					
					if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($_REQUEST['qty']==$packaging['qty'])) {

						$qty=$row_demo['qty'];
						$requiredqty=$row_demo['requiredqty'];
						// $location=$packaging['location'];

						$stock = getlocationstock('',$row_demo['product'],date('d-m-Y'),$location);
						$avlqty = $qty+$stock;

					} else {

						$qty='';
						$requiredqty=round(($row_demo['qty']*$_REQUEST['qty'])/$bill_of_material['qty'],2);
						$qty = $requiredqty;
						// $location=$_REQUEST['location'];

						$avlqty = getlocationstock('',$row_demo['product'],date('d-m-Y'),$location);
					}
				
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i; ?> </label>
					</td>
					<td  style="width: 15%;">
					<?php
						$product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						// if($_REQUEST['PTask']=='view') {
					?>
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
					<?php /* } else { ?>
					<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);" style="width:100%;">
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 group by name ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
					</select>
					<?php } */ ?>
					</td>
					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit']; ?>"/>
						</div>
					</td>

					<td style="width: 10%;">
						<input type="text" id="requiredqty_<?php echo $i;?>" class=" form-control number"  readonly <?php echo $readonly;?> name="requiredqty_<?php echo $i;?>" value="<?php echo $requiredqty; ?>" />
					</td>

					<td style="width: 10%;">
						<input type="text" id="avlqty_<?php echo $i; ?>" class="tdalign form-control number"  readonly <?php echo $readonly;?> name="avlqty_<?php echo $i;?>" value="<?php echo $avlqty; ?>" />
					</td>

					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" onblur="total_qty(<?php echo $i; ?>);check_mainstock(this.id);" class=" form-control number tdalign" <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $qty; ?>"/>
					</td>

					<td style="width: 10%;text-align:center;">
						<button type="button" class="btn btn-light btn-sm" onclick="check_qty('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#packagingbatch"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>
					
					<td style="width: 10%;">
						<input type="text" id="totalsum_<?php echo $i;?>" class="tdalign number form-control" readonly  <?php echo $readonly;?>  name="totalsum_<?php echo $i;?>" value="<?php echo $row_demo['totalsum']; ?>" onblur="grand_(<?php echo $i; ?>);" />
					</td>
					
				<?php if($_REQUEST['Task']!='view') { ?>
					<td style='width:2%'>
						<!-- <i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i> -->
					</td>
				<?php } ?>
				</tr>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<?php
					$tot_data=$utilObj->getSingleRow("packaging","id='".$id."' "); 
				?>
				<td style="width: 2%;"></td>
				<td style="width: 15%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;">
					Total Quantity : <input type="text" class="tdalign form-control number" name="total_req" id="total_req" readonly value="<?php echo $tot_data['total_req']; ?>">
				</td>
				<td style="width: 5%;"></td>
				<td style="width: 10%;">
					Grandtotal : <input type="text" class="tdalign form-control number" name="grand_total" id="grand_total" readonly value="<?php echo $tot_data['grand_total']; ?>">
				</td>
			</tfoot>
		</table>
		<br>
		<table>
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php if ($_REQUEST['PTask'] != 'view') { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table>
		
		<div class="modal fade" style = "max-width=40%; " id="packagingbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="packbatch">
			
				</div>
			</div>
		</div>

		<!--table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php 
					if(($_REQUEST['PTask']!='view' )){?>			
						<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table-->

		<!-- --------------- Functions --------------- -->
		<script>
			
			function check_mainstock(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batstock = $("#avlqty_"+id).val();
				var bqty = $("#qty_"+id).val();


				if(parseFloat(batstock)<parseFloat(bqty)) {
					alert("You don't have enough Stock!!!");
					$("#qty_"+id).val('');
				}

			}

			function total_qty(id)
			{
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='qty_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
			
				// console.log('Total :', totalquantity);
				
				$("#total_req").val(totalquantity);
			
			}

			function check_qty(i)
			{
				var quantity = $("#qty_"+i).val();
				var product = $("#product_"+i).val();

				if (quantity == '' || quantity=='0') {
					alert ('please enter quantity first . . . !');

				} else {
					packaging_batchdata(i);
				}
			}

			function packaging_batchdata(i) {
								                      
				var qty =$("#qty_"+i).val();
				var mqty =$("#mqty").val();
				var stock =$("#stock_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				var id = $("#id").val();
				var location =$("#location").val();
				var product =$("#product_"+i).val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'packaging_batchdata', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,maincnt:i,id:id,location:location,mqty:mqty },
					success: function (data) {
						$('#packbatch').html(data);
						$('#packagingbatch').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

		</script>

	<?php
	break;

	// ------------------------------- Packaging Batch Modal -------------------------------
	case 'packaging_batchdata':
		
		$product_id = $_REQUEST['product'];
		$qty = $_REQUEST['qty'];
		$mqty = $_REQUEST['mqty'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$maincnt = $_REQUEST['maincnt'];

		$i=0;
		$totalstock=0;
	?>

		<div id="packbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				<div class="container">
					<p>
						<?php $loc=$utilObj->getSingleRow("location","id='".$location."'"); ?>
						Production Location : &nbsp; <?php echo $loc['name']; ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Requried Quantity : &nbsp; <?php echo $qty; ?>
					</p>
					<table class = "table border-top" id="mybatch1">
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="req_qty" id="req_qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="mqty" id="mqty" value="<?php echo $mqty; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
						<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">

						<thead>
							<tr>
								<th>Batch Name</th>
								<th>Batch Stock</th>
								<th>Batch Rate</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($PTask == 'update' || $_REQUEST['PTask']!='view') {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='packaging_out' ");
							} else {
								// $product[0]['id']=1;
								$product = $utilObj->getMultipleRow("temp_sale_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='packaging_out' ");

								if(empty($product)) {
									$product = $utilObj->getMultipleRow("purchase_batch", "product='".$product_id."' AND location='".$location."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='physical_batch_in' OR type='production_in') ");
								}
							}
							foreach ($product as $info) {
								
								if($PTask == 'update' || $_REQUEST['PTask']=='view') {

									$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );

									$batname = $info['batchname'];
									$bat_rate = $info['bat_rate'];
									$batid = $info['purchase_batch'];
									$batqty = $totalstock+$info['quantity'];

									$bat_qty = $info['quantity'];

								} else {
									
									$totalstock = getbatchstock($info['id'],$product_id, date('Y-m-d'),$info['location'] );

									$batname = $info['batchname'];
									$bat_rate = $info['bat_rate'];
									$batid = $info['id'];
									$batqty = $totalstock;
									
									$bat_qty = $info['quantity'];
								}

								if($PTask == 'update' || $_REQUEST['PTask']=='view') {

									$totsum += $info['quantity'];
								}

								$i++;
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<td style="width:20%">
										<input type="text" id="batchname1_<?php echo $i; ?>" class="form-control number" name="batchname1_<?php echo $i; ?>" value="<?php echo $batname; ?>" readonly />
										<input type="hidden" id="batchid_<?php echo $i; ?>" class="form-control number" name="batchid_<?php echo $i; ?>" value="<?php echo $batid; ?>" readonly />
									</td>

									<td style="width:15%">
										<input type="text" id="batqty_<?php echo $i; ?>" class="tdalign form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $batqty; ?>" readonly />
									</td>

									<td style="width:15%">
										<input type="text" id="bat_rate_<?php echo $i; ?>" class="tdalign form-control number" name="bat_rate_<?php echo $i; ?>" value="<?php echo $bat_rate; ?>" readonly />
									</td>

									<td style="width:20%">
										<input type="text" id="batchremove_<?php echo $i; ?>" class="tdalign form-control number batch_remove_input" name="batchremove_<?php echo $i; ?>" value="<?php echo $bat_qty; ?>" onblur="total_qty(<?php echo $i; ?>);get_subtotal(this.id);check_stockqty(this.id);" />
									</td>
									
									<td style="width:25%">
										<input type="text" id="sub_total_<?php echo $i; ?>" class="tdalign form-control number" name="sub_total_<?php echo $i; ?>" value="<?php echo $info['batch_price']; ?>" readonly />
									</td>
								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value=""/>
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<td style="width:20%"></td>
						<td style="width:15%"></td>
						<td style="width:15%"></td>
						<td style="width:20%">
							Total Quantity : <input type="text" class="tdalign form-control number" name="tot_qty" id="tot_qty" readonly value="<?php echo $totsum; ?>">
						</td>
						<td style="width:25%">
							Grandtotal : <input type="text" class="tdalign form-control number" name="grand_tot" id="grand_tot" readonly value="<?php echo $info['sub_total']; ?>">
						</td>
					</table>
					<br>
					<!-- <div class="col-md-2">
						<button type="button" class="btn btn-warning" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
					</div> -->
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();" />
				
			</div>
		</div>

		<script>
			
			function total_qty(id) {
				
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='batchremove_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
			
				console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);
			
			}

			function get_subtotal(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batqty = $("#batchremove_"+id).val();
				var batrate = $("#bat_rate_"+id).val();
				var cnt2 = $("#cnt2").val();

				var sub_total = batqty*batrate;

				$("#sub_total_"+id).val(sub_total);

				grand_total(id);
			}

			function check_stockqty(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batstock = $("#batqty_"+id).val();
				var bqty = $("#batchremove_"+id).val();

				if(parseFloat(batstock)<parseFloat(bqty)) {
					alert("You don't have enough Stock!!!");
					$("#batchremove_"+id).val('');
				}
			}

			function grand_total(id) {

				var totalquantity = 0;
			
				$("[id^='sub_total_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;

					totalquantity += quant;
				});
			
				console.log('Total :', totalquantity);
				
				$("#grand_tot").val(totalquantity);
			
			}

			function check_submit_qty(maincnt) {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#req_qty").val();
				// alert(main_qty);

				if (tot_qty == main_qty) {
					packagingbatch();
					// grand_(maincnt);
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is doesn't match Material quantity.");
					} else {
						alert("Your total batch quantity is doesn't match Material quantity.");
					}
				}

			}

			function packagingbatch() {

				var product = $("#product_batch").val();
				var location = $("#location").val();
				var cnt2 = $("#cnt2").val();
				var maincnt = $("#maincnt").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "packaging_out";
				var grand_tot = $("#grand_tot").val();

				// ---------------------------------------------------------

				var batchname_array=[];
				var batchid_array=[];
				var bat_rate_array=[];
				var batchremove_array=[];
				var sub_total_array=[];
				
				for(var i=1;i<=cnt2;i++)
				{
					var batchname = $("#batchname1_"+i).val();
					var batchid = $("#batchid_"+i).val();
					var batchremove = $("#batchremove_"+i).val();
					var bat_rate = $("#bat_rate_"+i).val();
					var sub_total = $("#sub_total_"+i).val();

					batchname_array.push(batchname);
					batchid_array.push(batchid);
					bat_rate_array.push(bat_rate);
					batchremove_array.push(batchremove);
					sub_total_array.push(sub_total);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'packagingbatch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,cnt2:cnt2,location:location,batchid_array:batchid_array,grand_tot:grand_tot,sub_total_array:sub_total_array,bat_rate_array:bat_rate_array },
					success: function (data) {
						$('#packagingbatch').modal('hide');
						$("#totalsum_"+maincnt).val(grand_tot);
						grand_(maincnt);
						get_batchrate();
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

			function grand_(id)
			{
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='totalsum_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					// alert(quant);

					totalquantity += quant;
				});
			
				// console.log('Total :', totalquantity);
				
				$("#grand_total").val(totalquantity);
			
			}

			function get_batchrate() {

				// var total_quantity = $("#total_req").val();

				var total_quantity = $("#mqty").val();
				var grand_total = $("#grand_total").val();
				
				// alert(total_quantity);
				// alert(grand_total);

				var avg_rate = parseFloat(grand_total)/parseFloat(total_quantity);
				$("#pro_batch_rate").val(avg_rate.toFixed(2));

			}

			// function addRowbatch(tableID) {
			// 	var count=$("#cnt2").val();	

			// 	var i=parseFloat(count)+parseFloat(1);

			// 	var cell1="<tr id='row2_"+i+"'>";
				
			// 	cell1 += "<td style='width:10%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
			// 	<option value=''>Select</option>\
			// 		<?php
			// 		$record=$utilObj->getMultipleRow("location","1");
			// 		foreach($record as $e_rec){	
			// 			echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			// 		}
							
			// 		?>
			// 	</select></td>";
				
			// 	cell1 += "<td style='width:30%'><div id='batch_div_"+i+"'></div></td>";

			// 	cell1 += "<td style='width:30%'><div id='stock_div_"+i+"'></div></td>";
				
			// 	cell1 += "<td style='width:30%'><input type='text' id='batch_remove_"+i+"' class='form-control number batch_remove_input' name='batch_remove_"+i+"' value='' onblur='total_qty("+i+");'/></td>";
			
			// 	cell1 += "<td style='width2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


			
			// 	$("#mybatch1").append(cell1);
			// 	$("#cnt2").val(i);
			// 	// $("#particulars_"+i).select2();
			// }

			// function delete_row_batch(rwcnt)
			// {
			// 	var id=rwcnt.split("_");
			// 	rwcnt=id[1];
			// 	var count=$("#cnt2").val();	
				
			// 	if(count>1)
			// 	{
			// 		var r=confirm("Are you sure!");
			// 		if (r==true)
			// 		{		
						
			// 			$("#row2_"+rwcnt).remove();
							
			// 			for(var k=rwcnt; k<=count; k++)
			// 			{
			// 				var newId=k-1;
							
			// 				jQuery("#row2_"+k).attr('id','row2_'+newId);
							
			// 				jQuery("#idd_"+k).attr('name','idd_'+newId);
			// 				jQuery("#idd_"+k).attr('id','idd_'+newId);
			// 				jQuery("#idd_"+newId).html(newId);
							
			// 				jQuery("#location1_"+k).attr('name','location1_'+newId);
			// 				jQuery("#location1_"+k).attr('id','location1_'+newId);
							
			// 				jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
			// 				jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

			// 				jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
			// 				jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

			// 				jQuery("#batch_remove_"+k).attr('name','batch_remove_'+newId);
			// 				jQuery("#batch_remove_"+k).attr('id','batch_remove_'+newId);
							
							
			// 				jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
			// 			}
			// 			jQuery("#cnt2").val(parseFloat(count-1)); 

			// 			total_qty();

			// 		}
			// 	}
			// 	else {
			// 		alert("Can't remove row Atleast one row is required");
			// 		return false;
			// 	}	 
			// }

			// function get_batch(this_id) {
			// 	var id=this_id.split("_");
			// 	id=id[1];
			// 	var location1 = $('#location1_'+id).val();
			// 	var product = $("#product_batch").val();

			// 	jQuery.ajax({
			// 		url: 'get_ajax_values.php',
			// 		type: 'POST',
			// 		data: { Type: 'get_batch', location1:location1,id:id,product:product },
			// 		success: function (data) {
			// 			$('#batch_div_'+id).html(data);
				
			// 		}
			// 	});

			// }

			// function get_batch_stock(this_id) {
			// 	var id=this_id.split("_");
			// 	id=id[1];
			// 	var location1 = $('#location1_'+id).val();
			// 	var batchname1 = $('#batchname1_'+id).val();
			// 	var product = $("#product_batch").val();

			// 	jQuery.ajax({
			// 		url: 'get_ajax_values.php',
			// 		type: 'POST',
			// 		data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,batchname1:batchname1 },
			// 		success: function (data) {
			// 			$('#stock_div_'+id).html(data);
			// 		}
			// 	});
			// }

		</script>

	<?php
	break;

	// ------------------------- Packaging Batch Handler -------------------------
	case 'packagingbatch':
		
		if($_REQUEST['PTask']=='update'){
			$common=$_REQUEST['deliveryid'];
		}else{
			$common=$_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_sale_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='".$_REQUEST['type']."' ");

		$cnt2 = $_REQUEST['cnt2'];

		for($i=0;$i<$cnt2;$i++) {

			if($_REQUEST['batchremove_array'][$i] !=0) {

				$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['bat_rate_array'][$i],'batch_price'=>$_REQUEST['sub_total_array'][$i],'sub_total'=>$_REQUEST['grand_tot'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'created'=>date("Y-m-d H:i:s"),'lastedited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

				$insertedId=$utilObj->insertRecord('temp_sale_batch', $arrValue1);
			}
		}

	break;


	// ================================= USE IN=Packaging (2) =================================	
	case 'getunit_forpackaging':
		$record=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."' ");
		// $record=$utilObj->getSingleRow("bill_of_material","id='".$_REQUEST['product']."' ");
	?>
		<input type="text" style="width:100%;"  class=" form-control  smallinput " onchange="get_billofmaterial_rowtable_forpackaging();" readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $record['unit'];?>"/>	
   	<?php  
	break;

	// ================================= USE IN=Sale Return (1) =================================	
	case 'get_saleinvoice':
         
		$sale_return=$utilObj->getSingleRow("sale_return"," id='".$_REQUEST['id']."' ");
	?>	
			<label class="form-label"> Sale Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
			<div >
				<?php if($_REQUEST['PTask']=='view' ){
					$readonly="readonly";
					$sale_invoice_no=$utilObj->getSingleRow("sale_invoice","id in (select sale_invoice_no from  sale_return where id ='".$_REQUEST['id']."')");
				?>
					<input type="hidden" id="sale_invoice_no" <?php echo $readonly;?> name="sale_invoice_no" value="<?php echo $sale_invoice_no['id'];?>"/>
					<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $sale_invoice_no['sale_invoiceno'];?>"/>
					
					<?php  }else{ ?>
				<select id="sale_invoice_no" name="sale_invoice_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange=" saleinvoice_forsalereturn_rowtable();">
					<option value=""> Select Sale Invoice No</option>
					<?php 
						$record=$utilObj->getMultipleRow("sale_invoice","customer ='".$_REQUEST['customer']."' group by sale_invoiceno");
						foreach($record as $e_rec){
							if($sale_return['sale_invoice_no']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sale_invoiceno"].'</option>';
						}
					?> 
				</select>
				
				<?php } ?>
			</div>
	<?php
	break;
	
	case 'saleinvoice_forsalereturn_rowtable1':

		$id = $_REQUEST['id'];

	    $purchase_invoice_no = $_REQUEST['sale_invoice_no'];
		$location = $_REQUEST['location'];
		// echo $location;

		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		// $state= $account_ledger['mail_state'];
		$state= $_REQUEST['supplier'];

	    $purchase_return=$utilObj->getSingleRow("purchase_return"," id='".$_REQUEST['id']."' ");
	    $purchase_order=$utilObj->getSingleRow("purchase_return"," id='".$_REQUEST['id']."' ");

		if($_REQUEST['PTask']=='view') {

			$readonly="readonly";
		} else {

			$readonly =" ";
		}
		
	?>
		<table class="table table-bordered " id="myTable" > 
			<input type="hidden" name="p_invoice_no" id="p_invoice_no" value="<?php echo $purchase_invoice_no; ?>">
			<thead>
				<tr>
					<th style="width:2%; text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%; text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:8%; text-align:center;">Ledger</th>
					<th style="width: 4%; text-align:center;">Unit </th>
					<?php if($state==27) { ?>

						<th style="width: 3%; text-align:center;">CGST </th>
						<th style="width: 3%; text-align:center;">SGST </th>
					<?php } else { ?>

						<th style="width: 3%; text-align:center;">IGST </th>
					<?php } ?>
					<th style="width:5%; text-align:center;">Return Quantity<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 5%; text-align:center;">Batch</th>
					<th style="width:5%; text-align:center;">Rate<span class="required required_lbl" style="color:red;">*</span></th>
					<!-- <th id="totalth" style="width: 8%;">Rejected Qty</th> -->
					
					<th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th>
				<?php if($_REQUEST['PTask']!='view') { ?>

					<th style="width:1%;text-align:center;"></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($purchase_invoice_no==$purchase_return['purchase_invoice_no']))
				{ 
					//echo "condi 1";
					$record5=$utilObj->getMultipleRow("purchase_return_details","parent_id='".$_REQUEST['id']."' order by id  ASC");
				} 
				// else if(($purchase_invoice_no!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'))
				// {  	
				// 	// echo "condi 2";
				// 	$record5=$utilObj->getMultipleRow("purchase_invoice_details","parent_id='".$purchase_invoice_no."' order by id  ASC ");
				// }
				else
				{ 
					$record5[0]['id']=1;
				}   
				foreach($record5 as $row_demo)
				{ 
				// var_dump($row_demo);
				
				$i++;
				$totalstock = 0;
				

				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($purchase_invoice_no==$purchase_return['purchase_invoice_no'])) { 

					$returnqty=$row_demo['rejectedqty'];
					$total=$row_demo['total'];
					$subtot=$purchase_return['subt'];

					$taxble = $row_demo['taxable'];
				} else {

					$returnqty=0;
					$total=0;
					$subtot=0;					 
				}

				$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 15%;">
						<!-- <input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"  id="pname_<?php echo $i;?>" name="pname_<?php echo $i;?>" style="width:100%;" class=" form-control"  readonly <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/> -->

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_ledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);check_batch_invoice(this.id);" style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
						</select>
					</td>
					<td style="width:8%; text-align:center;">
						<?php
							// $ledger=$utilObj->getSingleRow("account_ledger"," id='".$row_demo['ledger']."' ");
						?>
						<!-- <input type="text" id="ledger_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="ledger_<?php echo $i;?>" value="<?php echo $ledger['name'];?>" /> -->

						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
						<?php

							if( $_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
								// echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");
								
								foreach($record as $e_rec){
									if($rows['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}

							} else {
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");

								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");

								echo '<option value="">Select Ledger</option>';
								foreach($record as $e_rec)
								{	
									if($state==27) {
										if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									} else {
										if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								}
							}
						?> 
						</select>
					</td>
					<td style="width: 4%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					
					<?php if( $state==27){ ?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
						</td>
						
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
						</td>
					<?php }else{?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="igst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> readonly name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
						</td>
					<?php } ?>
					
					<?php
						// $psum = $utilObj->getSum("purchase_return_details","parent_id IN(select id from purchase_return where purchase_invoice_no = '".$_REQUEST['purchase_invoice_no']."')","rejectedqty");
						// $tqty = $row_demo['qty']-$psum;

						// if($_REQUEST['PTask']=='update') {
					?> 
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty']; ?>" />
						</td>
					<!-- <?php // } else { ?>
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> readonly name="qty_<?php echo $i;?>" value="<?php echo $tqty; ?>"/>
						</td>
					<?php // } ?> -->

					<td style="width: 5%;text-align:center;">
						<button type="button" class="btn btn-light" id="btn_<?php echo $i;?>" onclick="check_qty(this.id);" data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>
					
					<td style="width: 5%;" class="tdalign">
						<input type="text" id="rate_<?php echo $i;?>" class="number form-control" <?php echo $readonly;?> readonly name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate']; ?>"/>

						<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="0" >

						<input type="hidden" name="res_<?php echo $i; ?>" id="res_<?php echo $i;?>" value="" >
					</td>
							
					<!-- <td style='width:8%'>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number" id="taxable_<?php echo $i;?>" <?php echo $readonly;?> name="taxable_<?php echo $i;?>" readonly="readonly"  value="<?php echo (($row_demo['qty']*$row_demo['rate'])-(($row_demo['qty']*$row_demo['rate'])*($row_demo['disc']/100)));?>"  />
					</td> -->
					
					<!-- <td style="width: 5%;" class="tdalign">
						<input type="text" id="rejectedqty_<?php echo $i;?>" class=" form-control number"   name="rejectedqty_<?php echo $i;?>" value="<?php echo $returnqty;?>" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

						
					</td> -->
					
					<td style="width: 10%;" class="tdalign">
						<input type="text" id="taxable_<?php echo $i;?>" class="number form-control"   readonly name="taxable_<?php echo $i;?>" value="<?php echo $taxble;?>"/>
					</td>
					 
					<?php if($_REQUEST['Task']!='view'){?>
						<td style='width:1%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
				<?php } ?>

				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if( $state==27){ ?>
						<td></td>
						<td></td>
					<?php } else { ?>
						<td></td>
					<?php } ?>
					<td>
						<input type="hidden" id="cgsttot" name="cgsttot" value="0" />
						<input type="hidden" id="sgsttot" name="sgsttot" value="0" />
						<input type="hidden" id="igsttot" name="igsttot" value="0" />
					</td>
					<!-- <td></td> -->
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td>
						<input type="text" id="totaltaxable" class="number form-control" readonly name="totaltaxable" value="<?php echo $purchase_return['totaltaxable']; ?>"/>
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>

		

		<div class="modal fade" style = "max-width=40%; " id="purreturnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="purbatch">
			
				</div>
			</div>
		</div>


		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("purchase_return_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="0" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="0">
				</td>
				<td style="width:9%;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore11" onclick="addRowdetail('dtable');">Add More</button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>
		
		
		<script>

			function check_batch_invoice(id) {
				
				var id=id.split("_");
				// alert(parent_id);
				var PTask = PTask;
				id=id[1];
				// alert(id);
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_return',id:id,product:product},
					success:function(data)
					{	
						//alert(data);
						$("#batch2_"+id).html(data);	
						$(this).next().focus();
					}
				});

			}

			function get_ledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_ledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

			function check_qty(this_id)
			{	
				var mainid = this_id;
				var i=this_id.split("_");
        		i=i[1];

				var quantity = $("#qty_"+i).val();
				var product = $("#product_"+i).val();

				if (quantity==0) {
					if(quantity=='') {

						alert ('please enter quantity first . . . !');
						return false;
					} else {

						alert ('please enter quantity first . . . !');
						return false;
					}
				} else {
					getbatchdata(product,i,mainid);
				}
			}

			function getbatchdata(product,i,mainid) {
				
				// alert("hii");
				var qty = $("#qty_"+i).val();
				var stock = $("#stock_"+i).val();
				var common_id = $("#ad").val();
				var id = $("#id").val();
				var PTask = $("#PTask").val();
				var location =$("#location").val();
				var p_invoice_no = $("#p_invoice_no").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'purchase_batch_return', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,p_invoice_no:p_invoice_no,mainid:mainid },
					success: function (data) {
						$('#purbatch').html(data);
						$('#purreturnbatch').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
				
			}
		</script>
								
	<?php
	break;

	// =================== USE IN = SALE Return (2) ==================================== 
	case 'saleinvoice_forsalereturn_rowtable':

		$sale_invoice_no = $_REQUEST['sale_invoice_no'];
		$account_ledger = $utilObj->getSingleRow("account_ledger", " id='" . $_REQUEST['customer'] . "' ");
		
		$state= $_REQUEST['supplier'];

		$sale_return = $utilObj->getSingleRow("sale_return", " id='" . $_REQUEST['id'] . "' ");
		$purchase_order = $utilObj->getSingleRow("sale_return", " id='" . $_REQUEST['id'] . "' ");
		//var_dump($purchase_return);
		if ($_REQUEST['PTask'] == 'view') {

			$readonly = "readonly";
		} else {
			
			$readonly = " ";
		}

	?>
		
		<table class="table table-bordered " id="myTable" >

			<thead>
				<tr>
					<th style="width:2%; text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%; text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:8%; text-align:center;">Ledger</th>
					<th style="width: 4%; text-align:center;">Unit </th>
					<?php if($state==27) { ?>

						<th style="width: 3%; text-align:center;">CGST </th>
						<th style="width: 3%; text-align:center;">SGST </th>
					<?php } else { ?>

						<th style="width: 3%; text-align:center;">IGST </th>
					<?php } ?>
					<th style="width:5%; text-align:center;">Return Quantity<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 5%; text-align:center;">Batch</th>
					<th style="width:5%; text-align:center;">Rate<span class="required required_lbl" style="color:red;">*</span></th>
					<!-- <th id="totalth" style="width: 8%;">Rejected Qty</th> -->
					
					<th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th>
				<?php if($_REQUEST['PTask']!='view') { ?>

					<th style="width:1%;text-align:center;"></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')
				{ 
					//echo "condi 1";
					$record5=$utilObj->getMultipleRow("sale_return_details","parent_id='".$_REQUEST['id']."' order by id  ASC");
				}
				else
				{ 
					$record5[0]['id']=1;
				}   
				foreach($record5 as $row_demo)
				{ 
				// var_dump($row_demo);
				
				$i++;
				$totalstock = 0;
				

				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($purchase_invoice_no==$purchase_return['purchase_invoice_no'])) { 

					$returnqty=$row_demo['rejectedqty'];
					$total=$row_demo['total'];
					$subtot=$purchase_return['subt'];

					$taxble = $row_demo['taxable'];
				} else {

					$returnqty=0;
					$total=0;
					$subtot=0;					 
				}

				$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 15%;">
						<!-- <input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"  id="pname_<?php echo $i;?>" name="pname_<?php echo $i;?>" style="width:100%;" class=" form-control"  readonly <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/> -->

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_ledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);check_batch_invoice(this.id);" style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
						</select>
					</td>
					<td style="width:8%; text-align:center;">
						<?php
							// $ledger=$utilObj->getSingleRow("account_ledger"," id='".$row_demo['ledger']."' ");
						?>
						<!-- <input type="text" id="ledger_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="ledger_<?php echo $i;?>" value="<?php echo $ledger['name'];?>" /> -->

						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
						<?php

							if( $_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
								// echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");
								
								foreach($record as $e_rec){
									if($rows['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}

							} else {
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");

								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");

								echo '<option value="">Select Ledger</option>';
								foreach($record as $e_rec)
								{	
									if($state==27) {
										if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									} else {
										if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								}
							}
						?> 
						</select>
					</td>
					<td style="width: 4%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					
					<?php if( $state==27){ ?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
						</td>
						
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
						</td>
					<?php }else{?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="igst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> readonly name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
						</td>
					<?php } ?>
					
					<?php
						// $psum = $utilObj->getSum("purchase_return_details","parent_id IN(select id from purchase_return where purchase_invoice_no = '".$_REQUEST['purchase_invoice_no']."')","rejectedqty");
						// $tqty = $row_demo['qty']-$psum;

						// if($_REQUEST['PTask']=='update') {
					?> 
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty']; ?>" />
						</td>
					<!-- <?php // } else { ?>
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> readonly name="qty_<?php echo $i;?>" value="<?php echo $tqty; ?>"/>
						</td>
					<?php // } ?> -->

					<td style="width: 5%;text-align:center;">
						<button type="button" class="btn btn-light" id="btn_<?php echo $i;?>" onclick="check_qty(this.id);" data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>
					
					<td style="width: 5%;" class="tdalign">
						<input type="text" id="rate_<?php echo $i;?>" class="number form-control" <?php echo $readonly;?> readonly name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate']; ?>"/>

						<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="0" >

						<input type="hidden" name="res_<?php echo $i; ?>" id="res_<?php echo $i;?>" value="" >
					</td>
							
					<!-- <td style='width:8%'>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number" id="taxable_<?php echo $i;?>" <?php echo $readonly;?> name="taxable_<?php echo $i;?>" readonly="readonly"  value="<?php echo (($row_demo['qty']*$row_demo['rate'])-(($row_demo['qty']*$row_demo['rate'])*($row_demo['disc']/100)));?>"  />
					</td> -->
					
					<!-- <td style="width: 5%;" class="tdalign">
						<input type="text" id="rejectedqty_<?php echo $i;?>" class=" form-control number"   name="rejectedqty_<?php echo $i;?>" value="<?php echo $returnqty;?>" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

						
					</td> -->
					
					<td style="width: 10%;" class="tdalign">
						<input type="text" id="taxable_<?php echo $i;?>" class="number form-control"   readonly name="taxable_<?php echo $i;?>" value="<?php echo $taxble;?>"/>
					</td>
					 
					<?php if($_REQUEST['Task']!='view'){?>
						<td style='width:1%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
				<?php } ?>

				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if( $state==27){ ?>
						<td></td>
						<td></td>
					<?php } else { ?>
						<td></td>
					<?php } ?>
					<td>
						<input type="hidden" id="cgsttot" name="cgsttot" value="0" />
						<input type="hidden" id="sgsttot" name="sgsttot" value="0" />
						<input type="hidden" id="igsttot" name="igsttot" value="0" />
					</td>
					<!-- <td></td> -->
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td>
						<input type="text" id="totaltaxable" class="number form-control" readonly name="totaltaxable" value="<?php echo $purchase_return['totaltaxable']; ?>"/>
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>

		<div class="modal fade" style = "max-width=40%; " id="saleinvoicereturnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="salesinvoicereturnbatch">
							
				</div>
			</div>
		</div>

		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("sale_return_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="0" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="0">
				</td>
				<td style="width:9%;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore11" onclick="addRowdetail('dtable');">Add More</button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>
		<!-- ------------------------------------------------------------- -->
								
	<?php
	break;
	// ================================= USE IN=Purchse Return (1) =================================	
	case 'get_puchaseinvoice':
		$purchase_return=$utilObj->getSingleRow("purchase_return"," id='".$_REQUEST['id']."' ");
	?>	
		<label class="form-label"> Purchase Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div>
			<?php if($_REQUEST['PTask']=='view' ){
				$readonly="readonly";
				$purchase_invoice_no=$utilObj->getSingleRow("purchase_invoice","id in (select purchase_invoice_no from  purchase_return where id ='".$_REQUEST['id']."')");
			?>
				<input type="hidden" id="purchase_invoice_no" <?php echo $readonly;?> name="purchase_invoice_no" value="<?php echo $purchase_invoice_no['id'];?>"/>

				<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $purchase_invoice_no['invoicenumber'];?>"/>
			<?php } else { ?>
				<select id="purchase_invoice_no" name="purchase_invoice_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="get_posinvoice();">
					<option value=""> Select Purchase Invoice No</option>
					<?php
						$psum = $utilObj->getSum("purchase_return_details","parent_id IN(select id from purchase_return where purchase_invoice_no = '".$_REQUEST['purchase_invoice_no']."')","rejectedqty");

						$tqty = $row_demo['qty']-$psum;

						if($_REQUEST['PTask']!='update') {
							$cmd='id in (select purchase_invoice_no from purchase_return)';
						}

						$record=$utilObj->getMultipleRow("purchase_invoice","supplier ='".$_REQUEST['supplier']."' group by invoicenumber");
						
						foreach($record as $e_rec) {
							if($_REQUEST['PTask']!='update') {
								// Rejectded Quantity Sum
								$sum=$utilObj->getSum("purchase_return_details","parent_id IN(select id from purchase_return where purchase_invoice_no = '".$e_rec["id"]."')","rejectedqty");

								$invoice=$utilObj->getSingleRow("purchase_invoice_details","parent_id = '".$e_rec["id"]."'");

								if($sum!=$invoice['qty']){
									// if($purchase_return['purchase_invoice_no']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["invoicenumber"].'</option>';
								}
							} else {
								if($purchase_return['purchase_invoice_no']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["invoicenumber"].'</option>';
							}
						}
					?> 
				</select>
			<?php } ?>
		</div>
		
	<?php
	break;

	// ============================= USE IN = Purchase Return (2) =============================

	case 'purchaseinvoice_rowtable':

		$id = $_REQUEST['id'];

	    $purchase_invoice_no = $_REQUEST['purchase_invoice_no'];
		$location = $_REQUEST['location'];
		// echo $location;

		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		// $state= $account_ledger['mail_state'];
		$state= $_REQUEST['supplier'];
		
	    $purchase_return=$utilObj->getSingleRow("purchase_return"," id='".$_REQUEST['id']."' ");
	    $purchase_order=$utilObj->getSingleRow("purchase_return"," id='".$_REQUEST['id']."' ");

		if($_REQUEST['PTask']=='view') {

			$readonly="readonly";
		} else {

			$readonly =" ";
		}
		
	?>
		<table class="table table-bordered " id="myTable" > 
			<input type="hidden" name="p_invoice_no" id="p_invoice_no" value="<?php echo $purchase_invoice_no; ?>">
			<thead>
				<tr>
					<th style="width:2%; text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%; text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:8%; text-align:center;">Ledger</th>
					<th style="width: 4%; text-align:center;">Unit </th>
					<?php if($state==27) { ?>

						<th style="width: 3%; text-align:center;">CGST </th>
						<th style="width: 3%; text-align:center;">SGST </th>
					<?php } else { ?>

						<th style="width: 3%; text-align:center;">IGST </th>
					<?php } ?>
					<th style="width:5%; text-align:center;">Return Quantity<span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 5%; text-align:center;">Batch</th>
					<th style="width:5%; text-align:center;">Rate<span class="required required_lbl" style="color:red;">*</span></th>
					<!-- <th id="totalth" style="width: 8%;">Rejected Qty</th> -->
					
					<th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th>
				<?php if($_REQUEST['PTask']!='view') { ?>

					<th style="width:1%;text-align:center;"></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($purchase_invoice_no==$purchase_return['purchase_invoice_no']))
				{ 
					//echo "condi 1";
					$record5=$utilObj->getMultipleRow("purchase_return_details","parent_id='".$_REQUEST['id']."' order by id  ASC");
				} 
				else if(($purchase_invoice_no!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'))
				{  	
					// echo "condi 2";
					$record5=$utilObj->getMultipleRow("purchase_invoice_details","parent_id='".$purchase_invoice_no."' order by id  ASC ");
				}
				else
				{ 
					$record5[0]['id']=1;
				}   
				foreach($record5 as $row_demo)
				{ 
				// var_dump($row_demo);
				
				$i++;
				$totalstock = 0;
				

				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($purchase_invoice_no==$purchase_return['purchase_invoice_no'])) { 

					$returnqty=$row_demo['rejectedqty'];
					$total=$row_demo['total'];
					$subtot=$purchase_return['subt'];

					$taxble = $row_demo['taxable'];
				} else {

					$returnqty=0;
					$total=0;
					$subtot=0;					 
				}

				$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 15%;">
						<!-- <input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"  id="pname_<?php echo $i;?>" name="pname_<?php echo $i;?>" style="width:100%;" class=" form-control"  readonly <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/> -->

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_ledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);check_batch_invoice(this.id);" style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
						</select>
					</td>
					<td style="width:8%; text-align:center;">
						<?php
							// $ledger=$utilObj->getSingleRow("account_ledger"," id='".$row_demo['ledger']."' ");
						?>
						<!-- <input type="text" id="ledger_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="ledger_<?php echo $i;?>" value="<?php echo $ledger['name'];?>" /> -->

						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
						<?php

							if( $_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
								// echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");
								
								foreach($record as $e_rec){
									if($rows['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}

							} else {
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");

								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");

								echo '<option value="">Select Ledger</option>';
								foreach($record as $e_rec)
								{	
									if($state==27) {
										if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									} else {
										if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								}
							}
						?> 
						</select>
					</td>
					<td style="width: 4%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					
					<?php if( $state==27){ ?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
						</td>
						
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?>  readonly name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
						</td>
					<?php }else{?>
						<td style="width: 3%;" class="tdalign">
							<input type="text" id="igst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> readonly name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
						</td>
					<?php } ?>
					
					<?php
						// $psum = $utilObj->getSum("purchase_return_details","parent_id IN(select id from purchase_return where purchase_invoice_no = '".$_REQUEST['purchase_invoice_no']."')","rejectedqty");
						// $tqty = $row_demo['qty']-$psum;

						// if($_REQUEST['PTask']=='update') {
					?> 
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty']; ?>" />
						</td>
					<!-- <?php // } else { ?>
						<td style="width: 5%;" class="tdalign">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> readonly name="qty_<?php echo $i;?>" value="<?php echo $tqty; ?>"/>
						</td>
					<?php // } ?> -->

					<td style="width: 5%;text-align:center;">
						<button type="button" class="btn btn-light" id="btn_<?php echo $i;?>" onclick="check_qty(this.id);" data-bs-toggle="modal" data-bs-target="#"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>
					
					<td style="width: 5%;" class="tdalign">
						<input type="text" id="rate_<?php echo $i;?>" class="number form-control" <?php echo $readonly;?> readonly name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate']; ?>"/>

						<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="0" >

						<input type="hidden" name="res_<?php echo $i; ?>" id="res_<?php echo $i;?>" value="" >
					</td>
							
					<!-- <td style='width:8%'>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number" id="taxable_<?php echo $i;?>" <?php echo $readonly;?> name="taxable_<?php echo $i;?>" readonly="readonly"  value="<?php echo (($row_demo['qty']*$row_demo['rate'])-(($row_demo['qty']*$row_demo['rate'])*($row_demo['disc']/100)));?>"  />
					</td> -->
					
					<!-- <td style="width: 5%;" class="tdalign">
						<input type="text" id="rejectedqty_<?php echo $i;?>" class=" form-control number"   name="rejectedqty_<?php echo $i;?>" value="<?php echo $returnqty;?>" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

						
					</td> -->
					
					<td style="width: 10%;" class="tdalign">
						<input type="text" id="taxable_<?php echo $i;?>" class="number form-control"   readonly name="taxable_<?php echo $i;?>" value="<?php echo $taxble;?>"/>
					</td>
					 
					<?php if($_REQUEST['Task']!='view'){?>
						<td style='width:1%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
				<?php } ?>

				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if( $state==27){ ?>
						<td></td>
						<td></td>
					<?php } else { ?>
						<td></td>
					<?php } ?>
					<td>
						<input type="hidden" id="cgsttot" name="cgsttot" value="0" />
						<input type="hidden" id="sgsttot" name="sgsttot" value="0" />
						<input type="hidden" id="igsttot" name="igsttot" value="0" />
					</td>
					<!-- <td></td> -->
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td>
						<input type="text" id="totaltaxable" class="number form-control" readonly name="totaltaxable" value="<?php echo $purchase_return['totaltaxable']; ?>"/>
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>

		

		<div class="modal fade" style = "max-width=40%; " id="purreturnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="purbatch">
			
				</div>
			</div>
		</div>


		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("purchase_return_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="0" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="0">
				</td>
				<td style="width:9%;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore11" onclick="addRowdetail('dtable');">Add More</button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>
		
		
		<script>

			function check_batch_invoice(id) {
				
				var id=id.split("_");
				// alert(parent_id);
				var PTask = PTask;
				id=id[1];
				// alert(id);
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_return',id:id,product:product},
					success:function(data)
					{	
						//alert(data);
						$("#batch2_"+id).html(data);	
						$(this).next().focus();
					}
				});

			}

			function get_ledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_ledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

			function check_qty(this_id)
			{	
				var mainid = this_id;
				var i=this_id.split("_");
        		i=i[1];

				var quantity = $("#qty_"+i).val();
				var product = $("#product_"+i).val();

				if (quantity==0) {
					if(quantity=='') {

						alert ('please enter quantity first . . . !');
						return false;
					} else {

						alert ('please enter quantity first . . . !');
						return false;
					}
				} else {
					getbatchdata(product,i,mainid);
				}
			}

			function getbatchdata(product,i,mainid) {
				
				// alert("hii");
				var qty = $("#qty_"+i).val();
				var stock = $("#stock_"+i).val();
				var common_id = $("#ad").val();
				var id = $("#id").val();
				var PTask = $("#PTask").val();
				var location =$("#location").val();
				var p_invoice_no = $("#p_invoice_no").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'purchase_batch_return', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,p_invoice_no:p_invoice_no,mainid:mainid },
					success: function (data) {
						$('#purbatch').html(data);
						$('#purreturnbatch').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
				
			}
		</script>
								
		<?php
	break;
	
	// ------------------------------- Purchase return batch modal -------------------------------
	case 'purchase_batch_return':

		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		$qty = $_REQUEST['qty'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$p_invoice_no = $_REQUEST['p_invoice_no'];
		$maincnt=$_REQUEST['i'];
		$mainid=$_REQUEST['mainid'];
		$totalstock=0;

		$i=0;
	?>

		<div id="purbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">

					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
					<input type="hidden" name="mainid" id="mainid" value="<?php echo $mainid; ?>">
					<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">

					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">

					<!-- <input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"> -->

					<p>
					<?php $pname=$utilObj->getSingleRow("stock_ledger","id ='".$product_id."' "); ?>
						Quantity :<?php echo $qty; ?>&nbsp;&nbsp;
						Product :<?php echo $pname['name']; ?>
					</p>
					
					<!-- --------------------------------------------------------------------- -->

					<table class = "table border-top" id="mybatch1">
						<thead>
							<tr>
								<th>Location</th>
								<th>Batch Name</th>
								<th>Stock Quantity</th>
								<th>Batch Rate</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
								
						<tbody>
						<?php
							// if($PTask == 'update') {
							// 	$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_delivery' ");
							// } else {
							// 	$product = $utilObj->getMultipleRow("temp_sale_batch", "product='".$product_id."' AND parent_id='".$common_id."' AND type='sale_delivery' ");
							// 	if(empty($purivodata)) {
							// 		$product[0]['id']=1;
							// 	}
							// 	// $product[0]['id']=1;
							// }

							if($PTask != 'update') {

								$product = $utilObj->getMultipleRow("temp_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='purchase_return' ");
								if(empty($product)) {

									// $product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND flag='0' AND purchase_batch='' AND parent_id='".$p_invoice_no."' ");
									$product[0]['id']=1;
								}
							} else {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."' AND type='purchase_return' ");
							}

							foreach ($product as $info) {
								$i++;

								$battotal = $info['quantity']*$info['bat_rate'];
								$tot_amt += $battotal;
								$tot_qty += $info['quantity'];
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<td style="width:15%;">
										<select id="location1_<?php echo $i;?>" name="location1_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_batch(this.id);">	
										<?php 

											echo '<option value="">Select</option>';
											$record=$utilObj->getMultipleRow("location","1");
											foreach($record as $e_rec)
											{
												if($info['location']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
											}
										?>
										</select>
									</td>

									<td style="width:15%;">
										<div id="batch_div_<?php echo $i; ?>">
										<?php
											if($PTask=='update') {
										?>
											<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);get_batch_rate(this.id);">	
											<?php 
												$batch=$utilObj->getMultipleRow("purchase_batch","location='".$info['location']."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='production' OR type='packaging') ");

												echo '<option value="">Select</option>';
												foreach($batch as $e_rec)
												{
													if($info['batchname']==$e_rec["id"]) echo $select='selected'; else $select='';
													echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["batchname"] .'</option>';
												}
											?>
											</select>
										<?php } ?>
										</div>
									</td>

									<td style="width:15%;">
										<div id="stock_div_<?php echo $i;?>">
										<?php
											if($PTask=='update') {

											$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );
										?>
											<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $info['bat_rate']; ?>"/>
										<?php } ?>
										</div>
									</td>

									<td style="width:15%;">
										<div id="rate_div_<?php echo $i; ?>">
										<?php
											if($PTask=='update') {

										?>
											<input readonly type="text" id="batrate_<?php echo $i; ?>" readonly class=" form-control number" name="batrate_<?php echo $i; ?>" value="<?php echo $info['bat_rate']; ?>"/>
										<?php } ?>
										</div>
									</td>

									<td style='width:15%'>
										<input type="text" id="batchremove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batchremove_<?php echo $i; ?>" value="<?php echo $info['batqty']; ?>" onkeyup="total_qty(<?php echo $i; ?>);get_battotal(this.id);"/>
									</td>

									<td style="width:15%;">
										<input readonly type="text" id="battotal_<?php echo $i; ?>" readonly class=" form-control number" name="battotal_<?php echo $i; ?>" value="<?php echo $battotal; ?>"/>
									</td>

									<?php 
										if($i>1) {
									?>
										<td style='width:0%'>
											<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch(this.id);"></i>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value=""/>
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<td style="width:15%;"></td>
						<td style="width:15%;"></td>
						<td style="width:15%;"></td>
						<td style="width:15%;text-align:right;">
							<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
						</td>
						<td style="width:15%;">
							Total Quantity : <input class="form-control number" type="text" readonly name="tot_qty" id="tot_qty" value="<?php echo $tot_qty; ?>" >
						</td>
						<td style="width:15%;">
							Total : <input class="form-control number" type="text" readonly name="tot_amt" id="tot_amt" value="<?php echo $tot_amt; ?>" >
						</td>
						<td style="width:0%;"></td>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();" />
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			function total_qty(id) {

				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
				var totalamt = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='batchremove_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
				
				$("#tot_qty").val(totalquantity);

				// Assuming batqty1_id elements are input fields
				$("[id^='battotal_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalamt += quant;
				});
				
				$("#tot_amt").val(totalamt);
				
			}

			function get_battotal(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batchremove = $("#batchremove_"+id).val();
				var batrate = $("#batrate_"+id).val();

				var battotal = parseFloat(batchremove*batrate);
				$("#battotal_"+id).val(battotal);

				total_qty(id);
			}

			function addRowbatch(tableID) {

				var count=$("#cnt2").val();	

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row2_"+i+"'>";
				
				cell1 += "<td style='width:15%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
				<option value=''>Select</option>\
					<?php
					$record=$utilObj->getMultipleRow("location","1");
					foreach($record as $e_rec){	
						echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
					}
							
					?>
				</select></td>";
				
				cell1 += "<td style='width:15%'><div id='batch_div_"+i+"'></div></td>";

				cell1 += "<td style='width:15%'><div id='stock_div_"+i+"'></div></td>";

				cell1 += "<td style='width:15%'><div id='rate_div_"+i+"'></div></td>";
				
				cell1 += "<td style='width:15%'><input type='text' id='batchremove_"+i+"' class='form-control number batch_remove_input' name='batchremove_"+i+"' value='' onkeyup='total_qty("+i+");get_battotal(this.id)' /></td>";

				cell1 += "<td style='width:15%'><input type='text' id='battotal_"+i+"' class='form-control number batch_remove_input' name='battotal_"+i+"' value=''  /></td>";
			
				cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


			
				$("#mybatch1").append(cell1);
				$("#cnt2").val(i);
				// $("#particulars_"+i).select2();
			}

			function delete_row_batch(rwcnt)
			{
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt2").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row2_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row2_"+k).attr('id','row2_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#location1_"+k).attr('name','location1_'+newId);
							jQuery("#location1_"+k).attr('id','location1_'+newId);
							
							jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
							jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

							jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
							jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

							jQuery("#batchremove_"+k).attr('name','batchremove_'+newId);
							jQuery("#batchremove_"+k).attr('id','batchremove_'+newId);
							
							
							jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
						}
						jQuery("#cnt2").val(parseFloat(count-1)); 

						total_qty();

					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}

			function get_batch(this_id) {

				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_batch', location1:location1,id:id,product:product },
					success: function (data) {

						$('#batch_div_'+id).html(data);
					}
				});

			}

			function get_batch_stock(this_id) {
				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var batchname1 = $('#batchname1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,batchname1:batchname1 },
					success: function (data) {
						$('#stock_div_'+id).html(data);
					}
				});
			}

			function get_batch_rate(this_id) {
				
				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var batchname1 = $('#batchname1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_batch_rate', location1:location1,id:id,product:product,batchname1:batchname1 },
					success: function (data) {
						$('#rate_div_'+id).html(data);
					}
				});
			}

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#tot_qty').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();

			});

			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#qty").val();

				if (tot_qty == main_qty) {
					purchasereturn_batch();
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("please add quantity in exsiting batch or add new batch.");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("please remove some quantity from exsiting batch.");
					}
				}

			}

			function purchasereturn_batch() {

				
				var maincnt = $("#maincnt").val();
				var mainid = $("#mainid").val();
				var product = $("#product_batch").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();

				var totqty = $("#tot_qty").val();
				var totamt = $("#tot_amt").val();
				var type = "purchase_return";

				// var batchIds = [];
				// $(".batch_id").each(function () {
				// 	batchIds.push($(this).val());
				// });

				var res = 1;

				var cnt1 = $("#cnt2").val();
				var batchname_array=[];
				var batchid_array=[];
				var batchremove_array=[];
				var batrate_array=[];
				var location_array=[];
				
				for(var i=1;i<=cnt1;i++)
				{	
					var location = $("#location1_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batchid = $("#batch_id_"+i).val();
					var batchremove = $("#batchremove_"+i).val();
					var batrate = $("#batrate_"+i).val();

					location_array.push(location);
					batchname_array.push(batchname);
					batchid_array.push(batchname);
					batchremove_array.push(batchremove);
					batrate_array.push(batrate);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'purchasereturn_batch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,batchid_array:batchid_array,cnt1:cnt1,batrate_array:batrate_array },
					success: function (data) {
						$('#purreturnbatch').modal('hide');
						$('#res_'+maincnt).val(res);

						var rate = parseFloat(totamt/totqty);
						$('#rate_'+maincnt).val(rate);
						getrowgst(mainid);
						gettotgst(maincnt);
						// alert(data);
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});

			}

		</script>
	<?php 
	break;

	// ----------------------------- Purchase return batch handler -----------------------------
	case 'purchasereturn_batch':
	
		if($_REQUEST['PTask']=='update') {

			$common=$_REQUEST['deliveryid'];
		} else {

			$common=$_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='".$_REQUEST['type']."' ");
		
		$cnt2 = $_REQUEST['cnt1'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location_array'][$i],'bat_rate'=>$_REQUEST['batrate_array'][$i] );
		
			$insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);
		}
	
	break;
	
	// ------------------------------- Purchase return batch modal -------------------------------
	case 'sale_batch_return':

		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		$qty = $_REQUEST['qty'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$p_invoice_no = $_REQUEST['p_invoice_no'];
		$maincnt=$_REQUEST['i'];
		$mainid=$_REQUEST['mainid'];
		$totalstock=0;

		$i=0;
	?>

		<div id="salesinvoicereturnbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">

					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
					<input type="hidden" name="mainid" id="mainid" value="<?php echo $mainid; ?>">
					<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">

					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">


					<p>
					<?php $pname=$utilObj->getSingleRow("stock_ledger","id ='".$product_id."' "); ?>
						Quantity :<?php echo $qty; ?>&nbsp;&nbsp;
						Product :<?php echo $pname['name']; ?>
					</p>
					
					<!-- --------------------------------------------------------------------- -->

					<table class = "table border-top" id="mybatch1">
						<thead>
							<tr>
								<th>Location</th>
								<th>Batch Name</th>
								<th>Stock Quantity</th>
								<th>Batch Rate</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
								
						<tbody>
						<?php
							// if($PTask == 'update') {
							// 	$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_delivery' ");
							// } else {
							// 	$product = $utilObj->getMultipleRow("temp_sale_batch", "product='".$product_id."' AND parent_id='".$common_id."' AND type='sale_delivery' ");
							// 	if(empty($purivodata)) {
							// 		$product[0]['id']=1;
							// 	}
							// 	// $product[0]['id']=1;
							// }

							if($PTask != 'update') {

								$product = $utilObj->getMultipleRow("temp_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='sale_return' ");
								if(empty($product)) {

									$product[0]['id']=1;
								}
							} else {

								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_return' ");
							}

							foreach ($product as $info) {
								$i++;

								$battotal = $info['quantity']*$info['bat_rate'];
								$tot_amt += $battotal;
								$tot_qty += $info['quantity'];
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<td style="width:15%;">
										<select id="location1_<?php echo $i;?>" name="location1_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_batch(this.id);">	
										<?php 

											echo '<option value="">Select</option>';
											$record=$utilObj->getMultipleRow("location","1");
											foreach($record as $e_rec)
											{
												if($info['location']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
											}
										?>
										</select>
									</td>

									<td style="width:15%;">
										<div id="batch_div_<?php echo $i; ?>">
										<?php
											if($PTask=='update') {
										?>
											<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);get_batch_rate(this.id);">	
											<?php 
												$batch=$utilObj->getMultipleRow("purchase_batch","location='".$info['location']."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='production' OR type='packaging') ");

												echo '<option value="">Select</option>';
												foreach($batch as $e_rec)
												{
													if($info['batchname']==$e_rec["id"]) echo $select='selected'; else $select='';
													echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["batchname"] .'</option>';
												}
											?>
											</select>
										<?php } ?>
										</div>
									</td>

									<td style="width:15%;">
										<div id="stock_div_<?php echo $i;?>">
										<?php
											if($PTask=='update') {

											$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );
										?>
											<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $info['bat_rate']; ?>"/>
										<?php } ?>
										</div>
									</td>

									<td style="width:15%;">
										<div id="rate_div_<?php echo $i; ?>">
										<?php
											if($PTask=='update') {

										?>
											<input readonly type="text" id="batrate_<?php echo $i; ?>" readonly class=" form-control number" name="batrate_<?php echo $i; ?>" value="<?php echo $info['bat_rate']; ?>"/>
										<?php } ?>
										</div>
									</td>

									<td style='width:15%'>
										<input type="text" id="batchremove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batchremove_<?php echo $i; ?>" value="<?php echo $info['quantity']; ?>" onkeyup="total_qty(<?php echo $i; ?>);get_battotal(this.id);"/>
									</td>

									<td style="width:15%;">
										<input readonly type="text" id="battotal_<?php echo $i; ?>" readonly class=" form-control number" name="battotal_<?php echo $i; ?>" value="<?php echo $battotal; ?>"/>
									</td>

									<?php 
										if($i>1) {
									?>
										<td style='width:0%'>
											<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch(this.id);"></i>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value=""/>
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<td style="width:15%;"></td>
						<td style="width:15%;"></td>
						<td style="width:15%;"></td>
						<td style="width:15%;text-align:right;">
							<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
						</td>
						<td style="width:15%;">
							Total Quantity : <input class="form-control number" type="text" readonly name="tot_qty" id="tot_qty" value="<?php echo $tot_qty; ?>" >
						</td>
						<td style="width:15%;">
							Total : <input class="form-control number" type="text" readonly name="tot_amt" id="tot_amt" value="<?php echo $tot_amt; ?>" >
						</td>
						<td style="width:0%;"></td>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();" />
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			function total_qty(id) {

				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
				var totalamt = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='batchremove_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
				
				$("#tot_qty").val(totalquantity);

				// Assuming batqty1_id elements are input fields
				$("[id^='battotal_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalamt += quant;
				});
				
				$("#tot_amt").val(totalamt);
				
			}

			function get_battotal(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batchremove = $("#batchremove_"+id).val();
				var batrate = $("#batrate_"+id).val();

				var battotal = parseFloat(batchremove*batrate);
				$("#battotal_"+id).val(battotal);

				total_qty(id);
			}

			function addRowbatch(tableID) {

				var count=$("#cnt2").val();	

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row2_"+i+"'>";
				
				cell1 += "<td style='width:15%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
				<option value=''>Select</option>\
					<?php
					$record=$utilObj->getMultipleRow("location","1");
					foreach($record as $e_rec){	
						echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
					}
							
					?>
				</select></td>";
				
				cell1 += "<td style='width:15%'><div id='batch_div_"+i+"'></div></td>";

				cell1 += "<td style='width:15%'><div id='stock_div_"+i+"'></div></td>";

				cell1 += "<td style='width:15%'><div id='rate_div_"+i+"'></div></td>";
				
				cell1 += "<td style='width:15%'><input type='text' id='batchremove_"+i+"' class='form-control number batch_remove_input' name='batchremove_"+i+"' value='' onkeyup='total_qty("+i+");get_battotal(this.id)' /></td>";

				cell1 += "<td style='width:15%'><input type='text' id='battotal_"+i+"' class='form-control number batch_remove_input' name='battotal_"+i+"' value=''  /></td>";
			
				cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


			
				$("#mybatch1").append(cell1);
				$("#cnt2").val(i);
				// $("#particulars_"+i).select2();
			}

			function delete_row_batch(rwcnt)
			{
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt2").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row2_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row2_"+k).attr('id','row2_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#location1_"+k).attr('name','location1_'+newId);
							jQuery("#location1_"+k).attr('id','location1_'+newId);
							
							jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
							jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

							jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
							jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

							jQuery("#batchremove_"+k).attr('name','batchremove_'+newId);
							jQuery("#batchremove_"+k).attr('id','batchremove_'+newId);
							
							
							jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
						}
						jQuery("#cnt2").val(parseFloat(count-1)); 

						total_qty();

					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}

			function get_batch(this_id) {

				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_sale_batch', location1:location1,id:id,product:product },
					success: function (data) {

						$('#batch_div_'+id).html(data);
					}
				});

			}

			function get_batch_stock(this_id) {
				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var batchname1 = $('#batchname1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,batchname1:batchname1 },
					success: function (data) {
						$('#stock_div_'+id).html(data);
					}
				});
			}

			function get_batch_rate(this_id) {
				
				var id=this_id.split("_");
				id=id[1];
				var location1 = $('#location1_'+id).val();
				var batchname1 = $('#batchname1_'+id).val();
				var product = $("#product_batch").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'get_batch_rate1', location1:location1,id:id,product:product,batchname1:batchname1 },
					success: function (data) {
						$('#rate_div_'+id).html(data);
					}
				});
			}

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#tot_qty').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();

			});

			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#qty").val();

				if (tot_qty == main_qty) {
					purchasereturn_batch();
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("please add quantity in exsiting batch or add new batch.");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("please remove some quantity from exsiting batch.");
					}
				}

			}

			function purchasereturn_batch() {

				var cnt1 = $("#cnt2").val();
				var maincnt = $("#maincnt").val();
				var mainid = $("#mainid").val();
				var product = $("#product_batch").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();

				var totqty = $("#tot_qty").val();
				var totamt = $("#tot_amt").val();
				var type = "sale_return";

				// var batchIds = [];
				// $(".batch_id").each(function () {
				// 	batchIds.push($(this).val());
				// });

				var batchname_array=[];
				var batchid_array=[];
				var batchremove_array=[];
				var batrate_array=[];
				var location_array=[];

				var res = 1;
				
				for(var i=1;i<=cnt1;i++)
				{	
					var location = $("#location1_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batchid = $("#batch_id_"+i).val();
					var batchremove = $("#batchremove_"+i).val();
					var batrate = $("#batrate_"+i).val();

					location_array.push(location);
					batchname_array.push(batchname);
					batchid_array.push(batchname);
					batchremove_array.push(batchremove);
					batrate_array.push(batrate);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'salereturn_batch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,batchid_array:batchid_array,cnt1:cnt1,batrate_array:batrate_array },
					success: function (data) {
						$('#saleinvoicereturnbatch').modal('hide');
						$('#res_'+maincnt).val(res);

						var rate = parseFloat(totamt/totqty);
						$('#rate_'+maincnt).val(rate);
						getrowgst(mainid);
						gettotgst(maincnt);
						// alert(data);
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});

			}

		</script>
	<?php 
	break;

	// ----------------------------- Purchase return batch handler -----------------------------
	case 'salereturn_batch':
	
		if($_REQUEST['PTask']=='update') {

			$common=$_REQUEST['deliveryid'];
		} else {

			$common=$_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_sale_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='".$_REQUEST['type']."' ");
		
		$cnt2 = $_REQUEST['cnt1'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'created'=>date("Y-m-d H:i:s"),'lastedited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location_array'][$i],'bat_rate'=>$_REQUEST['batrate_array'][$i] );
		
			$insertedId=$utilObj->insertRecord('temp_sale_batch', $arrValue1);
		}
	
	break;
	
	case 'get_bom':
		$product = $_REQUEST['product'];
	?>
		<label class="form-label">BOM<span class="required required_lbl" style="color:red;">*</span></label>
		<select id="bom" name="bom" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_billofmaterial_rowtable();" style="width:100%;">	
			<?php 
				echo '<option value="">Select</option>';
				$record=$utilObj->getMultipleRow("bill_of_material","product='".$product."' ");
				foreach($record as $e_rec)
				{
					if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["bom_name"] .'</option>';
				}
			?>
		</select>
	<?php
	break;
	
	// =================================== USE IN=production(1) ===================================

	case'get_billofmaterial_rowtable':

		// echo $_REQUEST['product'];
		// echo $_REQUEST['unit'];

		$common_id = $_REQUEST['ad'];
		$bom = $_REQUEST['bom'];
		$id = $_REQUEST['id'];
		$loaction = $_REQUEST['location'];
		$bill_of_material = $utilObj->getSingleRow("bill_of_material","id ='".$_REQUEST['bom']."' ");

		$production=$utilObj->getSingleRow("production","product ='".$_REQUEST['product']."' AND unit ='".$_REQUEST['unit']."' AND  location ='".$_REQUEST['location']."' ");

		//var_dump($bill_of_material);	
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
	?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/>
		<input type="hidden" id="mqty"  name="mqty" value="<?php echo $_REQUEST['qty']; ?>"/>
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit</th>
					<th style="width:10%;text-align:center;">Required Quantity</th>
					<th style="width:10%;text-align:center;">Available Quantity</th>
					<th style="width:10%;text-align:center;">Used Quantity</th>
					<th style="width:10%;text-align:center;">Batch</th>
					<?php ?>
						<th style="width:10%;text-align:center;">Total</th>
					<?php ?>
					<?php if($_REQUEST['Task']!='view') { ?>
						<th style="width:2%;text-align:center;"></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($_REQUEST['qty']==$production['qty']))
				{ 
					// echo "condi 1";

					$record5=$utilObj->getMultipleRow("production_details","parent_id='".$_REQUEST['id']."' order by id  ASC");

				} else if(($bill_of_material!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')) {  
					// echo "condi 2";
					
					$record5=$utilObj->getMultipleRow("bill_of_material_details","parent_id='".$bill_of_material['id']."' order by id  ASC ");
							
				} else { 
					$record5[0]['id']=1;					
				} 
			foreach($record5 as $row_demo)
			{ 
				$i++;
				// var_dump($row_demo);
				// echo ">>".$row_demo['product'];

				if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&($_REQUEST['qty']==$production['qty'])) {
					$qty = $row_demo['qty'];
					$requiredqty=$row_demo['requiredqty'];
					// $loaction=$production['location'];

					$stock = getlocationstock('',$row_demo['product'],date('d-m-Y'),$loaction);
					$avlqty = $qty+$stock;
				} else {
					$qty='';
					$requiredqty=round(($row_demo['qty']*$_REQUEST['qty'])/$bill_of_material['qty'],2);
					$qty = $requiredqty;
					// $loaction=$_REQUEST['location'];

					$avlqty = getlocationstock('',$row_demo['product'],date('d-m-Y'),$loaction);
				}
				
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td style="width: 15%;">
						<?php 

						$product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						// if($_REQUEST['PTask']=='view'||$_REQUEST['PTask']=='update'){?>
						<input type="hidden" id="product_<?php echo $i;?>"  <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id']; ?>"/>

						<input type="text"   style="width:100%;" class=" form-control" readonly  <?php echo $readonly;?>  value="<?php echo $product['name']; ?>" />

						<?php /* } else { ?>
						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);" style="width:100%;">	
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 group by name ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
						<?php } */?>
					</td>

					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit']; ?>"/>
						</div>
					</td>

					<td style="width: 10%;">
						<input type="text" id="requiredqty_<?php echo $i; ?>" class="tdalign form-control number"  readonly <?php echo $readonly;?> name="requiredqty_<?php echo $i;?>" value="<?php echo $requiredqty; ?>"/>
					</td> 

					<td style="width: 10%;">
						<input type="text" id="avlqty_<?php echo $i; ?>" class="tdalign form-control number"  readonly <?php echo $readonly;?> name="avlqty_<?php echo $i;?>" value="<?php echo $avlqty; ?>"/>
					</td>

					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" onblur="total_qty(<?php echo $i; ?>);check_mainstock(this.id);" class="tdalign form-control number" <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $qty; ?>" />
					</td>

					<td style="width: 5%;text-align:center;">
						<button type="button" class="btn btn-light btn-sm" onclick="check_qty('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#productionbatch" ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>
					
					<?php
						// $tot_data=$utilObj->getSingleRow("temp_sale_batch","parent_id='".$common_id."' AND product='".$row_demo['product']."' ");
					?>
					<td style="width: 10%;">
						<input type="text" id="totalsum_<?php echo $i;?>" class="tdalign number form-control" readonly  <?php echo $readonly;?>  name="totalsum_<?php echo $i;?>" value="<?php echo $row_demo['totalsum']; ?>" onblur="grand_(<?php echo $i; ?>);" />
					</td>
					

					<?php if($_REQUEST['Task']!='view') { ?>
						<td style='width:2%'>
							<!-- <i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i> -->
						</td>
					<?php } ?>

				</tr>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<?php
					$tot_data=$utilObj->getSingleRow("production","id='".$id."' "); 
				?>
				<td style="width: 2%;"></td>
				<td style="width: 15%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;"></td>
				<td style="width: 10%;">
					Total Quantity : <input type="text" class="tdalign form-control number" name="total_req" id="total_req" readonly value="<?php echo $tot_data['total_req']; ?>">
				</td>
				<td style="width: 5%;"></td>
				<td style="width: 10%;">
					Grandtotal : <input type="text" class="tdalign form-control number" name="grand_total" id="grand_total" readonly value="<?php echo $tot_data['grand_total']; ?>">
				</td>
			</tfoot>
		</table> <br>
		<table>
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php if ($_REQUEST['PTask'] != 'view') { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table>

		<div class="modal fade" style = "max-width=40%; " id="productionbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="probatch">
			
				</div>
			</div>
		</div>

		<script>

			function check_mainstock(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batstock = $("#avlqty_"+id).val();
				var bqty = $("#qty_"+id).val();


				if(parseFloat(batstock)<parseFloat(bqty)) {
					alert("You don't have enough Stock!!!");
					$("#qty_"+id).val('');
				}

			}

			function total_qty(id)
			{
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='qty_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
			
				// console.log('Total :', totalquantity);
				
				$("#total_req").val(totalquantity);
			
			}

			function qty_check(i) {

				var req_qty = $("#requiredqty_"+i).val();
				var used_qty = $("#qty_"+i).val();

				if(req_qty != used_qty) {
					alert("Your enter Quantity doesn't match to the Required Quantity!!");
				}
			}

			// function grand_(id)
			// {
			// 	// var quant = $("#batqty1_"+id).val();
			// 	var totalquantity = 0;
			
			// 	// Assuming batqty1_id elements are input fields
			// 	$("[id^='totalsum_']").each(function() {
			// 		var quant = parseFloat($(this).val()) || 0;
			// 		// Convert the value to a number, default to 0 if not a valid number
			// 		// alert(quant);

			// 		totalquantity += quant;
			// 	});
			
			// 	// console.log('Total :', totalquantity);
				
			// 	$("#grand_total").val(totalquantity);
			
			// }

			function check_qty(i)
			{
				var quantity = $("#qty_"+i).val();
				var product = $("#product_"+i).val();

				if (quantity == '' || quantity=='0' ) {
					alert ('please enter quantity first . . . !');

				} else {
					production_batchdata(i);
				}
			}

			function production_batchdata(i) {
			
				var qty =$("#qty_"+i).val();
				var mqty =$("#mqty").val();
				var stock =$("#stock_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				var id = $("#id").val();
				var location =$("#location").val();
				var product =$("#product_"+i).val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'production_batchdata', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,id:id,location:location,maincnt:i,mqty:mqty },
					success: function (data) {
						$('#probatch').html(data);
						$('#productionbatch').modal('show');
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}
		</script>
	<?php
	break;

	case 'production_batchdata':
	
		$product_id = $_REQUEST['product'];
		$qty = $_REQUEST['qty'];
		$mqty = $_REQUEST['mqty'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$maincnt = $_REQUEST['maincnt'];

		$i = 0;
		// $totalstock=0;
	?>
		<div id="probatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
				<p>
					<?php $loc=$utilObj->getSingleRow("location","id='".$location."'"); ?>
					<?php $pname=$utilObj->getSingleRow("stock_ledger","id='".$product_id."'"); ?>
					Production Location : &nbsp; <?php echo $loc['name']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					Product Name : &nbsp; <?php echo $pname['name']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					Requried Quantity : &nbsp; <?php echo $qty; ?>
				</p>
				<table class = "table border-top" id="mybatch1">
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">

					<input type="hidden" name="req_qty" id="req_qty" value="<?php echo $qty; ?>">
					<input type="hidden" name="mqty" id="mqty" value="<?php echo $mqty; ?>">
					<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<thead>
							<tr>
								<th>Batch Name</th>
								<th>Batch Stock</th>
								<th>Batch Rate</th>
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($PTask == 'update' || $_REQUEST['PTask']=='view') {

								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='production_out' ");

							} else {

								$product = $utilObj->getMultipleRow("temp_sale_batch","product='".$product_id."' AND parent_id='".$common_id."' AND type='production_out' ");
 
								if(empty($product)) {
									$product = $utilObj->getMultipleRow("purchase_batch", "product='".$product_id."' AND location='".$location."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='physical_batch_in') ");
								}
								
								// $product = $utilObj->getMultipleRow("purchase_batch", "product='".$product_id."' AND location='".$location."'  ");
							}

							foreach ($product as $info) {

								if($PTask == 'update' || $_REQUEST['PTask']=='view') {

									$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );

									$batname = $info['batchname'];
									$bat_rate = $info['bat_rate'];
									$batid = $info['purchase_batch'];
									$batqty = $totalstock+$info['quantity'];
									
									$bat_qty = $info['quantity'];

								} else {
									
									$totalstock = getbatchstock($info['id'],$product_id, date('Y-m-d'),$info['location'] );

									$batname = $info['batchname'];
									$bat_rate = $info['bat_rate'];
									$batid = $info['id'];

									// if($totalstock<0) {
									// 	$
									// } else {
										$batqty = $totalstock;
									// }
									
									$bat_qty = $info['quantity'];
								}

								if($PTask == 'update' || $_REQUEST['PTask']=='view') {

									$totsum += $info['quantity'];
								}

								$i++;
						?>

							<tr id='row2_<?php echo $i; ?>'>
								<td style="width:20%">
									<input type="text" id="batchname1_<?php echo $i; ?>" class="form-control number" name="batchname1_<?php echo $i; ?>" value="<?php echo $batname; ?>" readonly />
									<input type="hidden" id="batchid_<?php echo $i; ?>" name="batchid_<?php echo $i; ?>" value="<?php echo $batid; ?>" />
								</td>

								<td style="width:15%">
									<input type="text" id="batqty_<?php echo $i; ?>" class="tdalign form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $batqty; ?>" readonly />
								</td>

								<td style="width:15%">
									<input type="text" id="bat_rate_<?php echo $i; ?>" class="tdalign form-control number" name="bat_rate_<?php echo $i; ?>" value="<?php echo $bat_rate; ?>" readonly />
								</td>

								<td style="width:20%">
									<input type="text" id="batchremove_<?php echo $i; ?>" class="tdalign form-control number batch_remove_input" name="batchremove_<?php echo $i; ?>" value="<?php echo $bat_qty; ?>" onblur="total_qty(<?php echo $i; ?>);get_subtotal(this.id);check_stockqty(this.id);" />
								</td>
								
								<td style="width:25%">
									<input type="text" id="sub_total_<?php echo $i; ?>" class="tdalign form-control number" name="sub_total_<?php echo $i; ?>" value="<?php echo $info['batch_price']; ?>" readonly />
								</td>
							</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value="" />
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<td style="width:20%"></td>
						<td style="width:15%"></td>
						<td style="width:15%"></td>
						<td style="width:20%">
							Total Quantity : <input type="text" class="tdalign form-control number" name="tot_qty" id="tot_qty" readonly value="<?php echo $totsum; ?>">
						</td>
						<td style="width:25%">
							Grandtotal : <input type="text" class="tdalign form-control number" name="grand_tot" id="grand_tot" readonly value="<?php echo $info['sub_total']; ?>">
						</td>
					</table>
					<br>
					<!-- <div class="col-md-2">
						<button type="button" class="btn btn-warning" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
					</div> -->
				</div>
			</div>
			
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty(<?php echo $maincnt; ?>); " />

				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			function total_qty(id)
			{
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='batchremove_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});
			
				console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);
			
			}

			function get_subtotal(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batqty = $("#batchremove_"+id).val();
				var batrate = $("#bat_rate_"+id).val();
				var cnt2 = $("#cnt2").val();

				var sub_total = batqty*batrate;

				$("#sub_total_"+id).val(sub_total);

				grand_total(id);
			}

			function check_stockqty(this_id) {

				var id=this_id.split("_");
				id=id[1];

				var batstock = $("#batqty_"+id).val();
				var bqty = $("#batchremove_"+id).val();

				if(parseFloat(batstock)<parseFloat(bqty)) {
					alert("You don't have enough Stock!!!");
					$("#batchremove_"+id).val('');
				}
			}

			function grand_total(id)
			{
				var totalquantity = 0;
			
				$("[id^='sub_total_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;

					totalquantity += quant;
				});
			
				console.log('Total :', totalquantity);
				
				$("#grand_tot").val(totalquantity);
			
			}

			function check_submit_qty(maincnt) {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#req_qty").val();
				// alert(main_qty);

				if (tot_qty == main_qty) {
					productionbatch();
					// grand_(maincnt);
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is doesn't match Material quantity.");
					} else {
						alert("Your total batch quantity is doesn't match Material quantity.");
					}
				}

			}

			function productionbatch() {

				var product = $("#product_batch").val();
				var location = $("#location").val();
				var cnt2 = $("#cnt2").val();
				var maincnt = $("#maincnt").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "production_out";
				var grand_tot = $("#grand_tot").val();

				// ---------------------------------------------------------
				// var location_array=[];
				var batchname_array=[];
				var batchid_array=[];
				var bat_rate_array=[];
				var batchremove_array=[];
				var sub_total_array=[];
				
				for(var i=1;i<=cnt2;i++)
				{	
					// var location = $("#location1_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batchid = $("#batchid_"+i).val();
					var batchremove = $("#batchremove_"+i).val();
					var bat_rate = $("#bat_rate_"+i).val();
					var sub_total = $("#sub_total_"+i).val();

					// location_array.push(location);
					batchname_array.push(batchname);
					batchid_array.push(batchid);
					bat_rate_array.push(bat_rate);
					batchremove_array.push(batchremove);
					sub_total_array.push(sub_total);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'productionbatch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,cnt2:cnt2,location:location,batchid_array:batchid_array,grand_tot:grand_tot,sub_total_array:sub_total_array,bat_rate_array:bat_rate_array },
					success: function (data) {
						$('#productionbatch').modal('hide');
						$("#totalsum_"+maincnt).val(grand_tot);
						grand_(maincnt);
						get_batchrate();
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

			function grand_(id)
			{
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
			
				// Assuming batqty1_id elements are input fields
				$("[id^='totalsum_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					// alert(quant);

					totalquantity += quant;
				});
			
				// console.log('Total :', totalquantity);
				
				$("#grand_total").val(totalquantity);
			
			}

			function get_batchrate() {

				// var total_quantity = $("#total_req").val();

				var total_quantity = $("#mqty").val();
				var grand_total = $("#grand_total").val();

				// alert(total_quantity);
				// alert(grand_total);

				var avg_rate = parseFloat(grand_total)/parseFloat(total_quantity);
				// $("#pro_batch_rate").val(avg_rate);

				$("#pro_batch_rate").val(avg_rate.toFixed(2));

			}

			// function addRowbatch(tableID) {
			// 	var count=$("#cnt2").val();	

			// 	var i=parseFloat(count)+parseFloat(1);

			// 	var cell1="<tr id='row2_"+i+"'>";
				
			// 	cell1 += "<td style='width:10%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
			// 	<option value=''>Select</option>\
			// 		<?php
			// 		$record=$utilObj->getMultipleRow("location","1");
			// 		foreach($record as $e_rec){	
			// 			echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			// 		}
							
			// 		?>
			// 	</select></td>";
				
			// 	cell1 += "<td style='width:30%'><div id='batch_div_"+i+"'></div></td>";

			// 	cell1 += "<td style='width:30%'><div id='stock_div_"+i+"'></div></td>";
				
			// 	cell1 += "<td style='width:30%'><input type='text' id='batch_remove_"+i+"' class='form-control number batch_remove_input' name='batch_remove_"+i+"' value='' onblur='total_qty("+i+")' /></td>";
			
			// 	cell1 += "<td style='width2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


			
			// 	$("#mybatch1").append(cell1);
			// 	$("#cnt2").val(i);
			// 	// $("#particulars_"+i).select2();
			// }

			// function delete_row_batch(rwcnt)
			// {
			// 	var id=rwcnt.split("_");
			// 	rwcnt=id[1];
			// 	var count=$("#cnt2").val();	
				
			// 	if(count>1)
			// 	{
			// 		var r=confirm("Are you sure!");
			// 		if (r==true)
			// 		{		
						
			// 			$("#row2_"+rwcnt).remove();
							
			// 			for(var k=rwcnt; k<=count; k++)
			// 			{
			// 				var newId=k-1;
							
			// 				jQuery("#row2_"+k).attr('id','row2_'+newId);
							
			// 				jQuery("#idd_"+k).attr('name','idd_'+newId);
			// 				jQuery("#idd_"+k).attr('id','idd_'+newId);
			// 				jQuery("#idd_"+newId).html(newId);
							
			// 				jQuery("#location1_"+k).attr('name','location1_'+newId);
			// 				jQuery("#location1_"+k).attr('id','location1_'+newId);
							
			// 				jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
			// 				jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

			// 				jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
			// 				jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

			// 				jQuery("#batch_remove_"+k).attr('name','batch_remove_'+newId);
			// 				jQuery("#batch_remove_"+k).attr('id','batch_remove_'+newId);
							
							
			// 				jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
			// 			}
			// 			jQuery("#cnt2").val(parseFloat(count-1)); 

			// 			total_qty();

			// 		}
			// 	}
			// 	else {
			// 		alert("Can't remove row Atleast one row is required");
			// 		return false;
			// 	}	 
			// }

			// function get_batch(this_id) {
			// 	var id=this_id.split("_");
			// 	id=id[1];
			// 	var location1 = $('#location1_'+id).val();
			// 	var product = $("#product_batch").val();

			// 	jQuery.ajax({
			// 		url: 'get_ajax_values.php',
			// 		type: 'POST',
			// 		data: { Type: 'get_batch', location1:location1,id:id,product:product },
			// 		success: function (data) {
			// 			$('#batch_div_'+id).html(data);
				
			// 		}
			// 	});

			// }

			// function get_batch_stock(this_id) {
			// 	var id=this_id.split("_");
			// 	id=id[1];
			// 	var location1 = $('#location1_'+id).val();
			// 	var batchname1 = $('#batchname1_'+id).val();
			// 	var product = $("#product_batch").val();

			// 	jQuery.ajax({
			// 		url: 'get_ajax_values.php',
			// 		type: 'POST',
			// 		data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,		batchname1:batchname1 },
			// 		success: function (data) {
			// 			$('#stock_div_'+id).html(data);
			// 		}
			// 	});
			// }

			

		</script>
	<?php
	break;

	// --------------------- Production Batch Handler---------------------
	case 'productionbatch':
		
		if($_REQUEST['PTask']=='update'){
			$common=$_REQUEST['deliveryid'];
		}else{
			$common=$_REQUEST['common_id'];
		}
		
		$batchdata = $utilObj->deleteRecord("temp_sale_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='".$_REQUEST['type']."' ");
		
		$cnt2 = $_REQUEST['cnt2'];

		for($i=0;$i<$cnt2;$i++) {
			if($_REQUEST['batchremove_array'][$i] !=0) {
				$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['bat_rate_array'][$i],'batch_price'=>$_REQUEST['sub_total_array'][$i],'sub_total'=>$_REQUEST['grand_tot'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'created'=>date("Y-m-d H:i:s"),'lastedited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

				$insertedId=$utilObj->insertRecord('temp_sale_batch', $arrValue1);
			}
		}

	break;

	// ================================= USE IN=production (2) =================================
	case 'get_unit_forproduction':
		$record=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."' ");
		// $record=$utilObj->getSingleRow("bill_of_material","id='".$_REQUEST['product']."' ");
	?>
		<input type="text" style="width:100%;"  class=" form-control  smallinput " onchange="get_billofmaterial_rowtable();" readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $record['unit'];?>" />

   	<?php  
	break;
	// ================================= USE IN=bill of material (1) =================================	
	case 'get_unit_billofmaterial':
		$record=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."' ");
	?>
		<!--select id="unit" name="unit"  onchange="get_billofmaterial_rowtable();" <?php echo $disabled;?> data-allow-clear="true"  class="select2 form-select "  >
		 <option value=""> Select unit</option>
			
				<?php 
					/* $record=$utilObj->getMultipleRow("stock_ledger","id='".$_REQUEST['product']."' ");
					foreach($record as $e_rec)
					{
						if($row_demo['unit']==$e_rec["unit"]) echo $select='selected'; else $select='';
						echo '<option value="'.$e_rec["unit"].'" '.$select.'>'.$e_rec["unit"] .'</option>';
					} */
				?> 
		</select-->
		<input type="text" style="width:100%;"  class=" form-control  smallinput "  readonly id="unit" <?php echo $readonly;?> name="unit" value="<?php echo $record['unit'];?>"/>
				
		<script>
			// $('#unit').select2();
		</script>		
   	<?php
	break;
	// ================================= USE IN (1) credit note============================================
	case'get_credittype':
		$credit_note=$utilObj->getSingleRow("credit_note"," id='".$_REQUEST['id']."' ");
	?>	
		<label class="form-label">Sale Invoice No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div id="saleorder_div">
			<?php if($_REQUEST['PTask']=='view' ){
				$readonly="readonly";
				$sale_invoice=$utilObj->getSingleRow("sale_invoice","id in (select sale_invoiceno from  credit_note where id ='".$_REQUEST['id']."')");
			?>
				<input type="hidden" id="sale_invoiceno" <?php echo $readonly;?> name="sale_invoiceno" value="<?php echo $sale_invoice['id'];?>"/>
				<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $sale_invoice['sale_invoiceno'];?>"/>
				
				<?php  }else{ ?>
			<select id="sale_invoiceno" name="sale_invoiceno" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange=" saleinvoice_rowtable();">
				<option value=""> Select Sale Invoice No</option>
			
				<?php 
						
					//$record=$utilObj->getMultipleRow("sale_order","id not in (select  saleorder_no  from  delivery_challan where purchaseorder_no!='".$GRN_no['purchaseorder_no']."') group by order_no");
					$record=$utilObj->getMultipleRow("sale_invoice","  customer='".$_REQUEST['customer']."' group by sale_invoiceno");
					foreach($record as $e_rec){
						if($credit_note['sale_invoiceno']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sale_invoiceno"].'</option>';
					}
				?> 
			</select>
		
		<?php } ?>
		</div>
	<?php
	break;

	// ================================= USE IN (2)credit note=================================
	case 'saleinvoice_rowtable': 
		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['customer']."' ");
		$state= $account_ledger['mail_state']; 
			if($state==27){
				$colspan=9;
			}else{
				$colspan=8;
			}
		$sale_invoiceno=$utilObj->getSingleRow("credit_note","id ='".$_REQUEST['id']."'");	
		//var_dump($delivery_challan_no);
		//echo $_REQUEST['delivery_challan_no'];
		//$sale_invoiceno=$utilObj->getSingleRow("sale_invoice","id ='".$_REQUEST['sale_invoiceno']."'");
		//var_dump($saleorder_no);	
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
	?>
	      <input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
			<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>	
					<?php if( $state==27){?>
					<th style="width: 5%;text-align:center;">CGST </th>
					<th style="width: 5%;text-align:center;">SGST </th>
						<?php }else{?>
					<th style="width: 5%;text-align:center;">IGST </th>
						<?php }?>
					<th style="width:10%;text-align:center;">Stock </th>
					<th style="width:10%;text-align:center;">Quantity </th>
					<th style="width:10%;text-align:center;">Rate </th>
					<th style="width:10%;text-align:center;"> Return Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Total </th>
					 <?php if($_REQUEST['Task']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
					 <?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=$qty=$total_quantity=$stock_chk=0;
				
				    if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')&&$_REQUEST['sale_invoiceno']==$sale_invoiceno['sale_invoiceno'])
					{ 
				      //echo "condi 1";
					  $record5=$utilObj->getMultipleRow("credit_note_details","parent_id='".$_REQUEST['id']."' order by product  ASC");
					} else if(($_REQUEST['sale_invoiceno']!=''&& $_REQUEST['PTask']=='Add')||($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'))
					{  
				      //echo "condi 2";
					  $_REQUEST['sale_invoiceno'];
					  $record5=$utilObj->getMultipleRow("sale_invoice_details","parent_id='".$_REQUEST['sale_invoiceno']."' order by product  ASC ");
							 
					}
					else
					{ 
						$record5[0]['id']=1;					
					}   
			foreach($record5 as $row_demo)
			{ 
				$i++;
				$totalstock=0;
			
				?>
				<tr id='row_<?php echo $i;?>'>
					<td  style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td  style="width: 20%;">
					    <?php 
                        $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']!=''){?>
						<input type="hidden" id="product_<?php echo $i;?>"  name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control"  readonly <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>"/>
						<?php }?>
					</td>
					<td style="width: 10%;">
					<div id='unitdiv_<?php echo $i;?>'>
						<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
					</div>
					</td>
					<?php if($state==27){?>
					<td style="width: 5%;">
					 <input type="text" id="cgst_<?php echo $i;?>" class=" form-control number"   readonly  name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
					 </td>
					 
					 <td style="width: 5%;">
					 <input type="text" id="sgst_<?php echo $i;?>" class=" form-control number"  readonly   name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
					 </td>
						<?php }else{?>
					 <td style="width: 5%;">
					 <input type="text" id="igst_<?php echo $i;?>" class=" form-control number" readonly    name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
					 </td>
						<?php }?>
						
					 <td style="width: 10%;">
					 <?php 
                  	 $totalstock=getstock($row_demo['product'],$row_demo['unit'],date('Y-m-d'),$_REQUEST['id'],''); ?>
					 <input type="text"  id="stock_<?php echo $i;?>" class=" form-control number"  name="stock__<?php echo $i;?>" readonly  value="<?php echo $totalstock;?>"/>
					 </td>
					 
					 
					 <td style="width: 10%;">
					 <input type="text" id="qty_<?php echo $i;?>" class=" form-control number"  readonly  <?php echo $readonly;?>  <?php echo $readonly.$read;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					 </td>
					 
					 <td style="width: 10%;">
					 <input type="text" id="rate_<?php echo $i;?>" class="number form-control"  readonly    <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>"/>
					 </td> 
					 
					 <td style="width: 10%;">
					 <input type="text" id="returnqty_<?php echo $i;?>" class="number form-control"     onkeyup="Gettotal(id);" onchange="Gettotal(id);"  <?php echo $readonly;?> name="returnqty_<?php echo $i;?>" value="<?php echo $row_demo['returnqty'];?>"/>
					 </td>
					  
					 <td style="width: 10%;">
					 <input type="text" id="total_<?php echo $i;?>" class="number form-control" readonly  <?php echo $readonly;?>  name="total_<?php echo $i;?>" value="<?php  echo $row_demo['total']; ?>"/>
					 </td>
					 
					 <?php if($_REQUEST['Task']!='view'){?>
					<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
					</td>
					 <?php 
					   //chk qty is smaller than stock_chki
					   if($remain_qty >$totalstock){
						$stock_chk++; 
					   }
					 
					 } ?>
				</tr>
				<script>
				// Gettotal('qty_<?php echo $i;?>');
				get_totalqty();
				</script>
			<?php 
			$grandtotal+=$row_demo['total'];
			} ?>
					
			</tbody>
			<tfoot>
			<tr>
			
				<td colspan="<?php echo $colspan; ?>" style="text-align:right;">
				Grandtotal
				</td>
				<td>
				 <input type="text" id="grandtotal" class="number form-control" readonly name="grandtotal" value="<?php  echo $grandtotal;?>"/>
				</td>
				<td>
				</td>
			</tr>
			</tfoot>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
		</table>
		 <table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
                   <td>
						<?php 
						/* if(($_REQUEST['PTask']!='view' )){?>			
							<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php } */ ?> 
				</td>			
			</tr>
		</table> 
	
	<?php
	break;
	
	// ========================== USE IN = Sale Receipt == (1) ==========================
	case 'saletable':

		if( $_REQUEST['PTask']=='view') {

			$disabled="disabled";
		} else {

			$disabled="";
		}

		if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {

			// echo $_REQUEST['id'];
			$sale_receipt=$utilObj->getSingleRow("sale_receipt","id = '".$_REQUEST['id']."' group by customer ");
			// var_dump($purchase_invoice);
		}
		// echo "hiii";
		// $i=0;
		// echo " PurchaseFrom = '".$_REQUEST['cust']."'  ";
		// $ro =$utilObj->getCount("sale_invoice","customer = '".$_REQUEST['cust']."' ");
		// if($ro>0){
		// 	$i++;					
	?>
		<!-- ------------------------------------------------------------------------- -->

		<table id="myTable" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 10%;"> Bill No. </th>
					<th style="width: 4%;"> Invoice Date. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php		
				$i=0;
				$total=0;

				if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}

				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {
					// var_dump($info);
					$i++;
					if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )  {

						$invodate = $info['invodate'];

						$bill_adust=$utilObj->getSingleRow("bill_adjustment","id ='".$info['purchaseid']."' ");

						$purchase=$utilObj->getSum("bill_adjustment","id='".$info['id']."' ","amount");

						$remain = $bill_adust['total_amt'] - $purchase;
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php if($_REQUEST['PTask']=='update') { ?>
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo date('Y-m-d',strtotime($invodate)); ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="form-control tdalign" readonly <?php echo $readonly;?> name="pendingamt_<?php echo $i; ?>" value="<?php echo $remain; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $purchase_payment['amt_pay']; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
								
		<?php //} ?>
	<?php
	break;
	
	// ========================== USE IN = Cash Receipt == (1) ==========================
	case 'saletable1':

		if( $_REQUEST['PTask']=='view') {

			$disabled="disabled";
		} else {

			$disabled="";
		}

		if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {

			// echo $_REQUEST['id'];
			$sale_receipt=$utilObj->getSingleRow("cash_receipt","id = '".$_REQUEST['id']."' group by customer ");
			// var_dump($purchase_invoice);
		}
		// echo "hiii";
		$i=0;
		// echo " PurchaseFrom = '".$_REQUEST['cust']."'  ";
		$ro =$utilObj->getCount("sale_invoice","customer = '".$_REQUEST['cust']."' ");
		if($ro>0){
			$i++;					
	?>
		<table id="datatable-buttons" style="background:#e8e9ed;" class=" table  table-sm table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 2%;"><!--<input type='checkbox' value='0'  class='group-checkable' id='select_all' data-set='#datatable-buttons.checkboxes' />-->SR.No </th>
					<!--th style="width: 10%;"> Invoice No. </th-->
					<th style="width: 5%;"> Invoice Issued Date </th>
					<th style="width: 10%;"> Invoice No. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 10%;"> Amount </th>
					<!-- <th style="width: 10%;"> Discount </th>
					<th style="width: 10%;"> Balance </th>		 -->
				</tr>
			</thead>
			<tbody>
				<?php
					$i=0;
					$total=0;
						
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$_REQUEST['cust']==$sale_receipt['customer'] ){
						// echo "1";
						$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM cash_receipt_details where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());
						// var_dump($rows);
					} else {
						// echo "2";
						$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM sale_invoice where customer= '".$_REQUEST['cust']."' AND location= '".$_REQUEST['location']."'  order by Created DESC ")or die(mysqli_error());
					}

					while($info=mysqli_fetch_array($rows))
					{
						// var_dump($info);
						$i++;

						if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$_REQUEST['cust']==$sale_receipt['customer']  ){
							
							$sale=$utilObj->getSum("cash_receipt_details","saleid='".$info['saleid']."' AND id!='".$info['id']."'","amount");
							$saledisc=$utilObj->getSum("sale_receipt_details","saleid='".$info['saleid']."' AND id!='".$info['id']."'","discount");
							$record=$utilObj->getSingleRow("sale_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['saleid']."'"); 
							$payment=$utilObj->getSingleRow("sale_receipt","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['parent_id']."'"); 
							
							if ($record['id']==$info['saleid'])
								{
									$checked = "checked";							 
								}
								else
								{
									$checked = "";							  
								}
								$totalchk=$total=$payment['amt_pay'];
								$remain= ($record['grandtotal']-$sale) - $info['amount'] - $info['discount'];
								
						}else{
							$sale=$utilObj->getSum("cash_receipt_details","saleid='".$info['id']."'","amount");
							$saledisc=$utilObj->getSum("sale_receipt_details","saleid='".$info['id']."'","amount");
							$record=$utilObj->getSingleRow("sale_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['id']."'"); 
							// $saleadvance=$utilObj->getSum("sale_advance_used","saleid='".$info['ID']."'","amount");
							$totalchk +=$record['grandtotal']-$sale-$saledisc;//-$saleadvance;	//For showing No data available in table
						
						}
							
						if(($record['grandtotal']-$sale-$saledisc)>0) {
				?>
					<tr class='even '> 
						<td style='width:2%' class='controls'>
							<?php echo $i; ?>&nbsp;&nbsp;
							<input type='checkbox' id='checkbox<?php echo $i;?>' class='checkboxes' $dasable $dasable1 name='check_list' onchange='gettotaltable();getinputbox(<?php echo $i;?>,<?php echo $record['grandtotal']-$sale-$saledisc; ?>);getinputvalues(<?php echo $i;?>,<?php echo $record['grandtotal']-$sale-$saledisc; ?>);' value='<?php echo $record['id']."#".$record['grandtotal'];?>'<?php echo $checked;?> <?php echo $disabled;?> />
						</td>

						<td style="">
							<?php echo date('d-m-Y',strtotime($record['date'])); ?>
						</td>
						<td>
							<input type='hidden' id='saleid<?php echo $i;?>' name='saleid<?php echo $i;?>' value='<?php echo $record['id'];?>'>

							<?php echo $record['saleino_code']; ?>
						</td>

						<td class="tdalign">
							<?php echo $record['grandtotal']; ?>
						</td>

						<td class="tdalign">
							<?php echo ($record['grandtotal']-$sale-$saledisc); ?>
						</td>

						<!-- <input type='hidden' id='saleid<?php echo $i;?>' name='saleid<?php echo $i;?>' value='<?php echo $record['id'];?>'> -->

						<?php
							// echo "<td class='controls'>".$record['invoicenumber'] . "</a> </td>";
							// echo "<td >". ($record['grandtotal']-$sale-$saledisc-$saleadvance) . "</td>"; 
						?>
							
						<td id='checkboxshow<?php echo $i;?>' >
						<?php
							if($_REQUEST['PTask']=='update') {
						?>
							<input type="text" class="form-control " value="<?php echo $info['amount'];?>" name="bank<?php echo $i;?>" id="bank<?php echo $i;?>" onkeyup="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$sale);?>);" onblur="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$sale); ?>);"  <?php echo $disabled;?> />
						<?php } ?>
						</td>
					
						<!-- <td id='discount<?php echo $i;?>' >
							<input type="text" class="form-control " value="<?php echo $info['discount'];?>" name="bank1<?php echo $i;?>" id="bank1<?php echo $i;?>" onkeyup="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$sale);?>);" onblur="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$sale); ?>);"  <?php echo $disabled;?> />
						</td> -->
					
						<?php  ?>

						<!-- <td id ='checkboxvalue<?php echo $i;?>'>
							<input type="text" class="form-control" value="<?php echo $remain;?>" readonly>
						</td> -->
					</tr>
					<?php
						}
						}
						if($totalchk==0){
							echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
						}
					?>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style='text-align: center;padding-top: 15px;'>Total</td>
						<td><input type="text" value="<?php echo $total;?>" id="totalvalue" name="totalvalue" disabled style="height: 35px;width:100%;padding-left:10px"></td>
						<!--td><input type="text" value="" id="totalvalue" name="totalvalue" disabled style="height: 35px;padding-left: 12px;width:100%;"></td-->
					</tr>
				</tfoot>
				<input type='hidden' name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
		</table>
								
		<?php } ?>

		<?php
	break;

	// ===================================== USE IN-Sale Invoice(1) =====================================

	case 'get_deliverychallan':
		$delivery_challan_no = $utilObj->getSingleRow("sale_invoice", " id='" . $_REQUEST['id'] . "' ");
		if($_REQUEST['type']=='Against_delivery'){
		?>
	
	
		<label class="form-label"> Dellivery Challan No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div id="saleorder_div">
		<?php if ($_REQUEST['PTask'] == 'view') {

			$readonly = "readonly";
			$delivery_challan = $utilObj->getSingleRow("delivery_challan", "id in (select delivery_challan_no from  sale_invoice where id ='" . $_REQUEST['id'] . "')");
			?>
				<input type="hidden" id="delivery_challan_no" <?php echo $readonly; ?> name="delivery_challan_no" value="<?php echo $delivery_challan['id']; ?>"/>
				<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly; ?>  value="<?php echo $delivery_challan['challan_no']; ?>"/>
		<?php } else { ?>

			<select id="delivery_challan_no" name="delivery_challan_no" <?php echo $disabled; ?> class="select2 form-select " data-allow-clear="true" onchange="saleorder_delivery_rowtable();show_pos(this.value)">
				<option value=""> Select Dellivery Challan No</option>
				<?php
					$record = $utilObj->getMultipleRow("delivery_challan", "customer ='" . $_REQUEST['customer'] . "'group by challan_no");
					foreach ($record as $e_rec) {

						if ($delivery_challan_no['delivery_challan_no'] == $e_rec["id"])
							echo $select = 'selected';
						else
							$select = '';
						echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["challan_no"] . '</option>';
					}
				?> 
			</select>

		<?php } ?>
		</div>
	<?php }
	break;
	// ===================================== USE IN-Sale Invoice(2) =====================================
 
	case 'saleorder_delivery_rowtable':
		$account_ledger = $utilObj->getSingleRow("account_ledger", " id='" . $_REQUEST['customer'] . "' ");
		// $state = $account_ledger['mail_state'];
		$state = $_REQUEST['customer'];

		if ($state == 27) {
			
			if ($_REQUEST['type'] != 'Direct_Sale') {

				$colspan = 6;
			} else {

				$colspan = 6;
			}
		} else {

			if ($_REQUEST['type'] != 'Direct_Sale') {

				$colspan = 5;
			} else {

				$colspan = 5;
			}
		}

		$delivery_challan_no = $utilObj->getSingleRow("sale_invoice", "id ='" . $_REQUEST['id'] . "'  ");
		$purchase_order = $utilObj->getSingleRow("sale_invoice", "id ='" . $_REQUEST['id'] . "'  ");

		// var_dump($delivery_challan_no);
		// echo $_REQUEST['delivery_challan_no'];
		$saleorder_no = $utilObj->getSingleRow("sale_order", "id in ( select  saleorder_no  from  delivery_challan where  id ='" . $_REQUEST['delivery_challan_no'] . "')");
		// var_dump($saleorder_no);
		
		if ($_REQUEST['PTask'] == 'view') {

			$readonly = "readonly";
			$disabled = "disabled";
		} else {

			$readonly = "";
			$disabled = "";

		}
	?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/>
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Ledger</th>
					<th style="width: 10%;text-align:center;">Unit </th>	
					<?php if ($state == 27) { ?>
						<th style="width: 5%;text-align:center;">CGST </th>
						<th style="width: 5%;text-align:center;">SGST </th>
					<?php } else { ?>
						<th style="width: 5%;text-align:center;">IGST </th>
					<?php } ?>
					<?php /* if ($_REQUEST['type'] != 'Direct_Sale') { ?>
						<th style="width:5%;text-align:center;">Order Quantity </th>
					<?php } */ ?>
					<th style="width:10%;text-align:center;">Invoice Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Rate </th>
					<th style="width:10%;text-align:center;">Batch </th>
					<th style="width:10%;text-align:center;">DISC (%) </th>
					<th style="width:10%;text-align:center;">Taxable </th>
					<!-- <th style="width:10%;text-align:center;">Total </th> -->
					
					<?php if ($_REQUEST['Task'] != 'view') { ?>
						<th style="width:2%;text-align:center;"></th>
					<?php } ?>
				</tr>
			</thead>

			<tbody>
			<?php
				$i = $qty = $total_quantity = $stock_chk = 0;

				if (($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view') && $_REQUEST['delivery_challan_no'] == $delivery_challan_no['delivery_challan_no']) {
					// echo "condi 1";
					$record5 = $utilObj->getMultipleRow("sale_invoice_details", "parent_id='" . $_REQUEST['id'] . "' order by product  ASC");
				} else if (($_REQUEST['delivery_challan_no'] != '' && $_REQUEST['PTask'] == 'Add') ) {
					// echo "condi 2";
					$record5 = $utilObj->getMultipleRow("sale_order_details", "parent_id='" . $saleorder_no['id'] . "'   AND  parent_id in ( select id from sale_order ) order by product  ASC ");
				} else {
					$record5[0]['id'] = 1;
				}

				foreach ($record5 as $row_demo) {

					$i++;
					$totalstock = 0;

					$delivery_challan_qty = $utilObj->getSingleRow("delivery_challan_details", "parent_id in(select id from delivery_challan where saleorder_no='" . $saleorder_no['id'] . "')AND product='" . $row_demo['product'] . "' AND parent_id='" . $_REQUEST['delivery_challan_no'] . "'");

					if (($_REQUEST['delivery_challan_no'] == '' && $_REQUEST['PTask'] == 'Add')) {
						$sale_invoice_details = $utilObj->getSingleRow("sale_invoice_details", "parent_id in(select id from sale_invoice where delivery_challan_no in (select  parent_id from  delivery_challan_details where  id='" . $delivery_challan_qty['id'] . "'))AND product='" . $row_demo['product'] . "'");

						$order_qty = $delivery_challan_qty['qty'] - $sale_invoice_details['qty'];
						$remain_qty = $delivery_challan_qty['qty'];
						$total = 0;
					} else if($_REQUEST['delivery_challan_no'] != '') {
						//echo '2';
						$sale_invoice_details = $utilObj->getSingleRow("sale_invoice_details", "parent_id in(select id from sale_invoice where delivery_challan_no in (select  parent_id from  delivery_challan_details where  id='" . $delivery_challan_qty['id'] . "'))AND product='" . $row_demo['product'] . "'");

						$order_qty = $row_demo ['qty'] ;
						$remain_qty = $row_demo['qty'];
						$total = $row_demo['total'];
					} else {
						$order_qty = $row_demo['orderqty'];
						$remain_qty = $row_demo['qty'];
						$total = $row_demo['total'];
					}
				?>
					<tr id='row_<?php echo $i; ?>'>
						<td  style="text-align:center;width:2%;">
							<label  id="idd_<?php echo $i; ?>"   name="idd_<?php echo $i; ?>"><?php echo $i; ?> </label>
						</td>

						<td  style="width: 20%;">
						<?php

							$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
							if ($_REQUEST['type'] == 'Against_delivery') {
						?>

							<input type="hidden" id="product_<?php echo $i; ?>"  name="product_<?php echo $i; ?>" value="<?php echo $product['id']; ?>"/>
							<input type="text"   style="width:100%;" class=" form-control"  readonly <?php echo $readonly . $read; ?>  value="<?php echo $product['name']; ?>"/>
						<?php } else { ?>

							<select id="product_<?php echo $i; ?>" name="product_<?php echo $i; ?>" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);check_batch_type1(this.id);get_saleledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);" style="width:100%;">	
							<?php
								echo '<option value="">Select</option>';
								$record = $utilObj->getMultipleRow("stock_ledger", "1 ");
								foreach ($record as $e_rec) {
									
									if ($row_demo['product'] == $e_rec["id"]) echo $select = 'selected';
									else $select = '';
									echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["name"] . '</option>';
								}
							?>
							</select>
						<?php } ?>
						</td>

						<td style="width:10%;">
							<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php

							if( ($_REQUEST['PTask']=='view'&&$_REQUEST['type'] == 'Against_delivery') || ($_REQUEST['PTask']=='update') ) {
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=28 group by name");
								foreach($record as $e_rec){
									if($row_demo['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}

							} 
							else {
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");

								$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=28 group by name");

								echo '<option value="">Select Ledger</option>';
								foreach($record as $e_rec)
								{	
									if($state==27) {
										if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									} else {
										if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
									
								}
							}
							?>
							</select>
						</td>

						<td style="width: 10%;">
							<div id='unitdiv_<?php echo $i; ?>'>
								<input type="text" id="unit_<?php echo $i; ?>" class=" form-control required"  readonly <?php echo $readonly . $read; ?> name="unit_<?php echo $i; ?>" value="<?php echo $row_demo['unit']; ?>"/>
							</div>
						</td>

						<?php if ($state == 27) { ?>
							<td style="width: 5%;">
								<input type="text" id="cgst_<?php echo $i; ?>" class=" form-control number"   readonly  name="cgst_<?php echo $i; ?>" value="<?php echo $row_demo['cgst']; ?>"/>
								</td>

								<td style="width: 5%;">
								<input type="text" id="sgst_<?php echo $i; ?>" class=" form-control number"  readonly   name="sgst_<?php echo $i; ?>" value="<?php echo $row_demo['sgst']; ?>"/>
							</td>
						<?php } else { ?>
							<td style="width: 5%;">
								<div id='igstdiv_<?php echo $i; ?>'>
									<input type="text" id="igst_<?php echo $i; ?>" class=" form-control required"  readonly <?php echo $readonly . $read; ?> name="igst_<?php echo $i; ?>" value="<?php echo $row_demo['igst']; ?>"/>
								</div>
							</td>
						<?php } ?>
														
						<td style="width:10%;">
							<?php if($_REQUEST['type'] != 'Direct_Sale') { ?>
								<input type="text" id="qty_<?php echo $i; ?>" class=" form-control number"    name="orderqty_<?php echo $i; ?>" readonly  value="<?php echo $remain_qty; ?>" onblur="getrate(this.id);" />
							<?php } else { ?>
								<input type="text" id="qty_<?php echo $i; ?>" class=" form-control number"   name="orderqty_<?php echo $i; ?>"  value="<?php echo $remain_qty; ?>" onblur="getrate(this.id);" />
							<?php } ?>
						</td>
					 
						<td style="width: 10%;">
							<input type="text" id="rate_<?php echo $i; ?>" class="number form-control" <?php if ($_REQUEST['pricetype'] != 'NotApplicable' || $_REQUEST['type'] != 'Direct_Sale') { ?>readonly<?php } ?> name="rate_<?php echo $i; ?>" value="<?php echo $row_demo['rate']; ?>" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i;?>);" />


							<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="" >
							<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="" >
							<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="" >
						</td>

						<?php if ($_REQUEST['type'] == 'Direct_Sale') { ?>
							<td style="width: 10%;text-align:center;">
								<div id="divbatch_<?php echo $i; ?>" style="text-align:center;">
									<?php if ($_REQUEST['PTask'] == 'update') {
										//if ($product['batch_maintainance'] == '1') { ?>
											<div id='divbatch_<?php echo $i; ?>'>
											<button type="button" class="btn btn-light btn-sm" onClick="check_qty(<?php echo $i; ?>)" ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
											</div>
										<?php //}
									} ?>
								</div>
							</td>
						<?php } else { ?>

							<td style="width: 10%;text-align:center;">
							<?php //if ($product['batch_maintainance'] == '1') { ?>
								<button type="button" onclick="getviewbatch('<?php echo $row_demo['product']; ?>','<?php echo $i; ?>');" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#saleinvoicebatch"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
							<?php //} ?>
							</td>
						<?php } ?>

						<td style="width: 10%;">
							<input type="text" id="disc_<?php echo $i; ?>" class="number form-control"  <?php if ($_REQUEST['pricetype'] != 'NotApplicable') { ?> readonly <?php } ?> name="disc_<?php echo $i; ?>" value="<?php echo $row_demo['disc']; ?>"/>
						</td>
											
						<td style="width: 10%;">
							<input type="text" id="taxable_<?php echo $i; ?>" class="number form-control"  readonly name="taxable_<?php echo $i; ?>" value="<?php echo $row_demo['taxable']; ?>"/>
						</td>
					  
						<!-- <td style="width: 10%;">
							<input type="text" id="total_<?php echo $i; ?>" class="number form-control" readonly  <?php echo $readonly; ?> name="total_<?php echo $i; ?>" value="<?php echo $total; ?>"/>
						</td> -->
					 
						<?php if ($_REQUEST['Task'] != 'view') { ?>
							<td style='width:2%'>
								<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
							</td>
							<?php if ($remain_qty > $totalstock) {
								$stock_chk++;
							} ?>
						<?php } ?>
					</tr>

										
					<script>
						Gettotal('product_<?php echo $i; ?>');
						Gettotal1('product_<?php echo $i; ?>');
						Getgst('product_<?php echo $i; ?>');
						Gettotgst('product_<?php echo $i; ?>');
						showgrandtotal(); 
					</script>
					<?php
						$total_quantity += $remain_qty;
					?>
				<?php } ?>
					
			</tbody>
			<tfoot>
				<tr>
					<td colspan="<?php echo $colspan; ?>" style="text-align:right;">Total Quantity</td>
					<td>
						<input type="text" id="total_quantity" class="number form-control" readonly name="total_quantity" value="<?php echo $total_quantity; ?>"/>
					</td>
					<td>
						<input type="hidden" id="cgsttot" name="cgsttot" value="" />
						<input type="hidden" id="sgsttot" name="sgsttot" value="" />
						<input type="hidden" id="igsttot" name="igsttot" value="" />
					</td>
					<td style="text-align:center;">Discount</td>
					<td style="text-align:center;">
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totdiscount" name="totdiscount" onkeyup="get_discamt();" value="<?php echo $purchase_order['totdiscount']; ?>" />
					</td>
					<td>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totaltaxable" name="totaltaxable" readonly  value="<?php echo $purchase_order['totaltaxable']; ?>" /></td>
				</tr>
			</tfoot>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php
					if ($_REQUEST['type'] == 'Direct_Sale') {
						if ($_REQUEST['PTask'] != 'view') { ?>			
							<button type="button" class="btn btn-warning" id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php }
					} ?> 
				</td>			
			</tr>
		</table>

		<div class="modal fade" style = "max-width=40%; " id="saleinvoicebatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="salesinvoicebatch">
					
				</div>
			</div>
		</div>

		<div class="modal fade" style = "max-width=40%; " id="saleinvoiceaddbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="salesinvoiceaddbatch">
					
				</div>
			</div>
		</div>



		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("sale_invoice_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceamt']; ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="">
				</td>
				<td style="width:9%;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore11" onclick="addRowdetail('dtable');">Add More</button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo $purchase_order['totserviceamt']; ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo $purchase_order['cgstamt']; ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo $purchase_order['sgstamt']; ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo $purchase_order['igstamt']; ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo $purchase_order['subtotgst']; ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo $purchase_order['grandtotal']; ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>



		<!-- -------------------------------------------------- -->
		<!-- <div class="container border border-light p-2 mb-2">
			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label " style="text-align:right;">Amount Before Tax</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="true" class=" form-control col-md-7 smallinput col-xs-12" readonly id="subt" style="width: 137PX;" name="subt" value="<?php echo $delivery_challan_no['subt']; ?>">
					</div>
				</div>
				<div class="col">
					<label for="first-name"  class="control-label " >Transp Cost</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="tran();showgrandtotal();" onBlur="tran();showgrandtotal();" id="transcost" value="<?php if (!empty($delivery_challan_no['transcost'])) {
								echo $delivery_challan_no['transcost'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="transcost"  >
					</div>
				</div>
				<div class="col">
					<label for="first-name"  class="control-label " >Transp GST (%)</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="tran();showgrandtotal();" onBlur="tran();showgrandtotal();" id="transgst" value="<?php if (!empty($delivery_challan_no['transgst'])) {
								echo $delivery_challan_no['transgst'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="transgst" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Transp GST Amount</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12" id="transamount" readonly style="width: 112px;" value="<?php if (!empty($delivery_challan_no['transamount'])) {
							echo $delivery_challan_no['transamount'];
						} else {
							echo '0';
						} ?>" name="transamount" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label" >Total Transportation</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12 number" readonly id="trans" style="width: 137px;" name="trans" value="<?php if (!empty($delivery_challan_no['trans'])) {
							echo $delivery_challan_no['trans'];
						} else {
							echo '0';
						} ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
			</div>

			<br>

			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label " >Total CGST </label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totcst_amt" style="width: 112px;" name="totcst_amt" value="<?php echo $delivery_challan_no['totcst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Total SGST</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totsgst_amt" style="width: 112px;" name="totsgst_amt" value="<?php echo $delivery_challan_no['totsgst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Total IGST</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
							<input type="text" readonly="readonly" class=" form-control col-md-7 smallinput col-xs-12" id="totigst_amt" style="width: 112px;" name="totigst_amt" value="<?php echo $delivery_challan_no['totigst_amt'] ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >TCS/TDS</label>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<select class="select2 form-select " data-placeholder="Select TCS/TDS "  style="width:112px" <?php echo $disabled; ?> name="tcs_tds" id="tcs_tds">
							<option></option>
							<option value="TCS" <?php if ($delivery_challan_no["tcs_tds"] == 'TCS')
								echo $select = 'selected';
							else
								$select = ''; ?>>TCS</option> 
							<option value="TDS" <?php if ($delivery_challan_no["tcs_tds"] == 'TDS')
								echo $select = 'selected';
							else
								$select = ''; ?>>TDS</option> 		
						</select>
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >TCS/TDS (%)</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12 number" onkeyup="showgrandtotal();" onBlur="tran();showgrandtotal();" id="tcs_tds_percen" value="<?php if (!empty($delivery_challan_no['tcs_tds_percen'])) {
								echo $delivery_challan_no['tcs_tds_percen'];
							} else {
								echo '0';
							} ?>" style="width: 112px;" name="tcs_tds_percen" >
					</div>
				</div>
			</div>

			<br>

			<div class="row">
				<div class="col">
					<label for="first-name" class="control-label " >TCS TDS Amount</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12" id="tcs_tds_amt" readonly style="width: 112px;" value="<?php if (!empty($delivery_challan_no['tcs_tds_amt'])) {
							echo $delivery_challan_no['tcs_tds_amt'];
						} else {
							echo '0';
						} ?>" name="tcs_tds_amt" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >TCS TDS Amount</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" class=" form-control col-md-7 smallinput col-xs-12" id="tcs_tds_amt" readonly style="width: 112px;" value="<?php if (!empty($delivery_challan_no['tcs_tds_amt'])) {
							echo $delivery_challan_no['tcs_tds_amt'];
						} else {
							echo '0';
						} ?>" name="tcs_tds_amt" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" >
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Round-OFF</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" <?php echo $readonly; ?> class=" form-control col-md-7 smallinput col-xs-12" id="roff" style="width: 112px;" name="roff" value="<?php if (!empty($delivery_challan_no['roff'])) {
							echo $delivery_challan_no['roff'];
						} else {
							echo '0';
						} ?>" onkeyup="showgrandtotal();" onBlur="showgrandtotal();">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label ">Narration</label>
					<div class="col-md-6 col-sm-4 col-xs-12">
						<textarea type="text" <?php echo $readonly; ?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 93%; float:right;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $delivery_challan_no['otrnar']; ?></textarea>
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Discount %</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text"  class=" form-control col-md-7 smallinput col-xs-12"  id="mdiscount" onkeyup="showgrandtotal1();" onBlur="showgrandtotal1();" style="width: 137PX;" name="mdiscount" value="<?php echo $delivery_challan_no['discount']; ?>">
					</div>
				</div>
				<div class="col">
					<label for="first-name" class="control-label " >Grand Total</label>
					<div class="col-md-2 col-sm-4 col-xs-12">
						<input type="text" readonly="true" class=" form-control col-md-7 smallinput col-xs-12" readonly id="grandtotal" style="width: 137PX;" name="grandtotal" value="<?php if (!empty($delivery_challan_no['grandtotal'])) {
							echo $delivery_challan_no['grandtotal'];
						} else {
							echo '0';
						} ?>">
					</div>
				</div>
		
			</div>
		</div> -->
		<!-- -------------------------------------------------- -->	
		
		
		<script>

			function get_saleledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_saleledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

		</script>

	<?php
	break;
	// =================================== USE IN-delivery challan (1) ===================================
	case'get_saleorder':
		$delivery_challan_no=$utilObj->getSingleRow("delivery_challan"," id='".$_REQUEST['id']."' ");
	?>
		<label class="form-label"> Sale Order No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div id="saleorder_div">
		<?php if($_REQUEST['PTask']=='view' ) {
			$readonly="readonly";
			$requisition=$utilObj->getSingleRow("sale_order"," id in (select saleorder_no from  delivery_challan where id='".$_REQUEST['id']."')");
		?>
			<input type="hidden" id="saleorder_no" <?php echo $readonly;?> name="saleorder_no" value="<?php echo $requisition['id'];?>"/>

			<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $requisition['order_no'];?>"/>
						
		<?php } else { ?>

			<select id="saleorder_no" name="saleorder_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange=" saleorder_rowtable();">
				<option value=""> Select SaleOrder No</option>
				<?php
					// $record=$utilObj->getMultipleRow("sale_order","id not in (select  saleorder_no  from  delivery_challan where purchaseorder_no!='".$GRN_no['purchaseorder_no']."') group by order_no");

					$record=$utilObj->getMultipleRow("sale_order","customer ='".$_REQUEST['customer']."'group by order_no");
					foreach($record as $e_rec){
						// $qty=$utilObj->getSum("delivery_challan_details","parent_id in(select id from delivery_challan where saleorder_no='".$delivery_challan_no['saleorder_no']."')AND product='".$e_rec["id"]."'","qty");	
						// $remain_qty=$row_demo['qty']-$qty;
						// if($remain_qty!=0)
						// {
						if($delivery_challan_no['saleorder_no']==$e_rec["id"]) echo $select='selected'; else $select='';
						echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["order_no"].'</option>';
						// }
					}
				?> 
			</select>
		<?php } ?>
		</div>

	<?php
	break;
	// ============================= USE IN - delivery challan(2) =============================
 
	case 'saleorder_rowtable':
		/* $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['customer']."' ");
			$state= $account_ledger['mail_state']; */
		$saleorder_no = $utilObj->getSingleRow("delivery_challan", "id ='" . $_REQUEST['id'] . "'");
		if ($_REQUEST['PTask'] == 'view') {
			$readonly = "readonly";
			$disabled = "disabled";
		} else {
			$readonly = "";
			$disabled = "";
		}
	?>
		<input type="hidden" id="PTask"  name="PTask" value="<?php echo $_REQUEST['PTask']; ?>"/>
	
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Rate</th>
					<th style="width:5%;text-align:center;">Batch <span class="required required_lbl" style="color:red;">*</span></th>

						<?php 
					if ($_REQUEST['Task'] != 'view') { ?>
							<th style="width:2%;text-align:center;"></th>
						<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = $qty = $total_quantity = $stock_chk = 0;

				if (($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view') && $_REQUEST['saleorder_no'] == $saleorder_no['saleorder_no']) {
					//echo "condi 1";
					$record5 = $utilObj->getMultipleRow("delivery_challan_details", "parent_id='" . $_REQUEST['id'] . "'");
				} else if (($_REQUEST['saleorder_no'] != '' && $_REQUEST['PTask'] == 'Add') || ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view')) {
					//echo "condi 2";
					$record5 = $utilObj->getMultipleRow("sale_order_details", "parent_id='" . $_REQUEST['saleorder_no'] . "'");
				} else {
					$record5[0]['id'] = 1;
				}

				foreach ($record5 as $row_demo) {

				$productbatch = $utilObj->getSingleRow("stock_ledger","id = '".$row_demo['product']."'");

				$i++;
				$totalstock = 0;
				if (($_REQUEST['saleorder_no'] != '' && $_REQUEST['PTask'] == 'Add')) {
					// echo "kkkkk";
					$qty = $utilObj->getSum("delivery_challan_details", "parent_id in(select id from delivery_challan where saleorder_no='" . $_REQUEST['saleorder_no'] . "')AND product='" . $row_demo['product'] . "'", "qty");
					$remain_qty = $row_demo['qty'] - $qty;
					//echo ">>".$row_demo['product'];
					$saleorder_loc = $utilObj->getSingleRow("sale_order", "id ='" . $_REQUEST['saleorder_no'] . "'");
					$location = $saleorder_loc['location'];
				} else {
					$remain_qty = $row_demo['qty'];
					$saleorder_loc = $utilObj->getSingleRow("sale_order", " id in (select  saleorder_no from  delivery_challan  where id ='" . $row_demo['parent_id'] . "')");
					$location = $saleorder_loc['location'];
				}

			?>
				<tr id='row_<?php echo $i; ?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i; ?>"   name="idd_<?php echo $i; ?>"><?php echo $i; ?> </label>
					</td>

					<td  style="width: 20%;">
					<?php
						$product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
						if ($_REQUEST['PTask'] != '') { 
					?>
						<input type="hidden" id="product_<?php echo $i; ?>"  name="product_<?php echo $i; ?>" value="<?php echo $product['id']; ?>"/>
						<input type="text"   style="width:100%;" class=" form-control"  readonly <?php echo $readonly . $read; ?>  value="<?php echo $product['name']; ?>"/>
						<?php } ?>
					</td>

					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i; ?>'>
							<input type="text" id="unit_<?php echo $i; ?>" class=" form-control required"  readonly <?php echo $readonly . $read; ?> name="unit_<?php echo $i; ?>" value="<?php echo $row_demo['unit']; ?>"/>
						</div>
					</td>
					
					
				 
					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i; ?>" class=" form-control number"  onkeyup="get_totalqty();stock_check();" onchange="get_totalqty();stock_check();" <?php echo $readonly . $read; ?> name="qty_<?php echo $i; ?>" value="<?php echo $remain_qty; ?>"/>
					</td>

					<td style="width: 10%;">
						<input type="text" id="rate_<?php echo $i; ?>" class=" form-control number"  onkeyup="" onchange="" <?php echo $readonly . $read; ?> name="rate_<?php echo $i; ?>" value="<?php echo $row_demo['rate']; ?>"/>
					</td>
										 
					<?php 
						//if ($productbatch['batch_maintainance'] == '1') {
					?>
						<td style="width: 10%;text-align:center;"><div id='divbatch_<?php echo $i; ?>'>
							<button type="button" class="btn btn-light" onClick="check_qty(<?php echo $i; ?>)"><?php if($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask']=='view'){?> <i class="fas fa-box fa-lg" style="color: #000000;"></i> <?php } else { ?><i class="fas fa-box fa-lg" style="color: #000000;"></i><?php } ?></button>
							</div>
						</td>
					<?php //} else { ?>
						<!-- <td></td> -->
					<?php //} ?>
					<?php if ($_REQUEST['Task'] != 'view') { ?>
						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
				
					<?php
						//chk qty is smaller than stock_chki
						if ($row_demo['qty'] > $totalstock) {
							$stock_chk++;
						}
					} ?>
				</tr>
				<?php $total_quantity += $remain_qty; ?>
				<?php } ?>
				
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" style="text-align:right;"> Total Quantity </td>
					<td>
						<input type="text" id="total_quantity" class="number form-control" readonly name="total_quantity" value="<?php echo $total_quantity; ?>"/>
					</td>
					<td> </td>
				</tr>
			</tfoot>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php
					/* if(($_REQUEST['PTask']!='view' )){?>			
						<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } */?>
				</td>			
			</tr>
		</table> 
			
		<div class="row text-center" >
			<div id="submit_div" style="margin-bottom:10px;text-align:right;" class="col-md-6">
			<?php //echo "stock chk=".$stock_chk;
				if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'Add') { ?>	
					<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
			<?php } ?>
			</div>

			<?php 
				if($_REQUEST['PTask']=='view') {
			?>	
				<?php if((CheckEditMenu())==1) {  ?>
				<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
						<a href="delivery_challan_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
				</button>
				<?php } ?>
			<?php } ?>

			<div class="col-md-6" style="text-align:left;">
				<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
			</div>
		</div>
		<div class="modal fade" style = "max-width=40%; " id="salebatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="salesbatch">
			
				</div>
			</div>
		</div>

	<?php
	break;
	// ================================= USE IN -sale order(1) =================================
 
	case 'get_materialtable': 
  		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['customer']."' ");

		// $state= $account_ledger['mail_state'];
		$state= $_REQUEST['customer'];

		if( $state==27){
			$colspan=8;
		} else {
			$colspan=7;
		}
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
		if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
			$id=$_REQUEST['id'];
			$rows=$utilObj->getSingleRow("sale_order","id ='".$id."'");
			$sale_order=$rows['order_no'];	
			$date=date('d-m-Y',strtotime($rows['date']));
			$grandtotal=$rows['grandtotal'];
			
			
		} else{
			$rows=null;
		}
	?>
		<input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%; text-align:center;">Ledger</th>
					<th style="width: 10%;text-align:center;">Unit </th>
						<?php if( $state==27){?>
					<th style="width: 5%;text-align:center;">CGST </th>
					<th style="width: 5%;text-align:center;">SGST </th>
						<?php }else{?>
					<th style="width: 5%;text-align:center;">IGST </th>
						<?php }?>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Rate <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th>
					 <?php if($_REQUEST['Task']!='view'){?>
					<th style="width:2%;text-align:center;"></th>
					 <?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view')
				{ 
					//echo "condi 1";
						$record5=$utilObj->getMultipleRow("sale_order_details","parent_id='".$_REQUEST['id']."'");
				}
					else
				{ 
					$record5[0]['id']=1;					
				}
				foreach($record5 as $row_demo)
				{ 
				$i++;
				// echo ">>".$row_demo['product'];
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
							<label  id="idd_<?php echo $i;?>"   name="idd_<?php echo $i;?>"><?php echo $i;?> </label>
					</td>
					<td  style="width: 20%;">
					<?php 

                        $product=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");
						if($_REQUEST['PTask']=='view') { 
					?>

						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>"/>
					<?php }else { ?>

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_saleledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);" style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
						</select>
					<?php } ?>
					</td>

					<td style="width:10%;">
						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
						<?php

						if( $_REQUEST['PTask']=='view' || $_REQUEST['PTask']=='update') {

							echo '<option value="">Select Ledger</option>';
							$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=28 group by name");
							foreach($record as $e_rec){
								if($row_demo['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
							}
						} else {

							$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");

							$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=28 group by name");

							echo '<option value="">Select Ledger</option>';
							foreach($record as $e_rec)
							{	
								if($state==27) {
									if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								} else {
									if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
								
							}
						} ?>
						</select>
					</td>

					<td style="width:10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>

					<?php if( $state==27) { ?>

						<td style="width: 5%;">
							<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> name="cgst_<?php echo $i;?>" value="<?php echo $row_demo['cgst'];?>"/>
						</td>
						
						<td style="width: 5%;">
							<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> name="sgst_<?php echo $i;?>" value="<?php echo $row_demo['sgst'];?>"/>
						</td>
					<?php } else { ?>

						<td style="width: 5%;">
							<input type="text" id="igst_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> name="igst_<?php echo $i;?>" value="<?php echo $row_demo['igst'];?>"/>
						</td>
					<?php } ?>

					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly.$read;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
					</td>
					 
					<td style="width: 10%;">
						<input type="text" id="rate_<?php echo $i;?>" class="number form-control" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i; ?>);" <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>"/>

						<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="" >
						<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="" >
						<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="" >
					</td>
					
					<td style="width: 10%;">
						<input type="text" id="taxable_<?php echo $i;?>" class="number form-control" readonly  <?php echo $readonly;?> name="taxable_<?php echo $i;?>" value="<?php echo $row_demo['taxable'];?>"/>
					</td>

					<?php if($_REQUEST['Task']!='view') { ?>

						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
			<?php } ?>
					
			</tbody>
			<tfoot>
				<tr>
					<td colspan="<?php echo $colspan; ?>" style="text-align:right;">
						Grandtotal

						<input type="hidden" id="cgsttot" name="cgsttot" value="" />
						<input type="hidden" id="sgsttot" name="sgsttot" value="" />
						<input type="hidden" id="igsttot" name="igsttot" value="" />
					</td>
					<td>
						<input type="text" id="totaltaxable" class="number form-control" readonly name="totaltaxable" value="<?php echo $rows['totaltaxable'];?>"/>
					</td>
					<td>
					</td>
				</tr>
			</tfoot>
			<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
		</table>
		<table style="width:100%;" class="taxtbl">
			<tr style="margin:10px;text-align:right;">
				<td>
					<?php if(($_REQUEST['PTask']!='view' )) { ?>			
						<button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					<?php } ?> 
				</td>			
			</tr>
		</table> 

		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;

				$purchase_order=$utilObj->getSingleRow("sale_order","id ='".$id."'");

				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("sale_order_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="">
				</td>
				<td style="width:9%;text-align:center;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-light btn-sm" id="addmore11" onclick="addRowdetail('dtable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>

		<!-- ------------------------------------------------------------- -->

		<script>

			function get_saleledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_saleledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

			var count = $("#cnt").val();

			for(var i=1;i<=count;i++) {

				$("#product_"+i).select2({
					dropdownParent: $('#table_div')
				});

				$("#ledger_"+i).select2({
					dropdownParent: $('#table_div')
				});

			}

		</script>
	
	<?php
	break;
	
	// ================================= USE IN=purchase payment == (1) =================================
	case 'purchasetable':

		if( $_REQUEST['PTask']=='view') {

			$disabled="disabled";
		} else {

			$disabled="";
		}
		
		if($_REQUEST['PTask']=='update'|| $_REQUEST['PTask']=='view'){
			//echo $_REQUEST['id'];
			$purchase_payment=$utilObj->getSingleRow("purchase_payment","id = '".$_REQUEST['id']."' group by supplier ");
			//var_dump($purchase_invoice);
		}
		// /* echo ">>". $_REQUEST['cust'];
		// echo ">>". $$purchase_invoice['supplier']; */
		// echo "hiii";

		//$i=0;

		//$ro =$utilObj->getCount("purchase_invoice","supplier = '".$_REQUEST['cust']."' ");

		//if($ro>0){
			//$i++;
	?>
		<table id="myTable" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 10%;"> Bill No. </th>
					<th style="width: 4%;"> Invoice Date. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php		
				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}

				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {
					// var_dump($info);
					$i++;
					if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )  {

						$invodate = $info['invodate'];

						$bill_adust=$utilObj->getSingleRow("bill_adjustment","id ='".$info['purchaseid']."' ");

						$purchase=$utilObj->getSum("bill_adjustment","id='".$info['id']."' ","amount");

						$remain = $bill_adust['total_amt'] - $purchase;
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['cust']."'
							// ) AS subquery");
		
							// while ($inward1 = mysqli_fetch_array($in, MYSQLI_ASSOC)) {
								
							// 	$inwarddata1[]=$inward1;
							// }
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo date('Y-m-d',strtotime($invodate)); ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> name="pendingamt_<?php echo $i; ?>" value="<?php echo $remain; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $purchase_payment['amt_pay']; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
								
		<?php //} ?>
	<?php
	break;

	// ================================= USE IN=purchase payment == (1) =================================
	case 'purchasetable1':
		if( $_REQUEST['PTask']=='view'){
			$disabled="disabled";
		}else{
			$disabled="";
		}
		if($_REQUEST['PTask']=='update'|| $_REQUEST['PTask']=='view'){
			//echo $_REQUEST['id'];
			$purchase_payment=$utilObj->getSingleRow("cash_payment","id = '".$_REQUEST['id']."' group by supplier ");
			//var_dump($purchase_invoice);
		}
		// /* echo ">>". $_REQUEST['cust'];
		// echo ">>". $$purchase_invoice['supplier']; */
		// echo "hiii";
		$i=0;
		// echo " PurchaseFrom = '".$_REQUEST['cust']."'  ";
		$ro =$utilObj->getCount("purchase_invoice","supplier = '".$_REQUEST['cust']."' ");

		if($ro>0) {
			$i++;
	?>
		<table id="datatable-buttons" style="background:#e8e9ed;" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 2%;"><!--<input type='checkbox' value='0'  class='group-checkable' id='select_all' data-set='#datatable-buttons.checkboxes' />-->SR.No </th>
					<th style="width: 5%;"> Invoice Issued Date </th>
					<th style="width: 10%;"> Invoice No. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 10%;"> Amount </th> 
					<!-- <th style="width: 10%;"> Discount </th> -->
					<!-- <th style="width: 10%;"> Balance </th> -->
				</tr>
			</thead>
			<tbody>
			<?php		
				$i=0;
				$total=0;

				if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust']){
					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM cash_payment_details where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());
					// var_dump($rows);
				} else {
					// echo " con 2";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM purchase_invoice where supplier= '".$_REQUEST['cust']."'order by Created DESC ")or die(mysqli_error());
				}

				while($info=mysqli_fetch_array($rows))
				{
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						$purchase=$utilObj->getSum("cash_payment_details","purchaseid='".$info['purchaseid']."' AND id!='".$info['id']."'","amount");

						$purchasedisc=$utilObj->getSum("cash_payment_details","purchaseid='".$info['purchaseid']."' AND id!='".$info['id']."'","discount");

						$record=$utilObj->getSingleRow("purchase_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['purchaseid']."'"); 

						$payment=$utilObj->getSingleRow("cash_payment","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['parent_id']."'"); 
											
						if ($record['id']==$info['purchaseid'])
						{

							$checked = "checked";
						} else {
							
							$checked = "";
						}
						$totalchk=$total=$payment['amt_pay'];
						$remain= ($record['grandtotal']-$purchase) - $info['amount'] - $info['discount'];
											
					} else {

						$purchase=$utilObj->getSum("cash_payment_details","purchaseid='".$info['id']."'","amount");
						$purchasedisc=$utilObj->getSum("purchase_payment_details","purchaseid='".$info['id']."'","amount");
						$record=$utilObj->getSingleRow("purchase_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['id']."'"); 
						// $purchaseadvance=$utilObj->getSum("purchase_advance_used","purchaseid='".$info['ID']."'","amount");
						$totalchk +=$record['grandtotal']-$purchase-$purchasedisc;//-$purchaseadvance;	//For showing No data available in table

					}
					if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					{
			?>
				<tr class='even'>
					<td style='width:2%' class='controls'>
						<?php echo $i; ?>&nbsp;&nbsp;
						<input type='checkbox' id='checkbox<?php echo $i;?>' class='checkboxes' $dasable $dasable1 name='check_list' onchange='gettotaltable();getinputbox(<?php echo $i;?>,<?php echo $record['grandtotal']-$purchase-$purchasedisc; ?>);getinputvalues(<?php echo $i;?>,<?php echo $record['grandtotal']-$purchase-$purchasedisc; ?>);' value='<?php echo $record['id']."#".$record['grandtotal'];?>'<?php echo $checked; ?> <?php echo $disabled; ?> />
					</td>
					<td style="">
						<?php echo date('d-m-Y',strtotime($record['date'])); ?>
					</td>

					<td>
						<input type='hidden' id='purchaseid<?php echo $i;?>' name='purchaseid<?php echo $i;?>' value='<?php echo $record['id']; ?>'>

						<?php echo $record['pur_invoice_code']; ?>
					</td>

					<td class="tdalign">
						<?php echo $record['grandtotal']; ?>
					</td>

					<td class="tdalign">
						<?php echo ($record['grandtotal']-$purchase-$purchasedisc-$purchaseadvance); ?>
					</td>

					<?php
					// echo "<td class='controls'>".$record['invoicenumber'] . "</a> </td>";
					// echo "<td >". ($record['grandtotal']-$purchase-$purchasedisc-$purchaseadvance) . "</td>"; 
					?>
									
					<td id='checkboxshow<?php echo $i;?>' >
					<?php
						if($_REQUEST['PTask']=='update') {
					?>
						<input type="text" class="form-control tdalign" value="<?php echo $info['amount'];?>" name="bank<?php echo $i;?>" id="bank<?php echo $i;?>" onkeyup="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$purchase);?>);" onblur="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$purchase); ?>);"  <?php echo $disabled;?> />
					<?php } ?>
					</td>
							
					<!-- <td id='discount<?php echo $i;?>' >
					<?php
						if($_REQUEST['PTask']=='update') {
					?>
						<input type="text" class="form-control " value="<?php echo $info['discount'];?>" name="bank1<?php echo $i;?>" id="bank1<?php echo $i;?>" onkeyup="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$purchase);?>);" onblur="gettotaltable();getinputvalues(<?php echo $i;?>,<?php echo ($record['grandtotal']-$purchase); ?>);"  <?php echo $disabled;?> />
					<?php } ?>
					</td> -->
							
					<?php  ?>

					<!-- <td id ='checkboxvalue<?php echo $i;?>'>
						<input type="text" class="form-control" value="<?php echo $remain;?>" readonly>
					</td>  	 -->
				</tr>
				<?php
						}
					}

					if($totalchk==0){
						echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					}
				?>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style='text-align: center;padding-top: 15px;'>Total</td>
						<td>
							<input type="text" class="tdalign form-control" value="<?php echo $total; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
						</td>
						<!--td><input type="text" value="" id="totalvalue" name="totalvalue" disabled style="height: 35px;padding-left: 12px;width:100%;"></td-->
					</tr>
				</tfoot>
				<input type='hidden' name="cnt" id="cnt" value="<?php echo $i; ?>">
			</tbody>
		</table>
								
		<?php } ?>
	<?php
	break;

	// =========================== (2) ===========================
	case 'cashmethod':
	
	?>
		<select id="bankid" name="bankid" class="required" onchange="getBalance();" data-placeholder="Select Account No." style="width:100%"  <?php echo $disabled;?> <?php echo $readonly; ?> >
			<option></option>		 
			<?php 
				
				if($_REQUEST['mode']=='cash') { 
					$cnd_bank='bank_type="CaSh IN Hand"'; 
				}
				else{ 
				    $cnd_bank='bank_type="Bank"';
				}				
				 
				$Account=$utilObj->getMultipleRow("accounts","$cnd_bank Order BY Branch ");				
				
				foreach($Account as $a_rec) {
					echo  '<option value="'.$a_rec["id"].'" '.$select.'>'.$a_rec["name"].' '.$a_rec["ACNo"].' </option>';
				}
			?>				
		</select>
		<script>$('#bankid').select2();</script>
	<?php
	break;

	// =================================== USE IN = purchase Invoice (1) ===================================
	case 'get_purchaseorderno_invoice':
		
		$state = $_REQUEST['supplier'];
		$common_id = $_REQUEST['ad'];
	    $purchase_invoice=$utilObj->getSingleRow("purchase_invoice"," id='".$_REQUEST['id']."'");
	?>

		<input type="hidden" name="cnti" id="cnti" value="<?php echo $_REQUEST['i']; ?>">
		<label class="form-label"> GRN No. <span class="required required_lbl" style="color:red;">*</span></label>
		
		<div id="purchaseorder_div">
			<?php if($_REQUEST['PTask']=='view' ) {
				$readonly="readonly";
			
				$requisition=$utilObj->getSingleRow("grn"," id in (select purchaseorder_no from  purchase_invoice where id='".$_REQUEST['id']."')");
			?>
				<input type="hidden" id="purchaseorder_no" <?php echo $readonly;?> name="purchaseorder_no" value="<?php echo $requisition['id'];?>"/>
				<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly;?>  value="<?php echo $requisition['grn_no'];?>"/>
				
			<?php } else { ?>

				<select id="purchaseorder_no" name="purchaseorder_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="purchaseorder_rowtable_invoice(this.value);">
				<option value="">Select GRN No</option>
			
				<?php 
					if($_REQUEST['PTask']=='update'){
						$cmd="id not in (select  purchaseorder_no from  purchase_invoice where purchaseorder_no!='".$purchase_invoice['purchaseorder_no']."'  )";
					}else{
						$cmd="1";
					}
					$record=$utilObj->getMultipleRow("grn","supplier='".$_REQUEST['supp']."' AND flag='0' group by grn_no");
					// id not in (select  purchaseorder_no from  grn where 1)
					foreach($record as $e_rec) {

						// $dqty=$utilObj->getSum("purchase_invoice_details","parent_id in(select id from purchase_invoice where purchaseorder_no='".$e_rec["id"]."') ","qty");	

						// $sqty=$utilObj->getSum("grn_details","parent_id in(select id from grn where id='".$e_rec["id"]."') ","qty");

						// $reqty = $sqty - $dqty;

						if($_REQUEST['PTask']!='update') {

							// if($reqty!=0) {

								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["grn_no"].'</option>';
							// }
						} else {

							if($purchase_invoice['purchaseorder_no']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["grn_no"].'</option>';
						}
					}
				?>
			</select>
				
			<?php } ?>
		</div>
		
		<script>

			function get_ledger(this_id,state) {

				var id = this_id.split("_");
				id = id[1];
				var i = $("#cnti").cal();
				var pid = $("#product_"+i).val();

				alert("huiod");

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_ledger',id:id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+i).html(data);
					}
				});
			}
		</script>

	<?php
	break; 

	// =============================================== (2) ===============================================
	case 'purchaseorder_rowtable_invoice':
		
	 	$purchaseorder_no=$_REQUEST['purchaseorder_no'];
		$grn_id=$_REQUEST['grn_id'];
		$common_id = $_REQUEST['ad'];

		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		$state= $_REQUEST['supplier'];
		$purchase_invoice=$utilObj->getSingleRow("purchase_invoice"," id='".$_REQUEST['id']."' ");
		$purchase_order = $utilObj->getSingleRow("purchase_invoice"," id='".$_REQUEST['id']."'");
	
		if($purchaseorder_no!='') {

			$read="readonly";
		} else {
			
			$read="";
		}
				
	?>
		<table class="table table-bordered " id="myTable" > 
			<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
			<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
			<thead>
				<tr>
					<th style="width:1%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style = "width:10%;">Ledger</th>
					<th style="width: 5%;text-align:center;">Unit </th>
				<?php if( $state==27) { ?>
					<th style="width: 3%;text-align:center;">CGST </th>
					<th style="width: 3%;text-align:center;">SGST </th>
				<?php } else { ?>
					<th style="width: 3%;text-align:center;">IGST </th>
				<?php } ?>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:8%;text-align:center;">Rate <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 4%;text-align:center;">Batch</th>
					<th id="unitth" style='width:5%;text-align:center;'>Disc (%)</th>
					<th id="totalth" style="width: 10%;text-align:center;">Taxable Amount</th>
					<!-- <th style="width:10%;text-align:center;">Total <span class="required required_lbl" style="color:red;">*</span></th> -->
				<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Purchaseorder') { ?>
					<th style="width:1%;text-align:center;"></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;

				if($_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['purchaseorder_no']!=''&&$purchase_invoice['purchaseorder_no']==$purchaseorder_no) {

					// echo "condi 1";
					$record5=$utilObj->getMultipleRow("purchase_invoice_details","parent_id='".$_REQUEST['id']."'");
				} else if (($_REQUEST['purchaseorder_no']!='' || $_REQUEST['type']=='Against_Purchaseorder')&&$purchase_invoice['purchaseorder_no']!=$_REQUEST['purchaseorder_no']) {

					// echo "condi 2";
					$record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['grn_id']."'  ");
					$read="readonly";
				} else if ($_REQUEST['PTask']=='view'||$_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Direct_Purchase') {

					//echo "condi 3";
					$record5=$utilObj->getMultipleRow("purchase_invoice_details","parent_id='".$_REQUEST['id']."'");
						
					if($_REQUEST['PTask']=='view') {

						$readonly="readonly";
						$disabled="disabled";
						$read="";
					} else {

						$readonly=" ";
						$disabled="";
					}
		
				} else {

					$record5[0]['id']=1;					
				}  
				foreach($record5 as $row_demo) {

					if ($_REQUEST['PTask']=='update') {
	
						$rqty = $row_demo['qty'];
					} else {
	
						$dqty=$utilObj->getSum("purchase_invoice_details","parent_id in(select id from purchase_invoice where purchaseorder_no='".$_REQUEST['purchaseorder_no']."') AND product='".$row_demo['product']."' ","qty");
	
						$rqty = $row_demo['qty'] - $dqty;
					}

					if(!empty($rqty) || $_REQUEST['type']=='Direct_Purchase') {
					$i++;

					if($_REQUEST['type']=='Against_Purchaseorder') {

						$pro=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
						$gst=$utilObj->getSingleRow("gst_data"," id='".$pro['igst']."' OR id='".$pro['cgst']."' ");
	
						$igst = $gst['igst'];
						$cgst = $gst['cgst'];
						$sgst = $gst['sgst'];
	
						$taxable = $row_demo['qty']*$row_demo['rate'];
	
						$igstamt = ($taxable*$igst)/100;
						$cgstamt = ($taxable*$cgst)/100;
						$sgstamt = ($taxable*$sgst)/100;
	
						$tottaxable += $taxable;
	
						$totigstamt += $igstamt;
						$totcgstamt += $cgstamt;
						$totsgstamt += $sgstamt;
	
						$qty = $row_demo['qty'];
					}
					else if ($_REQUEST['PTask']=='update' && $_REQUEST['type']=='Against_Purchaseorder') {
	
						$igst = $row_demo['igst'];
						$cgst = $row_demo['cgst'];
						$sgst = $row_demo['sgst'];
	
						$taxable = $row_demo['taxable'];
						$tottaxable = $purchase_order['totaltaxable'];
	
						$qty = $row_demo['qty'];
					}
					else if ($_REQUEST['PTask']=='update') {
	
						$igst = $row_demo['igst'];
						$cgst = $row_demo['cgst'];
						$sgst = $row_demo['sgst'];
	
						$taxable = $row_demo['taxable'];
						$tottaxable = $purchase_order['totaltaxable'];
	
						$qty = $row_demo['qty'];
					}
				
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:1%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 15%;">
					<?php 

						$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");

						if($_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
					?>

						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

						<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>"/>

					<?php } else { ?>

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);check_batch_invoice(this.id);get_ledger(this.id,<?php echo $state; ?>);get_gstdata(this.id);" style="width:100%;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
						</select>

					<?php } ?>
					</td>

					<td>
						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
						<?php

							if( $_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
								// echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory='1' group by name");
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");
								
								foreach($record as $e_rec){
									if($rows['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}

							} else {
								$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");

								$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory='1' group by name");

								echo '<option value="">Select Ledger</option>';
								foreach($record as $e_rec)
								{	
									if($state==27) {
										if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									} else {
										if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								}
							}
						?> 
						</select>
					</td>
					
					<td style="width: 5%;">
						<div id='unitdiv_<?php echo $i; ?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					
				<?php if( $state==27) { ?>
					<td style="width: 3%;">
						<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> name="cgst_<?php echo $i;?>" value="<?php echo $cgst; ?>"/>
					</td>
					
					<td style="width: 3%;">
						<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> name="sgst_<?php echo $i;?>" value="<?php echo $sgst; ?>"/>
					</td>
				<?php } else { ?>
					
					<td style="width: 3%;">
						<input type="text" id="igst_<?php echo $i;?>" class=" form-control number"   <?php echo $readonly;?> name="igst_<?php echo $i; ?>" value="<?php echo $igst; ?>"/>
					</td>
				<?php } ?>
				
					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $rqty; ?>"/>
					</td>

					<?php $mate1=$utilObj->getSingleRow("grn","id='".$row_demo['parent_id']."'");

					if($mate1['type']=='Against_Purchaseorder') { ?>

					<?php
						$rate=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$mate1['purchaseorder_no']."'");
						foreach($rate as $rate1) {
					?>
						<td style="width: 8%;">
							<input type="text" id="rate_<?php echo $i;?>" class="number form-control"  onKeyUp="getrowgst(this.id);gettotgst(<?php echo $i;?>);" <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate']; ?>" />

							<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="<?php echo $igstamt; ?>" >
							<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="<?php echo $cgstamt; ?>" >
							<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="<?php echo $sgstamt; ?>" >
						</td>
						<?php } ?>
					<?php } else { ?>
						<td style="width: 8%;">
							<input type="text" id="rate_<?php echo $i;?>" class="number form-control tdalign"  onKeyUp="getrowgst(this.id);gettotgst(<?php echo $i;?>);"   <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate']; ?>" />
							
							<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="<?php echo $igstamt; ?>" >
							<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="<?php echo $cgstamt; ?>" >
							<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="<?php echo $sgstamt; ?>" >

							<input type="hidden" name="res_<?php echo $i; ?>" id="res_<?php echo $i;?>" value="" >
						</td>
					<?php } ?>

					<td style="width: 4%; text-align:center;">
					<?php
						if ($_REQUEST['type']=='Against_Purchaseorder' ) { ?>
							<!-- ----------------------------- View Batch ----------------------------->
							<?php if($_REQUEST['PTask']=='update') { ?>
								<div id='batch1_<?php echo $i;?>' class="d-flex justify-content-center align-items-center" >
									<button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#purinvoicebatch1" onclick="open_view_batch('<?php echo $row_demo['product']; ?>','<?php echo $i; ?>','<?php echo $purchaseorder_no; ?>')"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
								</div>
							<?php } else { ?>
								<div id='batch1_<?php echo $i;?>' class="d-flex justify-content-center align-items-center" >
									<button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#purinvoicebatch1" onclick="open_view_batch('<?php echo $row_demo['product']; ?>','<?php echo $i; ?>','<?php echo $purchaseorder_no; ?>')"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
								</div>
							<?php } ?>
							<!----------------------------- Modal ----------------------------->
						<?php } else { ?>
							<?php if($_REQUEST['PTask']=='update') {
							?>
								<button type="button" class="btn btn-light btn-sm" onclick="purino_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#purinvoicebatch2"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
							<?php } else { ?> 
								<!-- ------------------------ Add Batch ------------------------ -->
								<div id='batch2_<?php echo $i;?>' >

								</div>
							<?php } ?>
							<!-- ------------------------------------------------------------------ -->
						<?php } ?>
					</td>

					<td style='width:5%'>
						<input type="text" style="width:100%;" class=" form-control qty smallinput number" id="disc_<?php echo $i;?>" <?php echo $readonly;?> name="disc_<?php echo $i;?>" onKeyUp="getrowgst(this.id);gettotgst(<?php echo $i;?>);" value="<?php if($row_demo['disc']>0){ echo $row_demo['disc']; } else { echo 0; } ?>" />
					</td>         

					<td style='width:10%'>
						<input type="text" style="width: 100%;" class="tdalign form-control tax smallinput number" id="taxable_<?php echo $i;?>" <?php echo $readonly;?> name="taxable_<?php echo $i;?>" readonly="readonly" value="<?php echo number_format($taxable,2); ?>"  />
					</td>

					<!-- <td style="width: 10%;">
						<input type="text" id="total_<?php echo $i;?>" class="number form-control"  onchange="Getgst(this.id);showgrandtotal();" onkeyup="showgrandtotal();" onBlur="showgrandtotal();" <?php echo $readonly;?> name="total_<?php echo $i;?>" value="<?php echo $row_demo['total'];?>"/>
					</td> -->
					
					<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Purchaseorder'){ ?>
						<td style='width:1%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>
				</tr>
				<script>
					Gettotal('product_<?php echo $i;?>');
					Gettotal1('product_<?php echo $i;?>');
					Getgst('product_<?php echo $i;?>');
					Gettotgst('product_<?php echo $i;?>');
					showgrandtotal();
				</script>
			<?php } ?>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if($state==27) { ?>
						<td>
							<input type="hidden" id="cgsttot" name="cgsttot" value="<?php echo $totcgstamt; ?>" />
						</td>
						<td style="text-align:center;">
							<input type="hidden" id="sgsttot" name="sgsttot" value="<?php echo $totsgstamt; ?>" />
							<?php
								if($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view') {
							?>			
							<!-- <button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button> -->
						<?php } ?> 
						</td>
					<?php } else { ?>
						<td style="text-align:center;">
							<input type="hidden" id="igsttot" name="igsttot" value="<?php echo $totigstamt; ?>" />
							<?php
								if($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view') {
							?>
								<!-- <button type="button" class="btn btn-light" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button> -->
							<?php } ?> 
						</td>
					<?php } ?>
					<td style="text-align:center;"></td>
					<!-- <td style="text-align:center;">
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totdiscount" name="totdiscount" onkeyup="get_discamt();" value="<?php echo $purchase_order['totdiscount']; ?>" />
					</td> -->
					<?php if($_REQUEST['type']!='Against_Purchaseorder') { ?>
						<td style='width:1%;text-align:center;'>
							<button type="button" class="btn btn-light btn-sm" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
						</td>
					<?php } ?>
					<td>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totaltaxable" name="totaltaxable" readonly  value="<?php echo number_format($tottaxable,2); ?>" />
					</td>
					<?php if($_REQUEST['type']!='Against_Purchaseorder') { ?>
						<td style='width:1%'>
							<!-- <button type="button" class="btn btn-light btn-sm" id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button> -->
						</td>
					<?php } ?>
				</tr>
			</tfoot>
		</table>
		
		<div class="modal fade" style = "max-width=40%;" id="purinvoicebatch1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="purinvoice1" >
					
				</div>
			</div>
		</div>

		<div class="modal fade" style = "max-width=40%;" id="purinvoicebatch2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="purinvoice2" >
					
				</div>
			</div>
		</div>
		
		<!-- <table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
				<?php 
					if(($_REQUEST['PTask']!='view' && $purchaseorder_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) {
				?>			
					<button type="button" class="btn btn-warning  " id="addmore" onclick="addRow('myTable');">Add More</button>
				<?php } ?> 
				</td>			
			</tr>
		</table> -->
		
		<?php

			if($state==27) {
				$cgst_ledger = $utilObj->getSingleRow("account_ledger", "group_name='16' AND gst_ledger_usage='Purchase' AND gst_type='CGST' ");

				$sgst_ledger = $utilObj->getSingleRow("account_ledger", "group_name='16' AND gst_ledger_usage='Purchase' AND gst_type='SGST' ");
			} else {
				$igst_ledger = $utilObj->getSingleRow("account_ledger", "group_name='16' AND gst_ledger_usage='Purchase' AND gst_type='IGST' ");
			}
			
		?>

		<!-- ------------------------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("purchase_invoice_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:2%;">
						<?php echo $j; ?>
					</td>
					<td style="width:13%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:5%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:5%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:5%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:5%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="" >
					</td>
					<td style="width:1%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>

		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="5"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="0">
				</td>
				<td style="width:9%;">
					<button type="button" class="btn btn-light" id="addmore11" onclick="addRowdetail('dtable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
				</td>
				<td style="width:11%;">
					<input type="text" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:2%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						GST Value
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Invoice Value
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>
		
		
		
		<script>

			function get_ledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_ledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

		
			function open_view_batch(product,i,pid) {
				var c_id = $("#ad").val();
				var PTask =$("#PTask").val();
				var location =$("#location").val();
				// alert(location);
				
				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'open_view_batch', product: product,PTask:PTask,c_id:c_id,location:location,pid:pid},
					success: function (data) {
						$('#purinvoice1').html(data);
						$('#purinvoicebatch1').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
				
				
			}

			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#myqty").val();
				// alert(main_qty);

				if (tot_qty == main_qty) {
					savedatabatch();
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("please add quantity in exsiting batch or add new batch.");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("please remove some quantity from exsiting batch.");
					}
				}
			}


			function total_qty(id) {
					
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;

				$("[id^='batqty1_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});

				console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);

			}

			function check_batch_invoice(id){
				
				var id=id.split("_");
				// alert(parent_id);
				var PTask = PTask;
				id=id[1];
				// alert(id);
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_invoice',id:id,product:product},
					success:function(data)
					{	
						//alert(data);
						$("#batch2_"+id).html(data);	
						$(this).next().focus();
					}
				});

			}

			function check_qty(i) {
				var quantity = $("#qty_"+i).val();

				// alert(quantity);
				if (quantity == '') {
					alert ('please enter quantity first . . . !');

				} else {
					// optn_batch_modal(i,quantity);
					purino_batchdata(i);
				}
			}
			
			function purino_batchdata(i) {
								                      
				var qty =$("#qty_"+i).val();
				var bat_rate =$("#rate_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				var id = $("#id").val();
				var location =$("#location").val();
				var product =$("#product_"+i).val();

				var productName = "Product name : ";
				var selectedOptionText = productName + $("#product_"+i + " option:selected").html();

				// var paragraphElement = document.getElementById("myParagraph");
				// paragraphElement.textContent = selectedOptionText;

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'purino_batchdata', product:product,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,selectedOptionText:selectedOptionText,bat_rate:bat_rate },
					success: function (data) {
						$('#purinvoice2').html(data);
						$('#purinvoicebatch2').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

		</script>

		<?php
	break;

	case 'purino_batchdata':
		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		// $location = $_REQUEST['location'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$qty = $_REQUEST['qty'];
		$bat_rate = $_REQUEST['bat_rate'];
		$maincnt = $_REQUEST['i'];
		$i = 0;
	?>

		<div id="purinvoice2">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Batch</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<p id="myParagraph">
						<?php echo $_REQUEST['selectedOptionText']; ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Received Quantity &nbsp;:&nbsp;<?php echo $qty; ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Product rate &nbsp;:&nbsp;<?php echo $bat_rate; ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
					</p>

					<input type="hidden" name="myqty" id="myqty" value="<?php echo $qty; ?>">

					<table class="table table-striped" id="mybatch">

						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="bat_rate" id="bat_rate" value="<?php echo $bat_rate; ?>">

						<thead>
							<tr>
								<th>Location</th>
								<th>Batch name</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($_REQUEST['PTask']=='update') {
								$purivodata = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."' AND type='purchase_invoice' ");
							} else {
								$purivodata = $utilObj->getMultipleRow("temp_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='purchase_invoice' ");
								if(empty($purivodata)) {
									$purivodata[0]['id']=1;
								}
							}

							foreach($purivodata as $pidata) {
								$i++;
								
								$tot_sum += $pidata['batqty'];

								if($_REQUEST['PTask']=='update') {
									$bat_qty=$pidata['batqty'];
								} else {
									$bat_qty=$pidata['quantity'];
								}
						?>
							<tr id="row1_<?php echo $i;?>">
								<td style="width:10%;">
									<select id="location_<?php echo $i;?>" name="location_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
									<?php 
										echo '<option value="">Select</option>';
										$record=$utilObj->getMultipleRow("location","1");
										foreach($record as $e_rec)
										{
											if($pidata['location']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
										}
									?>
									</select>
								</td>
								<td style="width:20%;">
									<input type="text" id="batchname1_<?php echo $i;?>" class=" form-control number" name="batchname1_<?php echo $i;?>" value="<?php echo $pidata['batchname']; ?>" />
								</td>
								<td style="width:10%;">
									<input type="text" id="batqty1_<?php echo $i;?>" class=" form-control number batqty2_<?php echo $i; ?>" name="batqty1_<?php echo $i;?>" value="<?php echo $bat_qty; ?>" onblur="total_qty(<?php echo $i; ?>)" />
								</td>
								<?php 
									if($i>1) {
								?>
									<td style='width:2%'>
										<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch('this.id');"></i>
									</td>
								<?php } ?>
							</tr>
							
						<?php } ?>
						<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i ;?>">
						</tbody>
						<td></td>
						<td></td>
						<td>
							Total Quantity : <input type="text" class="form-control number" name="tot_qty" id="tot_qty" value="<?php echo $tot_sum; ?>">
						</td>
					</table>
					<div class="col-md-2">
						<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRowbatch('mybatch');">Add More</button>
					</div>
				</div>
			</div>
			<div class="modal-footer" >
				<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();" />

				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>
			
			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#myqty").val();

				if (tot_qty == main_qty) {
					savedatabatch();
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Batch quantity doesn't match to Received Quantity");
					} else {
						alert("Batch quantity doesn't match to Received Quantity");
					}
				}

			}

			function savedatabatch()
			{
				var PTask = $("#PTask").val();
				var maincnt = $("#maincnt").val();
				var product = $("#product_batch").val();
				var bat_rate = $("#bat_rate").val();
				var cnt1 = $("#cnt1").val();
				
				var common_id = $("#common_id").val();
				var id = $("#id").val();

				var location_array=[];
				var batchname_array=[];
				var batqty_array=[];

				var res = 1;
				
				for(var i=1;i<=cnt1;i++)
				{	
					var location = $("#location_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batqty = $("#batqty1_"+i).val();	

					location_array.push(location);
					batchname_array.push(batchname);
					batqty_array.push(batqty);
				}

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'batchinvoice', product:product,common_id:common_id, batqty_array:batqty_array, batchname_array:batchname_array,cnt1:cnt1,PTask:PTask,id:id,location_array:location_array,bat_rate:bat_rate },
					success:function(data)
					{
						if(data!="")
						{	
							for (var i = 1; i <= cnt1; i++) {
								$('#location_' + i).val('');
								$('#batchname1_' + i).val('');
								$('#batqty1_' + i).val('');
							}
							$('#tot_qty').val('');

							$('#res_'+maincnt).val(res);

							$('#purinvoicebatch2').modal('hide');
						} else {
							alert('error in handler');
						}
					}
				});			 
			}

			var count = $("#cnt1").val();

			for(var i=1;i<=count;i++) {

				$("#location_"+i).select2({
					dropdownParent: $('#mybatch')
				});

			}

			function addRowbatch(tableID) {
				var count=$("#cnt1").val();	
				var state=$("#state").val();	

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row1_"+i+"'>";
				
				// cell1 += "<td style='width:10%;'><select name='location_"+i+"'  onchange='get_stock(this.id);get_qty(this.id);'  class='select2 form-select' id='location_"+i+"'>\

				cell1 += "<td style='width:10%;'><select name='location_"+i+"' class='select2 form-select' id='location_"+i+"'>\
				<option value=''>Select</option>\
					<?php
					$record=$utilObj->getMultipleRow("location","1");
					foreach($record as $e_rec){	
						echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
					}
							
					?>
				</select></td>";
				
				cell1 += "<td><input type='text' id='batchname1_"+i+"' class='form-control number' name='batchname1_"+i+"' value='' /></td>";
				
				cell1 += "<td><input type='text' id='batqty1_"+i+"' class='form-control number' name='batqty1_"+i+"' value='' onblur='total_qty("+i+")' /></td>";
			
				cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id)'></i></td>";


			
				$("#mybatch").append(cell1);
				$("#cnt1").val(i);
				// $("#particulars_"+i).select2();

				$("#location_"+i).select2({
					dropdownParent: $('#mybatch')
				});
			}

			function delete_row_batch(rwcnt)
			{
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt1").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row1_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row1_"+k).attr('id','row1_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#batchname1_"+k).attr('name','batchname1_'+newId);
							jQuery("#batchname1_"+k).attr('id','batchname1_'+newId);
							
							jQuery("#batqty1_"+k).attr('name','batqty1_'+newId);
							jQuery("#batqty1_"+k).attr('id','batqty1_'+newId);
							
							
							jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
							
						}
						jQuery("#cnt1").val(parseFloat(count-1)); 

						total_qty();

					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}
		</script>

	<?php
	break;
	
	// --------------------------------- NEW INVOICE ADD BATCH HANDLER ---------------------------------
	case 'batchinvoice':
		
		if($_REQUEST['PTask']=='update'){
			$common=$_REQUEST['id'];
		}else{
			$common=$_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='purchase_invoice' ");

		$typebatch="purchase_invoice";
		$cnt1=$_REQUEST['cnt1'];

		for($i=0;$i<$cnt1;$i++) {

			$arrValue=array('id'=>uniqid(),'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['bat_rate'],'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$typebatch,'batchname'=> $_REQUEST['batchname_array'][$i], 'quantity'=>$_REQUEST['batqty_array'][$i],'location'=>$_REQUEST['location_array'][$i] );
		
			$insertedId=$utilObj->insertRecord('temp_batch',$arrValue);

		}

		if($insertedId)
		echo $Msg='Record has been Added Sucessfully! ';

	break;
	// --------------------------------- VIEW INVOICE BATCH ---------------------------------
	case 'open_view_batch':
		// $i=$_REQUEST['i'];
		$i=$_REQUEST['i'];
	?>
		<div id="purinvoice1">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">View Batch</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				<div class="container">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Location</th>
								<th>Batch name</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$batchdetail = $utilObj->getMultipleRow("purchase_batch", "product='" .$_REQUEST['product']. "' AND type='grn' AND parent_id='" .$_REQUEST['pid']. "'" );

							foreach($batchdetail as $batchinfo) {
							$i++;
						?>
							<tr>
								<td>
									<?php 
										$loc=$utilObj->getSingleRow("location","id='".$batchinfo['location']."'");
									?>
									<input type="text" id="location_<?php echo $i;?>" class=" form-control number" name="location_<?php echo $i;?>" value="<?php echo $loc['name']; ?>" readonly />
								</td>
								<td>
									<input type="text" id="batchname1_<?php echo $i;?>" class=" form-control number" name="batchname1_<?php echo $i;?>" value="<?php echo $batchinfo['batchname']; ?>" readonly />
								</td>
								<td>
									<input type="text" id="batstock1_<?php echo $i;?>" class=" form-control number batstock1_<?php echo $i;?>" name="batstock1_<?php echo $i;?>" value="<?php echo $batchinfo['batqty']; ?>" readonly />
								</td>
							</tr>
						<?Php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
				<!-- <button type="button" class="btn btn-primary btn-sm">Save Form</button> -->
			</div>
		</div>
	
	<?php
	break;
	
	// ------------------------------------ ADD BATCH BUTTON ------------------------------------
	case 'check_batch_invoice':
	
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>
		<div id='batch2_<?php echo $i;?>'>
			<button type="button" class="btn btn-light btn-sm" onClick="check_qty(<?php echo $i;?>)" ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
		</div>

	<?php
	break;
	
	// ------------------------------------ Purchase Return BATCH ------------------------------------
	case 'check_batch_return':
	
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>
		<div id='batch2_<?php echo $i;?>'>
			<button type="button" class="btn btn-light" id="btn_<?php echo $i;?>" onClick="check_qty(this.id);" ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
		</div>

	<?php
	break;
	
	// ------------------------------------ Purchase Return BATCH ------------------------------------
	case 'batch_sale_return':
	
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>
		<div id='batch2_<?php echo $i;?>'>
			<button type="button" class="btn btn-light" id="btn_<?php echo $i;?>" onClick="check_qty(this.id);" ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
		</div>

	<?php
	break;
	
	// ================================= USE IN = GRN (1) =================================
	case 'get_purchaseorderno':

	    $GRN_no=$utilObj->getSingleRow("grn"," id='".$_REQUEST['id']."' ");
		$place = explode(",",$GRN_no['multipid']);

		$common_id = $_REQUEST['ad'];
	?>
		<label class="form-label"> Purchase Order No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div id="purchaseorder_div">
			<?php if($_REQUEST['PTask']=='view' ) {
				$readonly="readonly";
			
				$requisition=$utilObj->getSingleRow("purchase_order"," id in (select purchaseorder_no from GRN where id='".$_REQUEST['id']."')");
			?>
				<input type="hidden" id="purchaseorder_no" <?php echo $readonly;?> name="purchaseorder_no" value="<?php echo $requisition['id'];?>"/>
				<input type="text"   style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $requisition['order_no'];?>"/>
				
			<?php } else { ?>

				<select id="purchaseorder_no" name="purchaseorder_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange="getmultipid();" >
					<option value="">Select PurchseOrder No</option>
				<?php 
					// if($_REQUEST['PTask']!='update' ) {
					// 	$cmd='id in (select parent_id from purchase_order_details where rm_qty > 0)';
					// } else {
					// 	$cmd='1';
					// }
					
					// $record=$utilObj->getMultipleRow("purchase_order","$cmd AND supplier = '".$_REQUEST['supplier']."' ");

					if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {

						$record=$utilObj->getMultipleRow("purchase_order","supplier = '".$_REQUEST['supplier']."' ");
					} else {

						$record=$utilObj->getMultipleRow("purchase_order","supplier = '".$_REQUEST['supplier']."'  AND flag='0' ");
					}

					foreach($record as $e_rec) {

						if($_REQUEST['PTask']!='update') {

							$sum=$utilObj->getSum("grn_details","parent_id IN(select id from grn where purchaseorder_no = '".$e_rec["id"]."')","qty");

							$invoice=$utilObj->getSum("purchase_order_details","parent_id IN(select id from purchase_order where id = '".$e_rec["id"]."')","qty");

							$rqty = $invoice - $sum;
							// $invoice=$utilObj->getSingleRow("purchase_order_details","parent_id = '".$e_rec["id"]."' ");

							if($rqty!= 0) {
								// if($purchase_return['purchase_invoice_no']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo "hii";
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["order_no"].'</option>';
							}

						} else {

							if($GRN_no['purchaseorder_no']==$e_rec["id"]) echo $select='selected'; else $select='';

							// if(in_array($e_rec["id"],$place)) { $select='selected';} else $select='';
							echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["order_no"].'</option>';
						}

						// echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["order_no"].'</option>';
						// if($GRN_no['purchaseorder_no']==$e_rec["id"]) echo $select='selected'; else $select='';
						// echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["order_no"].'</option>';
					}
				?> 
			</select>
			
			<?php } ?>
		</div>
		<?php
	break;

	// ================================ GRN (2) ================================
	case 'purchaseorder_rowtable':

		$purchaseorder_no=$_REQUEST['purchaseorder_no'];
		$common_id = $_REQUEST['ad'];

		$mpids=explode(",",$_REQUEST['mpids']);

		/* $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		$state= $account_ledger['mail_state']; */
		$GRN=$utilObj->getSingleRow("grn"," id='".$_REQUEST['id']."'");

		$type = $_REQUEST['type'];
	 
		if($purchaseorder_no!='') {

			$read="readonly";
		} else {

			$read=" ";
		}
	?>

		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 5%;text-align:center;">Unit </th>
					<th style="width: 10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 8%;text-align:center;">Rate </th>
					<th style="width: 3%;text-align:center;" >Batch</th>
					<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Purchaseorder'){?>
						<th style="width:2%;text-align:center;"></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				foreach($mpids as $pdata) {

					if($_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['purchaseorder_no']!=''&& $GRN['multipid']==$pdata) { 

						// echo "condi 1";
						// $record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['id']."'");

						$record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['id']."' ");

					} else if(($_REQUEST['purchaseorder_no']!='' || $_REQUEST['type']=='Against_Purchaseorder')&&$GRN['multipid']!=$pdata) { 

						// echo "condi 2";
						// echo $_REQUEST['requisition_no'];
						// $record5=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$_REQUEST['purchaseorder_no']."' ");

						$record5=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$pdata."' ");

						$read="readonly";

					} else if($_REQUEST['PTask']=='view' || $_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Direct_Purchase') {

						$record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['id']."'");
						if($_REQUEST['PTask']=='view') {

							$readonly="readonly";
							$disabled="disabled";
							$read="";
						} else {

							$readonly="";
							$disabled="";
						}
					} else {
						
						$record5[0]['id']=1;	
					}

					foreach($record5 as $row_demo)
					{ 
						
						$batch_check=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."' ");

						if (($_REQUEST['purchaseorder_no'] != '' && $_REQUEST['PTask'] == 'Add')) {
							// echo "kkkkk";
							$qty = $utilObj->getSum("grn_details", "parent_id in(select id from grn where purchaseorder_no='" . $_REQUEST['purchaseorder_no'] . "')AND product='" . $row_demo['product'] . "' ", "qty");
							$remain_qty = $row_demo['qty'] - $qty;
							// echo ">>".$row_demo['product'];
						} else {

							$remain_qty = $row_demo['qty'];
						}

						// echo $row_demo['qty'];
						if(!empty($remain_qty) || $_REQUEST['type']=='Direct_Purchase') {
							$i++;
						
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td style="width:15%;">
					<?php 

                        $product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");

						if($_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='')) { ?>
						
							<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

							<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>" />

					<?php } else { ?>

						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);check_batch_grn(this.id);" style="width:210px;">	
						<?php 
							echo '<option value="">Select</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 ");
							foreach($record as $e_rec)
							{
								if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?> 
						</select>
					<?php } ?>
					</td>

					<td style="width: 5%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit']; ?>"/>
						</div>
					</td>

					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i;?>" class="form-control number tdalign" <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $remain_qty; ?>"/>
					</td>

					<td style="width: 8%;">
						<input type="text" id="rate_<?php echo $i;?>" class=" form-control required tdalign" name="rate_<?php echo $i;?>" value="<?php echo number_format($row_demo['rate'], 2); ?>"/>

						<input type="hidden" name="res_<?php echo $i;?>" id="res_<?php echo $i;?>" value="">
					</td>
					
					<!-- <?php if($_REQUEST['PTask']=='update') { ?>
						<td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty'];?>"/>
						</td>
					<?php } else { ?>
						<td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number" <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['rm_qty'];?>"/>
						</td>
					<?php } ?> -->

					<td style="width: 3%;text-align:center;">
					<?php if($_REQUEST['PTask']=='update' || $type=='Against_Purchaseorder' || $_REQUEST['PTask']=='view' ) { 
						$productcheck = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
					?>
						<button type="button" class="btn btn-light" onclick="grn_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#grnbatch"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					<?php } else { ?> 
						<!-- ------------------------ Add Batch ------------------------ -->
						<div id='batchgrn_<?php echo $i;?>' >

						</div>
					<?php } ?>
					</td> 

				<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Purchaseorder') { ?>
					<td style='width:2%'>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row(this.id);"></i>
					</td>
				<?php } ?>
				</tr>
			<?php } ?>
			<?php } ?>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl">
			<tr style="margin:10px;text-align:center;">
				<td>
				<?php 
					if(($_REQUEST['PTask']!='view' && $purchaseorder_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) {
				?>			
					<button type="button" class="btn btn-light  " id="addmore" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
				<?php  } ?> 
				</td>			
			</tr>
		</table> 
		

		<div class="modal fade" style = "max-width=40%;" id="grnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="gbatch">
					
				</div>
			</div>
		</div>

		<script>
		// ------------------------------------ NEW GRN ------------------------------------
			function check_batch_grn(id){
				
				var id=id.split("_");
				// alert(parent_id);
				var PTask = PTask;
				id=id[1];
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_grn',id:id,product:product},
					success:function(data)
					{	
						//alert(data);
						$("#batchgrn_"+id).html(data);	
						$(this).next().focus();
					}
				});

			}

			function check_qty(i) {
				var quantity = $("#qty_"+i).val();

				if (quantity == '') {
					alert ('please enter quantity first . . . !');

				} else {
					// optn_batch_modal(i,quantity);
					grn_batchdata(i);
				}
			}

			function grn_batchdata(i) {
								                      
				var qty =$("#qty_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				var id = $("#id").val();
				var location =$("#location").val();
				var product =$("#product_"+i).val();
				var rate =$("#rate_"+i).val();

				var productName = "Product name : ";
				var qtyName = "Received Quantity : ";
				var selectedOptionText = productName + $("#product_"+i + " option:selected").html();
				var rec_qty = qtyName+qty;

				// var paragraphElement = document.getElementById("myParagraph");
				// paragraphElement.textContent = selectedOptionText;

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'grn_batchdata', product:product,qty:qty,PTask:PTask,common_id:common_id,location:location,i:i,id:id,selectedOptionText:selectedOptionText,rec_qty:rec_qty,rate:rate },
					success: function (data) {
						$('#gbatch').html(data);
						$('#grnbatch').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

		// ------------------------------------------------------------------------------
			function get_totalqty()
			{
				var cnt=jQuery("#cnt").val();
				//alert(cnt);
				var grandtotal=0;	
				for(var i=1; i<=cnt;i++)
				{	
					var qty= jQuery("#qty_"+i).val();
					if(qty==''){ qty=0;}
					grandtotal = parseFloat(grandtotal)+parseFloat(qty);
				}
				alert(grandtotal);
				// jQuery("#total_quantity").val(parseFloat(grandtotal).toFixed(2));	
			}

			function check_batch_type(id, task, parent_id){
				// alert(task);
				var id=id.split("_");
				// var PTask = PTask;
				// id=id[1];
				// alert(parent_id);
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_type',id:id,task:task},
					success:function(data)
					{	
						//alert(data);
						$("#batchdiv_"+id).html(data);
						$(this).next().focus();
					}
				});	

			}
			
			function check_batch_type1(id){
			
				var id=id.split("_");
				// alert(parent_id);
				// var PTask = PTask;
				id=id[1];
				// alert(id);
				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
				data: { Type:'check_batch_type1',id:id,product:product},
					success:function(data)
					{	
						//alert(data);
						$("#batchdiv_"+id).html(data);	
						$(this).next().focus();
					}
				});	

			}

			// function check_submit_qty() {

			// 	var tot_qty = $("#tot_qty").val();
			// 	var main_qty = $("#myqty").val();
			// 	// alert(main_qty);

			// 	if (tot_qty == main_qty) {
			// 		savedatabatch();
			// 		// alert("LoL Noobs . . .");
			// 	} else {
			// 		if (main_qty > tot_qty) {
			// 			alert("Your total batch quantity is less than Material quantity.");
			// 			alert("please add quantity in exsiting batch or add new batch.");
			// 		} else {
			// 			alert("Your total batch quantity is greater than Material quantity.");
			// 			alert("please remove some quantity from exsiting batch.");
			// 		}
			// 	}

			// }

			function total_qty(id) {
				
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;

				// Assuming batqty1_id elements are input fields
				$("[id^='batqty1_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});

				console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);

			}

			

			function optn_batch_modal(i,quantity){

				//alert(i);
				var main_qty = $("#qty_"+i).val();
				$("#myqty").val(main_qty);
				
				
				var product = $("#product_"+i).val();

				var productName = "Product name : ";
				var selectedOptionText = productName + $("#product_"+i + " option:selected").html();
				

				$("#product_nm").val(product);

				var paragraphElement = document.getElementById("myParagraph");
				paragraphElement.textContent = selectedOptionText;
				// paragraphElement.textContent("Product name " + selectedOptionText);

				$('#addgrn').modal('show');

			}

			function optn_batch_modal1(i, id, common_id) {
				// alert(common_id);  // Main ID
				// alert(id); // Product ID
				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'editmodal', id: id, i:i, common_id:common_id },
					success: function (data) {
						
						$('#mybatch1').html(data);
						var product = $("#product_"+i).val();

						var productName = "Product name : ";
						var selectedOptionText = productName + $("#product_"+i + " option:selected").html();
						

						$("#product_nm").val(product);	

						var paragraphElement = document.getElementById("myParagraph1");
						paragraphElement.textContent = selectedOptionText;

						$('#addgrn1').modal('show');
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

			// -----------Update GRN Modal---------------------------------------

			function addRowbatch2(tableID) {
				// alert(tableID);
				var count=$("#cnt2").val();	
				var state=$("#state").val();

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row2_"+i+"'>";
				
				
				cell1 += "<td><input type='text' id='batchname_"+i+"' class='form-control number' name='batchname_"+i+"' value='' /></td>";
				
				cell1 += "<td><input type='text' id='batqty_"+i+"' class='form-control number' name='batqty_"+i+"' value='' /></td>";
			
				cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch2(this.id);'></i></td>";


			
				$("#mybatch1").append(cell1);
				$("#cnt2").val(i);
				// $("#particulars_"+i).select2();
			}

			function delete_row_batch2(rwcnt)
			{
				//alert("count");
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt2").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row2_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row2_"+k).attr('id','row2_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#batchname_"+k).attr('name','batchname_'+newId);
							jQuery("#batchname_"+k).attr('id','batchname_'+newId);
							
							jQuery("#batqty_"+k).attr('name','batqty_'+newId);
							jQuery("#batqty_"+k).attr('id','batqty_'+newId);
							
							
							jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
							
						}
						jQuery("#cnt2").val(parseFloat(count-1)); 
					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}
		</script>
		<script>

		</script>
	<?php
	break;
	
	// ---------------------------------- New GRN Form ----------------------------------
	case 'check_batch_grn':
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>
		<div id='batchgrn_<?php echo $i;?>'>
			<button type="button" class="btn btn-light" onClick="check_qty(<?php echo $i;?>)"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
		</div>

	<?php
	break;
	
	// -------------------------------- NEW GRN BATCH --------------------------------
	case 'grn_batchdata':

		$product_id = $_REQUEST['product'];
		$bat_rate = $_REQUEST['rate'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$qty = $_REQUEST['qty'];
		$maincnt = $_REQUEST['i'];
		$i = 0;
	?>

		<div id="gbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add Batch</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">

					<p id="myParagraph">
						<?php echo $_REQUEST['selectedOptionText']; ?> 
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo $_REQUEST['rec_qty']; ?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						Received Quantity rate : <?php echo $bat_rate; ?>
					</p>

					<input type="hidden" name="myqty" id="myqty" value="<?php echo $qty; ?>">

					<table class="table table-striped" id="batch">

						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
						<input type="hidden" name="bat_rate" id="bat_rate" value="<?php echo $bat_rate; ?>">
						<input type="hidden" name="location12" id="location12" value="<?php echo $location; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">

						<thead>
							<tr>
								<th style="width:15%;">Location</th>
								<th style="width:20%;">Batch name</th>
								<th style="width:15%;">Quantity</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {
								$purivodata = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."' AND type='grn' ");
								
							} else {
								$purivodata = $utilObj->getMultipleRow("temp_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='grn' ");
								if(empty($purivodata)){
									$purivodata[0]['id']=1;
								}
							}

							foreach($purivodata as $pidata) {
								$i++;
								$tot_sum += $pidata['batqty'];

								if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') {
									$bat_qty=$pidata['batqty'];
								} else {
									$bat_qty=$pidata['quantity'];
								}

								if($_REQUEST['PTask']=='view') {

									$readonly = "readonly";
									$disabled = "disabled";
								}
						?>
							<tr id="row1_<?php echo $i;?>">
								<td style="width:15%;">
									<select id="location_<?php echo $i;?>" name="location_<?php echo $i;?>" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" style="width:100%;">
									<?php 
										echo '<option value="">Select</option>';
										$record=$utilObj->getMultipleRow("location","1");
										foreach($record as $e_rec)
										{
											if($pidata['location']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
										}
									?>
									</select>
								</td>
								<td style="width:20%;">
									<input <?php echo $readonly; ?> type="text" id="batchname1_<?php echo $i;?>" class=" form-control number" name="batchname1_<?php echo $i;?>" value="<?php echo $pidata['batchname']; ?>" />
								</td>
								<td style="width:15%;">
									<input <?php echo $readonly; ?> type="text" id="batqty1_<?php echo $i;?>" class=" form-control number batqty2_<?php echo $i; ?>" name="batqty1_<?php echo $i;?>" value="<?php echo $bat_qty; ?>" onblur="total_qty(<?php echo $i; ?>)" />
								</td>
								<td style='width:2%'>
								<?php 
									if($i>1) {
								?>
									<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch('this.id');"></i>
								<?php } ?>
								</td>
							</tr>
						<?php } ?>

						<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
						</tbody>
						<td></td>
						<td></td>
						<td>
							Total Quantity : <input <?php echo $readonly; ?> readonly type="text" name="tot_qty" id="tot_qty" value="<?php echo $tot_sum; ?>" class="form-control number">
						</td>
					</table>
					<div class="col-md-2">
						<button type="button" class="btn btn-warning" id="addmore1" onclick="addRowbatch('batch');">Add More</button>
					</div>
				</div>
			</div>
			<div class="modal-footer" >
				<?php if($_REQUEST['PTask']!='view') { ?>
					<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();"/>
				<?php } ?>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
			</div>
		</div>

		<script>

			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#myqty").val();
				// alert(main_qty);

				if (tot_qty == main_qty) {
					savedatabatch();
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Enter Batch Quantity doesn't match Received Quantity.");
					} else {
						alert("Enter Batch Quantity doesn't match Received Quantity.");
					}
				}

			}

			function savedatabatch()
			{
				var PTask = $("#PTask").val();
				var maincnt = $("#maincnt").val();
				var product = $("#product_batch").val();
				var bat_rate = $("#bat_rate").val();
				var cnt1 = $("#cnt1").val();
				var common_id = $("#common_id").val();
				var id = $("#id").val();

				var location_array=[];
				var batchname_array=[];
				var batqty_array=[];

				var res = 1
				
				for(var i=1;i<=cnt1;i++)
				{	
					var location = $("#location_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batqty = $("#batqty1_"+i).val();
					
					location_array.push(location);
					batchname_array.push(batchname);
					batqty_array.push(batqty);
				}
				
				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'batchtask',common_id:common_id, batqty_array:batqty_array, batchname_array:batchname_array, cnt1:cnt1,location_array:location_array,PTask:PTask,product:product,id:id,bat_rate:bat_rate },
					success:function(data)
					{	
						
						if(data!="")
						{
							alert("Batch Added successfully.")
							for (var i = 1; i <= cnt1; i++) {
								$('#location_' + i).val('');
								$('#batchname1_' + i).val('');
								$('#batqty1_' + i).val('');
							}
							$('#tot_qty').val('');

							$('#res_'+maincnt).val(res);


							$('#grnbatch').modal('hide');

						} else {
							alert('error in handler');
						}
					}
				});			 
			}

			var count = $("#cnt1").val();

			for(var i=1;i<=count;i++) {

				$("#location_"+i).select2({
					dropdownParent: $('#batch')
				});

			}

			function addRowbatch(tableID) {
				var count=$("#cnt1").val();
				var state=$("#state").val();

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row1_"+i+"'>";
				
				cell1 += "<td style='width:15%;'><select name='location_"+i+"' onchange=''  class='select2 form-select' id='location_"+i+"' style='width:100%;'>\
				<option value=''>Select</option>\
					<?php
					$record=$utilObj->getMultipleRow("location","1");
					foreach($record as $e_rec){	
						echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
					}
							
					?>
				</select></td>";

				cell1 += "<td style='width:20%;'><input type='text' id='batchname1_"+i+"' class='form-control number' name='batchname1_"+i+"' value='' /></td>";
				
				cell1 += "<td style='width:15%;'><input type='text' id='batqty1_"+i+"' class='form-control number' name='batqty1_"+i+"' value='' onblur='total_qty("+i+")' /></td>";
			
				cell1 += "<td style='width:2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id)'></i></td>";


			
				$("#batch").append(cell1);
				$("#cnt1").val(i);
				// $("#location_"+i).select2();
				// $(".select2").select2();

				$("#location_"+i).select2({
					dropdownParent: $('#batch')
				});
			}

			function delete_row_batch(rwcnt)
			{
				
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt1").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row1_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row1_"+k).attr('id','row1_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#batchname1_"+k).attr('name','batchname1_'+newId);
							jQuery("#batchname1_"+k).attr('id','batchname1_'+newId);
							
							jQuery("#batqty1_"+k).attr('name','batqty1_'+newId);
							jQuery("#batqty1_"+k).attr('id','batqty1_'+newId);
							
							
							jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
						}
						jQuery("#cnt1").val(parseFloat(count-1)); 
						total_qty();

					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}
		</script>

	<?php
	break;
	
	// -------------------------------- NEW GRN BATCH HANDLER --------------------------------
	case 'batchtask':

		if($_REQUEST['PTask']=='update') {
			$common=$_REQUEST['id'];
		} else {
			$common=$_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='grn' ");

		$typebatch="grn";
		$cnt1=$_REQUEST['cnt1'];

		for($i=0;$i<$cnt1;$i++) {

			$arrValue=array('id'=>uniqid(),'product'=>$_REQUEST['product'],'bat_rate'=>$_REQUEST['bat_rate'],'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>$typebatch,'batchname'=> $_REQUEST['batchname_array'][$i], 'quantity'=>$_REQUEST['batqty_array'][$i],'location'=>$_REQUEST['location_array'][$i] );
			
			print_r($arrValue);
			$insertedId=$utilObj->insertRecord('temp_batch',$arrValue);

		}

		if($insertedId)
		echo $Msg='Record has been Added Sucessfully! ';

	break;

	// ============================= Use In=Purchase Order And GRN AND saleorder =============================

	case 'find_state':
	  	$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		echo $state= $account_ledger['mail_state'];
	break;

	// ==================================== USE IN=Purchase Order (1) ====================================

	case 'requisition_rowtable':

		$requisition_no = $_REQUEST['requisition_no'];
		$account_ledger = $utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		// $state = $account_ledger['mail_state'];
		$state = $_REQUEST['supplier'];
		$purchase_order = $utilObj->getSingleRow("purchase_order"," id='".$_REQUEST['id']."' ");
		if($requisition_no!='') {

			$read="readonly";
		} else {

			$read="";
		}

	?>
		<h4 class="role-title">Material Details</h4>
		<input type="hidden" name="state" id="state" value="<?php echo $state; ?>">
		<table class="table table-bordered " id="myTable" >
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width:15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Ledger</th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<?php if($state==27) { ?>
						<th style="width: 7%;text-align:center;">CGST </th>
						<th style="width: 7%;text-align:center;">SGST </th>
					<?php } else{ ?>
						<th style="width: 7%;text-align:center;">IGST </th>
					<?php } ?>
					<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Rate <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width:10%;text-align:center;">Taxable Amount</th>

					<!-- <th style="width:10%;text-align:center;">Total</th> -->

					<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Requisition'){?>
						<th style="width:2%;text-align:center;"></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if($_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Against_Requisition'&&$_REQUEST['requisition_no']!=''&&$purchase_order['requisition_no']==$requisition_no)
				{ 
					// echo "condi 1";
					$record5=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$_REQUEST['id']."'");

				} else if(($_REQUEST['requisition_no']!='' || $_REQUEST['type']=='Against_Requisition')&&$purchase_order['requisition_no']!=$_REQUEST['requisition_no'])  { 

					// echo "condi 2";
					// echo $_REQUEST['requisition_no'];
					$record5=$utilObj->getMultipleRow("purchase_requisition_details","parent_id='".$_REQUEST['requisition_no']."'AND  parent_id in(select id from  purchase_requisition)");
					$read="readonly";

				} else if($_REQUEST['PTask']=='view'||$_REQUEST['PTask']=='update'&&$_REQUEST['type']=='Direct_Purchase') {
					
					// echo "condi 3";
					$record5=$utilObj->getMultipleRow("purchase_order_details","parent_id='".$_REQUEST['id']."'");
				
					if($_REQUEST['PTask']=='view'){
						$readonly="readonly";
						$disabled="disabled";
						$read="";
					}else{
						$readonly=" ";
						$disabled="";
					}
				}
				else
				{
					$record5[0]['id']=1;					
				}  
				foreach($record5 as $row_demo)
				{ 
					$i++;

					$dqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where requisition_no='".$_REQUEST['requisition_no']."') AND product='".$row_demo['product']."' ","qty");

					$rqty = $row_demo['qty'] - $dqty;

					if($_REQUEST['PTask']!='update') {

						$mate1 = $utilObj->getSingleRow("stock_ledger", "id='".$row_demo['product']."' ");
			
						$mate2 = $utilObj->getSingleRow("gst_data", "id='".$mate1['igst']."' ");

						$igst = $mate2['igst'];
						$cgst = $mate2['cgst'];
						$sgst = $mate2['sgst'];
					} else {

						$igst = $row_demo['igst'];
						$cgst = $row_demo['cgst'];
						$sgst = $row_demo['sgst'];
					}

			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>

					<td style="width:15%;">
					<?php 
						$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
						if($_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Requisition'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$requisition_no!='') ) { ?>

							<!-- <input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

							<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly.$read;?>  value="<?php echo $product['name'];?>" /> -->

							<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_gstdata(this.id);get_ledger(this.id,<?php echo $state; ?>);" style="width:120px;">	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					<?php } else { ?>
						<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);get_gstdata(this.id);get_ledger(this.id,<?php echo $state; ?>);" style="width:120px;">	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","1 ");
								foreach($record as $e_rec)
								{
									if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					<?php } ?>
					</td>
						
					<td style="width: 10%;">
						<select id="ledger_<?php echo $i;?>" name="ledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> style="width:100%;">

						<?php
						if( $_REQUEST['PTask']=='view'&&($_REQUEST['type']=='Against_Purchaseorder'&&$_REQUEST['PTask']!='update')||($_REQUEST['PTask']=='update'&&$purchaseorder_no!='') ) {
							echo '<option value="">Select Ledger</option>';
							$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");
							foreach($record as $e_rec){
								if($rows['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
							}

						} else {
							$data=$utilObj->getSingleRow("stock_ledger","id='".$row_demo['product']."'");

							$record=$utilObj->getMultipleRow("account_ledger","1 AND group_name=27 group by name");

							echo '<option value="">Select Ledger</option>';
							foreach($record as $e_rec)
							{	
								if($state==27) {
									if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								} else {
									if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
								
							}
						} ?>
						</select>
					</td>

					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required"  readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>"/>
						</div>
					</td>
					<?php if( $state==27) { ?>
						<td style="width: 7%;">
							<input type="text" id="cgst_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> name="cgst_<?php echo $i;?>" value="<?php echo $cgst; ?>"/>
						</td>
						
						<td style="width: 7%;">
							<input type="text" id="sgst_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> name="sgst_<?php echo $i;?>" value="<?php echo $sgst; ?>"/>
						</td>
					<?php } else { ?>
						<td style="width: 7%;">
							<input type="text" id="igst_<?php echo $i;?>" class=" form-control number"  <?php echo $readonly;?> name="igst_<?php echo $i;?>" value="<?php echo $igst; ?>"  />

						</td>
					<?php } ?>

					<?php if($_REQUEST['PTask']=='update') { ?>
						<td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number tdalign"  <?php  // echo $readonly.$read;?> name="qty_<?php echo $i;?>" value="<?php echo $row_demo['qty']; ?>" />
						</td>
					<?php } else { ?>
						<td style="width: 10%;">
							<input type="text" id="qty_<?php echo $i;?>" class=" form-control number tdalign"  <?php  // echo $readonly.$read;?> name="qty_<?php echo $i;?>" value="<?php echo $rqty; ?>"  />
						</td>
					<?php } ?>

					<td style="width: 10%;">
						<input type="text" id="rate_<?php echo $i;?>" class="number form-control tdalign"  <?php echo $readonly;?> name="rate_<?php echo $i;?>" value="<?php echo $row_demo['rate'];?>" onkeyup="getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

						<input type="hidden" name="rowigstamt_<?php echo $i; ?>" id="rowigstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowcgstamt_<?php echo $i; ?>" id="rowcgstamt_<?php echo $i;?>" value="0" >
						<input type="hidden" name="rowsgstamt_<?php echo $i; ?>" id="rowsgstamt_<?php echo $i;?>" value="0" >
					</td>
						
					<td style='width:10%'>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="taxable_<?php echo $i;?>" <?php echo $readonly;?> name="taxable_<?php echo $i;?>" readonly="readonly" value="<?php echo number_format($row_demo['taxable'],2); ?>" />
					</td>

					<!-- <td style="width: 10%;">
						<input type="text" id="total_<?php echo $i;?>" class="number form-control tdalign"  onchange="Getgst(this.id);showgrandtotal();" onkeyup="Getgst(this.id);showgrandtotal();" onBlur="Getgst(this.id);showgrandtotal();" <?php echo $readonly;?> name="total_<?php echo $i;?>" value="<?php echo $row_demo['total'];?>" />
					</td> -->

					<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']=='Direct_Purchase') { ?>
						<td style='width:2%'>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
						</td>
					<?php } ?>

				</tr>
				<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td>
						
					</td>
					<td></td>
					<td></td>
					<?php if($state==27) { ?>
						<td>
							<input type="hidden" id="cgsttot" name="cgsttot" value="0" />
						</td>
						<td style="text-align:center;">
							<input type="hidden" id="sgsttot" name="sgsttot" value="0" />
							<?php
							if(($_REQUEST['PTask']!='view' && $requisition_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
							<button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button>
						<?php } ?> 
						</td>
					<?php } else { ?>
						<td style="text-align:center;">
							<input type="hidden" id="igsttot" name="igsttot" value="0" />
							<?php
								if(($_REQUEST['PTask']!='view' && $requisition_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) {
							?>			
								<button type="button" class="btn btn-warning btn-sm" id="addmore" onclick="addRow('myTable');">Add More</button>
							<?php } ?> 
						</td>
					<?php } ?>
					<td style="text-align:center;">Discount</td>
					<td style="text-align:center;">
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totdiscount" name="totdiscount" onkeyup="get_discamt();" value="<?php echo $purchase_order['totdiscount']; ?>" />
					</td>
					<td>
						<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totaltaxable" name="totaltaxable" readonly  value="<?php echo number_format($purchase_order['totaltaxable'],2); ?>" />
					</td>
					<!-- <td></td> -->
				</tr>
			</tfoot>
		</table>

		<!-- <table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;text-align:center;">
				<td>
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='')||($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning" id="addmore" onclick="addRow('myTable');">Add More</button>
					<?php } ?> 
				</td>			
			</tr>
		</table>  -->

		<!-- ------------------------------------------------------------- -->

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">Other Details</h4>
			<thead>
				<tr>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Ledger</th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<?php if($state==27) { ?>
						<th style="text-align:center;">CGST</th>
						<th style="text-align:center;">SGST</th>
					<?php } else { ?>
						<th style="text-align:center;">IGST</th>
					<?php } ?>
					<th style="text-align:center;"></th>
					<th style="text-align:center;"></th>
					<th style="text-align:center;">Amount</th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$j=0;
				if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
					$recordservice=$utilObj->getMultipleRow("purchase_order_other_details","parent_id='".$_REQUEST['id']."' ");
				} else { 
					$recordservice[0]['id'] = 1;					
				}
				foreach($recordservice as $row_demo1) {
					$j++;

			?>
				<tr id='row2_<?php echo $j; ?>'>
					<td style="width:3%;">
						<?php echo $j; ?>
					</td>
					<td style="width:15%;">
						<div id="ledgerdiv_<?php echo $j; ?>">
							<select id="serviceledger_<?php echo $j; ?>" name="serviceledger_<?php echo $j; ?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="getservice(this.id);">	
								<?php
									echo '<option value="">Select</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1");
									foreach($record as $e_rec)
									{
										if($row_demo1['ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
									}
								?>
							</select>
						</div>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">
							<input type="text" id="servicecgst_<?php echo $j; ?>" class=" form-control number" name="servicecgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicecgst'];?>" readonly />
						</td>
						<td style="width:7%;">
							<input type="text" id="servicesgst_<?php echo $j; ?>" class=" form-control number" name="servicesgst_<?php echo $j; ?>" value="<?php echo $row_demo1['servicesgst'];?>" readonly />
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							<input type="text" id="serviceigst_<?php echo $j; ?>" class=" form-control number" name="serviceigst_<?php echo $j; ?>" value="<?php echo $row_demo1['serviceigst'];?>" readonly />
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="serviceamt_<?php echo $j; ?>" class="number form-control tdalign" name="serviceamt_<?php echo $j; ?>" value="<?php echo number_format($row_demo1['serviceamt'],2); ?>" onkeyup="servicegstsum(this.id);servicetotgst(<?php echo $j; ?>);" />
 
						<input type="hidden" name="serviceigstamt_<?php echo $j; ?>" id="serviceigstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicecgstamt_<?php echo $j; ?>" id="servicecgstamt_<?php echo $j; ?>" value="0" >
						<input type="hidden" name="servicesgstamt_<?php echo $j; ?>" id="servicesgstamt_<?php echo $j; ?>" value="0" >
					</td>
					<td style="width:2%;">
						
					</td>
				</tr>
				<?php } ?>
				<input type="hidden" name="cntd" id="cntd" value="<?php echo $j; ?>">
			</tbody>
		</table>
		<table style="width:100%;" class="taxtbl" >
			<tr style="margin:10px;">
				<td colspan="4"></td>
				<td >
					<input type="hidden" name="totservicecgst" id="totservicecgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totservicesgst" id="totservicesgst" value="0">
				</td>
				<td >
					<input type="hidden" name="totserviceigst" id="totserviceigst" value="0">
				</td>
				<td style="width:9%;">
					<?php
						if(($_REQUEST['PTask']!='view' && $requisition_no=='') || ($_REQUEST['type']=='Direct_Purchase'&&$_REQUEST['PTask']!='view')) { ?>			
						<button type="button" class="btn btn-warning btn-sm" id="addmore11" onclick="addRowdetail('dtable');">Add More</button>
					<?php } ?> 
				</td>
				<td style="width:11%;">
					<input type="text" style="width: 100%;" class="form-control tax smallinput number tdalign" id="totserviceamt" name="totserviceamt" readonly value="<?php echo number_format($purchase_order['totserviceamt'],2); ?>" />
				</td>
				<td style="width:3%;"></td>
			</tr>
		</table>

		<table class="table table-striped" id="dtable">
			<h4 class="role-title">GST Details</h4>
			<tbody>
			
			<?php if($state==27) { ?>
				<tr id='rowgst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="cgstledger" name="cgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['cgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="cgstamt" class="number form-control tdalign" readonly name="cgstamt" value="<?php echo number_format($purchase_order['cgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				<tr id='row2gst'>
					<td style="width:3%;">
						2
					</td>
					<td style="width:15%;">
						<select id="sgstledger" name="sgstledger" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['sgstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="sgstamt" class="number form-control tdalign"  readonly name="sgstamt" value="<?php echo number_format($purchase_order['sgstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
				
			<?php } else { ?>

				<tr id='rowigst'>
					<td style="width:3%;">
						1
					</td>
					<td style="width:15%;">
						<select id="igstledger" name="igstledger" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true" >	
							<?php
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec)
								{
									if($purchase_order['igstledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
								}
							?> 
						</select>
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
					
					</td>
					<td style="width:10%;">
						<input type="text" id="igstamt" class="number form-control tdalign"  readonly name="igstamt" value="<?php echo number_format($purchase_order['igstamt'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			<?php } ?>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Sub Total
					</td>
					<td style="width:10%;">
						<input type="text" id="subtotgst" class="number form-control tdalign" readonly name="subtotgst" value="<?php echo number_format($purchase_order['subtotgst'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>

				<tr id=''>
					<td style="width:3%;">
						
					</td>
					<td style="width:15%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						
					</td>
					<?php if($state==27) { ?>
						<td style="width:7%;">

						</td>
						<td style="width:7%;">
							
						</td>
					<?php } else { ?>
						<td style="width:7%;">
							
						</td>
					<?php } ?>
					<td style="width:10%;">
						
					</td>
					<td style="width:10%;">
						Grand Total
					</td>
					<td style="width:10%;">
						<input type="text" id="grandtot" class="number form-control tdalign" readonly name="grandtot" value="<?php echo number_format($purchase_order['grandtotal'],2); ?>" />
					</td>
					<td style="width:2%;">
				
					</td>
				</tr>
			</tbody>
		</table>


		<script>

			var count = $("#cnt").val();

			for(var i=1;i<=count;i++) {

				$("#product_"+i).select2({
					dropdownParent: $('#table_div')
				});

				$("#ledger_"+i).select2({
					dropdownParent: $('#table_div')
				});

			}

			var cntd = $("#cntd").val();

			for(var j=1;j<=cntd;j++) {

				$("#serviceledger_"+j).select2({

					dropdownParent: $('#table_div')
				});
			}

			$("#cgstledger").select2({
				
				dropdownParent: $('#table_div')
			});

			$("#sgstledger").select2({
				
				dropdownParent: $('#table_div')
			});

			$("#igstledger").select2({
				
				dropdownParent: $('#table_div')
			});

			function get_ledger(this_id,state) {

				var id=this_id.split("_");
				id=id[1];
				var pid = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_ledger',id: id,this_id:this_id,state:state,pid:pid},
					success:function(data)
					{	
						$("#ledger_"+id).html(data);
					}
				});
			}

			var count = $("#cnt").val();

			// $("#product_1").select2({
			// 	dropdownParent: $('#table_div')
			// });

			for(var i=1;i<=count;i++) {

				$("#product_"+i).select2({
					dropdownParent: $('#table_div')
				});

				$("#ledger_"+i).select2({
					dropdownParent: $('#table_div')
				});

			}

		</script>

	<?php
	break;

	// ================================== Purchase Order (2) ==================================
	case 'chk_requisitionno_type':
	    $purchase_order=$utilObj->getSingleRow("purchase_order"," id='".$_REQUEST['id']."' ");
	?>

				<label class="form-label"> Requisition No. <span class="required required_lbl" style="color:red;">*</span></label>
				<div id="requisition_div">
					<?php
						if($_REQUEST['PTask']=='view'){
						$readonly="readonly";
						// $purchase_order=$utilObj->getSingleRow("requisition_no"," id='".$_REQUEST['id']."' ");
						$requisition=$utilObj->getSingleRow("purchase_requisition","id in (select requisition_no from  purchase_order where id='".$_REQUEST['id']."')");
					?>
						<input type="hidden" id="requisition_no" <?php echo $readonly;?> name="requisition_no" value="<?php echo $requisition['id'];?>"/>
						<input type="text"   style="width:100%;" class=" form-control"  <?php echo $readonly;?>  value="<?php echo $requisition['record_no'];?>"/>
						
					<?php } else { ?>
						<select id="requisition_no" name="requisition_no" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="requisition_rowtable();">
							<option value=""> Select Requisition No</option>
						
							<?php 
								$record=$utilObj->getMultipleRow("purchase_requisition","requi_flag='0' ");
								
								foreach($record as $e_rec) {

									// echo "Hulu1";
									// $dqty=$utilObj->getSum("purchase_order_details","parent_id in(select id from purchase_order where requisition_no='".$e_rec["id"]."') ","qty");	

									// $sqty=$utilObj->getSum("purchase_requisition_details","parent_id in(select id from purchase_requisition where id='".$e_rec["id"]."') ","qty");

									// $remain_qty=$sqty-$dqty;

									if($_REQUEST['PTask']!='update') {
										// if($remain_qty!=0) {
											
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["record_no"].'</option>';
										// }
									} else {

										if($purchase_order['requisition_no']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["record_no"].'</option>';
									}
								}
							?>
						</select>
				   
				   	<?php } ?>

				</div>
	<?php
	break;

	// ===============================================================================
	case 'get_nature_group':
		// $getdata=$utilObj->getSingleRow("group_master","parent_group='".$_REQUEST['parent']."'");
		$getdata=$utilObj->getSingleRow("group_master","group_name='".$_REQUEST['parent']."'");
	?>
		<div class="row" id="group_div">
			<div class="col-md-3"  >
				<label class="label">Nature of Group</label>
				<input type="text" id="nature_group" class="form-control" readonly <?php echo $readonly;?> placeholder="Nature of Group" name="nature_group" value="<?php echo $getdata['sub_report'];?>"/>
			</div>
			<div  class="col-md-3" >
				<label class="label">Report </label>
				<input type="text" id="nature" class="form-control" readonly <?php echo $readonly;?> placeholder="Nature of Group" name="nature" value="<?php echo $getdata['report'];?>"/> 
			</div>
		</div>

	<?php
	break;

	case 'get_grp_id':
		$getdata=$utilObj->getSingleRow("group_master","group_name='".$_REQUEST['id']."' ");
		echo $getdata['id'];
	break;

	case 'getfield':
		$getdata=$utilObj->getSingleRow("group_master","id='".$_REQUEST['id']."' ");
		echo $getdata['act_group'];
	break;
	// ========================================================
	case 'unit_refresh':
	?>	
		<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
		<div id="div_unit">
			<select id="unit" name="unit" <?php echo $disabled;?> class="select2 form-select "  data-allow-clear="true" onchange="menuhide();get_unit_formula();">
				
				<?php 
					echo '<option value="">Select Unit</option>';
					echo '<option value="AddNew">Add New</option>';
					$record=$utilObj->getMultipleRow("stock_ledger","1 group by unit");
					foreach($record as $e_rec){
					echo  '<option value="'.$e_rec["unit"].'" '.$select.'>'.$e_rec["unit"].'</option>';
					}
				?> 
			</select>
		</div>
			
	<?php
	break;
	// =================================================================

	case 'unit_alt_refresh':
	?>	
				<label class="form-label">Alternate Unit </label>
				<div id="div_altunit">
					<select id="alt_unit" name="alt_unit" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true"  onchange="menuhide1();get_unit_formula();">
						<?php 
							echo '<option value="">Select Alternate Unit</option>';
							echo '<option value="AddNew">Add New</option>';
							$record=$utilObj->getMultipleRow("stock_ledger","1 AND alt_unit!='' group by alt_unit");
							foreach($record as $e_rec){
									echo  '<option value="'.$e_rec["alt_unit"].'" '.$select.'>'.$e_rec["alt_unit"].'</option>';
								
							}
						?> 
					</select>
				</div>
			
<?php
	break;
//==============================================================================================================================================================================================
case 'get_unit_formula':

if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' )
{
	$id=$_REQUEST['id'];
	$rows=$utilObj->getSingleRow("stock_ledger","id ='".$id."'");   
} 
if($_REQUEST['PTask']=='view')
{
	$readonly="readonly";
} 
else
{
	$readonly="";
}
				?>	
		<div class="col-md-12">
			<label class="form-label">Conversion Formula</label>
				<table>
					<tr>
					<td><label class="form-label"><?php echo $_REQUEST['unit']; ?><span class="required required_lbl" style="color:red;">*</span></label></td>
					<td><input type="text" id="unit_qty" class="required form-control"  <?php echo $readonly;?>  name="unit_qty" value="<?php echo $rows['unit_qty']; ?>"/></td>
					<td><label class="form-label"><b>&nbsp;&nbsp;=&nbsp;&nbsp;</></label></td>
					<td><label class="form-label"><?php echo $_REQUEST['altunit'];?><span class="required required_lbl" style="color:red;">*</span></label></td>
					<td><input type="text" id="altunit_qty" class="required form-control"  <?php echo $readonly;?>  name="altunit_qty" value="<?php echo $rows['altunit_qty']; ?>"/></td>
					</tr>
				</table>
		</div>
		
<?php
	break;	
//==============================================================================================================================================================================================	
	case 'get_table':
				?>
					<select id="particulars_<?php echo $_REQUEST['cnt']; ?>" name="particulars_<?php echo $_REQUEST['cnt']; ?>" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" >
							
							<?php 
								echo '<option value="">Select</option>';
								$record=$utilObj->getMultipleRow("stock_ledger","under_group='".$_REQUEST['stock_gruop']."' group by name");
								foreach($record as $e_rec)
								{
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
								?>  
					</select>				
		
<?php
	break;
//================================================================================================================================	
	case 'get_button':
	
		if($_REQUEST['less_qty']!='' || $_REQUEST['PTask']=='update' )
		{
			?>
			<button type="button" class="btn btn-warning btn-sm " id="addmore" onclick="addRow1('<?Php echo $_REQUEST['rowid'];?>');">Add More</button>				
<?php
		}
	break;
//=================================USE IN=purchase requisition,purchase order,GRN========================================================	
	case 'get_unit':
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>
		<div id='unitdiv_<?php echo $i;?>'>
				<input type="text" style="width:100%;"  class=" form-control  smallinput " readonly id="unit_<?php echo $i;?>" <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $mate1['unit']; ?>"/>
		</div>
		<script>
			$('#qty_'+'<?php echo $i;?>').focus();	
		</script>		
		   <?php  
		
	break;

	// ================================= USE IN = sale invoice =================================
	case 'get_productdata':
		$i = $_REQUEST['id'];
		$mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "'");
		
		$stock=getstock($_REQUEST['product'], $mate1['unit'], date('Y-m-d'), '', $_REQUEST['location']);

		if($mate1['batch_maintainance']=='1') {

			$batch = $mate1['batch_maintainance']; 
		} else {

			$batch = $mate1['batch_maintainance']; 
		}

		echo trim($mate1['unit'] . "#" . $mate1['igst'] . "#" . $mate1['cgst'] . "#" . $mid1['sgst'] . "#" .$stock . "#" . $batch);

	break;

	// ========================== USE IN=sale invoice for rate and discount ==========================
	case 'getrate':

		$i = $_REQUEST['id'];
		$quantity = $_REQUEST['quantity'];
		$cnd = "AND applicable_date = (SELECT MAX(applicable_date) FROM pricelist)";
		$mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "'");
		$price = $utilObj->getMultipleRow("pricelist", "particulars='" . $_REQUEST['product'] . "' AND price_level = '".$_REQUEST['pricelevel']."' $cnd");

		foreach($price as $plist) {

			if($plist['from_qty']<=$quantity && $plist['less_qty']>=$quantity) {

				$rate=$plist['rate'];
				$discount=$plist['discount'];
				echo trim($rate . "#" . $discount);
			}
		}
	break;

	// ========================================== Voucher Start ==========================================

	case 'get_voucher_code':
		
		$mate1=$utilObj->getSingleRow("grn","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {

			$year_code = date("y")."-".(date("y")+1);
		} else {

			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
				echo $grn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				echo $grn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
				echo $grn_code = $prefix_label."/".$year_code."/".($formattedPono);
			}  else {

				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				echo $grn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }
		 
	break;

	// ================================= Purchase Invoice =================================
	
	case 'get_purchase_invoice_code':
		
		$mate1=$utilObj->getSingleRow("purchase_invoice","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $pur_ino_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $pur_ino_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(invoicenumber) AS pono from purchase_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);

				echo $pur_ino_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $pur_ino_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }
		 
	break;
	
	// =============================== Purchase Payment ===============================
	case 'get_purpay_code':
		
		$mate1=$utilObj->getSingleRow("purchase_payment","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $purpay_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $purpay_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }
		
	break;
	
	// =============================== Purchase Payment ===============================
	case 'get_purpay_code1':
		
		$mate1=$utilObj->getSingleRow("cash_payment","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $purpay_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $purpay_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $purpay_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $purpay_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		
	break;
	
	// ================================= Debit Note =================================
	case 'get_debit_note':
		
		$mate1=$utilObj->getSingleRow("debitnote_acc","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $debit_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $debit_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from debitnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $debit_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $debit_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}


	break;

	case 'get_sale_ino':
		
		$mate1=$utilObj->getSingleRow("sale_invoice","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $saleino_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $saleino_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(sale_invoiceno) AS pono from sale_invoice WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $saleino_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $saleino_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_sale_rec':
		
		$mate1=$utilObj->getSingleRow("sale_receipt","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $saler_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $saler_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

	break;

	case 'get_sale_rec1':
		
		$mate1=$utilObj->getSingleRow("cash_receipt","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $saler_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $saler_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_receipt WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $saler_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $saler_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

	break;
	
	case 'get_credit_code':
		
		$mate1=$utilObj->getSingleRow("creditnote_acc","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from creditnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."' ");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $credit_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."' ");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $credit_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from creditnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."' ");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $credit_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."' ");
				$result = mysqli_fetch_array($voucher_code);

				echo $credit_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}


	break;
	
	case 'get_preturn_code':
		
		$mate1=$utilObj->getSingleRow("grn_return","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $preturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $preturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from grn_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $preturn_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $preturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

	break;


	case 'get_preturn_code1':
		
		$mate1=$utilObj->getSingleRow("purchase_return","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $preturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $preturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $preturn_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $preturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

	break;

	// ---------------------------------- Delivery Challan Return ----------------------------------
	case 'get_dcreturn_code':
		
		$mate1=$utilObj->getSingleRow("delivery_return","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $preturn_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $preturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $preturn_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $preturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_sreturn_code':
		
		$mate1=$utilObj->getSingleRow("sale_return","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $sreturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $sreturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }
		
	break;

	case 'get_sreturn_code1':
		
		$mate1=$utilObj->getSingleRow("delivery_return","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {

			$year_code = date("y")."-".(date("y")+1);
		} else {

			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $sreturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from delivery_return WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $sreturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }
		
	break;

	case 'get_production_code':
		
		$mate1=$utilObj->getSingleRow("production","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {

			$year_code = date("y")."-".(date("y")+1);
		} else {

			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $sreturn_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from production WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $sreturn_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $sreturn_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

		break;
	
	case 'get_dis_code':
		
		$mate1=$utilObj->getSingleRow("dispatch","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $dis_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $dis_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from dispatch WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $dis_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $dis_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

		break;
	
	case 'get_pack_code':
		
		$mate1=$utilObj->getSingleRow("packaging","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from packaging WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $pack_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $pack_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(batch_no) AS pono from packaging WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $pack_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $pack_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

		// if ($mate1['voucher_type'] != '') {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(grn_no) AS pono from grn WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);

		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// } 
		
		// else {
		// 	$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
		// 	$result=mysqli_fetch_array($voucher_code);
		// 	echo $grn_code=($result['pono']+1)."/".date("y")."-".(date("y")+1);
		// }

		break;

	case 'get_stockt_code':
		
		$mate1=$utilObj->getSingleRow("stock_transfer","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} else {

				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from stock_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_gst_data':
		
		$data=$utilObj->getSingleRow2("gst_data","id='".$_REQUEST['id']."'");

		$record=$utilObj->getMultipleRow2("gst_data","1");
		foreach($record as $e_rec)
		{
			if($data['id']==$e_rec["id"]) echo $select='selected'; else $select='';
			echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cgst"] .'</option>';
		}

	break;

	case 'get_gst_data_ledger':
		
		$data=$utilObj->getSingleRow("gst_data","id='".$_REQUEST['id']."'");

		$record=$utilObj->getMultipleRow2("gst_data","1");
		foreach($record as $e_rec)
		{
			if($data['id']==$e_rec["id"]) echo $select='selected'; else $select='';
			echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["cgst"] .'</option>';
		}

	break;
	
	case 'get_sgst_data':
	
		$data=$utilObj->getSingleRow2("gst_data","id='".$_REQUEST['id']."'");

		$record=$utilObj->getMultipleRow2("gst_data","1");
		foreach($record as $e_rec)
		{
			if($data['id']==$e_rec["id"]) echo $select='selected'; else $select='';
			echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sgst"] .'</option>';
		}

	break;

	case 'get_sgst_data_ledger':
	
		$data=$utilObj->getSingleRow2("gst_data","id='".$_REQUEST['id']."'");

		$record=$utilObj->getMultipleRow2("gst_data","1");
		foreach($record as $e_rec)
		{
			if($data['id']==$e_rec["id"]) echo $select='selected'; else $select='';
			echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["sgst"] .'</option>';
		}

	break;

	case 'editmodal':
		$product_id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		// $i = $_REQUEST['i'];
		$i = 0;
		?>
		<table class = "table border-top" id="mybatch1">
										
										

			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>

			<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">

			<input type="hidden" name="CreatedAt" id="CreatedAt" value="<?php echo $CreatedAt; ?>">

			<thead>
				<tr>
					<th>Batch Name</th>
					<th>Quantity</th>
					
				</tr>
			</thead>
			<tbody>
				<?php $product=$utilObj->getMultipleRow("purchase_batch","product='".$product_id."'AND parent_id='".$common_id."'");
				// AND parent_id='".$common_id"'
				foreach($product as $info){
					
					$i++ ?>

					<tr id='row2_<?php echo $i;?>'>
						<td>
							<input type="text" id="batchname_<?php echo $i;?>" class=" form-control number" name="batchname_<?php echo $i;?>" value="<?php echo $info['batchname'];?>"/>
						</td>
						<td>
							<input type="text" id="batqty_<?php echo $i;?>" class=" form-control number" name="batqty_<?php echo $i;?>" value="<?php echo $info['batqty'];?>"/>
						</td>

						<?php if($_REQUEST['Task']!='view'){?>
						<td style='width:2%'>
								<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch2(this.id);"></i>
						</td>
						<?php } ?>
						
					</tr>
				<?php }?>

				<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
				
			</tbody>
			<td>Total Quantity : </td>											
		</table>
			
	<?php 
	break;
	
	// ------------------------------------ Sale Invoice View Batch ------------------------------------
	case 'viewsaleinvoicebatch':
		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		$i = 0;
	?>

	<div id="salesinvoicebatch">
		<div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>

		<div class="modal-body" >
			<div class="container">
				<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
				<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
				<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
				<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
				<table class = "table border-top" >
					<thead>
						<tr>
							<th>Location</th>
							<th>Batch Name</th>
							<th>Stock Quantity</th>
							<th>Quantity</th>
						</tr>
					</thead>
								
					<tbody>
					<?php
						$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND type='sale_delivery'");

						$productsum = $utilObj->getSum("purchase_batch", "product='" . $product_id . "'AND location='".$location."'", "batqty");
						$sumqty=0;
						foreach ($product as $info) {
							
							$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $info['location']);

							$i++;

							$loc = $utilObj->getSingleRow("location", "id='" . $info['location'] . "'");
							$p_name = $utilObj->getSingleRow("purchase_batch", "id='" . $info['purchase_batch'] . "'");
					?>

						<tr id='row2_<?php echo $i; ?>'>
							<input type="hidden" name="id[]" class="batch_id" value="<?php echo $info['id']; ?>">
        
							<td>
								<input readonly type="text" id="location_<?php echo $i; ?>" class=" form-control number" name="location_<?php echo $i; ?>" value="<?php echo $loc['name']; ?>"/>
								<input readonly type="hidden" id="location1_<?php echo $i; ?>" class=" form-control number" name="location1_<?php echo $i; ?>" value="<?php echo $info['location']; ?>"/>
							</td>

							<td>
								<input readonly type="text" id="batchname1_<?php echo $i; ?>" class=" form-control number" name="batchname_<?php echo $i; ?>" value="<?php echo $p_name['batchname']; ?>"/>
								<input readonly type="hidden" id="batchname_<?php echo $i; ?>" class=" form-control number" name="batchname_<?php echo $i; ?>" value="<?php echo $info['purchase_batch']; ?>"/>
							</td>
												
							<td>
								<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $totalstock; ?>"/>
							</td>

							<td>
								<input readonly type="text" id="batch_remove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $i; ?>" value="<?php echo $info['quantity'];?>"/>
							</td>
						</tr>	
					<?php } ?>
					<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
					</tbody>										
				</table>
			</div>
		</div>
		<div class="modal-footer" >
			<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="saveviewinvoice();" />
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
		</div>
	</div>
						
	<script>

		function saveviewinvoice() {

			var cnt2 = $("#cnt2").val();
			var product = $("#product").val();
			var common_id = $("#common_id").val();
			var PTask = $("#PTask").val();
			var deliveryid = $("#id").val();
			var type = "sale_invoice";
			
			var location_array=[];
			var batchname_array=[];
			var batchremove_array=[];
			
			for(var i=1;i<=cnt2;i++)
			{	
				var location = $("#location1_"+i).val();
				var batchname = $("#batchname_"+i).val();
				var batchremove = $("#batch_remove_"+i).val();

				location_array.push(location);
				batchname_array.push(batchname);
				batchremove_array.push(batchremove);
			}

			jQuery.ajax({
				url: 'get_ajax_values.php',
				type: 'POST',
				data: { Type: 'saveviewinvoice',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,cnt2:cnt2 },
				success: function (data) {
					$('#saleinvoicebatch').modal('hide');
			
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", status, error);
				}
			});
		}

	</script>

	<?php
	break;

	case 'saveviewinvoice':
		
		if($_REQUEST['PTask']=='update'){
			$common=$_REQUEST['deliveryid'];
		}else{
			$common=$_REQUEST['common_id'];
		}

		$cnt2 = $_REQUEST['cnt2'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchname_array'][$i],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'created'=>date("Y-m-d H:i:s"),'lastedited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location_array'][$i] );

			$insertedId=$utilObj->insertRecord('temp_sale_batch', $arrValue1);
		}

	break;
	
	// ---------------------------- Sale Invoice Batch ----------------------------
	case 'addsaleinvoicebatch':
		$product_id = $_REQUEST['product'];
		$rate = $_REQUEST['rate'];
		$stock = $_REQUEST['stock'];
		$qty = $_REQUEST['qty'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		$location = $_REQUEST['location'];
		$PTask=$_REQUEST['task'];
		$i = 0;
	?>

	<div  id="salesinvoiceaddbatch">
		<div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body" >
			<div class="container">
				<table class = "table border-top" id="mybatch1">
					<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
					<input type="hidden" name="rate" id="rate" value="<?php echo $rate; ?>">
					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<!-- <input type="hidden" name="location" id="location" value="<?php echo $location; ?>"> -->

					<thead>
						<tr>
							<th>Location</th>
							<th>Batch Name</th>
							<th>Stock Quantity</th>
							<th>Quantity</th>
						</tr>
					</thead>
								
					<tbody>
					<?php
						if($PTask == 'update') {
							$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_invoice' ");
						} else {
							$product = $utilObj->getMultipleRow("temp_sale_batch", "product='".$product_id."' AND parent_id='".$common_id."' AND type='sale_invoice' ");
							if(empty($purivodata)) {
								$product[0]['id']=1;
							}

							// $product[0]['id']=1;
						}
						foreach ($product as $info) {
							
							$i++
					?>

							<tr id='row2_<?php echo $i; ?>'>
								<td style="width:10%;">
									<select id="location1_<?php echo $i;?>" name="location1_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_batch(this.id);">	
									<?php 

										echo '<option value="">Select</option>';
										$record=$utilObj->getMultipleRow("location","1");
										foreach($record as $e_rec)
										{
											if($info['location']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
										}
									?>
									</select>
								</td>

								<td style="width:30%;">
									<div id="batch_div_<?php echo $i; ?>">
									<?php
										if($PTask=='update') {
									?>
										<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);">	
										<?php 
											$batch=$utilObj->getMultipleRow("purchase_batch","location='".$info['location']."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='production' OR type='packaging') ");

											echo '<option value="">Select</option>';
											foreach($batch as $e_rec)
											{
												if($info['batchname']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["batchname"] .'</option>';
											}
										?>
										</select>
									<?php } ?>
									</div>
								</td>

								<td style="width:30%;">
									<div id="stock_div_<?php echo $i;?>">
									<?php
										if($PTask=='update') {

										$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );
									?>
										<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $totalstock; ?>"/>
									<?php } ?>
				
									</div>
								</td>
								<td style='width:30%'>
									<input type="text" id="batch_remove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $i; ?>" value="<?php echo $info['quantity']; ?>" onblur="total_qty(<?php echo $i; ?>);"/>
								</td>
								<?php 
									if($i>1) {
								?>
									<td style='width:2%'>
										<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch(this.id);"></i>
									</td>
								<?php } ?>
							</tr>
						<?php } ?>
						<input type="hidden" name="total_batch_remove" id="total_batch_remove" value=""/>
						<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
					</tbody>
					
					<td style="width:30%;"></td>
					<td style="width:30%;"></td>
					<td style="width:30%;"></td>
					<td style="width:30%;">
						Total Quantity : <input type="text" readonly name="tot_qty" id="tot_qty" value="">
					</td>											
				</table>
				<br>
				<div class="col-md-2">
					<button type="button" class="btn btn-warning" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
				</div>
			</div>
		</div>
		<div class="modal-footer" >
			<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="saveinvoicesalebatch();" />
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
		</div>
	</div>

	<script>

		function total_qty(id) {
			
			// var quant = $("#batqty1_"+id).val();
			var totalquantity = 0;
		
			// Assuming batqty1_id elements are input fields
			$("[id^='batch_remove_']").each(function() {
				var quant = parseFloat($(this).val()) || 0;
				// Convert the value to a number, default to 0 if not a valid number
				totalquantity += quant;
			});
		
			console.log('Total :', totalquantity);
			
			$("#tot_qty").val(totalquantity);
		
		}
			
		function addRowbatch(tableID) {
			var count=$("#cnt2").val();	

			var i=parseFloat(count)+parseFloat(1);

			var cell1="<tr id='row2_"+i+"'>";
			
			cell1 += "<td style='width:10%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
			<option value=''>Select</option>\
				<?php
				$record=$utilObj->getMultipleRow("location","1");
				foreach($record as $e_rec){	
					echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
						
				?>
			</select></td>";
			
			cell1 += "<td style='width:30%'><div id='batch_div_"+i+"'></div></td>";

			cell1 += "<td style='width:30%'><div id='stock_div_"+i+"'></div></td>";
			
			cell1 += "<td style='width:30%'><input type='text' id='batch_remove_"+i+"' class='form-control number batch_remove_input' name='batch_remove_"+i+"' value='' onblur='total_qty("+i+")'/></td>";
		
			cell1 += "<td style='width2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


		
			$("#mybatch1").append(cell1);
			$("#cnt2").val(i);
			// $("#particulars_"+i).select2();
		}

		function delete_row_batch(rwcnt)
		{
			var id=rwcnt.split("_");
			rwcnt=id[1];
			var count=$("#cnt2").val();	
			
			if(count>1)
			{
				var r=confirm("Are you sure!");
				if (r==true)
				{		
					
					$("#row2_"+rwcnt).remove();
						
					for(var k=rwcnt; k<=count; k++)
					{
						var newId=k-1;
						
						jQuery("#row2_"+k).attr('id','row2_'+newId);
						
						jQuery("#idd_"+k).attr('name','idd_'+newId);
						jQuery("#idd_"+k).attr('id','idd_'+newId);
						jQuery("#idd_"+newId).html(newId);
						
						jQuery("#location1_"+k).attr('name','location1_'+newId);
						jQuery("#location1_"+k).attr('id','location1_'+newId);
						
						jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
						jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

						jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
						jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

						jQuery("#batch_remove_"+k).attr('name','batch_remove_'+newId);
						jQuery("#batch_remove_"+k).attr('id','batch_remove_'+newId);
						
						
						jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
						
					}
					jQuery("#cnt2").val(parseFloat(count-1)); 

					total_qty();

				}
			}
			else {
				alert("Can't remove row Atleast one row is required");
				return false;
			}	 
		}

		function get_batch(this_id) {
			var id=this_id.split("_");
			id=id[1];
			var location1 = $('#location1_'+id).val();
			var product = $("#product").val();

			jQuery.ajax({
				url: 'get_ajax_values.php',
				type: 'POST',
				data: { Type: 'get_batch', location1:location1,id:id,product:product },
				success: function (data) {
					$('#batch_div_'+id).html(data);
			
				}
			});

		}

		function get_batch_stock(this_id) {
			var id=this_id.split("_");
			id=id[1];
			var location1 = $('#location1_'+id).val();
			var batchname1 = $('#batchname1_'+id).val();
			var product = $("#product").val();

			jQuery.ajax({
				url: 'get_ajax_values.php',
				type: 'POST',
				data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,batchname1:batchname1 },
				success: function (data) {
					$('#stock_div_'+id).html(data);
				}
			});
		}

		function saveinvoicesalebatch(){
			var tot_qty = $('#tot_qty').val();
			var qty =$("#qty").val();
		
			if (tot_qty == qty) {
				saveinvoicesalesbatch();
			} else {
				if (qty > tot_qty) {
					alert("Your total batch quantity is less than Material quantity.");
					alert("Please add quantity in exsiting batch");
				} else {
					alert("Your total batch quantity is greater than Material quantity.");
					alert("Please remove some quantity from exsiting batch.");
				}
			}
		}

		$(document).ready(function () {
			function updateTotalBatchRemove() {
				var total = 0;

				$('.batch_remove_input').each(function () {
					var value = parseFloat($(this).val()) || 0;
					total += value;
				});

				$('#total_batch_remove').val(total);
			}

			$('.batch_remove_input').on('input', function () {
				updateTotalBatchRemove();
			});

			updateTotalBatchRemove();

			
		});

		function saveinvoicesalesbatch() {

			var cnt2 = $("#cnt2").val();
			var product = $("#product").val();
			var rate = $("#rate").val();
			var common_id = $("#common_id").val();
			var PTask = $("#PTask").val();
			var deliveryid = $("#id").val();
			var type = "sale_invoice";
			var batchIds = [];

			// ---------------------------------------------------------
			var location_array=[];
			var batchname_array=[];
			var batchremove_array=[];
			
			for(var i=1;i<=cnt2;i++)
			{	
				var location = $("#location1_"+i).val();
				var batchname = $("#batchname1_"+i).val();
				var batchremove = $("#batch_remove_"+i).val();

				location_array.push(location);
				batchname_array.push(batchname);
				batchremove_array.push(batchremove);
			}

			jQuery.ajax({
				url: 'get_ajax_values.php', type: 'POST',
				data: { Type: 'updatebattch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,cnt2:cnt2,rate:rate },
				success: function(data) {
					$('#saleinvoiceaddbatch').modal('hide');
			
				},
				error: function (xhr, status, error) {
					console.error("AJAX Error:", status, error);
				}
			});
		}

		function getqty(id){
			var batqty = parseFloat($("#batqty_" + id).val(), 10);
			var batchrmv = parseFloat($("#batch_remove_" + id).val(), 10);
			if(batqty<batchrmv){
				alert('Quantity is not greater than batch quantity');
				$("#batch_remove_"+id).val('');
			}
		}
	</script>

	<?php
	break;

	case 'viewbatch':
		$product_id = $_REQUEST['product'];
		$stock = $_REQUEST['stock'];
		$qty = $_REQUEST['qty'];
		$id = $_REQUEST['id'];
		$common_id = $_REQUEST['common_id'];
		$PTask = $_REQUEST['task'];
		$location = $_REQUEST['location'];
		$i = 0;
	?>
		<div  id="salesbatch">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Batch Form</h4> <br>

				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" >
				<div class="container">
					<table class = "table border-top" id="mybatch1">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>">
						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

						<thead>
							<tr>
								<th>Location</th>
								<th>Batch Name</th>
								<th>Stock Quantity</th>
								<th>Quantity</th>
							</tr>
						</thead>
								
						<tbody>
						<?php
							if($PTask == 'update' || $PTask=='view') {
								$product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_delivery' ");
							} else {
								$product = $utilObj->getMultipleRow("temp_sale_batch", "product='".$product_id."' AND parent_id='".$common_id."' AND type='sale_delivery' ");
								if(empty($purivodata)) {
									$product[0]['id']=1;
								}
								// $product[0]['id']=1;
							}
							foreach ($product as $info) {
								$i++;
						?>

								<tr id='row2_<?php echo $i; ?>'>
									<td style="width:10%;">
										<select id="location1_<?php echo $i;?>" name="location1_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_batch(this.id);">	
										<?php 

											echo '<option value="">Select</option>';
											$record=$utilObj->getMultipleRow("location","1");
											foreach($record as $e_rec)
											{
												if($info['location']==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
											}
										?>
										</select>
									</td>

									<td style="width:30%;">
										<div id="batch_div_<?php echo $i; ?>">
										<?php
											if($PTask=='update' || $PTask=='view') {
										?>
											<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);">	
											<?php 
												$batch=$utilObj->getMultipleRow("purchase_batch","location='".$info['location']."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='production' OR type='packaging' OR type='stockj_batch_in') ");

												echo '<option value="">Select</option>';
												foreach($batch as $e_rec)
												{
													if($info['batchname']==$e_rec["id"]) echo $select='selected'; else $select='';
													echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["batchname"] .'</option>';
												}
											?>
											</select>
										<?php } ?>
										</div>
									</td>

									<td style="width:30%;">
										<div id="stock_div_<?php echo $i;?>">
										<?php
											if($PTask=='update' || $PTask=='view')  {

											$totalstock = getbatchstock($info['purchase_batch'],$product_id, date('Y-m-d'),$info['location'] );
										?>
											<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $totalstock; ?>"/>
										<?php } ?>
                    
										</div>
									</td>
									<td style='width:30%'>
										<input type="text" id="batch_remove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $i; ?>" value="<?php echo $info['quantity']; ?>" onblur="total_qty(<?php echo $i; ?>)"/>
									</td>
									<?php 
										if($i>1) {
									?>
										<td style='width:2%'>
											<i class="bx bx-trash me-1"  id='deleteRowBatch_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row_batch(this.id);"></i>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
							<input type="hidden" name="total_batch_remove" id="total_batch_remove" value=""/>
							<input type="hidden" name="cnt2" id="cnt2" value="<?php echo $i; ?>">
						</tbody>
						<td style="width:30%;"></td>
						<td style="width:30%;"></td>
						<td style="width:30%;"></td>
						<td>
							Total Quantity : <input type="text" readonly name="tot_qty" id="tot_qty" value="" >
						</td>											
					</table>
					<br>
					<div class="col-md-2">
						<button type="button" class="btn btn-warning" id="addmore1" onclick="addRowbatch('mybatch1');">Add More</button>
					</div>
				</div>
			</div>
			<div class="modal-footer" >
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="savesalebatch();" />
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
			</div>
		</div>
	<script>
		
		function total_qty(id) {
				
			// var quant = $("#batqty1_"+id).val();
			var totalquantity = 0;
		
			// Assuming batqty1_id elements are input fields
			$("[id^='batch_remove_']").each(function() {
				var quant = parseFloat($(this).val()) || 0;
				// Convert the value to a number, default to 0 if not a valid number
				totalquantity += quant;
			});
		
			console.log('Total :', totalquantity);
			
			$("#tot_qty").val(totalquantity);
		
		}

		function addRowbatch(tableID) {
		var count=$("#cnt2").val();	

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row2_"+i+"'>";
		
		cell1 += "<td style='width:10%;'><select name='location1_"+i+"' onchange='get_batch(this.id);' class='select2 form-select' id='location1_"+i+"'>\
		<option value=''>Select</option>\
			<?php
			$record=$utilObj->getMultipleRow("location","1");
			foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
			}
					
			?>
		</select></td>";
		
		cell1 += "<td style='width:30%'><div id='batch_div_"+i+"'></div></td>";

		cell1 += "<td style='width:30%'><div id='stock_div_"+i+"'></div></td>";
		
		cell1 += "<td style='width:30%'><input type='text' id='batch_remove_"+i+"' class='form-control number batch_remove_input' name='batch_remove_"+i+"' value='' onblur='total_qty("+i+")' /></td>";
	
		cell1 += "<td style='width2%'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch(this.id);'></i></td>";


	
		$("#mybatch1").append(cell1);
		$("#cnt2").val(i);
		// $("#particulars_"+i).select2();
	}

	function delete_row_batch(rwcnt)
	{
		var id=rwcnt.split("_");
		rwcnt=id[1];
		var count=$("#cnt2").val();	
		
		if(count>1)
		{
			var r=confirm("Are you sure!");
			if (r==true)
			{		
				
				$("#row2_"+rwcnt).remove();
					
				for(var k=rwcnt; k<=count; k++)
				{
					var newId=k-1;
					
					jQuery("#row2_"+k).attr('id','row2_'+newId);
					
					jQuery("#idd_"+k).attr('name','idd_'+newId);
					jQuery("#idd_"+k).attr('id','idd_'+newId);
					jQuery("#idd_"+newId).html(newId);
					
					jQuery("#location1_"+k).attr('name','location1_'+newId);
					jQuery("#location1_"+k).attr('id','location1_'+newId);
					
					jQuery("#batch_div_"+k).attr('name','batch_div_'+newId);
					jQuery("#batch_div_"+k).attr('id','batch_div_'+newId);

					jQuery("#stock_div_"+k).attr('name','stock_div_'+newId);
					jQuery("#stock_div_"+k).attr('id','stock_div_'+newId);

					jQuery("#batch_remove_"+k).attr('name','batch_remove_'+newId);
					jQuery("#batch_remove_"+k).attr('id','batch_remove_'+newId);
					
					
					jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
					
				}
				jQuery("#cnt2").val(parseFloat(count-1)); 

				total_qty();

			}
		}
		else {
			alert("Can't remove row Atleast one row is required");
			return false;
		}	 
	}

	function get_batch(this_id) {
		var id=this_id.split("_");
		id=id[1];
		var location1 = $('#location1_'+id).val();
		var product = $("#product").val();

		jQuery.ajax({
			url: 'get_ajax_values.php',
			type: 'POST',
			data: { Type: 'get_batch', location1:location1,id:id,product:product },
			success: function (data) {
				$('#batch_div_'+id).html(data);
		
			}
		});

	}

	function get_batch_stock(this_id) {
		var id=this_id.split("_");
		id=id[1];
		var location1 = $('#location1_'+id).val();
		var batchname1 = $('#batchname1_'+id).val();
		var product = $("#product").val();

		jQuery.ajax({
			url: 'get_ajax_values.php',
			type: 'POST',
			data: { Type: 'get_batch_stock', location1:location1,id:id,product:product,batchname1:batchname1 },
			success: function (data) {
				$('#stock_div_'+id).html(data);
			}
		});
	}

	function getqty(id){
		var batqty = parseFloat($("#batqty_" + id).val(), 10);
		var batchrmv = parseFloat($("#batch_remove_" + id).val(), 10);
		if(batqty<batchrmv){
			alert('Quantity is not greater than batch quantity');
			$("#batch_remove_"+id).val('');
		}

	}

	function savesalebatch(){
		var tot_qty = $('#tot_qty').val();
		var qty =$("#qty").val();
		
		if (tot_qty == qty) {
			savesalesbatch();
		} else {
			if (qty > tot_qty) {
				alert("Your total batch quantity is less than Material quantity.");
				alert("Please add quantity in exsiting batch");
			} else {
				alert("Your total batch quantity is greater than Material quantity.");
				alert("Please remove some quantity from exsiting batch.");
			}
		}
	}
	
	$(document).ready(function () {
		function updateTotalBatchRemove() {
			var total = 0;

			$('.batch_remove_input').each(function () {
				var value = parseFloat($(this).val()) || 0;
				total += value;
			});

			$('#total_batch_remove').val(total);
		}

		$('.batch_remove_input').on('input', function () {
			updateTotalBatchRemove();
		});

		updateTotalBatchRemove();

	});


	function savesalesbatch() {

		var cnt2 = $("#cnt2").val();
		var product = $("#product").val();
		var common_id = $("#common_id").val();
		var PTask = $("#PTask").val();
		var deliveryid = $("#id").val();
		var type = "sale_delivery";
		var batchIds = [];
		
		var location_array=[];
		var batchname_array=[];
		var batchremove_array=[];
		
		for(var i=1; i<=cnt2; i++)
		{	
			var location = $("#location1_"+i).val();
			var batchname = $("#batchname1_"+i).val();
			var batchremove = $("#batch_remove_"+i).val();

			location_array.push(location);
			batchname_array.push(batchname);
			batchremove_array.push(batchremove);
		}

		jQuery.ajax({
			url: 'get_ajax_values.php',
			type: 'POST',
			data: { Type: 'updatebattch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,cnt2:cnt2 },
			success: function (data) {
				$('#salebatch').modal('hide');
		
			},
			error: function (xhr, status, error) {
				console.error("AJAX Error:", status, error);
			}
		});	

		// $(".batch_id").each(function () {
		// 	batchIds.push($(this).val());
		// });
		
		// // Iterate through batch IDs and update data
		// for (var i = 0; i < batchIds.length; i++) {
       
		// 	var id = batchIds[i];
		// 	var batqty = $("#batqty_"+id).val();
		// 	var batchname = $("#batchname_"+id).val();
		// 	var batchremove = $("#batch_remove_"+id).val();
			
		// }

	}

	</script>

	<?php
	break;

	case 'get_batch_stock':
		$i = $_REQUEST['id'];
		$totalstock = getbatchstock($_REQUEST['batchname1'],$_REQUEST['product'], date('Y-m-d'),$_REQUEST['location1'] );
	?>
		<div id="stoc_div_<?php echo $i; ?>">
			<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $totalstock; ?>"/>
		</div>
	<?php	
	break;

	case 'get_batch_rate':
		
		$i = $_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("purchase_batch","id='".$_REQUEST['batchname1']."'");
	?>
		<div id="rate_div_<?php echo $i; ?>">
			<input readonly type="text" id="batrate_<?php echo $i; ?>" class=" form-control number" name="batrate_<?php echo $i; ?>" value="<?php echo $mate1['bat_rate']; ?>"/>
		</div>
	<?php	
	break;

	case 'get_batch_rate1':
		
		$i = $_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("sale_batch","purchase_batch='".$_REQUEST['batchname1']."'");
	?>
		<div id="rate_div_<?php echo $i; ?>">
			<input readonly type="text" id="batrate_<?php echo $i; ?>" class=" form-control number" name="batrate_<?php echo $i; ?>" value="<?php echo $mate1['bat_rate']; ?>"/>
		</div>
	<?php	
	break;

	case 'get_batch':
		
		$i = $_REQUEST['id'];
		$location1 = $_REQUEST['location1'];
		$product = $_REQUEST['product'];
		
		$batch=$utilObj->getMultipleRow("purchase_batch","location='".$location1."' AND product='".$product."' AND (type='grn' OR type='purchase_invoice' OR type='transfer_batch_in' OR type='production_in' OR type='packaging_in' OR type='stockj_batch_in') ");
	?>	
		<input type="hidden" name="product_name" id="product_name" value="<?php echo $_REQUEST['product']; ?>">
		<div id='divbatch_<?php echo $i; ?>'>
			<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);get_batch_rate(this.id);">	
			<?php 
				echo '<option value="">Select</option>';
				foreach($batch as $e_rec)
				{
					if($pidata['id']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["batchname"] .'</option>';
				}
			?>
			</select>
		</div>

	<?php
	break;

	case 'get_sale_batch':

		$i = $_REQUEST['id'];
		$location1 = $_REQUEST['location1'];
		$product = $_REQUEST['product'];
		
		$batch=$utilObj->getMultipleRow("sale_batch","location='".$location1."' AND product='".$product."' AND type='sale_invoice' ");
		// $product = $utilObj->getMultipleRow("sale_batch", "product='" . $product_id . "' AND delivery_id='".$id."' AND type='sale_invoice' ");
	?>	
		<input type="hidden" name="product_name" id="product_name" value="<?php echo $_REQUEST['product']; ?>">
		<div id='divbatch_<?php echo $i; ?>'>
			<select id="batchname1_<?php echo $i;?>" name="batchname1_<?php echo $i;?>" class="select2 form-select required" data-allow-clear="true" onchange="get_batch_stock(this.id);get_batch_rate(this.id);">	
			<?php 
				echo '<option value="">Select</option>';
				foreach($batch as $e_rec)
				{	
					$mate1=$utilObj->getSingleRow("purchase_batch","id='".$e_rec["batchname"]."'");
					if($pidata['id']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["purchase_batch"].'" '.$select.'>'.$mate1["batchname"] .'</option>';
				}
			?>
			</select>
		</div>

	<?php
	break;

	case 'updatebattch':

		if($_REQUEST['PTask']=='update') {
			$common = $_REQUEST['deliveryid'];
		} else {
			$common = $_REQUEST['common_id'];
		}

		$batchdata = $utilObj->deleteRecord("temp_sale_batch", "product='".$_REQUEST['product']."' AND parent_id='".$common."' AND type='".$_REQUEST['type']."' ");

		$cnt2 = $_REQUEST['cnt2'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchname_array'][$i],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'created'=>date("Y-m-d H:i:s"),'lastedited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location_array'][$i],'bat_rate'=>$_REQUEST['rate'] );

			$insertedId=$utilObj->insertRecord('temp_sale_batch', $arrValue1);
		}
		
		
	break;

	case 'check_batch_type':
		
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
		$record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['id']."'");
	?>

		<?php if ($mate1['batch_maintainance'] == '1') { ?>
			<div id='batchdiv_<?php echo $i;?>'>

			<?php if($_REQUEST['PTask']=='update') { ?>
				<button type="button" class="btn btn-light" onClick="optn_batch_modal1('<?php echo $i; ?>','<?php echo $_REQUEST['product'];?>','<?php echo $_REQUEST['id']; ?> ')"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
			<?php } ?>

		</div>
		<?php } else {
			echo "This Product Doesn't support Batch";
		} ?>
		
		

		<?php
		break;
	case 'check_batch_type1':
		
		$i=$_REQUEST['id'];
		$mate1=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['product']."'");
	?>

		<?php if ($mate1['batch_maintainance'] == '1') { ?>
			<div id='batchdiv_<?php echo $i;?>'>
			
				<button type="button" class="btn btn-light" onClick="check_qty(<?php echo $i;?>)"  ><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
			
			</div>
		<?php } else {
			echo "This Product Doesn't support Batch";
		} ?>

	<?php
	break;

	case 'product_check':
		
	?>
		
		<select id="particulars_<?php echo $_REQUEST['i'];?>" name="particulars_<?php echo $_REQUEST['i'];?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
			<?php 
				echo '<option value="">Select</option>';
				$record=$utilObj->getMultipleRow("stock_ledger","cat_id='".$_REQUEST['val']."'");
				foreach($record as $e_rec)
				{
					
					if($rows['particulars']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
				}
				?>  
		</select>
	<?php
	break;

	// -------------------------------------------- GRN return --------------------------------------------
	case 'get_grnno':
	
		$grn_return=$utilObj->getSingleRow("grn_return"," id='".$_REQUEST['id']."' ");
		?>	
		<label class="form-label"> GRN No. <span class="required required_lbl" style="color:red;">*</span></label>
		<div >
			<?php if($_REQUEST['PTask']=='view' ){
				// $readonly="readonly";
				// $purchase_invoice_no=$utilObj->getSingleRow("purchase_invoice","id in (select purchase_invoice_no from  purchase_return where id ='".$_REQUEST['id']."')");
			?>
				<!-- <input type="hidden" id="purchase_invoice_no" <?php echo $readonly;?> name="purchase_invoice_no" value="<?php echo $purchase_invoice_no['id'];?>"/>
				<input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly;?>  value="<?php echo $purchase_invoice_no['invoicenumber'];?>"/> -->
					
			<?php } else { ?>
				<select id="grn_no" name="grn_no" <?php echo $disabled;?> class="select2 form-select " data-allow-clear="true" onchange=" grn_rowtable(this.value);">
				<option value=""> Select GRN No</option>
				
					<?php 
						
						// $record=$utilObj->getMultipleRow("purchase_invoice","supplier ='".$_REQUEST['supplier']."' AND location ='".$_REQUEST['location']."' group by invoicenumber");

						$record=$utilObj->getMultipleRow("grn","id not in (select purchaseorder_no from purchase_invoice )");

						foreach($record as $e_rec){
							if($grn_return['grn_order_no']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["grn_no"].'</option>';
						}
					?> 
				</select>
				
			<?php } ?>
		</div>

	<?php
	break;
	
	// ----------------------------- GRN Return 2 -----------------------------------
	case 'grn_rowtable':
		// $purchaseorder_no=$_REQUEST['purchaseorder_no'];
		$grn_no=$_REQUEST['grn_no'];
		$common_id = $_REQUEST['ad'];
		$location = $_REQUEST['location'];

		/* $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['supplier']."' ");
		$state= $account_ledger['mail_state']; */
		$grn_return=$utilObj->getSingleRow("grn_return"," id='".$_REQUEST['id']."'");
	
	 
		if($grn_no!=''){
			$read="readonly";
		}else{
			$read=" ";
		}
	?>
		<input type="hidden" name="grn_no" id="grn_no" value="<?php echo $grn_no; ?>" >
		<table class="table table-bordered " id="myTable" > 
			<thead>
				<tr>
					<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
					<th style="width: 10%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Unit </th>
					<th style="width: 10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
					<th style="width: 10%;text-align:center;">Rejected Quantity</th>
					<th style="width: 10%;text-align:center;">Batch</th>
					<?php if($_REQUEST['Task']!='view'&& $_REQUEST['type']!='Against_Purchaseorder'){?>
					<th style="width:2%;text-align:center;"></th>
					<?php }?>
				</tr>
			</thead>
			<tbody>
			<?php 
				$i=0;
				if( ($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') )
				{ 
					// echo "condi 1";
					$record5=$utilObj->getMultipleRow("grn_return_details","parent_id='".$_REQUEST['id']."'");
				} else
				if(($_REQUEST['grn_no']!='')&&$grn_return['grn_order_no']!=$_REQUEST['grn_no'])
				{ 
					// echo "condi 2";
					$record5=$utilObj->getMultipleRow("grn_details","parent_id='".$_REQUEST['grn_no']."' ");
					$read="readonly";
				} 
				else
				{
					$record5[0]['id']=1;	
				}  
				foreach($record5 as $row_demo)
				{
					if(($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view'))
					{ 
						$returnqty=$row_demo['return_qty'];
					} else {
						$returnqty=0;	 
					}

					$i++;
					$totalstock = 0;
					$product=$utilObj->getSingleRow("stock_ledger"," id='".$row_demo['product']."' ");
						
			?>
				<tr id='row_<?php echo $i;?>'>
					<td style="text-align:center;width:2%;">
						<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
					</td>
					<td  style="width: 20%;">
						<input type="hidden" id="product_<?php echo $i;?>" <?php echo $readonly;?> name="product_<?php echo $i;?>" value="<?php echo $product['id'];?>"/>

						<input type="text"  id="pname_<?php echo $i;?>" name="pname_<?php echo $i;?>" style="width:100%;" class=" form-control"  readonly <?php echo $readonly;?>  value="<?php echo $product['name'];?>"/>
					</td>
					
					<td style="width: 10%;">
						<div id='unitdiv_<?php echo $i;?>'>
							<input type="text" id="unit_<?php echo $i;?>" class=" form-control required" readonly <?php echo $readonly.$read;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit'];?>" />
						</div>
					</td>
					
					<td style="width: 10%;">
						<input type="text" id="qty_<?php echo $i; ?>" class=" form-control number" <?php echo $readonly;?> name="qty_<?php echo $i; ?>" value="<?php echo $row_demo['qty'];?>" readonly <?php echo $readonly; ?> />
					</td>
					

					<td style="width: 10%;">
						<input type="text" id="rejectedqty_<?php echo $i;?>" class=" form-control number" name="rejectedqty_<?php echo $i;?>" value="<?php echo $returnqty;?>" />
						<input type="hidden" name="res_<?php echo $i; ?>" id="res_<?php echo $i;?>" value="" >
					</td>

					<td style="width: 10%;text-align:center;">
						<button type="button" class="btn btn-light"  data-bs-toggle="modal" data-bs-target="#grnreturnbatch" onclick="check_qty(<?php echo $i;?>);"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
					</td>

				</tr>
			<?php } ?>
				<input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
			</tbody>
		</table>
		
		<div class="modal fade" style = "max-width=40%; " id="grnreturnbatch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style = "max-width: 800px; margin-left: 250px;" id="grbatch">
			
				</div>
			</div>
		</div>
		
		<script>

			function check_qty(i)
			{
				var quantity = $("#rejectedqty_"+i).val();
				var product = $("#product_"+i).val();

				if (quantity == '' || quantity=='0') {
					alert ('please enter quantity first . . . !');

				} else {
					grnreturn_batchdata(i);
				}
			}

			function grnreturn_batchdata(i) {
								                      
				var qty =$("#rejectedqty_"+i).val();
				var stock =$("#stock_"+i).val();
				var common_id =$("#ad").val();
				var PTask =$("#PTask").val();
				var id = $("#id").val();
				var product =$("#product_"+i).val();
				var grn_no =$("#grn_no").val();
				// alert(grn_no);

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'grnreturn_batchdata', product:product,stock:stock,qty:qty,PTask:PTask,common_id:common_id,i:i,id:id,grn_no:grn_no},
					success: function (data) {
						$('#grbatch').html(data);
						$('#grnreturnbatch').modal('show');
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}

		</script>
	<?php
	break;

	// --------------------------- GRN Return Batch ---------------------------
	case 'grnreturn_batchdata':
		$product_id = $_REQUEST['product'];
		$qty = $_REQUEST['qty'];
		$PTask = $_REQUEST['PTask'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$grn_no = $_REQUEST['grn_no'];

		$maincnt=$_REQUEST['i'];
		$i=0;
		$totalstock=0;
	?>
	
		<div id="grbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<div class="modal-body">
				<div class="container">

					<table class = "table border-top" >
							
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<thead>
							<tr>
								<th>Location</th>
								<th>Batch name</th>
								<th>Batch Stock</th>
								<th>Quantity</th>
							</tr>
						</thead>
						<tbody>
						<?php

							// if($PTask != 'update') {
							// 	$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND flag='0' AND parent_id='".$grn_no."' AND type='grn' ");
							// } else {
							// 	$product = $utilObj->getMultipleRow("purchase_batch", "product='". $product_id ."' AND parent_id='".$id."' AND type='grn_return' ");
							// }
							// $productsum = $utilObj->getSum("purchase_batch", "product='" . $product_id . "'", "batqty");
							// $sumqty=0;

							if($PTask != 'update' && $_REQUEST['PTask']!='view') {
								$product = $utilObj->getMultipleRow("temp_batch", "product='" . $product_id . "' AND parent_id='".$common_id."' AND type='grn_return' ");
								if(empty($product)) {
									$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND flag='0' AND parent_id='".$grn_no."' AND type='grn' ");
								}
							} else {
								$product = $utilObj->getMultipleRow("purchase_batch", "product='". $product_id ."' AND parent_id='".$id."' AND type='grn_return' ");
							}
							$productsum = $utilObj->getSum("purchase_batch", "product='" . $product_id . "'", "batqty");
							$sumqty=0;

							foreach ($product as $info) {

							$loc=$utilObj->getSingleRow("location","id ='".$info['location']."'");
							if($PTask != 'update') {
								$totalstock = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $info['location']);
							} else {
								$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $info['location']);
							}

							if($PTask == 'update' || $_REQUEST['PTask']=='view') {

								$b_id = $info['purchase_batch'];
								$totsum += $info['batqty'];
								$bat_qty=$info['batqty'];
							} else {

								$b_id = $info['id'];
								$bat_qty=$info['quantity'];
							}

							if($_REQUEST['PTask']=='view') {

								$readonly = "readonly";
								$disabled = "disabled";
							}

							$i++;
						?>
							<tr id="row1_<?php echo $i;?>">
								<input type="hidden" name="batch_id_<?php echo $i; ?>" id="batch_id_<?php echo $i;?>" class="batch_id" value="<?php echo $b_id; ?>">
								
								<td>
									<input type="text" id="locationname_<?php echo $i;?>" class=" form-control number" name="locationname_<?php echo $i;?>" value="<?php echo $loc['name']; ?>" readonly />
									<input type="hidden" name="location_<?php echo $i;?>" id="location_<?php echo $i;?>" value="<?php echo $info['location']; ?>">
								</td>
								<td>
									<input type="text" id="batchname1_<?php echo $i; ?>" class=" form-control number" name="batchname1_<?php echo $i; ?>" value="<?php echo $info['batchname']; ?>" readonly />
								</td>
							<?php 
								if($PTask == 'update') {
									$total = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
									
									$quantity = $totalstock+$info['batqty'];
									$sumqty+=$totalstock+$info['batqty'];
							?>
								<td>
									<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $quantity; ?>"/>
								
								</td>
								<td>
									<input type="text" id="batch_remove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $i; ?>" value="<?php echo $bat_qty; ?>"/>
								</td>
							<?php } else { ?>
								<td>
									<input readonly type="text" id="batqty_<?php echo $i; ?>" class=" form-control number" name="batqty_<?php echo $i; ?>" value="<?php echo $totalstock; ?>"/>
								</td>
								<td>
									<input type="text" id="batch_remove_<?php echo $i; ?>" class="form-control number batch_remove_input" name="batch_remove_<?php echo $i; ?>" value="<?php echo $bat_qty; ?>"/>
								</td>
							<?php } ?>
							</tr>
						<?php } ?>
						<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
						</tbody>
						<td></td>
						<td></td>
						<td></td>
						<td>
							Total Quantity : <input type="text" class="form-control number" name="tot_qty" id="tot_qty" value="<?php echo $totsum; ?>">
						</td>
					</table>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<input type="button" class="btn btn-primary" id="closemodal" name="sbumit" value="Submit"  onClick="check_submit_qty();" />
			</div>
		</div>
		
		<script>

			$(document).ready(function () {
				function updateTotalBatchRemove() {
					var total = 0;

					$('.batch_remove_input').each(function () {
						var value = parseFloat($(this).val()) || 0;
						total += value;
					});

					$('#tot_qty').val(total);
				}

				$('.batch_remove_input').on('input', function () {
					updateTotalBatchRemove();
				});

				updateTotalBatchRemove();

			});

			function check_submit_qty() {

				var tot_qty = $("#tot_qty").val();
				var main_qty = $("#qty").val();

				if (tot_qty == main_qty) {
					grnreturnbatch();
					// alert("LoL Noobs . . .");
				} else {
					if (main_qty > tot_qty) {
						alert("Your total batch quantity is less than Material quantity.");
						alert("please add quantity in exsiting batch or add new batch.");
					} else {
						alert("Your total batch quantity is greater than Material quantity.");
						alert("please remove some quantity from exsiting batch.");
					}
				}

			}

			function grnreturnbatch() {

				// var cnt1 = $("#cnt1").val();
				// var product = $("#product_batch").val();
				// var common_id = $("#common_id").val();
				// var PTask = $("#PTask").val();
				// var deliveryid = $("#id").val();
				// var type = "grn_return";

				// var batchname_array=[];
				// var batchid_array=[];
				// var batchremove_array=[];
				// var location_array=[];
				
				var cnt1 = $("#cnt1").val();
				var maincnt = $("#maincnt").val();
				var product = $("#product_batch").val();
				var common_id = $("#common_id").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "grn_return";

				var res = 1;

				// var batchIds = [];
				// $(".batch_id").each(function () {
				// 	batchIds.push($(this).val());
				// });

				var batchname_array=[];
				var batchid_array=[];
				var batchremove_array=[];
				var location_array=[];
				
				for(var i=1;i<=cnt1;i++)
				{	
					var location = $("#location_"+i).val();
					var batchname = $("#batchname1_"+i).val();
					var batchid = $("#batch_id_"+i).val();
					var batchremove = $("#batch_remove_"+i).val();

					location_array.push(location);
					batchname_array.push(batchname);
					batchid_array.push(batchid);
					batchremove_array.push(batchremove);
				}

				jQuery.ajax({
					url: 'get_ajax_values.php', type: 'POST',
					data: { Type: 'grnreturnbatch',deliveryid:deliveryid,batchremove_array:batchremove_array,product:product,common_id:common_id,batchname_array:batchname_array,PTask:PTask,type:type,location_array:location_array,batchid_array:batchid_array,cnt1:cnt1 },
					success: function (data) {

						$('#grnreturnbatch').modal('hide');
						$('#res_'+maincnt).val(res);
						// alert(data);
					},
					error: function (xhr, status, error) {

						console.error("AJAX Error:", status, error);
					}
				});


				// $(".batch_id").each(function () {
				// 	batchIds.push($(this).val());
				// });

				// // Iterate through batch IDs and update data
				// for (var i = 0; i < batchIds.length; i++) {

				// 	var id = batchIds[i];
				// 	var batqty = $("#batqty_"+id).val();
				// 	var batchname = $("#batchname1_"+id).val();
				// 	var location = $("#location_"+id).val();
				// 	var batchremove = $("#batch_remove_"+id).val();
				// 	jQuery.ajax({
				// 		url: 'get_ajax_values.php',
				// 		type: 'POST',
				// 		data: { Type: 'grnreturnbatch', id:id,deliveryid:deliveryid,batqty:batqty,batchremove:batchremove,product:product,common_id:common_id,batchname:batchname,PTask:PTask,type:type,location:location},
				// 		success: function (data) {
				// 			$('#grnreturnbatch').modal('hide');
					
				// 		},
				// 		error: function (xhr, status, error) {
				// 			console.error("AJAX Error:", status, error);
				// 		}
				// 	});	
				// }

			}

		</script>
	<?php 
	break;

	// ------------------------- GRN return batch Handler -------------------------
	case 'grnreturnbatch':
	
		if($_REQUEST['PTask']=='update'){
			$common=$_REQUEST['deliveryid'];
		}else{
			$common=$_REQUEST['common_id'];
		}

		$cnt2 = $_REQUEST['cnt1'];

		for($i=0;$i<$cnt2;$i++) {

			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['batchid_array'][$i],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname_array'][$i],'quantity'=>$_REQUEST['batchremove_array'][$i],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location_array'][$i] );
		
			$insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);
		}

		// $arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['id'],'product'=>$_REQUEST['product'],'location'=>$_REQUEST['location'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname'],'quantity'=>$_REQUEST['batchremove'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s") );

		// $insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);
	
	break;
	
	// --------------------------- Sale Invoice Batch ---------------------------
	case 'check_batch_type2':

		$i = $_REQUEST['id'];
		$mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "'");
		?>
		<?php //if ($mate1['batch_maintainance'] == '1') { ?>
					<div id='divbatch_<?php echo $i; ?>'>
						<button type="button" class="btn btn-light btn-sm" onClick="check_qty(<?php echo $i; ?>)"  style="text-align:center;"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>

				</div>
		<?php //} else {
			// echo "This Product Doesn't support Batch";
		//} ?>


		<?php
		break;

		case 'getpricelevel':
			$data=$utilObj->getSingleRow("account_ledger","id='".$_REQUEST['customer']."'"); 
			if($data['price_level']=='1'){
				echo $data['price_level'];
			}else{
				?>
				<label class="form-label">Price Level Type<span class="required required_lbl" style="color:red;">*</span></label>
				<select id="pricetype" name="pricetype" onchange="getapplicable();" class="form-select select2 tdstax_field" data-allow-clear="true">
			<option value="">Select</option>
				
						<option  value="Applicable" <?php if($rows['type']=='Applicable'){ echo 'selected';}else{ echo ' ';} ?> >Applicable</option>
						<option  value="NotApplicable" <?php if($rows['type']=='NotApplicable'){ echo 'selected';}else{ echo ' ';} ?> >Not Applicable</option>
			</select>
			
			<?php
			}

		break;

		case 'getapplicable':
			if($_REQUEST['pricetype']=='Applicable'){
			?><label class="form-label">Price Level <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="pricelevel" name="pricelevel" <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
				<option value="">Select</option>
					<?php	
						$data=$utilObj->getMultipleRow("pricelist","1 AND applicable_date = (SELECT MAX(applicable_date) FROM pricelist) group by price_level"); 
						foreach($data as $info){
							if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
							echo  '<option value="'.$info["price_level"].'" '.$select.'>'.$info["price_level"].'</option>';
						}  
					?>
			</select>
		<?php } ?>
			
		<?php
		break;

		case 'getlocation':
			$saleorder = $utilObj->getSingleRow("sale_order","id = '".$_REQUEST['saleorder']."'");
		?>

			<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="locations" name="locations" class="select2 form-select required" data-allow-clear="true" >	
			<?php 
				echo '<option value="">Select Location</option>';
				$record=$utilObj->getMultipleRow("location","1");
				foreach($record as $e_rec)
				{
					if($saleorder['location']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
				}
			?>
			</select>
	<?php
	break;

	case 'get_gstdata':
	
		$i = $_REQUEST['id'];

		// $mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "' ");

		$odate = $_REQUEST['date'];
		$order_date=date('Y-m-d',strtotime($odate));

		$rows = $utilObj->getSingleRow("ledger_gst_history", "product='".$_REQUEST['product']."' ");

		if($order_date>=$rows['fromdate'] && $order_date<=$rows['todate']) {
			$cmd = "fromdate<='".$order_date."' AND todate>='".$order_date."' ";
		} else {
			$cmd = "fromdate<='".$order_date."' AND todate IS NULL";
		}

		$mate1 = $utilObj->getSingleRow("ledger_gst_history", "$cmd AND product='".$_REQUEST['product']."' ");
		
		$mate2 = $utilObj->getSingleRow("gst_data", "id='".$mate1['igst']."' ");

		echo trim($mate2 ['igst'] . "#" . $mate2['cgst'] . "#" . $mate2['sgst']);

	break;

	case 'get_address':
	
		$account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$_REQUEST['customer']."' ");
  		$mate2=$utilObj->getSingleRow("account_ledger_address"," al_id='".$_REQUEST['customer']."' ");
  		$mate3=$utilObj->getSingleRow("states","code='".$account_ledger['mail_state']."' ");

		// echo $account_ledger_address['address'];
		echo trim($mate2['address']."#".$mate3['name']."#".$mate3['code']."#".$mate2['address']."#".$account_ledger['mail_pin']."#".$account_ledger['mail_pin']);

	break;

	case 'check_stockjournalbatch':
	
		$i = $_REQUEST['id'];
		$type = $_REQUEST['type'];
		$mate1 = $utilObj->getSingleRow("stock_ledger", "id='" . $_REQUEST['product'] . "'");
	?>
		<?php if ($type=='consumption') { ?>

			<div id='divbatch_<?php echo $i; ?>'>
				<button type="button" class="btn btn-light" onclick="stockjournal_batchdata('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#stockjournalbatch"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
			</div>
		<?php } else { ?>

			<div id='divbatch_<?php echo $i; ?>'>
				<button type="button" class="btn btn-light" onclick="stockjournal_batchdata1('<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#stockjournalbatch1"><i class="fas fa-box fa-lg" style="color: #000000;"></i></button>
			</div>
		<?php } ?>

	<?php
	break;

	case 'stockjournal_batchdata':
	
		$product_id = $_REQUEST['product'];
		$qty = $_REQUEST['qty'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$type = $_REQUEST['stock_type'];

		$maincnt=$_REQUEST['i'];
		$c=0;
		$totalstock=0;
	?>

		<div id="stkjbatch">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">

				<p>
					Type :<?php echo $type; ?> &nbsp;&nbsp; Main QTY : <?php echo $qty; ?>
				</p>

				<table class = "table border-top" >
						
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
					<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
					<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">

					<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
					<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
					<input type="hidden" name="main_type" id="main_type" value="<?php echo $type; ?>">
					<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<thead>
							<tr>
								<th>Batch Name</th>
								<th>Batch Rate</th>
								<th>Batch Stock</th>
								<th>Quantity</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$b_id = '';
							if($PTask != 'update' && $_REQUEST['PTask']!='view') {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND flag='0' AND location='".$location."' AND purchase_batch='' ");
							} else {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."'  ");
							}
							// $productsum = $utilObj->getSum("purchase_batch", "product='" . $product_id . "'", "batqty");
							$sumqty=0;
							foreach ($product as $info) {
								
							if($PTask != 'update' && $_REQUEST['PTask']!='view') {

								$totalstock = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
							} else {

								$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $location);
							}

							if($PTask == 'update' && $_REQUEST['PTask']=='view') {

								$b_id = $info['purchase_batch'];
							} else {

								$b_id = $info['id'];
							}

							$c++;
						?>
							<tr id="row1_<?php echo $c;?>">
								<input type="hidden" name="id[]" class="batch_id" value="<?php echo $b_id; ?> ">
								<td>
									<input type="text" id="batchname1_<?php echo $b_id; ?>" class=" form-control number" name="batchname1_<?php echo $b_id; ?>" value="<?php echo $info['batchname']; ?>" readonly />
								</td>

								<td>
									<input type="text" id="batrate_<?php echo $b_id; ?>" class=" form-control number" name="batrate_<?php echo $b_id; ?>" value="<?php echo $info['bat_rate']; ?>" readonly />
								</td>

							<?php 
								if($PTask == 'update' || $_REQUEST['PTask']=='view') {
									
									$total = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
									
									$quantity = $totalstock+$info['quantity'];
									$qtysum = $info['quantity']*$info['bat_rate'];
									$sumqty+=$totalstock+$info['quantity'];?>
								<td>
									<input readonly type="text" id="batqty_<?php echo $b_id; ?>" class=" form-control number" name="batqty_<?php echo $b_id; ?>" value="<?php echo $quantity; ?>"/>
								</td>
								<td>
									<input type="text" id="batchremove_<?php echo $b_id; ?>" class="form-control number batch_remove_input" name="batchremove_<?php echo $b_id; ?>" onkeyup="get_qtytot(this.id);" value="<?php echo $info['batqty']; ?>"/>
								</td>
								<td>
									<input type="text" id="qtysum_<?php echo $b_id; ?>" class="form-control number" name="qtysum_<?php echo $b_id; ?>"  value="<?php echo $qtysum; ?>" readonly />
								</td>
							<?php } else { ?>

								<td>
									<input readonly type="text" id="batqty_<?php echo $b_id; ?>" class=" form-control number" name="batqty_<?php echo $b_id; ?>" value="<?php echo $totalstock; ?>"/>
								</td>
								<td>
									<input type="text" id="batchremove_<?php echo $b_id; ?>" class="form-control number batch_remove_input" name="batchremove_<?php echo $b_id; ?>" onKeyup="get_qtytot(this.id);" value=""/>
								</td>
								<td>
									<input type="text" id="qtysum_<?php echo $b_id; ?>" class="form-control number " name="qtysum_<?php echo $b_id; ?>" onKeyup="" value="" readonly />
								</td>
							<?php } ?>
								
							</tr>
							<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $c; ?>">

						<?php } ?>
						</tbody>
						<td></td>
						<td></td>
						<td></td>
						<td>
							Total Quantity : <input class="form-control number " readonly type="text" name="tot_qty" id="tot_qty" value="">
						</td>
						<td>
							Total : <input class="form-control number " readonly type="text" name="tot_sub" id="tot_sub" value="" >
						</td>
					</table>
				</div>
			</div>
			
			<div class="modal-footer">
				<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Submit"  onClick="checksub();" />
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			function get_qtytot(bid) {

				var id=bid.split("_");
				bid=id[1];

				var batrate = $("#batrate_"+bid).val(); 
				var batqty = $("#batchremove_"+bid).val();

				var qtysum = parseFloat(batrate)*parseFloat(batqty);

				// alert(qtysum);
				$("#qtysum_"+bid).val(qtysum);
				
				total_qty(bid);
			}

			function total_qty(id) {
				
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
				var totalsub = 0;

				// Assuming batqty1_id elements are input fields
				$("[id^='batchremove_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});

				$("[id^='qtysum_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalsub += quant;
				});

				// console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);
				$("#tot_sub").val(totalsub);

				
			}

			function checksub() {

				var mainqty = $("#qty").val();
				var maincnt = $("#maincnt").val();
				var totalquantity = $("#tot_qty").val();

				if(mainqty!=totalquantity) {

					alert("Please check your batch QTY!!!");
				} else {

					stockjournalbatch(maincnt);
				}

			
			}

			function stockjournalbatch(maincnt) {

				var cnt1 = $("#cnt1").val();
				var location = $("#location").val();
				var product = $("#product_batch").val();
				var common_id = $("#ad").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "";
				var batchIds = [];
				var batch_type = $("#main_type").val();

				var tot_qty = $("#tot_qty").val();
				var tot_sub = $("#tot_sub").val();

				var mqty = $("#qty").val();
				var mamt = '';

				var mrate = parseFloat(tot_sub)/parseFloat(tot_qty);

				var type = "stockj_batch_out";

				$(".batch_id").each(function () {

					batchIds.push($(this).val());
				});

				// Iterate through batch IDs and update data
				for (var i = 0; i < batchIds.length; i++) {

					var id = batchIds[i];
					var batqty = $("#batqty_"+id).val();
					var batrate = $("#batrate_"+id).val();
					var batchname = $("#batchname1_"+id).val();
					var batchremove = $("#batchremove_"+id).val();

					if(batchremove!=0 || batchremove!='') {
						jQuery.ajax({
							url: 'get_ajax_values.php',
							type: 'POST',
							data: { Type: 'stockjournalbatch', id:id,deliveryid:deliveryid,batqty:batqty,batchremove:batchremove,product:product,common_id:common_id,batchname:batchname,PTask:PTask,type:type,location:location,batrate:batrate },
							success: function (data) {

								$('#stockjournalbatch').modal('hide');

								$("#rate_"+maincnt).val(mrate);

								mamt = parseFloat(mqty)*parseFloat(mrate);

								$("#amount_"+maincnt).val(mamt);

							},
							error: function (xhr, status, error) {
								console.error("AJAX Error:", status, error);
							}
						});	

					}
				}
			}
		</script>
	<?php
	break;

	case 'stockjournalbatch':
		
		if($_REQUEST['PTask']=='update') {

			$common=$_REQUEST['deliveryid'];
		} else {

			$common=$_REQUEST['common_id'];
		}

		$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'purchase_batch'=>$_REQUEST['id'],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST['batchname'],'bat_rate'=>$_REQUEST['batrate'],'quantity'=>$_REQUEST['batchremove'],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

		$insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);

	break;

	case 'stockjournal_batchdata1':
	
		$product_id = $_REQUEST['product'];
		$PTask = $_REQUEST['PTask'];
		$location = $_REQUEST['location'];
		$qty = $_REQUEST['qty'];
		$rate = $_REQUEST['rate'];
		$stock = $_REQUEST['stock'];
		$common_id = $_REQUEST['common_id'];
		$id = $_REQUEST['id'];
		$type = $_REQUEST['stock_type'];

		$maincnt=$_REQUEST['i'];
		$i=0;
		$totalstock=0;
	?>

		<div id="stkjbatch1">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Batch Details</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<p>
						Type :<?php echo $type; ?> &nbsp;&nbsp; Rate :<?php echo $rate; ?> &nbsp;&nbsp; QTY :<?php echo $qty; ?>
					</p>

					<table class = "table border-top" id="mybatch1">
							
						<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>">
						<input type="hidden" name="product_batch" id="product_batch" value="<?php echo $product_id; ?>">
						<input type="hidden" name="location" id="location" value="<?php echo $location; ?>">

						<input type="hidden" name="common_id" id="common_id" value="<?php echo $common_id; ?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
						<input type="hidden" name="qty" id="qty" value="<?php echo $qty; ?>">
						<input type="hidden" name="main_type" id="main_type" value="<?php echo $type; ?>">
						<input type="hidden" name="maincnt" id="maincnt" value="<?php echo $maincnt; ?>">
						<thead>
							<tr>
								<th >Batch name</th>
								<th >Batch Qty</th>
								<th >Batch Rate</th>
								<th >Amount</th>
								<th ></th>
							</tr>
						</thead>
						<tbody>
						<?php
							$b_id = '';
							if($PTask != 'update' && $_REQUEST['PTask']!='view') {

								$product[0]['id']=1;
							} else {

								$product = $utilObj->getMultipleRow("purchase_batch", "product='" . $product_id . "' AND parent_id='".$id."' AND location='".$location."' ");
							}

							$sumqty=0;
							foreach ($product as $info) {
								
							// if($PTask != 'update'){
							// 	$totalstock = getbatchstock($info['id'],$info['product'], date('Y-m-d'), $location);
							// }else{
							// 	$totalstock = getbatchstock($info['purchase_batch'],$info['product'], date('Y-m-d'), $location);
							// }

							// if($PTask == 'update') {

							// 	$b_id = $info['purchase_batch'];
							// } else {

							// 	$b_id = $info['id'];
							// }

							$i++;
						?>
							<tr id='row2_<?php echo $i; ?>'>
								<td style="max-width:5%;">
									<input type='text' id="batchname_<?php echo $i; ?>" class='form-control number' name="batchname_<?php echo $i; ?>" value="<?php echo $info['batchname']; ?>" />
								</td>

								<td style="max-width:5%;">
									<input type='text' id="batqty_<?php echo $i; ?>" class='form-control number' name="batqty_<?php echo $i; ?>" value="<?php echo $info['batqty']; ?>" />
								</td>

								<td style="max-width:5%;">
									<input type='text' id="batrate_<?php echo $i; ?>" class='form-control number' name="batrate_<?php echo $i; ?>" value="<?php echo $info['bat_rate']; ?>" onkeyup="get_qtytot(this.id);" />
								</td>

								<td style="max-width:5%;">
									<input type="text" id="qtysum_<?php echo $i; ?>" class="form-control number " name="qtysum_<?php echo $i; ?>" onKeyup="" value="" readonly />
								</td>

								<td style="max-width:1%;text-align:center;">
									<i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;text-align:center;'  onclick='delete_row_batch2(this.id);'></i>
								</td>
							</tr>
						<?php } ?>
						<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
						</tbody>
						<td></td>
						<td></td>
						<td>
							Total Quantity : <input class="form-control number " readonly type="text" name="tot_qty" id="tot_qty" value="">
						</td>
						<td>
							Total : <input class="form-control number " readonly type="text" name="tot_sub" id="tot_sub" value="" >
						</td>
						<td >
							<button type="button" class="btn btn-light" id="addmore1" onclick="addRowbatch2('mybatch1');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
						</td>
					</table>
					
				</div>
			</div>
			
			<div class="modal-footer">
				<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Submit"  onClick="stockjournalbatch();" />
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
			</div>
		</div>

		<script>

			function get_qtytot(bid) {

				var id=bid.split("_");
				bid=id[1];

				var batrate = $("#batrate_"+bid).val(); 
				var batqty = $("#batqty_"+bid).val();

				var qtysum = parseFloat(batrate)*parseFloat(batqty);

				// alert(qtysum);
				$("#qtysum_"+bid).val(qtysum);
				
				total_qty(bid);
			}

			function total_qty(id) {
				
				// var quant = $("#batqty1_"+id).val();
				var totalquantity = 0;
				var totalsub = 0;

				// Assuming batqty1_id elements are input fields
				$("[id^='batqty_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalquantity += quant;
				});

				$("[id^='qtysum_']").each(function() {
					var quant = parseFloat($(this).val()) || 0;
					// Convert the value to a number, default to 0 if not a valid number
					totalsub += quant;
				});

				// console.log('Total :', totalquantity);
				
				$("#tot_qty").val(totalquantity);
				$("#tot_sub").val(totalsub);

				
			}

			function checksub() {

				var mainqty = $("#qty").val();
				var maincnt = $("#maincnt").val();
				var totalquantity = $("#tot_qty").val();

				if(mainqty!=totalquantity) {

					alert("Please check your batch QTY!!!");
				} else {

					stockjournalbatch();
				}

			
			}

			function addRowbatch2(tableID) {
				// alert(tableID);
				var count=$("#cnt1").val();	
				// var state=$("#state").val();

				var i=parseFloat(count)+parseFloat(1);

				var cell1="<tr id='row2_"+i+"'>";
				
				
				cell1 += "<td style='max-width:5%;'><input type='text' id='batchname_"+i+"' class='form-control number' name='batchname_"+i+"' value='' /></td>";
				
				cell1 += "<td style='max-width:5%;'><input type='text' id='batqty_"+i+"' class='form-control number' name='batqty_"+i+"' value='' /></td>";
				
				cell1 += "<td style='max-width:5%;'><input type='text' id='batrate_"+i+"' class='form-control number' name='batrate_"+i+"' value='' onkeyup='get_qtytot(this.id);' /></td>";

				cell1 += "<td style='max-width:5%;'><input type='text' id='qtysum_"+i+"' class='form-control number' name='qtysum_"+i+"' value='' onkeyup='get_qtytot(this.id);' /></td>";
			
				cell1 += "<td style='max-width:1%;text-align:center;'><i class='bx bx-trash me-1' id='deleteRowBatch_"+i+"' style='cursor: pointer;'  onclick='delete_row_batch2(this.id);'></i></td>";


			
				$("#mybatch1").append(cell1);
				$("#cnt1").val(i);
				// $("#particulars_"+i).select2();
			}

			function delete_row_batch2(rwcnt)
			{
				//alert("count");
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt1").val();	
				
				if(count>1)
				{
					var r=confirm("Are you sure!");
					if (r==true)
					{		
						
						$("#row2_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++)
						{
							var newId=k-1;
							
							jQuery("#row2_"+k).attr('id','row2_'+newId);
							
							jQuery("#idd_"+k).attr('name','idd_'+newId);
							jQuery("#idd_"+k).attr('id','idd_'+newId);
							jQuery("#idd_"+newId).html(newId);
							
							jQuery("#batchname_"+k).attr('name','batchname_'+newId);
							jQuery("#batchname_"+k).attr('id','batchname_'+newId);
							
							jQuery("#batqty_"+k).attr('name','batqty_'+newId);
							jQuery("#batqty_"+k).attr('id','batqty_'+newId);
							
							
							jQuery("#deleteRowBatch_"+k).attr('id','deleteRowBatch_'+newId);
							
						}
						jQuery("#cnt1").val(parseFloat(count-1));
					}
				}
				else {
					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}

			function stockjournalbatch(maincnt) {

				var cnt1 = $("#cnt1").val();
				var location = $("#location").val();
				var product = $("#product_batch").val();
				var common_id = $("#ad").val();
				var PTask = $("#PTask").val();
				var deliveryid = $("#id").val();
				var type = "";

				type = "stockj_batch_in";

				var tot_qty = $("#tot_qty").val();
				var tot_sub = $("#tot_sub").val();

				var mqty = $("#qty").val();
				var maincnt = $("#maincnt").val();
				var mamt = '';

				var mrate = parseFloat(tot_sub)/parseFloat(tot_qty);

				var batchname_array=[];
				var batqty_array=[];
				var batrate_array=[];

				for(var i=1;i<=cnt1;i++) {

					var batchname = $("#batchname_"+i).val();
					var batqty = $("#batqty_"+i).val();
					var batrate = $("#batrate_"+i).val();

					batchname_array.push(batchname);
					batqty_array.push(batqty);
					batrate_array.push(batrate);
				}


				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'stockjournalbatch1',deliveryid:deliveryid,product:product,common_id:common_id,PTask:PTask,type:type,location:location,batchname_array:batchname_array,batqty_array:batqty_array,cnt1:cnt1,batrate_array:batrate_array },
					success: function (data) {
						$('#stockjournalbatch1').modal('hide');

						$("#rate_"+maincnt).val(mrate);

						mamt = parseFloat(mqty)*parseFloat(mrate);

						$("#amount_"+maincnt).val(mamt);
				
					},
					error: function (xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			}
		</script>
	<?php
	break;

	case 'stockjournalbatch1':
		
		if($_REQUEST['PTask']=='update') {

			$common=$_REQUEST['deliveryid'];
		} else {

			$common=$_REQUEST['common_id'];
		}

		// $cnt=$_REQUEST['cnt1'];
		$cnt1 = $_REQUEST['cnt1'];

		for($i=0;$i<$cnt1;$i++) {
			
			$arrValue1=array('id'=>uniqid(),'parent_id'=>$common,'ClientID'=>$_SESSION['Client_Id'],'product'=>$_REQUEST['product'],'type'=>$_REQUEST['type'],'batchname'=>$_REQUEST["batchname_array"][$i],'quantity'=>$_REQUEST["batqty_array"][$i],'bat_rate'=>$_REQUEST["batrate_array"][$i],'CreatedAt'=>date("Y-m-d H:i:s"),'LastEdited'=>date("Y-m-d H:i:s"),'location'=>$_REQUEST['location'] );

			$insertedId=$utilObj->insertRecord('temp_batch', $arrValue1);
			
		}

	break;

	case 'get_ledger':

		$state = $_REQUEST['state'];

		$data=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['pid']."' ");

		$record=$utilObj->getMultipleRow("account_ledger","1 group by name");

		foreach($record as $e_rec) {

			if($data['purchase_local']!="na" || $data['purchase_outstate']!="na") {

				if($state==27) {

					if($data['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
				} else {
	
					if($data['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
				}
			} else {

				if($state==27) {
				?>

					<option value="na" <?php if($data["purchase_local"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
				<?php } else { ?>
	
					<option value="na" <?php if($data["purchase_outstate"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
				<?php }
			}
			 
		}

	break;

	case 'get_saleledger':
		
		$state = $_REQUEST['state'];

		$data=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['pid']."' ");

		$record=$utilObj->getMultipleRow("account_ledger","1 group by name");

		foreach($record as $e_rec) {

			if($data['sale_local']!="na" || $data['sale_outstate']!="na") {

				if($state==27) {

					if($data['sale_local']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
				} else {

					if($data['sale_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
					echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
				}
			} else {

				if($state==27) {
				?>

					<option value="na" <?php if($data["sale_local"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
				<?php } else { ?>
	
					<option value="na" <?php if($data["sale_outstate"]=='na') echo $select='selected'; else $select=''; ?>>N/A</option>
				<?php }
			}
		}

	break;

	case 'checkbilltype':
		
		$sid = $_REQUEST['value'];
		$id = $_REQUEST['id'];

		$payment1=$utilObj->getSingleRow("purchase_payment","id='".$id."' ");

		$data=$utilObj->getSingleRow("account_ledger","id='".$sid."' ");


		// if ($data['bill_wise_details'] == '1') {
			
		// if ($_REQUEST['PTask']=='makepayment') {
	?>
			
			<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();purchasetable();" style="width:100%"  <?php echo $disabled;?> name="type" id="type">
				<option value="">Select Type</option>
				<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
				<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
			</select>

		<?php // } else { ?>
		<?php // } ?>
			<!-- <label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="hidden" name="type" id="type" value="Advanced">
			<input type="text" value="New Reference" class="required form-control" readonly > -->
			
		<?php // } ?>
		
	<?php
	break;

	case 'checkbilltype1':
		
		$sid = $_REQUEST['value'];
		$id = $_REQUEST['id'];

		$payment1=$utilObj->getSingleRow("sale_receipt","id='".$id."' ");

		$data=$utilObj->getSingleRow("account_ledger","id='".$sid."' ");

		// if ($data['bill_wise_details'] == '1') {
		// if ($_REQUEST['PTask']=='makepayment') {

	?>
			
			<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();saletable();" style="width:100%"  <?php echo $disabled; ?> name="type" id="type">
				<option value="">Select Type</option>
				<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
				<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
			</select>

		<?php // } else { ?>
		<?php // } ?>
			<!-- <label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="hidden" name="type" id="type" value="Advanced">
			<input type="text" value="New Reference" class="required form-control" readonly > -->
			
		<?php // } ?>
		
	<?php
	break;

	case 'checkbilltype3':
		
		$sid = $_REQUEST['value'];
		$id = $_REQUEST['id'];

		$payment1=$utilObj->getSingleRow("cash_receipt","id='".$id."' ");

		$data=$utilObj->getSingleRow("account_ledger","id='".$sid."' ");

		// if ($data['bill_wise_details'] == '1') {
		// if ($_REQUEST['PTask']=='makepayment') {

	?>
			
			<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();saletable();" style="width:100%"  <?php echo $disabled; ?> name="type" id="type">
				<option value="">Select Type</option>
				<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
				<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
			</select>

		<?php // } else { ?>
		<?php // } ?>
			<!-- <label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="hidden" name="type" id="type" value="Advanced">
			<input type="text" value="New Reference" class="required form-control" readonly > -->
			
		<?php // } ?>
		
	<?php
	break;

	case 'checkbilltype2':
		
		$sid = $_REQUEST['value'];
		$id = $_REQUEST['id'];

		$payment1=$utilObj->getSingleRow("cash_payment","id='".$id."' ");

		$data=$utilObj->getSingleRow("account_ledger","id='".$sid."' ");

		// if ($data['bill_wise_details'] == '1') {
		// if ($_REQUEST['PTask']=='makepayment') {

	?>
			
			<label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="validate();purchasetable();" style="width:100%"  <?php echo $disabled; ?> name="type" id="type">
				<option value="">Select Type</option>
				<option value="Advanced" <?php if($payment1["ptype"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
				<option value="PO" <?php if($payment1["ptype"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
			</select>

		<?php // } else { ?>
		<?php // } ?>
			<!-- <label class="form-label" for="formValidationSelect2"> Payment Type <span class="required required_lbl" style="color:red;">*</span></label>
			<input type="hidden" name="type" id="type" value="Advanced">
			<input type="text" value="New Reference" class="required form-control" readonly > -->
			
		<?php // } ?>
		
	<?php
	break;

	case 'get_balance':
	
		$id = $_REQUEST['value'];
		$data = $utilObj->getSingleRow("account_ledger","id='".$id."' ");

		echo $data['op_balance'];

	break;

	case 'get_transfer_code':

		$mate1=$utilObj->getSingleRow("bank_transfer","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);

				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from bank_transfer WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);

				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_journal_code':

		$mate1=$utilObj->getSingleRow("journal_entry","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}
		

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from journal_entry WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $stockt_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from journal_entry WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_payment_code':

		$mate1=$utilObj->getSingleRow("cash_payment","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $stockt_code = $prefix_label."/".($result['pono']+1)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from cash_payment WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']+1);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'get_posinvoice':
		$invoice=$utilObj->getSingleRow("purchase_invoice","id='".$_REQUEST['purchase_invoice_no']."'");
		$pos=$utilObj->getSingleRow("states","code='".$invoice['pos_state']."'");

		echo trim($pos['name'] . "#" . $pos['code']);
	break;

	case 'show_pos':

		$type = $_REQUEST['type'];
		$did = $_REQUEST['value'];
		$challan=$utilObj->getSingleRow("delivery_challan","id='".$did."'");
		$order=$utilObj->getSingleRow("sale_order","id='".$challan['saleorder_no']."' ");

	?>
		<div id="show_pos">
			<select id="pos_state" name="pos_state" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" > 
			<?php
				echo '<option value="">Select Location</option>';
				$record=$utilObj->getMultipleRow("states","1");
				foreach($record as $e_rec)
				{
					if($order['pos_state']==$e_rec["code"]) echo $select='selected'; else $select='';
					echo '<option value="'.$e_rec["code"].'" '.$select.'>'.$e_rec["name"].'</option>';
				}
			?>  
			</select>
		</div>
	<?php
	break;

	// --------------------------------- Purchase Service voucher ---------------------------------

	case 'get_pservice_code':
		
		$mate1=$utilObj->getSingleRow("purchase_invoice_service","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from purchase_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."' ");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	// --------------------------------- Sale Service voucher ---------------------------------

	case 'get_sservice_code':
		
		$mate1=$utilObj->getSingleRow("sale_invoice_service","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 4) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from sale_invoice_service WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	// --------------------------------- Sale Service voucher ---------------------------------

	case 'get_sservice_code1':
		
		$mate1=$utilObj->getSingleRow("creditnote_acc","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";

		if (date("m") > 4) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from creditnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(recordnumber) AS pono from creditnote_acc WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				$val = $result['pono']+1;
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	// ---------------------------------- Purchase Invoice Service ----------------------------------
	case 'pservice_rowtable':
		
		$PTask = $_REQUEST['PTask'];
		$id = $_REQUEST['id'];
		$supplier = $_REQUEST['supplier'];
		$state = $_REQUEST['state'];

		$i = 0;
	?>	
		<div>
			<input type="hidden" value="state" id="state" value="<?php echo $state; ?>">
			<h4 class="role-title">Service Details</h4>
			<table class="table" id="myTable">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
						$record5=$utilObj->getMultipleRow("purchase_invoice_service_details","parent_id='".$id."' ");
					} else { 
						$record5[0]['id'] = 1;					
					}
					foreach($record5 as $row_demo) {
						$i++;
				?>
					<tr id="row1_<?php echo $i; ?>">
						<td style="text-align:center;"><?php echo $i; ?></td>
						<td style="text-align:center;">
							<select id="serviceledger_<?php echo $i;?>" onchange="get_gst_per(this.id);" name="serviceledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
								foreach($record as $e_rec){
									if($row_demo['service_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;">
							<input type="hidden" name="igstper_<?php echo $i;?>" id="igstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="sgstper_<?php echo $i;?>" id="sgstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="cgstper_<?php echo $i;?>" id="cgstper_<?php echo $i;?>" value="" >
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="serviceamt_<?php echo $i;?>" class="number form-control tdalign" name="serviceamt_<?php echo $i;?>" value="<?php echo number_format($row_demo['service_amt'], 2); ?>" onblur="get_service_subtot(<?php echo $i;?>);getrowgst(this.id);gettotgst(<?php echo $i; ?>);" />

							<input type="hidden" name="serviceigst_<?php echo $i;?>" id="serviceigst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicesgst_<?php echo $i;?>" id="servicesgst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicecgst_<?php echo $i;?>" id="servicecgst_<?php echo $i;?>" value="" >
						</td>
						
						<td style='width:1%'>
						<?php 
							if($i>1) {
						?>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row('this.id');"></i>
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
				</tbody>
				<tfoot>
					<tr>
						<td  colspan="3" style="text-align:right;">
							<?php if(($_REQUEST['PTask']!='view' )) { ?>			
								<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
							<?php } ?> 
						</td>
						<td style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="service_subtotal" class="number form-control tdalign" readonly name="service_subtotal" value="<?php echo number_format($row_demo['service_subtotal'],2); ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>

		<!-- --------------------------------------------------------------------------- -->
		<br>
		<div>
			<h4 class="role-title">GST Details</h4>
			<table class="table" id="myTable2">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">GST Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') { 
						$record1=$utilObj->getSingleRow("purchase_invoice_service","id='".$id."' ");
					}
					if($state == 27) {
				?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="cgst_ledger" name="cgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['cgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="cgst_amt" class="number form-control tdalign" name="cgst_amt" value="<?php echo number_format($record1['cgst_amt'],2); ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>

					<tr>
						<td style="text-align:center;">2</td>
						<td style="text-align:center;">
							<select id="sgst_ledger" name="sgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['sgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="sgst_amt" class="number form-control tdalign" name="sgst_amt" value="<?php echo number_format($record1['sgst_amt'],2); ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } else { ?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="igst_ledger" name="igst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['igst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="igst_amt" class="number form-control tdalign" name="igst_amt" value="<?php echo number_format($record1['igst_amt'], 2); ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="gst_subtotal" class="number form-control tdalign" readonly name="gst_subtotal" value="<?php echo number_format($record1['gst_subtotal'],2); ?>"/>
						</td>
						<td></td>
					</tr>

					<tr>
						<td colspan="4" style="text-align:right;">
							Grandtotal
						</td>
						<td >
							<input type="text" id="grandtotal" class="number form-control tdalign" readonly name="grandtotal" value="<?php echo number_format($record1['grandtotal'],2); ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php
	break;

	// ---------------------------------- Debit Note Service ----------------------------------
	case 'pservice_rowtable1':
		$PTask = $_REQUEST['PTask'];
		$id = $_REQUEST['id'];
		$supplier = $_REQUEST['supplier'];
		$state = $_REQUEST['state'];

		$i = 0;
	?>	
		<div>
			<input type="hidden" value="state" id="state" value="<?php echo $state; ?>">
			<h4 class="role-title">Service Details</h4>
			<table class="table" id="myTable">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
						$record5=$utilObj->getMultipleRow("debitnote_acc_details","parent_id='".$id."' ");
					} else { 
						$record5[0]['id'] = 1;					
					}
					foreach($record5 as $row_demo) {
						$i++;
				?>
					<tr id="row1_<?php echo $i; ?>">
						<td style="text-align:center;"><?php echo $i; ?></td>
						<td style="text-align:center;">
							<select id="serviceledger_<?php echo $i;?>" onchange="get_gst_per(this.id);" name="serviceledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
								foreach($record as $e_rec){
									if($row_demo['service_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;">
							<input type="hidden" name="igstper_<?php echo $i;?>" id="igstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="sgstper_<?php echo $i;?>" id="sgstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="cgstper_<?php echo $i;?>" id="cgstper_<?php echo $i;?>" value="" >
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="serviceamt_<?php echo $i;?>" class="number form-control tdalign" name="serviceamt_<?php echo $i;?>" value="<?php echo number_format($row_demo['service_amt'],2); ?>" onblur="get_service_subtot(<?php echo $i;?>);getrowgst(this.id);gettotgst(<?php echo $i; ?>);" />

							<input type="hidden" name="serviceigst_<?php echo $i;?>" id="serviceigst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicesgst_<?php echo $i;?>" id="servicesgst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicecgst_<?php echo $i;?>" id="servicecgst_<?php echo $i;?>" value="" >
						</td>
						
						<td style='width:1%'>
						<?php 
							if($i>1) {
						?>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row('this.id');"></i>
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
				</tbody>
				<tfoot>
					<tr>
						<td  colspan="3" style="text-align:right;">
							<?php if(($_REQUEST['PTask']!='view' )) { ?>			
								<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
							<?php } ?> 
						</td>
						<td style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="service_subtotal" class="number form-control tdalign" readonly name="service_subtotal" value="<?php echo $row_demo['service_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>

		<!-- --------------------------------------------------------------------------- -->
		<br>
		<div>
			<h4 class="role-title">GST Details</h4>
			<table class="table" id="myTable2">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">GST Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') { 
						$record1=$utilObj->getSingleRow("debitnote_acc","id='".$id."' ");
					}
					if($state == 27) {
				?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="cgst_ledger" name="cgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['cgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="cgst_amt" class="number form-control tdalign" name="cgst_amt" value="<?php echo $record1['cgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>

					<tr>
						<td style="text-align:center;">2</td>
						<td style="text-align:center;">
							<select id="sgst_ledger" name="sgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['sgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="sgst_amt" class="number form-control tdalign" name="sgst_amt" value="<?php echo $record1['sgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } else { ?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="igst_ledger" name="igst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='6698c49b43205' group by name");
								foreach($record as $e_rec){
									if($record1['igst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="igst_amt" class="number form-control tdalign" name="igst_amt" value="<?php echo $record1['igst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="gst_subtotal" class="number form-control tdalign" readonly name="gst_subtotal" value="<?php echo $record1['gst_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>

					<tr>
						<td colspan="4" style="text-align:right;">
							Grandtotal
						</td>
						<td >
							<input type="text" id="grandtotal" class="number form-control tdalign" readonly name="grandtotal" value="<?php echo $record1['grandtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php
	break;

	// ---------------------------------- Sale Service Rowtable ----------------------------------
	case 'sservice_rowtable':
		$PTask = $_REQUEST['PTask'];
		$id = $_REQUEST['id'];
		$supplier = $_REQUEST['supplier'];
		$state = $_REQUEST['state'];

		$i = 0;
	?>	
		<div>
			<input type="hidden" value="state" id="state" value="<?php echo $state; ?>">
			<h4 class="role-title">Service Details</h4>
			<table class="table" id="myTable1">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
						$record5=$utilObj->getMultipleRow("sale_invoice_service_details","parent_id='".$id."' ");
					} else { 
						$record5[0]['id'] = 1;					
					}
					foreach($record5 as $row_demo) {
						$i++;
				?>
					<tr id="row1_<?php echo $i; ?>">
						<td style="text-align:center;"><?php echo $i; ?></td>
						<td style="text-align:center;">
							<select id="serviceledger_<?php echo $i;?>" onchange="get_gst_per(this.id);" name="serviceledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
								foreach($record as $e_rec){
									if($row_demo['service_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;">
							<input type="hidden" name="igstper_<?php echo $i;?>" id="igstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="sgstper_<?php echo $i;?>" id="sgstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="cgstper_<?php echo $i;?>" id="cgstper_<?php echo $i;?>" value="" >
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="serviceamt_<?php echo $i;?>" class="number form-control tdalign" name="serviceamt_<?php echo $i;?>" value="<?php echo $row_demo['service_amt']; ?>" onblur="get_service_subtot(<?php echo $i;?>);getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

							<input type="hidden" name="serviceigst_<?php echo $i;?>" id="serviceigst_<?php echo $i;?>" value="0" >
							<input type="hidden" name="servicesgst_<?php echo $i;?>" id="servicesgst_<?php echo $i;?>" value="0" >
							<input type="hidden" name="servicecgst_<?php echo $i;?>" id="servicecgst_<?php echo $i;?>" value="0" >
						</td>
						
						<td style='width:1%'>
						<?php 
							if($i>1) {
						?>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row('this.id');"></i>
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
				</tbody>
				<tfoot>
					<tr>
						<td  colspan="3" style="text-align:right;">
							<?php if(($_REQUEST['PTask']!='view' )) { ?>			
								<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
							<?php } ?> 
						</td>
						<td style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="service_subtotal" class="number form-control tdalign" readonly name="service_subtotal" value="<?php echo $row_demo['service_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
			<!-- <br>
			<table style="width:100%;" class="taxtbl">
				<tr style="margin:10px;text-align:right;">
					<td>
						<?php if(($_REQUEST['PTask']!='view' )) { ?>			
							<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
						<?php } ?> 
					</td>			
				</tr>
			</table> -->
		</div>

		<!-- --------------------------------------------------------------------------- -->
		<br>
		<div>
			<h4 class="role-title">GST Details</h4>
			<table class="table" id="myTable2">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') { 
						$record1=$utilObj->getSingleRow("sale_invoice_service","id='".$id."' ");
					}
					if($state == 27) {
				?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="cgst_ledger" name="cgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec){
									if($record1['cgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="cgst_amt" class="number form-control tdalign" name="cgst_amt" value="<?php echo $record1['cgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>

					<tr>
						<td style="text-align:center;">2</td>
						<td style="text-align:center;">
							<select id="sgst_ledger" name="sgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec){
									if($record1['sgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="sgst_amt" class="number form-control tdalign" name="sgst_amt" value="<?php echo $record1['sgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } else { ?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="igst_ledger" name="igst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec){
									if($record1['igst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="igst_amt" class="number form-control tdalign" name="igst_amt" value="<?php echo $record1['igst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="gst_subtotal" class="number form-control tdalign" readonly name="gst_subtotal" value="<?php echo $record1['gst_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>

					<tr>
						<td colspan="4" style="text-align:right;">
							Grandtotal
						</td>
						<td >
							<input type="text" id="grandtotal" class="number form-control tdalign" readonly name="grandtotal" value="<?php echo $record1['grandtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php
	break;

	// ---------------------------------- Credit Note Rowtable ----------------------------------
	case 'sservice_rowtable1':
		$PTask = $_REQUEST['PTask'];
		$id = $_REQUEST['id'];
		$supplier = $_REQUEST['supplier'];
		$state = $_REQUEST['state'];

		$i = 0;
	?>	
		<div>
			<input type="hidden" value="state" id="state" value="<?php echo $state; ?>">
			<h4 class="role-title">Service Details</h4>
			<table class="table" id="myTable1">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {
						$record5=$utilObj->getMultipleRow("creditnote_acc_details","parent_id='".$id."' ");
					} else { 
						$record5[0]['id'] = 1;					
					}
					foreach($record5 as $row_demo) {
						$i++;
				?>
					<tr id="row1_<?php echo $i; ?>">
						<td style="text-align:center;"><?php echo $i; ?></td>
						<td style="text-align:center;">
							<select id="serviceledger_<?php echo $i;?>" onchange="get_gst_per(this.id);" name="serviceledger_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
								foreach($record as $e_rec){
									if($row_demo['service_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;">
							<input type="hidden" name="igstper_<?php echo $i;?>" id="igstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="sgstper_<?php echo $i;?>" id="sgstper_<?php echo $i;?>" value="" >
							<input type="hidden" name="cgstper_<?php echo $i;?>" id="cgstper_<?php echo $i;?>" value="" >
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="serviceamt_<?php echo $i;?>" class="number form-control tdalign" name="serviceamt_<?php echo $i;?>" value="<?php echo $row_demo['service_amt']; ?>" onblur="get_service_subtot(<?php echo $i;?>);getrowgst(this.id);gettotgst(<?php echo $i;?>);" />

							<input type="hidden" name="serviceigst_<?php echo $i;?>" id="serviceigst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicesgst_<?php echo $i;?>" id="servicesgst_<?php echo $i;?>" value="" >
							<input type="hidden" name="servicecgst_<?php echo $i;?>" id="servicecgst_<?php echo $i;?>" value="" >
						</td>
						
						<td style='width:1%'>
						<?php 
							if($i>1) {
						?>
							<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor:pointer;" onclick="delete_row('this.id');"></i>
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<input type="hidden" name="cnt1" id="cnt1" value="<?php echo $i; ?>">
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" style="text-align:right;">
							<?php if(($_REQUEST['PTask']!='view' )) { ?>
								
								<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
							<?php } ?> 
						</td>
						<td style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="service_subtotal" class="number form-control tdalign" readonly name="service_subtotal" value="<?php echo $row_demo['service_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
			<!-- <br>
			<table style="width:100%;" class="taxtbl">
				<tr style="margin:10px;text-align:right;">
					<td>
						<?php if(($_REQUEST['PTask']!='view' )) { ?>			
							<button type="button" class="btn btn-warning btn-sm" id="addmore1" onclick="addRow1('myTable');">Add More</button>
						<?php } ?> 
					</td>			
				</tr>
			</table> -->
		</div>

		<!-- --------------------------------------------------------------------------- -->
		<br>
		<div>
			<h4 class="role-title">GST Details</h4>
			<table class="table" id="myTable2">
				<thead>
					<tr>
						<th style="width:8%; text-align:center;">Sr No.</th>
						<th style="width:8%; text-align:center;">Service Ledger</th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;"></th>
						<th style="width:8%; text-align:center;">Amount</th>
					</tr>
				</thead>
				<tbody>
				<?php
					if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') { 
						$record1=$utilObj->getSingleRow("creditnote_acc","id='".$id."' ");
					}
					if($state == 27) {
				?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="cgst_ledger" name="cgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec){
									if($record1['cgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="cgst_amt" class="number form-control tdalign" name="cgst_amt" value="<?php echo $record1['cgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>

					<tr>
						<td style="text-align:center;">2</td>
						<td style="text-align:center;">
							<select id="sgst_ledger" name="sgst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec){
									if($record1['sgst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="sgst_amt" class="number form-control tdalign" name="sgst_amt" value="<?php echo $record1['sgst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } else { ?>
					<tr>
						<td style="text-align:center;">1</td>
						<td style="text-align:center;">
							<select id="igst_ledger" name="igst_ledger" <?php echo $disabled;?> class="select2 form-select " <?php echo $readonly;?> >
							<?php
								echo '<option value="">Select Ledger</option>';
								$record=$utilObj->getMultipleRow("account_ledger","group_name='66ffab7c743a3' group by name");
								foreach($record as $e_rec) {
									
									if($record1['igst_ledger']==$e_rec["id"]) echo $select='selected'; else $select='';
									echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"].'</option>';
								}
							?>
							</select>
						</td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;"></td>
						<td style="text-align:center;">
							<input type="text" id="igst_amt" class="number form-control tdalign" name="igst_amt" value="<?php echo $record1['igst_amt']; ?>"/>
						</td>
						<td style='width:1%'> </td>
					</tr>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:right;">
							Subtotal
						</td>
						<td >
							<input type="text" id="gst_subtotal" class="number form-control tdalign" readonly name="gst_subtotal" value="<?php echo $record1['gst_subtotal']; ?>"/>
						</td>
						<td></td>
					</tr>

					<tr>
						<td colspan="4" style="text-align:right;">
							Grandtotal
						</td>
						<td >
							<input type="text" id="grandtotal" class="number form-control tdalign" readonly name="grandtotal" value="<?php echo $record1['grandtotal']; ?>"/>
						</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
		</div>
	<?php
	break;

	case 'get_gst_per':
	
		$mate = $utilObj->getSingleRow("account_ledger", "id='".$_REQUEST['service_ledger']."' ");
		$mate2 = $utilObj->getSingleRow("gst_data", "id='".$mate['igst']."' ");
		if($mate2['igst']!='0') {
			echo trim($mate2 ['igst'] . "#" . $mate2['sgst'] . "#" . $mate2['cgst']);
		}

	break;

	case 'check_name':
		
		$tbl = $_REQUEST['table'];
		$col = $_REQUEST['col'];

		$cnt = $utilObj->getCount("".$tbl."","".$col." = '".$_REQUEST['val']."' ");
		echo $cnt;

	break;
	
	case 'get_pos':
		$mate2 = $utilObj->getSingleRow("account_ledger", "id='".$_REQUEST['id']."' ");
		$record=$utilObj->getMultipleRow("states","1 order by name");
		
		foreach($record as $e_rec)
		{
			if($mate2['mail_state']==$e_rec["code"]) $select='selected'; else $select='';
			echo '<option value="'.$e_rec["code"].'" '.$select.'>'.$e_rec["name"].'</option>';
		}

	break;

	case 'costreportdata':
		$batchid = $_REQUEST['batchid'];
		$batchtype = $_REQUEST['batchtype'];

	?>
		<div class="costtrack">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Details</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<table class = "table border-top table-striped">
						<thead>
							<tr>
								<th style="width:5%;">Sr no</th>
								<th style="width:20%;">Used Product</th>
								<th style="width:20%;">Required Quantity</th>
								<th style="width:15%;">Batch Name</th>
								<th style="width:15%;">Batch Rate</th>
								<th style="width:15%;">Batch Quantity</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i=0; 
								if($batchtype=='production_in') {

									$record=$utilObj->getMultipleRow("production_details","parent_id='".$batchid."' ");

								} else if($batchtype=='packaging_in') {

									$record=$utilObj->getMultipleRow("packaging_details","parent_id='".$batchid."' ");

								}

								foreach($record as $data) {

									$mate2=$utilObj->getSingleRow("stock_ledger","id='".$data['product']."' ");
									$i++;$j=0;

									$batchdata=$utilObj->getMultipleRow("sale_batch","delivery_id='".$batchid."' AND product='".$data['product']."' ");

									foreach($batchdata as $bdata) {
									
									$j++;
									// $cnt = count($batchdata);
									// $hidetd="hidetd";

									if($j==1){
										$cnt=Count($batchdata);
										$hidetd="";
									}else{
										$cnt=1;
										$hidetd="hidetd";
									} 

							?>
								<tr>
									<td class="<?php echo $hidetd; ?> controls " style="width:5%;" rowspan="<?php echo $cnt; ?>" >
										<?php echo $i; ?>
									</td>
									<td class="<?php echo $hidetd; ?>" style="width:20%;" rowspan="<?php echo $cnt; ?>" >
										<?php echo $mate2['name']; ?>
									</td>
									<td class="<?php echo $hidetd; ?> " style="width:20%;" rowspan="<?php echo $cnt; ?>" >
										<?php echo $data['qty']; ?>
									</td>

									<td style="width:15%;">
										<?php echo $bdata['batchname']; ?>
									</td>
									<td style="width:15%;">
										<?php echo $bdata['bat_rate']; ?>
									</td>
									<td style="width:15%;">
										<?php echo $bdata['quantity']; ?>
									</td>
								</tr>
								<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php
	break;

	case 'get_order_code':

		
		
		$mate1=$utilObj->getSingleRow("purchase_order","voucher_type='".$_REQUEST['voucher_type']."'");
		$mate3=$utilObj->getSingleRow("voucher_type","id='".$_REQUEST['voucher_type']."'");

		$prefix_label = $mate3['prefix_label'];
		$width = $mate3['codewidth'];

		$year_code = "";
 
		if (date("m") > 3) {
			$year_code = date("y")."-".(date("y")+1);
		} else {
			$year_code = (date("y")-1)."-".date("y");
		}

		if ($mate3['numbering_digit'] == 'Prefix') {
			
			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				$val = $result['pono']+1;

				$formattedPono = sprintf('%0' . $width . 'd', $val);
				// $formattedPono = sprintf('%04d', $val);

				echo $stockt_code = $prefix_label."/".($formattedPono)."/".$year_code;
			}  else {

				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				
				echo $stockt_code = $prefix_label."/".($result['pono'])."/".$year_code;
			}
		}
		else {

			if ($mate1['voucher_type'] != '') {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(order_no) AS pono from purchase_order WHERE voucher_type ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);
				$val = $result['pono']+1;
				// $formattedPono = sprintf('%04d', $val);
				$formattedPono = sprintf('%0' . $width . 'd', $val);
	
				echo $stockt_code = $prefix_label."/".$year_code."/".($formattedPono);
			} 
			
			else {
				$voucher_code = mysqli_query($GLOBALS['con'],"Select MAX(numbering_code) AS pono from voucher_type WHERE id ='".$_REQUEST['voucher_type']."'");
				$result = mysqli_fetch_array($voucher_code);

				echo $stockt_code = $prefix_label."/".$year_code."/".($result['pono']);
			}
		}

	break;

	case 'getservice':
	
		$mate = $utilObj->getSingleRow("account_ledger", "id='".$_REQUEST['service_ledger']."' ");
		$mate2 = $utilObj->getSingleRow("gst_data", "id='".$mate['igst']."' ");
		if($mate2['igst']!='0') {
			echo trim($mate2 ['igst'] . "#" . $mate2['sgst'] . "#" . $mate2['cgst']);
		}

	break;

	case 'updateledger':
		$pids=explode(",",$_REQUEST['sid']);

		$date=date('d-m-Y');

		// if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
		// 	$id=$_REQUEST['id'];
		// 	$rows=$utilObj->getSingleRow("purchase_invoice","id ='".$id."'");
		// 	$Invoicenumber=$rows['pur_invoice_code'];	   
		// 	$date=date('d-m-Y',strtotime($rows['date']));	
		// 	if($requisition_no!='')
		// 	{		
		// 		if($readonly!="readonly"){
		// 			$read="readonly";
		// 		}
		// 	}else{
		// 		$read=" ";
		// 	}
		// }
	?>
		<div id="updatesledger">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Update Stock Ledger</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">

					<!-- <div class="col-md-2">
						<label class="form-label">Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
					</div> -->

					<input type="hidden" name="stockid" id="stockid" value="<?php echo $_REQUEST['sid']; ?>" >
					<table class="table table-striped" id="stocktable">
						<thead>
							<tr>
								<th>Date</th>
								<th>
									Old HSN No
								</th>
								<th>
									New HSN No
								</th>
								<th>
									Old GST Rate
								</th>
								<th>
									New GST Rate
								</th>
								<!-- <th>
									Action
								</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
								// print_r($pids);
								$stock=$utilObj->getSingleRow("stock_ledger","id ='".$pids[0]."' ");
								$rows=$utilObj->getSingleRow("gst_data","id ='".$stock['igst']."' ");
							?>
							<tr>
								<td style="width:15%;">
									<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
								</td>
								<td style="width:10%;">
									<input type="text" readonly class=" form-control tdalign" id="oldhsn" name="oldhsn" value="<?php echo $stock['hsn_sac']; ?>" />
								</td>
								<td style="width:10%;">
									<input type="text" class=" form-control tdalign" id="newhsn" name="newhsn" value="" />
								</td>

								<td style="width:10%;">
									<input type="text" readonly class=" form-control tdalign" id="oldgstrate" name="oldgstrate" value="<?php echo $rows['igst']; ?>" />
								</td>
								<td style="width:10%;">
									<select id="newgstrate" name="newgstrate" <?php echo $disabled;?> class="select2 form-select" >
									<?php 
										echo '<option value="">Select IGST</option>';
										$record=$utilObj->getMultipleRow("gst_data","1 AND igst!='' group by igst");
										foreach($record as $e_rec){
											if($rows['igst']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["igst"].'</option>';
										}
									?> 
									</select>
								</td>
								<!-- <td style="width:10%;">
									
								</td> -->
							</tr>
						</tbody>
					</table>
					<div class="row" style="margin-bottom:10px;">
						<div class="col-sm-3"> 
							<label class="form-label">Sales Local</label>
							<select class="select2 form-select" id="sale_local" name="sale_local" >
								<?php 
									// echo '<option value="">Select Sales Local</option>';
									//echo '<option value="AddNew">Add New</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
									foreach($record as $e_rec){
										if($rows['sale_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
									}
								?>
							</select>
						</div>

						<div class="col-sm-3">
							<label class="form-label" >Purchase Local</label>
							<select class="select2 form-select" id="purchase_local" name="purchase_local" >
								<?php 
									// echo '<option value="">Select Purchase local</option>';
									// echo '<option value="AddNew">Add New</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
									foreach($record as $e_rec){
										if($rows['purchase_local']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
									}
								?>
							</select>
						</div>

						<div class="col-sm-3">
							<label class="form-label">Sales Outstate</label>
							<select class="select2 form-select" id="sale_outstate" name="sale_outstate" >
								<?php 
									// echo '<option value="">Select Sale outstate</option>';
									// echo '<option value="AddNew">Add New</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
									foreach($record as $e_rec){
										if($rows['sale_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
									}
								?>
							</select>
						</div>

						<div class="col-sm-3">
							<label class="form-label">Purchase Outstate</label>
							<select class="select2 form-select" id="purchase_outstate" name="purchase_outstate" >
								<?php
									// echo '<option value="">Select Purchase outstate</option>';
									// echo '<option value="AddNew">Add New</option>';
									$record=$utilObj->getMultipleRow("account_ledger","1 AND linking_inventory=1 group by name");
									foreach($record as $e_rec) {
										if($rows['purchase_outstate']==$e_rec["id"]) echo $select='selected'; else $select='';
										echo  '<option value="'.$e_rec["id"].'" '.$select.' >'.$e_rec["name"].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-12" style="text-align:right;margin-bottom:10px">
						<input type="button" class="btn btn-primary btn-sm" id="closemodal" name="sbumit" value="Update"  onClick="update_stockitem();" />
					</div>
				</div>
			</div>
		</div>

		<script>

			$("#date").flatpickr({
				dateFormat: "d-m-Y"
			});

			$("#newgstrate").select2({
				dropdownParent: $('#stocktable')
			});

			function update_stockitem() {
				
				var newgstrate = $("#newgstrate").val();
				var newhsn = $("#newhsn").val();
				var date = $("#date").val();
				var stockid = $("#stockid").val();

				var sale_local = $("#sale_local").val();
				var purchase_local = $("#purchase_local").val();
				var sale_outstate = $("#sale_outstate").val();
				var purchase_outstate = $("#purchase_outstate").val();

				jQuery.ajax({
					url: 'get_ajax_values.php',
					type: 'POST',
					data: { Type: 'update_stockitem', stockid:stockid,newgstrate:newgstrate,date:date,newhsn:newhsn,sale_local:sale_local,purchase_local:purchase_local,sale_outstate:sale_outstate,purchase_outstate:purchase_outstate },
					success: function (data) {
						$('#updatestockledger').modal('hide');
						alert("Selected records updated successfully!!!");
						location.reload();
					}
				});
			}

		</script>

	<?php
	break;

	case 'update_stockitem':

		$pids=explode(",",$_REQUEST['stockid']);

		foreach($pids as $row_data) {

			$arrValue = array('igst'=>$_REQUEST['newgstrate'],'cgst'=>$_REQUEST['newgstrate'],'sgst'=>$_REQUEST['newgstrate'],'hsn_sac'=>$_REQUEST['newhsn'],'sale_local'=>$_REQUEST['sale_local'],'purchase_local'=>$_REQUEST['purchase_local'],'sale_outstate'=>$_REQUEST['sale_outstate'],'purchase_outstate'=>$_REQUEST['purchase_outstate'] );

			$id= $row_data;

			$strWhere="id='".$row_data."'  ";
			// $Updaterec=$utilObj->updateRecord('stock_ledger', $strWhere, $arrValue);
			$Updaterec=$utilObj->updateRecord('stock_ledger', $strWhere, $arrValue);

			$rows=$utilObj->getSingleRow("ledger_gst_history","product ='".$row_data."' ");

			if($rows['todate']==NULL) {

				$todate = date('Y-m-d',strtotime($_REQUEST['date']."-1 days"));
				
				$strWhere1="product='".$id."' ";

				$arr = array('todate'=>$todate);
				$Updaterec1=$utilObj->updateRecord('ledger_gst_history', $strWhere1, $arr);
				
				if($id!='') {

					$arrValue1=array('id'=>uniqid(),'product'=>$id,'ClientID'=>$_SESSION['Client_Id'],'fromdate'=>date('Y-m-d',strtotime($_REQUEST['date'])),'igst'=>$_REQUEST['newgstrate'],'cgst'=>$_REQUEST['newgstrate'],'sgst'=>$_REQUEST['newgstrate'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'type'=>'stock_ledger','hsn'=>$_REQUEST['newhsn'] );

					$insertedId=$utilObj->insertRecord('ledger_gst_history', $arrValue1);

				}
			}

		}

	break;

	case 'sendrequi':
		
		$getinvno= mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from purchase_requisition");
		$result=mysqli_fetch_array($getinvno);
		$record_no=$result['pono']+1; 

		$username=$utilObj->getSingleRow("employee","id='".$_SESSION['Ck_User_id']."' ");
		$requisition_by = $username['name'];

		$product = $_REQUEST['product'];
		$qty = $_REQUEST['reqqty'];
		$date=date('d-m-Y');
		
	?>

		<div id="poreq">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Purchase Requisition</h3>
				</div>
				
				<form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">

					<input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "purchase_requisition"; ?>"/>

					<div class="col-md-4">
						<label class="form-label">Requisition No. <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="record_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Requisition No." name="record_no" value="<?php echo $record_no;?>"/>
					</div>
					
					<div class="col-md-4">
						<label class="form-label">Requisition Date <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date; ?>" <?php echo $disabled;?>/>
					</div>
					
					<div class="col-md-4">
						<label class="form-label">Requisition By <span class="required required_lbl" style="color:red;">*</span></label>
						<input type="text" id="requisition_by" class="required form-control"  <?php echo $readonly;?> placeholder="Requisition By" name="requisition_by" value="<?php echo $requisition_by; ?>" readonly/>
					</div>
					
					<!-- <div class="col-md-3">
						<label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
						<select id="location" name="location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
						<?php 
							echo '<option value="">Select Location</option>';
							$record=$utilObj->getMultipleRow("location","1");
							foreach($record as $e_rec)
							{
								if($rows['location']==$e_rec["id"]) echo $select='selected'; else $select='';
								echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
							}
						?>
						</select>
					</div> -->

					<div class="col-md-4">
						<label for="first-name" class="control-label" >Narration</label>
						<textarea type="text" <?php echo $readonly;?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 100%;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $purchase_invoice['otrnar'];?></textarea>
					</div>
						
					
					<h4 class="role-title">Material Details</h4>
				
					<table class="table table-bordered" id="myTable"> 
						<thead>
							<tr>
								<th style="width:2%;text-align:center;">Sr.No.</th> 
								<th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
								<th style="width: 10%;text-align:center;">Unit<span class="required required_lbl" style="color:red;">*</span> </th>
								<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
								<th style="width:5%;text-align:center;"></th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i=0;
							// $record5[0]['id']=1;
							$sid = rtrim($_REQUEST['sid'], ',');

							$pids = explode(",", $sid);

							// $pids=explode(",",$_REQUEST['sid']);


							foreach($pids as $row_demo)
							{ 
								$i++;

								$unit=$utilObj->getSingleRow("stock_ledger","id='".$row_demo."' ");

								$totstock = gettotalstock($row_demo,date('Y-m-d'));

                        		$rqqty = $totstock-$unit['reorderlvl'];

								if($rqqty < 0) {

									$rqqty = $unit['reorderlvl']-$totstock;
								}

						?>
							<tr id='row_<?php echo $i;?>'>
								<td style="text-align:center;">
									<label  id="idd_<?php echo $i;?>"  name="idd_<?php echo $i; ?>"><?php echo $i; ?></label>
								</td>
								<td>
									<select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);">	
										<?php
											echo '<option value="">Select</option>';
											$record=$utilObj->getMultipleRow("stock_ledger","1 ");
											foreach($record as $e_rec)
											{
												if($row_demo==$e_rec["id"]) echo $select='selected'; else $select='';
												echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
											}
										?>  
									</select>
								</td>
								<td>
									<div id='unitdiv_<?php echo $i;?>'>
										<input type="text" id="unit_<?php echo $i;?>" readonly class=" form-control required" name="unit_<?php echo $i;?>" value="<?php echo $unit['unit'];?>"/>
									</div>
								</td>
								<td>
									<input type="text" id="qty_<?php echo $i;?>" class="number form-control" name="qty_<?php echo $i;?>" value="<?php echo $rqqty; ?>"/>
								</td>
								<td style='width:5%'>
									<?php if($_REQUEST['Task']!='view') { ?>
										<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
							<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
						</tbody>
					</table>
				
					<div class="col-12 text-center">
						<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>

						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
					</div>
				</form>
			</div>
		</div>

		<script>

			$("#date").flatpickr({
				dateFormat: "d-m-Y"
			});

			function get_unit(this_id)
			{
				var id=this_id.split("_");
				id=id[1];

				var product = $("#product_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'get_unit',id:id,product:product},
					success:function(data)
					{	
						// alert(data);
						$("#unitdiv_"+id).html(data);	
						$(this).next().focus();
					}
				});				
			}

			function savedata()
			{

				// var PTask = $("#PTask").val();
				var cnt = $("#cnt").val();
				var record_no = $("#record_no").val();
				var date = $("#date").val();
				var requisition_by = $("#requisition_by").val();
				// var location = $("#location").val();
				
				
				var unit_array=[];
				var product_array=[];
				var qty_array=[];
				
				for(var i=1;i<=cnt;i++)
				{
					var unit = $("#unit_"+i).val();	
					var product = $("#product_"+i).val();
					var qty = $("#qty_"+i).val();
					
					product_array.push(product);
					unit_array.push(unit);
					qty_array.push(qty);
				
				} 

				var table = $("#table").val();
				var LastEdited = $("#LastEdited").val();

				jQuery.ajax({ url: 'get_ajax_values.php', type: 'POST',
					data: { Type: 'savesendrequi',cnt:cnt,record_no:record_no,date:date,LastEdited:LastEdited,requisition_by:requisition_by,unit_array:unit_array,product_array:product_array,qty_array:qty_array},
					success:function(data) {

						if(data!="") {

							// alert(data);
							$('#porequi').modal('hide');
						}
					}
				});	
			}

		</script>

	<?php
	break;

	case 'savesendrequi':
	
		$id=uniqid();

		$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'requisition_by'=>$_REQUEST['requisition_by'],'location'=>$_REQUEST['location'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));
		
		$insertedId=$utilObj->insertRecord('purchase_requisition',$arrValue);	

		$cnt1=$_REQUEST['cnt'];

		for($i=0;$i<$cnt1;$i++)
		{
			$id1=uniqid();
			
			$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'rm_qty'=>$_REQUEST['qty_array'][$i]);

			$insertedId=$utilObj->insertRecord('purchase_requisition_details', $arrValue2);
			
		}

		if($insertedId)
		echo $Msg='Record has been Added Sucessfully! ';

	break;


	case 'updatereorder':
	
	?>

		<div id="updaterolvl">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Update ReOrder Level</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="container">
					<table class="table table-striped" id="stocktable">
						<thead>
							<tr>
								<td style="width:1%;">Sr No</td>
								<td style="width:15%;">Ledger Name</td>
								<td style="width:8%;text-align:right;">Current ReOrder Level</td>
								<td style="width:8%;text-align:right;">New ReOrder Level</td>
							</tr>
						</thead>

						<tbody>
						<?php

							$i=0;

							$sid = rtrim($_REQUEST['sid'], ',');
							$pids=explode(",",$sid);

							foreach($pids as $row_demo) {

								$ledger=$utilObj->getSingleRow("stock_ledger","id='".$row_demo."' ");
								$i++;
						?>

							<tr>
								<td>
									<?php echo $i; ?>
									<input type="hidden" name="proid_<?php echo $i; ?>" id="proid_<?php echo $i; ?>" value="<?php echo $ledger['id']; ?>" >
								</td>

								<td>
									<input type="text" readonly class="form-control" id="productname_<?php echo $i; ?>" name="productname_<?php echo $i; ?>" value="<?php echo $ledger['name']; ?>" />
								</td>

								<td class="tdalign">
									<?php echo $ledger['reorderlvl']; ?>
								</td>

								<td>
									<input type="text" class=" form-control tdalign" id="newrolvl_<?php echo $i; ?>" name="newrolvl_<?php echo $i; ?>" value="" />
								</td>
							</tr>
						<?php } ?>
						<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
						</tbody>
						
					</table>
					<div class="col-md-12 text-center" style = "margin-top: 25px;margin-bottom: 25px;">

						<input type="button" class="btn btn-primary btn-sm mr-2" name="sbumit" value="Submit"  onClick="mysubmit();" style = "margin-right: 20px;"/>

						<button type="reset" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>

					</div>
				</div>
			</div>
		</div>

		<script>

			function mysubmit() {

				var cnt = $("#cnt").val();

				var product_array=[];
				var newrolvl_array=[];

				for(var i=1;i<=cnt;i++) {

					var product = $("#proid_"+i).val();
					var newrolvl = $("#newrolvl_"+i).val();

					product_array.push(product);
					newrolvl_array.push(newrolvl);
				}

				jQuery.ajax({ url: 'get_ajax_values.php', type: 'POST',
					data: { Type: 'saverorder',cnt:cnt,product_array:product_array,newrolvl_array:newrolvl_array},
					success:function(data)
					{
						if(data!="")
						{	
							// alert(data);
							$('#updatereorderlvl').modal('hide');
							alert("Updated Successfully!");
						}
					}
				});
			}
		</script>
		
	<?php
	break;

	case 'saverorder':

		$cnt1=$_REQUEST['cnt'];

		for($i=0;$i<$cnt1;$i++)
		{
			
			$arrValue2=array('LastEdited'=>date('Y-m-d H:i:s'),'reorderlvl'=>$_REQUEST['newrolvl_array'][$i] );

			$strWhere = "id='".$_REQUEST['product_array'][$i]."' ";

			$Updaterec=$utilObj->updateRecord('stock_ledger', $strWhere, $arrValue2);
			
		}

		if($Updaterec)
		echo $Msg='Record has been Added Sucessfully! ';

	break;

	case 'getposstate':
	
		$acc=$utilObj->getSingleRow("account_ledger","id='".$_REQUEST['id']."' ");

		echo $acc['mail_state'];

	break;

	case 'get_act_group':
		
		$acc=$utilObj->getSingleRow("group_master","group_name='".$_REQUEST['val']."' ");
		echo $acc['act_group'];

	break;

	case 'get_numcode':
		$limit = $_REQUEST['val'];
	?>
		<label class="form-label"> Numbering Code <span class="required required_lbl" style="color:red;">*</span></label>
		<input type="text" id="numbering_code" class="required form-control" placeholder=" Numbering code" name="numbering_code" value="<?php echo $rows['numbering_code']; ?>" maxlength="<?php echo $limit; ?>"/>
	<?php
	break;

	case 'get_bill':

		$i = $_REQUEST['id'];

	?>
		<?php
			if($_REQUEST['val']=='PO') {
		?>
			<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
				<option value="">Select Type</option>
				<?php
					$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' AND flag='0' ");
					foreach($inwarddata1 as $info) {

						// if($info["pid"]==$payment1['voucher_type']) { echo $select="selected"; } else { echo $select=""; }
						echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["voucher_code"].'</option>';
					}
				?>			
			</select>
		<?php } else { ?>

			<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $_REQUEST['recordnumber']; ?>"/>
		<?php } ?>

		<script>

			function getinvo_info(this_id) {

				var cust = $("#supplier").val();

				var id=this_id.split("_");
				id=id[1];

				var billno = $("#billno_"+id).val();

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type:'getinvo_info',billno:billno, cust:cust},
					success:function(data)
					{
						var bdid=data.split("#");
						var meas=bdid[0].split(",");
						jQuery("#invodate_"+id).val(bdid[0]);
						jQuery("#totalinvo_"+id).val(bdid[1]);
						// jQuery("#pendingamt_"+id).val(bdid[2]);
						jQuery("#pendingamt_" + id).val(bdid[2].trim());

					}
				});
			}
		</script>

	<?php
	break;

	case 'getinvo_info':

		$inward=$utilObj->getSingleRow("bill_adjustment","id ='".$_REQUEST['billno']."' ");

		$purchase=$utilObj->getSum("bill_adjustment","purchaseid='".$$inward['id']."' ","amount");
		
		if(!empty($purchase) || $purchase!=0) {

			$pending = $inward['amount'] - $purchase;
		} else {

			$pending = $inward['amount'];
		}
		
		echo trim(date('d-m-Y',strtotime($inward['date']))."#".$inward['total_amt']."#".$pending);
	?>

	<?php
	break;

	case 'adjust_purchase_invoice':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'get_bill1':

		$i = $_REQUEST['id'];

		$in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
			SELECT pid, sup, ptype, vcode FROM (
				SELECT id as pid, supplier as sup, type as ptype, purchaseid as vcode FROM purchase_payment_details
				UNION ALL
				SELECT id as pid, supplier as sup, type as ptype, purchaseid as vcode FROM cash_payment_details
			) AS combined_tables
			WHERE sup = '".$_REQUEST['cust']."' AND ptype = 'Advanced'
		) AS subquery");

		while ($inward = mysqli_fetch_array($in, MYSQLI_ASSOC)) {
			
			$inwarddata[]=$inward;
		}
	?>
		<?php
			if($_REQUEST['val']=='PO') {
		?>
			<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
				<option value="">Select Type</option>
				<?php
					foreach($inwarddata as $info) {
						if($info["pid"]==$payment1['voucher_type']) { echo $select="selected"; } else { echo $select=""; }
						echo  '<option value="'.$info["pid"].'" '.$select.'>'.$info["vcode"].'</option>';
					}
				?>
			</select>
		<?php } else { ?>

			<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $_REQUEST['recordnumber']; ?>"/>
		<?php } ?>

		<script>

			// function getinvo_info(this_id) {

			// 	var cust = $("#supplier").val();

			// 	var id=this_id.split("_");
			// 	id=id[1];

			// 	var billno = $("#billno_"+id).val();

			// 	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			// 		data: { Type:'getinvo_info1',billno:billno, cust:cust},
			// 		success:function(data)
			// 		{
			// 			var bdid=data.split("#");
			// 			var meas=bdid[0].split(",");
			// 			jQuery("#invodate_"+id).val(bdid[0]);
			// 			jQuery("#totalinvo_"+id).val(bdid[1]);
			// 			jQuery("#pendingamt_"+id).val(bdid[2]);
			// 		}
			// 	});
			// }

		</script>

	<?php
	break;

	case 'getinvo_info1':

		$in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
			SELECT pid, dat, tot FROM (
				SELECT id as pid, date as dat, amount as tot FROM purchase_payment_details
				UNION ALL
				SELECT id as pid, date as dat, amount as tot FROM cash_payment_details
			) AS combined_tables
			WHERE pid = '".$_REQUEST['billno']."'
		) AS subquery");

		$inward = mysqli_fetch_array($in);

		$purchase=$utilObj->getSum("purchase_invoice_adjust","purchaseid='".$_REQUEST['billno']."' ","amount");

		// $purchasedisc=$utilObj->getSum("cash_payment_details","purchaseid='".$_REQUEST['billno']."' ","amount");

		$pending = $inward['tot'] - $purchase;
		
		echo trim(date('d-m-Y',strtotime($inward['dat']))."#".$inward['tot']."#".$pending );
	?>

	<?php
	break;

	case 'adjust_purchase_service':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 10%;"> Bill No. </th>
					<th style="width: 4%;"> Invoice Date. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php		
				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
					// var_dump($rows);
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}
						$totalchk=$total=$payment['amt_pay'];
						$remain= ($record['grandtotal']-$purchase) - $info['amount'] - $info['discount'];

						$invodate = date('Y-m-d',strtotime($info['invodate']));
											
					} else {

					}
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['supplier']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php  ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $purchase_payment['amt_pay']; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'adjustentry2':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 10%;"> Bill No. </th>
					<th style="width: 4%;"> Invoice Date. </th>
					<th style="width: 8%;"> Total Invoice Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php		
				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM purchase_invoice_adjust where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
					// var_dump($rows);
				} else {
					// echo " con 2";
					// $rows = mysqli_query($GLOBALS['con'],"SELECT * FROM purchase_invoice where supplier= '".$_REQUEST['cust']."'order by Created DESC ")or die(mysqli_error());
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						$purchase=$utilObj->getSum("purchase_payment_details","purchaseid='".$info['purchaseid']."' AND id!='".$info['id']."'","amount");

						$purchasedisc=$utilObj->getSum("cash_payment_details","purchaseid='".$info['purchaseid']."' AND id!='".$info['id']."'","amount");

						$record=$utilObj->getSingleRow("purchase_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['purchaseid']."'"); 

						$payment=$utilObj->getSingleRow("purchase_payment","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['parent_id']."'"); 
											
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}
						$totalchk=$total=$payment['amt_pay'];
						$remain= ($record['grandtotal']-$purchase) - $info['amount'] - $info['discount'];

						$invodate = date('Y-m-d',strtotime($info['invodate']));
											
					} else {

						$purchase=$utilObj->getSum("purchase_payment_details","purchaseid='".$info['id']."'","amount");
						$purchasedisc=$utilObj->getSum("cash_payment_details","purchaseid='".$info['id']."'","amount");
						$record=$utilObj->getSingleRow("purchase_invoice","ClientID='".$_SESSION['Client_Id']."' AND id='".$info['id']."'");
						// $purchaseadvance=$utilObj->getSum("purchase_advance_used","purchaseid='".$info['ID']."'","amount");
						$totalchk +=$record['grandtotal']-$purchase-$purchasedisc;//-$purchaseadvance;	//For showing No data available in table
					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							$in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
								SELECT pid, sup,vcode FROM (
									SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
									UNION ALL
									SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
								) AS combined_tables
								WHERE sup = '".$_REQUEST['cust']."'
							) AS subquery");
		
							while ($inward1 = mysqli_fetch_array($in, MYSQLI_ASSOC)) {
								
								$inwarddata1[]=$inward1;
							}
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										foreach($inwarddata1 as $info1) {
											if($info1["pid"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["pid"].'" '.$select.'>'.$info1["vcode"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php  ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $purchase_payment['amt_pay']; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'get_bill2':

		$i = $_REQUEST['id'];

		$in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
			SELECT pid, sup, ptype, vcode FROM (
				SELECT id as pid, supplier as sup, type as ptype, purchaseid as vcode FROM purchase_payment_details
				UNION ALL
				SELECT id as pid, supplier as sup, type as ptype, purchaseid as vcode FROM cash_payment_details
			) AS combined_tables
			WHERE sup = '".$_REQUEST['cust']."' AND ptype = 'Advanced'
		) AS subquery");

		while ($inward = mysqli_fetch_array($in, MYSQLI_ASSOC)) {
			
			$inwarddata[]=$inward;
		}
	?>
		<?php
			if($_REQUEST['val']=='PO') {
		?>
			<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
				<option value="">Select Type</option>
				<?php
					foreach($inwarddata as $info) {
						if($info["pid"]==$payment1['voucher_type']) { echo $select="selected"; } else { echo $select=""; }
						echo  '<option value="'.$info["pid"].'" '.$select.'>'.$info["vcode"].'</option>';
					}
				?>
			</select>
		<?php } else { ?>

			<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $_REQUEST['recordnumber']; ?>"/>
		<?php } ?>

		<script>

			// function getinvo_info(this_id) {

			// 	var cust = $("#supplier").val();

			// 	var id=this_id.split("_");
			// 	id=id[1];

			// 	var billno = $("#billno_"+id).val();

			// 	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			// 		data: { Type:'getinvo_info1',billno:billno, cust:cust},
			// 		success:function(data)
			// 		{
			// 			var bdid=data.split("#");
			// 			var meas=bdid[0].split(",");
			// 			jQuery("#invodate_"+id).val(bdid[0]);
			// 			jQuery("#totalinvo_"+id).val(bdid[1]);
			// 			jQuery("#pendingamt_"+id).val(bdid[2]);
			// 		}
			// 	});
			// }

		</script>

	<?php
	break;

	case 'getinvo_info2':

		$in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
			SELECT pid, dat, tot FROM (
				SELECT id as pid, date as dat, amount as tot FROM purchase_payment_details
				UNION ALL
				SELECT id as pid, date as dat, amount as tot FROM cash_payment_details
			) AS combined_tables
			WHERE pid = '".$_REQUEST['billno']."'
		) AS subquery");

		$inward = mysqli_fetch_array($in);

		$purchase=$utilObj->getSum("purchase_invoice_adjust","purchaseid='".$_REQUEST['billno']."' ","amount");

		// $purchasedisc=$utilObj->getSum("cash_payment_details","purchaseid='".$_REQUEST['billno']."' ","amount");

		$pending = $inward['tot'] - $purchase;
		
		echo trim(date('d-m-Y',strtotime($inward['dat']))."#".$inward['tot']."#".$pending );
	?>

	<?php
	break;


	case 'adjust_purchase_return':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'adjust_sale_invoice':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'adjust_sale_service':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;


	case 'adjust_credit_note':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	<?php
	break;

	case 'adjust_sale_return':
		

	?>
		<table id="myTable1" style="" class=" table  table-sm table-bordered  " cellspacing="0" width="100%">
			<thead>
				<tr class=" table-light"> 
					<th style="width: 0%;text-align:center;">No.</th>
					<th style="width: 7%;"> Type </th>
					<th style="width: 8%;"> Bill No. </th>
					<th style="width: 6%;"> Bill Date. </th>
					<th style="width: 8%;"> Total Bill Amount </th>
					<th style="width: 8%;"> Pending Amount </th>
					<th style="width: 8%;"> Amount </th> 
					<th style="width: 0%;"></th>
				</tr>
			</thead>
			<tbody>
			<?php

				$i=0;
				$total=0;

				if( $_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' ) {

					// echo " con 1";
					$rows = mysqli_query($GLOBALS['con'],"SELECT * FROM bill_adjustment where ClientID='".$_SESSION['Client_Id']."' AND parent_id='".$_REQUEST['id']."' ")or die(mysqli_error());

					while ($inward = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}
				} else {

					// echo " con 2";
					$inwarddata[0]['id']=1;
				}

				// while($info=mysqli_fetch_array($rows))
				foreach($inwarddata as $info) {

					$adjusttot = $info['total_amt'];
					// var_dump($info);
					$i++;
					if( ($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' && $_REQUEST['ptype']!='Advanced')&&$purchase_payment['supplier']==$_REQUEST['cust'] ) 
					{
						
						if ($record['id']==$info['purchaseid']) {

							$checked = "checked";
						} else {
							
							$checked = "";
						}

						$invodate = date('d-m-Y',strtotime($info['invodate']));
											
					} else {

					}

					// if(($record['grandtotal']-$purchase-$purchasedisc)>0)//-$purchaseadvance
					// {
			?>
				<tr class='' id="row_<?php echo $i; ?>">
				
					<td style="text-align:center;">
						<?php echo $i; ?>
					</td>
					
					<td>
						<select class="required form-select select2" data-placeholder="Select Payment Method" onchange="get_bill1(this.id)" style="width:100%"  <?php echo $disabled;?> name="type_<?php echo $i; ?>" id="type_<?php echo $i; ?>">
							<option value="">Select Type</option>
							<option value="Advanced" <?php if($info["type"]=='Advanced') echo $select='selected'; else $select='';?>>New Reference</option> 
							<option value="PO" <?php if($info["type"]=='PO') echo $select='selected'; else $select='';?>>Against Bill</option>				
						</select>
					</td>

					<td>
						<div id="voucher_<?php echo $i; ?>">
						<?php
							// $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
							// 	SELECT pid, sup,vcode FROM (
							// 		SELECT id as pid, supplier as sup, pur_invoice_code as vcode FROM purchase_invoice
							// 		UNION ALL
							// 		SELECT id as pid, supplier as sup, voucher_code as vcode FROM purchase_invoice_service
							// 	) AS combined_tables
							// 	WHERE sup = '".$_REQUEST['supplier']."'
							// ) AS subquery");
						?>
						<?php if($_REQUEST['PTask']=='update') { ?>
							
							<?php
								if($info["type"]=='PO') {
							?>
								<select class="required form-select select2" data-placeholder="Select Payment Method" style="width:100%"  <?php echo $disabled;?> name="billno_<?php echo $i; ?>" id="billno_<?php echo $i; ?>" onchange="getinvo_info(this.id);">
									<option value="">Select Type</option>
									<?php
										$inwarddata1=$utilObj->getMultipleRow("bill_adjustment","supplier='".$_REQUEST['cust']."' AND type='Advanced' ");
										foreach($inwarddata1 as $info1) {
											if($info1["id"]==$info['purchaseid']) { echo $select="selected"; } else { echo $select=""; }
											echo  '<option value="'.$info1["id"].'" '.$select.'>'.$info1["voucher_code"].'</option>';
										}
									?>
								</select>
							<?php } else { ?>

								<input type="text" id="billno_<?php echo $i; ?>" class="required form-control" readonly name="billno_<?php echo $i; ?>" value="<?php echo $info['purchaseid']; ?>"/>
							<?php } ?>
						<?php } ?>
						</div>
					</td>

					<td>
						<input type="text" id="invodate_<?php echo $i; ?>" class="required form-control" readonly <?php echo $readonly;?> placeholder="Date" name="invodate_<?php echo $i; ?>" value="<?php echo $invodate; ?>"/>
					</td>

					<td>
						<input type="text" id="totalinvo_<?php echo $i; ?>" class="required form-control tdalign" readonly <?php echo $readonly;?> placeholder="" name="totalinvo_<?php echo $i; ?>" value="<?php echo $info['invoamt']; ?>"/>
					</td>

					<?php
						if($info['invoamt']!=0) {

							$pending = $info['invoamt'] - $info['amount'];
						} else {

							$pending = 0;
						}
						
					?>
					<td>
						<input type="text" id="pendingamt_<?php echo $i; ?>" class="required form-control tdalign" readonly name="pendingamt_<?php echo $i; ?>" value="<?php echo $pending; ?>"/>
					</td>

					<td>
						<input type="text" id="payamt_<?php echo $i; ?>" class="required form-control tdalign" placeholder="" name="payamt_<?php echo $i; ?>" value="<?php echo $info['amount']; ?>" onkeyup="gettotalamt(this.id);"/>
					</td>

					<td>
					<?php if($i>1) { ?>
						<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i; ?>' style="cursor:pointer;" onclick="delete_row_adjust(this.id);"></i>
					<?php } ?>
					</td>

				</tr>
				<?php
					// }
					}
					// if($totalchk==0){
					// 	echo "<tr><td colspan='5' style='text-align:center'>No data available in table</td></tr>";
					// }
				?>
				
				<input type='hidden' name="cntad" id="cntad" value="<?php echo $i; ?>">
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;">
						<button type="button" class="btn btn-light" id="addmore11" onclick="addRow('myTable');"><i class="fas fa-plus-circle fa-lg" style="color: #000000;"></i></button>
					</td>
					<td style='text-align: center;padding-top: 15px;'>Total</td>
					<td>
						<input type="text" class="tdalign form-control" value="<?php echo $adjusttot; ?>" id="totalvalue" name="totalvalue" readonly style="height: 35px;width:100%;padding-left:10px">
					</td>
					<td></td>
				</tr>
			</tfoot>
		</table>

	<?php
	break;

	case 'stockreq':
		
		$getinvno= mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from production_requisition");
		$result=mysqli_fetch_array($getinvno);
		$record_no=$result['pono']+1; 	
		$date=date('d-m-Y');
		$username=$utilObj->getSingleRow("employee","id='".$_SESSION['Ck_User_id']."' ");
		$requisition_by = $username['name'];
		$PTask = $_REQUEST['PTask'];
	?>
		<div id="streq">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Stock Requisition</h3>
				</div>
				<form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "purchase_requisition"; ?>"/>

					<div class="col-md-2">
                        <label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" class="form-control flatpickr" id="record_no" name="record_no" required value="<?php echo $record_no; ?>" <?php echo $readonly; ?> readonly />
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Requisition Date <span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date; ?>" <?php echo $disabled;?>/>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Requisition By <span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" id="requisition_by" class="required form-control"  <?php echo $readonly; ?> placeholder="Requisition By" name="requisition_by" value="<?php echo $requisition_by; ?>" readonly/>
                    </div>

					<div class="col-md-3">
                        <label class="form-label">from Location <span class="required required_lbl" style="color:red;">*</span></label>
                        <select id="from_location" name="from_location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
                        <?php 
                            echo '<option value="">Select Location</option>';

							$data=$utilObj->getMultipleRow("location","1 group by id");
							foreach($data as $info) {

								if($info['id']==$rows['from_location']){ echo $select="selected"; } else { echo $select=""; }
								echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
							}
                        ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">to Location <span class="required required_lbl" style="color:red;">*</span></label>
                        <select id="to_location" name="to_location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
                        <?php 
                            echo '<option value="">Select Location</option>';

                            $place = explode(",",$username['multiloc']);
							foreach($place as $pid) {

								$data=$utilObj->getMultipleRow("location","id = '".$pid."' group by id");

								foreach($data as $info) {

									if($info['id']==$rows['location']){ echo $select="selected"; } else { echo $select=""; }
									echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
								}  
							}
                        ?>
                        </select>
                    </div>
					
                    <div class="col-md-2">
                        <label class="form-label">Department Type<span class="required required_lbl" style="color:red;">*</span></label>
                        <select name="dep_type" id="dep_type" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true">
                            <option value="">Select</option>;
                            <option value="production" <?php if($rows["dep_type"]=='production') echo $select='selected'; else $select=''; ?>>Production</option>
                            <option value="packaging" <?php if($rows["dep_type"]=='packaging') echo $select='selected'; else $select=''; ?>>Packaging</option>
                            <option value="dispatch" <?php if($rows["dep_type"]=='dispatch') echo $select='selected'; else $select=''; ?>>Dispatch</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="first-name" class="control-label" >Narration</label>
                        <textarea type="text" <?php echo $readonly;?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 100%;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $purchase_invoice['otrnar'];?></textarea>
                    </div>
                    
                    <h4 class="role-title">Material Details</h4>
                
                    <table class="table table-bordered" id="myTable"> 
                        <thead>
                            <tr>
                                <th style="width:2%;text-align:center;">Sr.No.</th> 
                                <th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
                                <th style="width: 10%;text-align:center;">Unit<span class="required required_lbl" style="color:red;">*</span> </th>
                                <th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
                                <!-- <th style="width:5%;text-align:center;"></th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i=0;

							$sid = rtrim($_REQUEST['sid'], ',');

							$record5 = explode(",", $sid);

                            foreach($record5 as $row_demo) {

								$i++;

								$sale_order=$utilObj->getSingleRow("sale_order_details","id='".$row_demo."' ");

								$totstock = gettotalstock($sale_order['product'],date('Y-m-d'));
                        
								$rqqty='';
								$difqty = $sale_order['qty']-$totstock;
								
								if($difqty>=0) {

									$rqqty = $product['reorderlvl']+$difqty;
								} else {

									$difrqqty = $product['reorderlvl']+($difqty);
									if($difrqqty>0) {

										$rqqty = $difrqqty;
									} else {

										$rqqty = 0;
									}
								}

                        ?>
                            <tr id='row_<?php echo $i;?>'>
                                <td style="text-align:center;">
                                    <label id="idd_<?php echo $i;?>" name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
                                </td>

                                <td>
                                    <select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);">	
                                        <?php 
                                            echo '<option value="">Select</option>';
                                            $record=$utilObj->getMultipleRow("stock_ledger","1 ");
                                            foreach($record as $e_rec)
                                            {
                                                if($sale_order['product']==$e_rec["id"]) echo $select='selected'; else $select='';
                                                echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
                                            }
                                        ?>  
                                    </select>
                                </td>

                                <td>
                                    <div id='unitdiv_<?php echo $i;?>'>
                                        <input type="text" id="unit_<?php echo $i;?>" class=" form-control required" readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $sale_order['unit']; ?>"/>
                                    </div>
                                </td>

                                <td>
                                    <input type="text" id="qty_<?php echo $i;?>" class="number form-control"  <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $rqqty; ?>"/>
                                </td>
                            </tr>
                        <?php } ?>
                            <input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
                        </tbody>
                    </table>
					<div class="col-12 text-center">
						<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
                    </div>
				</form>
			</div>
		</div>

		<script>
			
			function savedata() {

				// var PTask = $("#PTask").val();
				var id = $("#id").val();
				var cnt = $("#cnt").val();
				var record_no = $("#record_no").val();
				var date = $("#date").val();
				var requisition_by = $("#requisition_by").val();
				var to_location = $("#to_location").val();
				var from_location = $("#from_location").val();
				var dep_type = $("#dep_type").val();
				var table = $("#table").val();
				var LastEdited = $("#LastEdited").val();	
				
				var unit_array=[];
				var product_array=[];
				var qty_array=[];

				console.log(to_location+"--");
				
				for(var i=1;i<=cnt;i++) {

					var unit = $("#unit_"+i).val();	
					var product = $("#product_"+i).val();
					var qty = $("#qty_"+i).val();	
					
					product_array.push(product);
					unit_array.push(unit);
					qty_array.push(qty);
				}

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type: 'save_salerequi', id:id,cnt:cnt,date:date,table:table,LastEdited:LastEdited,requisition_by:requisition_by,unit_array:unit_array,product_array:product_array,qty_array:qty_array,to_location:to_location,dep_type:dep_type,record_no:record_no,from_location:from_location },
					success:function(data)
					{	
						if(data!="") {

							alert("Record Added Successfully!");
							$('#stockreq').modal('hide');
						}
					}
				});	
			}
		</script>
		
	<?php
	break;

	case 'save_salerequi':
	
		$id=uniqid();

		$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'requisition_by'=>$_REQUEST['requisition_by'],'from_location'=>$_REQUEST['from_location'],'location'=>$_REQUEST['to_location'],'dep_type'=>$_REQUEST['dep_type'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));

		print_r($arrValue);
		$insertedId=$utilObj->insertRecord('production_requisition',$arrValue);

		$cnt=$_REQUEST['cnt'];

		for($i=0;$i<$cnt;$i++) {
			
			$id1=uniqid();
			
			$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'location'=>$_REQUEST['to_location'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'user'=>$_SESSION['Ck_User_id'] );

			print_r($arrValue2);
			$insertedId=$utilObj->insertRecord('production_requisition_details', $arrValue2);
		}

		if($insertedId)
		echo $Msg='Record has been Added Sucessfully! ';

	break;

	case 'packrequi':
		
		$getinvno= mysqli_query($GLOBALS['con'],"Select MAX(record_no) AS pono from production_requisition");
		$result=mysqli_fetch_array($getinvno);
		$record_no=$result['pono']+1; 	
		$date=date('d-m-Y');
		$username=$utilObj->getSingleRow("employee","id='".$_SESSION['Ck_User_id']."' ");
		$requisition_by = $username['name'];
		$PTask = $_REQUEST['PTask'];
	?>
		<div id="pkreq">
			<div class="modal-body ">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="remove_urldata(0);"></button>
				<div class="text-center mb-4">
					<h3 class="role-title">Stock Requisition</h3>
				</div>
				<form id="" data-parsley-validate class="row g-3" action="../purchase_requisition_list.php"  method="post" data-rel="myForm">
					<input type="hidden" name="PTask" id="PTask" value="<?php echo $PTask; ?>"/>  
					<input type="hidden"  name="id" id="id" value="<?php echo $rows['id'];?>"/>	
					<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
					<input type="hidden"  name="table" id="table" value="<?php echo "purchase_requisition"; ?>"/>

					<div class="col-md-2">
                        <label class="form-label">Record No<span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" class="form-control flatpickr" id="record_no" name="record_no" required value="<?php echo $record_no; ?>" <?php echo $readonly; ?> readonly />
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Requisition Date <span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date; ?>" <?php echo $disabled;?>/>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Requisition By <span class="required required_lbl" style="color:red;">*</span></label>
                        <input type="text" id="requisition_by" class="required form-control"  <?php echo $readonly; ?> placeholder="Requisition By" name="requisition_by" value="<?php echo $requisition_by; ?>" readonly/>
                    </div>

					<div class="col-md-3">
                        <label class="form-label">from Location <span class="required required_lbl" style="color:red;">*</span></label>
                        <select id="from_location" name="from_location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
                        <?php 
                            echo '<option value="">Select Location</option>';

							$data=$utilObj->getMultipleRow("location","1 group by id");
							foreach($data as $info) {

								if($info['id']==$rows['from_location']){ echo $select="selected"; } else { echo $select=""; }
								echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
							}
                        ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">to Location <span class="required required_lbl" style="color:red;">*</span></label>
                        <select id="to_location" name="to_location" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
                        <?php 
                            echo '<option value="">Select Location</option>';

                            $place = explode(",",$username['multiloc']);
							foreach($place as $pid) {

								$data=$utilObj->getMultipleRow("location","id = '".$pid."' group by id");

								foreach($data as $info) {

									if($info['id']==$rows['location']){ echo $select="selected"; } else { echo $select=""; }
									echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
								}  
							}
                        ?>
                        </select>
                    </div>
					
                    <div class="col-md-2">
                        <label class="form-label">Department Type<span class="required required_lbl" style="color:red;">*</span></label>
                        <select name="dep_type" id="dep_type" <?php echo $disabled; ?> class="select2 form-select required" data-allow-clear="true">
                            <option value="">Select</option>;
                            <option value="production" <?php if($rows["dep_type"]=='production') echo $select='selected'; else $select=''; ?>>Production</option>
                            <option value="packaging" <?php if($rows["dep_type"]=='packaging') echo $select='selected'; else $select=''; ?>>Packaging</option>
                            <option value="dispatch" <?php if($rows["dep_type"]=='dispatch') echo $select='selected'; else $select=''; ?>>Dispatch</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="first-name" class="control-label" >Narration</label>
                        <textarea type="text" <?php echo $readonly;?> class=" form-control smallinput col-xs-12" id="otrnar" style="width: 100%;" name="otrnar" onkeyup="showgrandtotal();" onBlur="showgrandtotal();"><?php echo $purchase_invoice['otrnar'];?></textarea>
                    </div>
                    
                    <h4 class="role-title">Material Details</h4>
                
                    <table class="table table-bordered" id="myTable"> 
                        <thead>
                            <tr>
                                <th style="width:2%;text-align:center;">Sr.No.</th> 
                                <th style="width: 15%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
                                <th style="width: 10%;text-align:center;">Unit<span class="required required_lbl" style="color:red;">*</span> </th>
                                <th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
                                <th style="width:2%;text-align:center;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i=0;

							// $sid = rtrim($_REQUEST['sid'], ',');

							// $record5 = explode(",", $sid);

							$record5=$utilObj->getMultipleRow("bill_of_material_details","parent_id='".$_REQUEST['bomid']."' order by id  ASC ");

                            foreach($record5 as $row_demo) {

								$i++;

								$sale_order=$utilObj->getSingleRow("sale_order_details","id='".$row_demo."' ");

								$totstock = gettotalstock($sale_order['product'],date('Y-m-d'));
                        
								$rqqty='';
								$difqty = $sale_order['qty']-$totstock;
								
								if($difqty>=0) {

									$rqqty = $product['reorderlvl']+$difqty;
								} else {

									$difrqqty = $product['reorderlvl']+($difqty);
									if($difrqqty>0) {

										$rqqty = $difrqqty;
									} else {

										$rqqty = 0;
									}
								}
								$bill_of_material=$utilObj->getSingleRow("bill_of_material","id ='".$_REQUEST['bomid']."' ");

								$requiredqty=round(($row_demo['qty']*$_REQUEST['qty'])/$bill_of_material['qty'],2);

                        ?>
                            <tr id='row_<?php echo $i;?>'>
                                <td style="text-align:center;">
                                    <label id="idd_<?php echo $i;?>" name="idd_<?php echo $i;?>"><?php echo $i; ?></label>
                                </td>

                                <td>
                                    <select id="product_<?php echo $i;?>" name="product_<?php echo $i;?>" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit(this.id);">	
                                        <?php 
                                            echo '<option value="">Select</option>';
                                            $record=$utilObj->getMultipleRow("stock_ledger","1 ");
                                            foreach($record as $e_rec)
                                            {
                                                if($row_demo['product']==$e_rec["id"]) echo $select='selected'; else $select='';
                                                echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
                                            }
                                        ?>  
                                    </select>
                                </td>

                                <td>
                                    <div id='unitdiv_<?php echo $i;?>'>
                                        <input type="text" id="unit_<?php echo $i;?>" class=" form-control required" readonly <?php echo $readonly;?> name="unit_<?php echo $i;?>" value="<?php echo $row_demo['unit']; ?>"/>
                                    </div>
                                </td>

                                <td>
                                    <input type="text" id="qty_<?php echo $i;?>" class="number form-control"  <?php echo $readonly;?> name="qty_<?php echo $i;?>" value="<?php echo $requiredqty; ?>"/>
                                </td>

								<td>
									<i class="bx bx-trash me-1"  id='deleteRow_<?php echo $i ;?>' style="cursor: pointer;" onclick="delete_row(this.id);"></i>
								</td>
                            </tr>
                        <?php } ?>
                            <input type="hidden" name="cnt" id="cnt" value="<?php echo $i ;?>">
                        </tbody>
                    </table>
					<div class="col-12 text-center">
						<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
                    </div>
				</form>
			</div>
		</div>

		<script>
			
			function delete_row(rwcnt) {
				
				var id=rwcnt.split("_");
				rwcnt=id[1];
				var count=$("#cnt").val();	
				if(count>1) {

					var r=confirm("Are you sure!");
					if (r==true) {		
						
						$("#row_"+rwcnt).remove();
							
						for(var k=rwcnt; k<=count; k++) {

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
							
							jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
							
						}
						jQuery("#cnt").val(parseFloat(count-1)); 
					}
				} else  {

					alert("Can't remove row Atleast one row is required");
					return false;
				}	 
			}

			function savedata() {

				// var PTask = $("#PTask").val();
				var id = $("#id").val();
				var cnt = $("#cnt").val();
				var record_no = $("#record_no").val();
				var date = $("#date").val();
				var requisition_by = $("#requisition_by").val();
				var to_location = $("#to_location").val();
				var from_location = $("#from_location").val();
				var dep_type = $("#dep_type").val();
				var table = $("#table").val();
				var LastEdited = $("#LastEdited").val();	
				
				var unit_array=[];
				var product_array=[];
				var qty_array=[];

				console.log(to_location+"--");
				
				for(var i=1;i<=cnt;i++) {

					var unit = $("#unit_"+i).val();
					var product = $("#product_"+i).val();
					var qty = $("#qty_"+i).val();	
					
					product_array.push(product);
					unit_array.push(unit);
					qty_array.push(qty);
				}

				jQuery.ajax({url:'get_ajax_values.php', type:'POST',
					data: { Type: 'save_packrequi', id:id,cnt:cnt,date:date,table:table,LastEdited:LastEdited,requisition_by:requisition_by,unit_array:unit_array,product_array:product_array,qty_array:qty_array,to_location:to_location,dep_type:dep_type,record_no:record_no,from_location:from_location },
					success:function(data) {

						if(data!="") {

							alert("Record Added Successfully!");
							$('#packrequi').modal('hide');
						}
					}
				});	
			}
		</script>
		
	<?php
	break;

	
	case 'save_packrequi':
	
		$id=uniqid();

		$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'record_no'=>$_REQUEST['record_no'],'requisition_by'=>$_REQUEST['requisition_by'],'from_location'=>$_REQUEST['from_location'],'location'=>$_REQUEST['to_location'],'dep_type'=>$_REQUEST['dep_type'],'date'=>date('Y-m-d',strtotime($_REQUEST['date'])));

		print_r($arrValue);
		$insertedId=$utilObj->insertRecord('production_requisition',$arrValue);

		$cnt=$_REQUEST['cnt'];

		for($i=0;$i<$cnt;$i++) {
			
			$id1=uniqid();
			
			$arrValue2=array('id'=>$id1,'parent_id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'location'=>$_REQUEST['to_location'],'product'=>$_REQUEST['product_array'][$i],'unit'=>$_REQUEST['unit_array'][$i],'qty'=>$_REQUEST['qty_array'][$i],'user'=>$_SESSION['Ck_User_id'] );

			print_r($arrValue2);
			$insertedId=$utilObj->insertRecord('production_requisition_details', $arrValue2);
		}

		if($insertedId)
		echo $Msg='Record has been Added Sucessfully! ';

	break;

}

