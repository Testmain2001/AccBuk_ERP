<?php 
    include("header.php");
    $task=$_REQUEST['PTask'];
    if($task=='') { $task='Add';}
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

    $getinvno= mysqli_query($GLOBALS['con'],"Select MAX(challan_no) AS pono from delivery_challan");
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

    $common_id=uniqid();
?>
<div class="container">
<div class="container" style ="background-color: white; padding: 30px; background: #ffffff; ">

    <div class="text-center mb-4">
		<h3 class="role-title">Delivery Challan</h3>
	</div>

    <form id="" data-parsley-validate class="row g-3" action="../sale_order_list.php"  method="post" data-rel="myForm">
			
        <input type="hidden"  name="PTask"      id="PTask"      value="<?php echo $task; ?>"/>  
        <input type="hidden"  name="common_id"  id="common_id"  value="<?php echo $common_id;?>"/>	
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
            <?php 
        if($_REQUEST['PTask']=='update'){
            $delivery_challan_no = $utilObj->getSingleRow("delivery_challan", " id='" . $_REQUEST['id'] . "' ");
            ?>
            <label class="form-label"> Sale Order No. <span class="required required_lbl" style="color:red;">*</span></label>
        <div id="saleorder_div">
        <?php if ($_REQUEST['PTask'] == 'view' || $_REQUEST['PTask'] == 'update') {
            $readonly = "readonly";
            $requisition = $utilObj->getSingleRow("sale_order", " id in (select saleorder_no from  delivery_challan where id='" . $_REQUEST['id'] . "')");
        ?>
            <input type="hidden" id="saleorder_no" <?php echo $readonly; ?> name="saleorder_no" value="<?php echo $requisition['id']; ?>"/>
            <input type="text"  style="width:100%;" class=" form-control" <?php echo $readonly; ?>  value="<?php echo $requisition['order_no']; ?>"/>

        <?php } else { ?>
            <select id="saleorder_no" name="saleorder_no" <?php echo $disabled; ?> class="select2 form-select " data-allow-clear="true" onchange=" saleorder_rowtable();getlocation()">
                <option value=""> Select SaleOrder No</option>

                <?php

                $record = $utilObj->getMultipleRow("sale_order", "customer ='" . $rows['customer'] . "'group by order_no");
                foreach ($record as $e_rec) {
                
                    if ($delivery_challan_no['saleorder_no'] == $e_rec["id"])
                        echo $select = 'selected';
                    else
                        $select = '';
                    echo '<option value="' . $e_rec["id"] . '" ' . $select . '>' . $e_rec["order_no"] . '</option>';
                    /*
                    }
                    */
                }
                ?> 
            </select>

        <?php } ?>
        </div>
        
        <?php }?>
            </div>
            <div class="col-md-4" id="location">
				<?php if($_REQUEST['PTask']=='update'){?>
                    <label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
			<select id="locations" name="locations" class="select2 form-select required" data-allow-clear="true" >	
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
            <?php }?>
			</div>
            
			
		
        <div id="table_div" style="overflow: hidden;">
        <?php 
        if($_REQUEST['PTask']=='update'){?>	
            <h4 class="role-title">Material Details</h4>
        <?php 
            $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
            $state= $account_ledger['mail_state'];
       
		?>
        <?php 
            $delivery_challan_no = $utilObj->getSingleRow("delivery_challan", " id='" . $_REQUEST['id'] . "' ");?>
           <input type="hidden" id="PTask"  name="PTask" value="<?php echo $_REQUEST['PTask']; ?>"/>
						  <!--input type="hidden" id="state"  name="state" value="<?php echo $state; ?>"/-->
							<table class="table table-bordered " id="myTable" > 
                           
							<thead>
								<tr>
									<th style="width:2%;text-align:center;">Sr.<br>No.</th> 
									<th style="width: 20%;text-align:center;">Product <span class="required required_lbl" style="color:red;">*</span></th>
									<th style="width: 10%;text-align:center;">Unit </th>	
									<th style="width:10%;text-align:center;">Stock <span class="required required_lbl" style="color:red;">*</span></th>
									<th style="width:10%;text-align:center;">Quantity <span class="required required_lbl" style="color:red;">*</span></th>
									
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
							} else
								if (($_REQUEST['saleorder_no'] != '' && $_REQUEST['PTask'] == 'Add') || ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'view')) {
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
                                            if ($_REQUEST['PTask'] != '') { ?>
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
                                        <?php
                                            $totalstock = getstock($row_demo['product'], $row_demo['unit'], date('Y-m-d'), $_REQUEST['id'], $location); 
                                            
                                            $product = $utilObj->getSingleRow("stock_ledger", "id='" . $row_demo['product'] . "'");
                                            $sale = $utilObj->getSingleRow("delivery_challan_details", "parent_id='" . $_REQUEST['id'] . "' AND product = '".$product['id']."'");
                                        
                                            $stock=$totalstock+$sale['qty'];
                                            ?>
                                            <input type="text"  id="stock_<?php echo $i; ?>" class=" form-control number"  name="stock__<?php echo $i; ?>" readonly  value="<?php echo $stock; ?>"/>
                                            </td>
                    
                                            <td style="width: 10%;">
                                            <input type="text" id="qty_<?php echo $i; ?>" class=" form-control number"  onkeyup="get_totalqty();stock_check();" onchange="get_totalqty();stock_check();"  name="qty_<?php echo $i; ?>" value="<?php echo $remain_qty; ?>"/>
                                            </td>
                                        <?php if ($productbatch['batch_maintainance'] == '1') {  ?>
                                            <td style="width: 10%;">
                                                <center>
                                                    <button type="button" class="btn btn-primary" onclick="getbatchdata1('<?php echo $row_demo['product']; ?>','<?php echo $i; ?>');" data-bs-toggle="modal" data-bs-target="#salebatch">Batch</button>
                                                </center>
                                            </td>
                                        <?php }else{?>
                                        <td></td>
                                        <?php }
                                            if ($_REQUEST['Task'] != 'view') { ?>
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
                                <?php
                                $total_quantity += $remain_qty;
                        } ?>
                
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right;">
                            Total Quantity
                            </td>
                            <td>
                                <input type="text" id="total_quantity" class="number form-control" readonly name="total_quantity" value="<?php echo $total_quantity; ?>"/>
                            </td>
                            <td>
                            </td>
                            <?php $sale = $utilObj->getSingleRow("sale_order_details", "parent_id='" . $_REQUEST['saleorder_no'] . "'");
                            $temp = $utilObj->getSingleRow("purchase_batch", "product='" . $sale['product'] . "'");
                            if ($temp != '') { ?>
                            <td>
                            </td>
                            <?php } ?>
                        </tr>
							</tfoot>
							<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
						</table>
						 <table style="width:100%;" class="taxtbl" >
							<tr style="margin:10px;text-align:center;">
								   <td>
										
								</td>			
							</tr>
						</table> 
				
						 <div class="row text-center" >
							<div id="submit_div" style="margin-bottom:10px;text-align:right;" class="col-md-6">
							<?php //echo "stock chk=".$stock_chk;
									if ($_REQUEST['PTask'] == 'update' || $_REQUEST['PTask'] == 'Add' && $stock_chk <= 0) { ?>	
										<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="savedata();"/>
							<?php } elseif ($_REQUEST['PTask'] != 'view') { ?>
									<span style="color:red;">Quantity Should Not Greater Than Stock!!!  
									</span>
							<?php } ?>
							</div>
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
                        <script>
							
							function getbatchdata1(product,i){
								var qty =$("#qty_"+i).val();
								var common_id =$("#common_id").val();
								var id =$("#id").val();
								var PTask =$("#PTask").val();
                                var location =$("#locations").val();
								jQuery.ajax({
									url: 'get_ajax_values.php',
									type: 'POST',
									data: { Type: 'viewbatch',location:location, product: product,qty:qty,common_id:common_id,PTask:PTask,id:id},
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
						  <script>
							function getbatchdata(product){
								var qty =$("#qty_1").val();
								var location =$("#location").val();
						
								jQuery.ajax({
									url: 'get_ajax_values.php',
									type: 'POST',
									data: { Type: 'viewbatch', product: product,qty:qty,location:location},
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
		<?php }?>
		</div>

    </form>

</div>
</div>
<script>
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
        // alert(saleorder_no);
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

    // function mysubmit(a)
    // {
       
    //     return _isValidpopup(a);	
    // }

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
            
        for(var i=1;i<=cnt;i++)
        {
            var unit = $("#unit_"+i).val();	
            var product = $("#product_"+i).val();
            var qty = $("#qty_"+i).val();
            
            product_array.push(product);
            unit_array.push(unit);
            qty_array.push(qty);
            
        
        } 
        //alert('hiii');
                
        jQuery.ajax({url:'handler/delivery_challan_form.php', type:'POST',
            data: { PTask:PTask,table:table,LastEdited:LastEdited,common_id:common_id,id:id,cnt:cnt,challan_no:challan_no,date:date,customer:customer,saleorder_no:saleorder_no,location:location,total_quantity:total_quantity,unit_array:unit_array,product_array:product_array,qty_array:qty_array},
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
</script>
<!------------------------- Footer --------------------------->
<?php 
    include("footer.php");
?>