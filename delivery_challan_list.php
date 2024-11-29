<?php
include("header.php");
$task = $_REQUEST['PTask'];
if ($task == '') {
	$task = 'Add';
}
if ($_REQUEST['PTask'] == 'view') {
	$readonly = "readonly";
	$disabled = "disabled";
} else {
	$readonly = "";
	$disabled = "";
}

?>

<div class="container-xxl flex-grow-1 container-p-y ">

	<div class="row">
		<div class="col-md-3">
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Delivery Challan </h4>
		</div>
		<div class="col-md-4">
		<?php if((CheckCreateMenu())==1) { ?>
			<button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
		<?php } ?>

		<?php if((CheckDeleteMenu())==1){ ?>
			<button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
		<?php } ?>
		</div>
	</div>
	<!-- Invoice List Table -->


	<div id="u_table" style="display:block">
		<div class="card">
			<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">

				<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
					<thead>
						<tr>
							<th width='3%'><input type='checkbox' value='0' id='select_all'
									onclick="select_all();" />&nbsp Sr.No.</th>
							<th width='10%'>Date</th>
							<th width='10%'>Chalan No.</th>
							<th width='10%'>Customer</th>
							<th width='10%'>Sale Order No.</th>
							<th>Product</th>
							<th>Unit</th>
							<th>Quantity</th>
							<th>User</th>
							<?php if ((CheckEditMenu()) == 1) { ?>
								<th width='10%'>Actions</th>
							<?php } ?>
						</tr>
					</thead>

					<tbody>
						<?php
						$i = 0;
						$data = $utilObj->getMultipleRow("delivery_challan", "1");
						foreach ($data as $info) {
							$i++;
							$j = 0;
							$href = 'delivery_challan_list.php?id=' . $info['id'] . '&PTask=view';
							$d1 = $rows = $utilObj->getCount("sale_invoice", "delivery_challan_no ='" . $info['id'] . "'");
							if ($d1 > 0) {
								$dis = "disabled";
							} else {
								$dis = "";
							}
							$customer = $utilObj->getSingleRow("account_ledger", "id='" . $info['customer'] . "'");
							$location = $utilObj->getSingleRow("location", "id='" . $info['location'] . "'");
							$sale_order = $utilObj->getSingleRow("sale_order", "id='" . $info['saleorder_no'] . "'");
							//$voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
							$data1 = $utilObj->getMultipleRow("delivery_challan_details", "parent_id='" . $info['id'] . "'");
							foreach ($data1 as $info1) {
								$j++;
								$product = $utilObj->getSingleRow("stock_ledger", "id='" . $info1['product'] . "'");
								if ($j == 1) {
									$rowspan = Count($data1);
									$hidetd = "";
								} else {
									$rowspan = 1;
									$hidetd = "hidetd";
								}


								?>
								<tr>
									<td class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input
											type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list'
											value='<?php echo $info['id']; ?>' />&nbsp &nbsp
										<?php echo $i; ?>
									</td>
									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
										<?php echo date('d-m-Y', strtotime($info['date'])); ?>
									</td>
									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><a
											href="<?php echo $href; ?>">
											<?php echo $info['challan_no']; ?>
									</td>
									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
										<?php echo $customer['name']; ?>
									</td>
									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
										<?php echo $sale_order['order_no']; ?>
									</td>
									<td>
										<?php echo $product['name']; ?>
									</td>
									<td>
										<?php echo $info1['unit']; ?>
									</td>
									<td>
										<?php echo $info1['qty']; ?>
									</td>
									<?php $username = $utilObj->getSingleRow("employee", "id='" . $info['user'] . "'"); ?>
									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
										<?php echo $username['name']; ?>
									</td>

									<td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
										<a data-content="Chalan Print" title=""
											href="chalan_print.php?id=<?php echo $info['id']; ?>&Task=update"
											class="btn btn-warning btn-xs" data-original-title="Chalan Print">Chalan Print</a>
										<?php

										if ($d1 == 0) { ?>
											<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
												data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
											<div class="dropdown-menu">
												<?php if ((CheckEditMenu()) == 1) { ?>
													<a class="dropdown-item" href="delivery_challan_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
												<?php } ?>
												<?php if ((CheckDeleteMenu()) == 1) { ?>
													<a class="dropdown-item"
														href="delivery_challan_list.php?id=<?php echo $info['id']; ?>&PTask=delete"><i
															class="bx bx-trash me-1"></i> Delete</a>
												<?php } ?>
											</div>

										<?php } ?>
										<?php if ($info['Created'] != '') {
											//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
											$username = mysqli_fetch_array($query);
											$created = date('d-m-Y h:i A', strtotime($info['Created']));
											$user = $username['fname'] . "  " . $username['lname'];
											$createuser = "Created : " . $user . " " . $created;
										} else {
											$createuser = "";
										}

										//User - Updated Entry
										if ($info['updateduser'] != '') {
											//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['updateduser']."'");
											$username = mysqli_fetch_array($query);
											$created = date('d-m-Y h:i A', strtotime($info['LastEdited']));
											$user = $username['fname'] . "  " . $username['lname'];
											$createuser .= "&#10; Updated : " . $user . " " . $created;
										} else {
											$createuser .= "";
										}
										?>
										<a $dasable ata-content='clock' title='<?php echo $createuser; ?>' class='popovers'
											data-placement='top' style='color:brown;' data-trigger='hover' href='#'><i
												class='fa fa-clock-o'></i></a>
									</td>
								</tr>
							<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php     $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(challan_no) AS pono from delivery_challan");
    $result=mysqli_fetch_array($getinvno);
    $challan_no=$result['pono']+1;  
    $date=date('d-m-Y');	
    if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
        $id=$_REQUEST['id'];
        $rows=$utilObj->getSingleRow("delivery_challan","id ='".$id."'");
        $challan_no=$rows['challan_no'];	
        $date=date('d-m-Y',strtotime($rows['date']));
        //$grandtotal=$rows['grandtotal'];
        
        
    } else{
        $rows=null;
    }

    $common_id=uniqid();?>
	<div class="container-xxl flex-grow-1 container-p-y "
		style=" background-color: white; padding: 30px; background: #ffffff; display:none"
		id="u_form">


		<div class="row form-validate">
			<!-- FormValidation -->
			<div class="col-12">
				<div class="card">
					<div class="card-body ">
					<form id="" data-parsley-validate class="row g-3" action="../delivery_challan_list.php"  method="post" data-rel="myForm">
			
			<input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
			<input type="hidden"  name="common_id"         id="common_id"         value="<?php echo $common_id;?>"/>	
			<input type="hidden"  name="id"         id="id"         value="<?php echo $rows['id'];?>"/>	
			<input type="hidden"  name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
			<input type="hidden"  name="table"      id="table"      value="<?php echo "delivery_challan"; ?>"/>
				<div class="col-md-4">
					<label class="form-label">Challan No. <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" id="challan_no" class="required form-control" readonly <?php echo $readonly;?> placeholder="Order No." name="challan_no" value="<?php echo $challan_no;?>"/>
				</div>
	
				<div class="col-md-4">
					<label class="form-label"> Date <span class="required required_lbl" style="color:red;">*</span></label>
					<input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
				</div>
				
				<div class="col-md-4">
					<label class="form-label">Customer<span class="required required_lbl" style="color:red;">*</span></label>
					<select id="customer" name="customer"  onchange="get_saleorder();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
					<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
							foreach($data as $info){
								if($info["id"]==$rows['customer']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div>
				<div class="col-md-4" id="sale_order_div">
				
				</div>

				<!-- <div class="col-md-4" id="location">
					
				</div> -->
				
				
			
			<div id="table_div" style="overflow: hidden;">
			
			</div>
	
		</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/ Content -->


<script>
function hideshow()
{ 
	if(document.getElementById('u_form').style.display=="none")
	{
		document.getElementById('u_form').style.display="block"
		document.getElementById('u_table').style.display="none"
		//document.getElementById('button').style.display="none"
		//$('#demo-form2').hide();
		$('#demo-form2').show();
		$("#add_new").val("Show List");
	}
	else
	{
		document.getElementById('u_form').style.display="none"
		document.getElementById('u_table').style.display="block"
		$(".add_new").val("Add New");
		$('#demo-form2').show();		
		window.location="delivery_challan_list.php";
		
	}
	
}	
	<?php
	if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view') { ?>
		window.onload = function () {

			document.getElementById("add_new").click();
			$("#add_new").val("Show List");
			get_saleorder();
			get_totalqty();
			// getlocation();
			

		};
	<?php } ?>



	<?php
	if ($_REQUEST['PTask'] == 'delete') { ?>
		window.onload = function () {
			var r = confirm("Are you sure to delete?");
			if (r == true) {
				deletedata("<?php echo $_REQUEST['id']; ?>");
			}
			else {
				window.location = "delivery_challan_list.php";
			}

		};
	<?php } ?>
	function CheckDelete() {

		var val = '';
		$('input[type="checkbox"]').each(function () {
			if (this.checked == true && this.value != 'on') {
				val += this.value + ",";
			}
		});
		if (val == '') {
			alert('Please Select Atleast 1 record!!!!');
		}
		else {
			val = val.substring(0, val.length - 1);
			window.location = "delivery_challan_list.php?PTask=delete&id=" + val;

		}
	}

	// function mysubmit(a)
	// {
	// 	return _isValidpopup(a);	
	// }

	// function remove_urldata() {
	// 	window.location = "delivery_challan_list.php";
	// }

	// function savedata() {
	// 	var PTask = $("#PTask").val();
	// 	var table = $("#table").val();
	// 	var LastEdited = $("#LastEdited").val();
	// 	var id = $("#id").val();


	// 	var cnt = $("#cnt").val();

	// 	var challan_no = $("#challan_no").val();
	// 	var date = $("#date").val();
	// 	var customer = $("#customer").val();
	// 	var saleorder_no = $("#saleorder_no").val();
	// 	var total_quantity = $("#total_quantity").val();
	// 	var unit_array = [];
	// 	var product_array = [];
	// 	var qty_array = [];

	// 	for (var i = 1; i <= cnt; i++) {
	// 		var unit = $("#unit_" + i).val();
	// 		var product = $("#product_" + i).val();
	// 		var qty = $("#qty_" + i).val();

	// 		product_array.push(product);
	// 		unit_array.push(unit);
	// 		qty_array.push(qty);


	// 	}
	// 	//alert('hiii');

	// 	jQuery.ajax({
	// 		url: 'handler/delivery_challan_form.php', type: 'POST',
	// 		data: { PTask: PTask, table: table, LastEdited: LastEdited, id: id, cnt: cnt, challan_no: challan_no, date: date, customer: customer, saleorder_no: saleorder_no, total_quantity: total_quantity, unit_array: unit_array, product_array: product_array, qty_array: qty_array },
	// 		success: function (data) {
	// 			if (data != "") {
	// 				//alert(data);				
	// 				// window.location = 'delivery_challan_list.php';
	// 			} else {
	// 				alert('error in handler');
	// 			}
	// 		}
	// 	});
	// }


	function deletedata(id) {
		var PTask = "<?php echo $_REQUEST['PTask']; ?>";

		jQuery.ajax({
			url: 'handler/delivery_challan_form.php', type: 'POST',
			data: { PTask: PTask, id: id },
			success: function (data) {
				if (data != "") {
					//alert(data);					
					window.location = 'delivery_challan_list.php';
				} else {
					alert('error in handler');
				}
			}
		});

	}

	function select_all() {

		// select all checkboxes
		$("#select_all").change(function () {  //"select all" change

			var status = this.checked; // "select all" checked status
			$('.checkboxes').each(function () { // iterate all listed checkbox items
				if (this.disabled == false) {
					this.checked = status; // change ".checkbox" checked status
					// alert(this.disabled);
				}
			});
		});

		// uncheck "select all", if one of the listed checkbox item is unchecked
		$('.checkboxes').change(function () { // ".checkbox" change

			if (this.checked == false) { // if this item is unchecked
				$("#select_all")[0].checked = false; //change "select all" checked status to false
			}
		});

	}

	window.onload=function(){
        $("#date").flatpickr({
        	dateFormat: "d-m-Y"
        });

    }

    function  get_saleorder(){
       
        var customer =$("#customer").val();
        var PTask = $("#PTask").val();
        var id =$("#id").val();
        if(customer==''){
            alert('Please Select customer !!!!');
            return false;
        }
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_saleorder',PTask:PTask,id:id,customer:customer},
            success:function(data)
            {	
                $("#sale_order_div").html(data);	
                if(PTask=="update"||PTask=='view'){
                    saleorder_rowtable();
                    getlocation();
                }
            }
        }); 
    }

    function  getlocation(){
        var saleorder = $('#saleorder_no').val();
        if(customer==''){
            alert('Please Select customer !!!!');
            return false;
        }
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'getlocation',saleorder:saleorder},
            success:function(data)
            {	
                $("#location").html(data);
            }
        }); 
    }

    function saleorder_rowtable(){
    
        var saleorder_no =$("#saleorder_no").val();
        var customer =$("#customer").val();
        var PTask = $("#PTask").val();
        var id =$("#id").val();
        if(customer==''){
            alert('Please Select customer !!!!');
            return false;
        }
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'saleorder_rowtable',PTask:PTask,id:id,customer:customer,saleorder_no:saleorder_no},
            success:function(data)
            {	
                //alert(data);
                $("#table_div").html(data);	
            }
        }); 
    }

    function get_unit(this_id)
    {	
        var id=this_id.split("_");
        id=id[1];
        var product = $("#product_"+id).val();
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_unit',id:id,product:product},
            success:function(data)
            {	
                $("#unitdiv_"+id).html(data);	
                $(this).next().focus();
            }
        });	
    }
        
    function get_totalqty()
    {
        var cnt=jQuery("#cnt").val();
        //alert(cnt);
        var grandtotal=0;
        for(var i=1; i<=cnt; i++)
        {	
            var qty= jQuery("#qty_"+i).val();
            if(qty==''){ qty=0;}
            grandtotal = parseFloat(grandtotal)+parseFloat(qty);
        }
        //alert(grandtotal);
        jQuery("#total_quantity").val(parseFloat(grandtotal).toFixed(2));	
    }

    function stock_check()
    {
        var cnt=jQuery("#cnt").val();
        var stock_chk=0;	
        for(var i=1; i<=cnt;i++)
        {	
            var qty= jQuery("#qty_"+i).val();
            var stock= jQuery("#stock_"+i).val();
            //alert("qty="+qty+'stock='+stock);
            if(parseFloat(qty)>parseFloat(stock)){ stock_chk++;}
        }
        //alert("chk="+stock_chk);
        if(stock_chk<=0){
            $("#submit_div").html('<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>');	
        }else{
            $("#submit_div").html('<span style="color:red;">Quantity Should Not Gratter Than Stock!!!</span><br>');	
        }
    }

    <?php 
    if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
        window.onload=function(){
            
			document.getElementById("add_new").click();
			$("#add_new").val("Show List"); 
			get_saleorder();
			saleorder_rowtable();
			// getlocation();
			get_totalqty();
			
        };  
    <?php } ?>



    <?php
    if($_REQUEST['PTask']=='delete'){?>	
        window.onload=function(){
            var r=confirm("Are you sure to delete?");
			if (r==true)
			{
				deletedata("<?php echo $_REQUEST['id'];?>");
			}
			else
			{
				window.location="delivery_challan_list.php";
			}
        
        };
    <?php } ?>	
    function CheckDelete()
    {
            
        var val='';
        $('input[type="checkbox"]').each(function()
        {	
            if(this.checked==true && this.value!='on')
            {
                val +=this.value+",";
            }
        });
        if(val=='')
        {
            alert('Please Select Atleast 1 record!!!!');
        }
        else
        {
            val = val.substring(0, val.length - 1);
            window.location="delivery_challan_list.php?PTask=delete&id="+val; 
                
        }
    }

    function mysubmit(a)
    {
        return _isValid2(a);	
    }

    function remove_urldata()
    {	 
        window.location="delivery_challan_list.php";
    } 
    
    function savedata()
    {
        var PTask = $("#PTask").val();
        var table = $("#table").val();
        var LastEdited = $("#LastEdited").val();
        var id = $("#id").val();
        var cnt = $("#cnt").val();
        var common_id = $("#common_id").val();
        var challan_no = $("#challan_no").val();
        var date = $("#date").val();
        var customer = $("#customer").val();
        var saleorder_no = $("#saleorder_no").val();
        var total_quantity = $("#total_quantity").val();
        var location = $("#locations").val();
        var unit_array=[];
        var product_array=[];
        var qty_array=[];
        var rate_array=[];
            
        for(var i=1;i<=cnt;i++)
        {
            var unit = $("#unit_"+i).val();	
            var product = $("#product_"+i).val();
            var qty = $("#qty_"+i).val();
            var rate = $("#rate_"+i).val();
            
            product_array.push(product);
            unit_array.push(unit);
            qty_array.push(qty);
            rate_array.push(rate);
        } 
        //alert('hiii');
                
        jQuery.ajax({url:'handler/delivery_challan_form.php', type:'POST',
            data: { PTask:PTask,table:table,LastEdited:LastEdited,common_id:common_id,id:id,cnt:cnt,challan_no:challan_no,date:date,customer:customer,saleorder_no:saleorder_no,location:location,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,qty_array:qty_array,rate_array:rate_array },
            success:function(data)
            {	
                if(data!="")
                {	
                    // alert(data);
					// console.log(data);
                   	window.location='delivery_challan_list.php';
                }else{
                    alert('error in handler');
                }
            }
        });			 
    }


    function deletedata(id){
        var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
            
        jQuery.ajax({url:'handler/delivery_challan_form.php', type:'POST',
            data: { PTask:PTask,id:id},
            success:function(data)
            {	
                if(data!="")
                {
                    //alert(data);					
                    window.location='delivery_challan_list.php';
                }else{
                alert('error in handler');	
                }
            }
        });
    
    }

    function select_all(){	

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

    }

	function check_qty(i) {
	var quantity = $("#qty_"+i).val();
	var PTask = $("#PTask").val();

	if (quantity == '' || quantity=='0') {
		alert ('please enter quantity first . . . !');

	} else {
		getdeliverybatch(i,quantity,PTask);
	}
}

function getdeliverybatch(i,quantity,task){
	var qty =$("#qty_"+i).val();
	var product =$("#product_"+i).val();
	var stock =$("#stock_"+i).val();
	var common_id =$("#common_id").val();
	var location =$("#locations").val();
	var sale_invoice_no =$("#sale_invoice_no").val();
	var id =$("#id").val();
	
	jQuery.ajax({
		url: 'get_ajax_values.php',
		type: 'POST',
		data: { Type: 'viewbatch',location:location,sale_invoice_no:sale_invoice_no,common_id:common_id,stock:stock,qty:qty,product: product,id:i,quantity:quantity,task:task,id:id},
		success: function (data) {
			$('#salesbatch').html(data);
			$('#salebatch').modal('show');
	
		},
		error: function (xhr, status, error) {
			console.error("AJAX Error:", status, error);
		}
	});
}

</script>


<!-- Footer -->
<?php
include("footer.php");
?>