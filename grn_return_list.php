<?php 
    include("header.php");
    // include 'handler/pricelist_form.php';
    $task=$_REQUEST['PTask'];
    if($task==''){ $task='Add';}
    if($_REQUEST['Task']=='view') {
        $readonly="readonly";
        $disabled="disabled";
    } else {
        $readonly="";
        $disabled="";
    }

    $ad = uniqid();

?>
<style>
    .hidetd {
        display: none;
    }
    .hidetxt {
        display: none;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y ">

    <div class="row">     
        <div class="col-md-2">       
        <h4 class="fw-bold mb-4" style="padding-top:2px;"> GRN Return</h4>
        </div>
        <div class="col-md-2">
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

    <div id="u_table" style="display:block">
        <div class="card">
            <div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
                
                <table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
                    <thead>
                        <tr>
                            <th>
                                <input type='checkbox' value='0' id='select_all' onclick="select_all();"/>&nbsp;
                                Sr.No.
                            </th>
                            <th>Date</th>
                            <th>Record No</th>
                            <th>Supplier</th>
                            <th>Voucher Type</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>GRN Quantity</th>
                            <th>Return Quantity</th>
                            <th>User</th>
                            <?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
                        </tr>
                    </thead>
                
                    <tbody>
                    <?php
                    $i=0;
                    $data=$utilObj->getMultipleRow("grn_return","1");
                    foreach($data as $info)
                    {
                        $i++;
                        $j=0;
                        $href= 'grn_return_list.php?id='.$info['id'].'&PTask=view';
                        //$d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
                        if($d1>0){
                            $dis="disabled";
                        }
                        $location=$utilObj->getSingleRow("location","id='".$info['location']."'");
                        $supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
                        $voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
                        $data1=$utilObj->getMultipleRow("grn_return_details","parent_id='".$info['id']."'");

                        foreach($data1 as $info1)
                        {
                            $j++;
                            $product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
                            if($j==1){
                                $rowspan=Count($data1);
                                $hidetd="";
                            }else{
                                $rowspan=1;
                                $hidetd="hidetd";
                            }  
                        
                        
                    ?>
                            <tr>
                                <td width='3%'  class="<?php echo $hidetd; ?> controls" rowspan="<?php echo $rowspan; ?>"><input type='checkbox' class='checkboxes' <?php //echo  $disabled; ?> name='check_list' value='<?php echo $info['id']; ?>'/>  &nbsp; <?php echo $i; ?> </td> 
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo date('d-m-Y',strtotime($info['date'])); ?></td>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['grnreturn_code']; ?></a> </td>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $supplier['name']; ?></td>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $voucher['name']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $info1['unit']; ?></td>
                                <td><?php echo $info1['qty']; ?></td>
                                <td><?php echo $info1['return_qty']; ?></td>
                                <?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
                                
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
                                    <!--div class="dropdown"-->
                                <?php 
                                //echo $d1;
                                    if($d1<=0){?>
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                    <?php if((CheckEditMenu())==1) {  ?>
                                        <a class="dropdown-item" href="grn_return_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <?php } ?>
                                    <?php if((CheckDeleteMenu())==1){ ?>
                                        <a class="dropdown-item" href="grn_return_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
                                    <?php } ?>
                                    </div>
                                    <!--/div-->
                                <?php } ?>
                                    <?php if($info['Created']!='')
                                    {
                                        //$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
                                        $username = mysqli_fetch_array($query);
                                        $created=date('d-m-Y h:i A',strtotime($info['Created']));	
                                        $user = $username['fname'] . "  ".  $username['lname'];
                                        $createuser = "Created : ".$user." ".$created;
                                    }
                                    else{
                                        $createuser="";	
                                    }	
                                    
                                    //User - Updated Entry
                                    if($info['updateduser']!='')
                                    {
                                        //$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['updateduser']."'");
                                        $username = mysqli_fetch_array($query);											
                                        $created=date('d-m-Y h:i A',strtotime($info['LastEdited']));	
                                        $user = $username['fname'] . "  ".  $username['lname'];
                                        $createuser.= "&#10; Updated : ".$user." ".$created;
                                    }
                                    else{
                                        $createuser.="";	
                                    }
                                    ?>
                                    <a $dasable ata-content='clock' title='<?php echo $createuser;?>' class='popovers' data-placement='top' style='color:brown;' data-trigger='hover'  href='#' ><i class='fa fa-clock-o' ></i></a>
                                    
                                </td>
                            </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ---------------------------- Main Form ---------------------------- -->
    <?php

        $date=date('d-m-Y');	

        if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
            $id=$_REQUEST['id'];
            $rows=$utilObj->getSingleRow("grn_return","id ='".$id."'");
            $recordnumber=$rows['grnreturn_code'];	   
            $date=date('d-m-Y',strtotime($rows['date']));	
            if($requisition_no!='')
            {		
                if($readonly!="readonly"){
                    $read="readonly";
                }
            }else{
                $read=" ";
            }
        }
    ?>

    <div class="container-xxl flex-grow-1 container-p-y " style="display:none; background-color: white; padding: 30px; background: #fff9f9; " id="u_form">
        <div class="row form-validate">
            <!-- FormValidation -->
            <div class="col-12">
				<div class="card">
                    <div class="card-body" >
                        <form id="demo-form2" data-parsley-validate class="row g-3" action="grn_return_list.php"  method="post" data-rel="myForm">
			
                            <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
                            <input type="hidden" name="id" id="id" value="<?php echo $rows['id'];?>"/>	
                            <input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
                            <input type="hidden" name="table" id="table" value="<?php echo "grn_return"; ?>"/>
                    
                            <input type="hidden" name="ad" id="ad" value="<?php echo $ad; ?> "/>
                                
                            <div class="col-md-4">
                                <label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="voucher_type" name="voucher_type"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_preturn_code();">
                                <option value="">Select</option>
                                    <?php	
                                        $data=$utilObj->getMultipleRow("voucher_type","parent_voucher=5 group by id"); 
                                        foreach($data as $info){
                                            if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
                                            echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                    
                            <div class="col-md-4">
                                    <label class="form-label">Record No. <span class="required required_lbl" style="color:red;">*</span></label>
                                    <input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $recordnumber;?>"/>
                            </div>
                    
                            <div class="col-md-4">
                                <label class="form-label">Return Date <span class="required required_lbl" style="color:red;">*</span></label>
                                <input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
                            </div>
                    
                                
                            
                            <div class="col-md-4">
                                <label class="form-label">Supplier <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="supplier" name="supplier"  onchange="find_state();get_grnno();"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
                                <option value="">Select</option>
                                    <?php	
                                        $data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
                                        foreach($data as $info){
                                            if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
                                            echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
                                        }  
                                    ?>
                                </select>
                            </div>

                            <!-- <div class="col-md-4">
                                <label class="form-label">Location <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="location" name="location" onchange="get_grnno();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
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
                            </div>	 -->

                            <div class="col-md-4" id="purchase_invoice_div">

                            </div>
                            
                            <h4 class="role-title">Material Details</h4>
                            <?php 
                                $account_ledger=$utilObj->getSingleRow("account_ledger"," id='".$rows['supplier']."' ");
                                $state= $account_ledger['mail_state'];
                            ?>
                            <input type="hidden" id="state"  name="state" value="<?php echo $state;?>"/>
                            <div id="table_div" style="overflow: hidden;">
                            
                    
                            </div>
                            
                            <div class="col-12 text-center">
                                <?php 
                                if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){?>	
                                    <input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
                                <?php } ?>

                                <?php 
									if($_REQUEST['PTask']=='view') {
								?>	
									<?php if((CheckEditMenu())==1) {  ?>
									<button type="button" class="add_new btn btn-warning" onclick="hideshow();" id="add_new" name="add_new">
											<a href="grn_return_list.php?id=<?php echo $_REQUEST['id']; ?>&PTask=update">Edit</a>
									</button>
									<?php } ?>
								<?php } ?>

                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /FormValidation -->
        </div>

    </div>

</div>


<script>

    function hideshow()
    { 
            
        if(document.getElementById('u_form').style.display=="none")
        {
            document.getElementById('u_form').style.display="block"
            document.getElementById('u_table').style.display="none"
            $('#demo-form2').show();
            $("#add_new").val("Show List");
            
            
        }
        else
        {
            document.getElementById('u_form').style.display="none"
            document.getElementById('u_table').style.display="block"
            $(".add_new").val("Add New");
            $('#demo-form2').show();		
            window.location="grn_return_list.php";
            
        }
        
    }

    <?php 
    if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view') { ?>	
    window.onload=function() {
        
        $("#date").flatpickr({
            dateFormat: "d-m-Y"
        });

        document.getElementById("add_new").click();
        $("#add_new").val("Show List"); 
        find_state();
        get_grnno();
        grn_rowtable();
    
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
                window.location="grn_return_list.php";
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
            window.location="grn_return_list.php?PTask=delete&id="+val; 
                
        }
    }

    function mysubmit(a)
    {
        // return _isValid2(a);
        savedata();
    }

    function remove_urldata()
    {	 
        window.location="grn_return_list.php";
    } 
    
    function savedata()
    {
    
        var PTask = $("#PTask").val();
        var table = $("#table").val();
        var LastEdited = $("#LastEdited").val();
        var id = $("#id").val();
        var cnt = $("#cnt").val();
        var ad = $("#ad").val();
        // alert(id);
        
        var recordnumber = $("#recordnumber").val();
        // var type = $("#type").val();
        var location = $("#location").val();
        var voucher_type = $("#voucher_type").val();
        var grn_no = $("#grn_no").val();
        var supplier = $("#supplier").val();
        var date = $("#date").val();
        

        var unit_array=[];
        var product_array=[];
        var qty_array=[];
        var rejectedqty_array=[];
        
        for(var i=1;i<=cnt;i++)
        {
            var unit = $("#unit_"+i).val();	
            var product = $("#product_"+i).val();
            var qty = $("#qty_"+i).val();	
            var rejectedqty = $("#rejectedqty_"+i).val();	
            
            
            product_array.push(product);
            unit_array.push(unit);
            qty_array.push(qty);
            rejectedqty_array.push(rejectedqty);
        } 

        var checksub = 0;

        for(var i=1;i<=cnt;i++)
        {
            // var producttxt = $("#product_"+i).find("option:selected").text();
            var producttxt = $("#pname_"+i).val();
            var res = $("#res_"+i).val();

            if(res!=1) {

                alert("Plase Add Batch for this "+producttxt);
                checksub = 1;
                break;
            }
        }

        if(checksub==0){
            jQuery.ajax({url:'handler/grn_return_form.php', type:'POST',
                data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,ad:ad,cnt:cnt,recordnumber:recordnumber,location:location,voucher_type:voucher_type,grn_no:grn_no,supplier:supplier,date:date,unit_array:unit_array,product_array:product_array,qty_array:qty_array,rejectedqty_array:rejectedqty_array},
                success:function(data)
                {	
                    if(data!="")
                    {	
                        // alert(data);
                        window.location='grn_return_list.php';
                    }else{
                        alert('error in handler');
                    }
                }
            });
        }
    } 


    function deletedata(id){
        var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
        
        jQuery.ajax({url:'handler/grn_return_form.php', type:'POST',
            data: { PTask:PTask,id:id},
            success:function(data)
            {	
                if(data!="")
                {
                    //alert(data);					
                    window.location='grn_return_list.php';
                }else{
                    
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

    // window.onload=function(){
    //     $("#date").flatpickr({
    //         dateFormat: "d-m-Y"
    //     });
    // }

    function get_preturn_code() {

        var voucher_type = $("#voucher_type").val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_preturn_code1',voucher_type:voucher_type},
            success:function(data)
            {	
                //alert(data);
                $("#recordnumber").val(data);	
                // $(this).next().focus();
            }
        });

    }

    function find_state() {
        var supplier =$("#supplier").val();
        if(supplier==''){
            alert('Please Select Supplier !!!!');
            return false;
        }

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'find_state',supplier:supplier},
            success:function(data)
            {	
                // alert(data);
                $("#state").val(data);	
            }
        }); 
    }

    function get_grnno()
    {	
        // alert('hii');
        var PTask = $("#PTask").val();
        var id = $("#id").val();
        var supplier =$("#supplier").val();
        // var location =$("#location").val();

        var ad = $("#ad").val();
        
        
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_grnno',id:id,PTask:PTask,supplier:supplier,ad:ad},
            success:function(data)
            {	
                $("#purchase_invoice_div").html(data);	
                $(".select2").select2();
                if(PTask=='update'||PTask=='view'||PTask=='Add'){
                    grn_rowtable();
                }
            }
        }); 
                
    }

    function grn_rowtable(val)
    {	
        var PTask = $("#PTask").val();
        var id = $("#id").val();
        var type = $("#type").val();
        var location = $("#location").val();
        var grn_no = $("#grn_no").val();

        var ad = $("#ad").val();
        
        var supplier =$("#supplier").val();
        if(supplier==''){
            alert('Please Select Supplier !!!!');
            return false;
        }
        if(location==''){
            alert('Please Select Location !!!!');
            return false;
        }
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'grn_rowtable',type:type,id:id,PTask:PTask,grn_no:grn_no,supplier:supplier,location:location,ad:ad},
            success:function(data)
            {	
                // alert(data);
                $("#table_div").html(data);	
                $(".select2").select2();
            }
        }); 
                
    }

    

</script>
<!-- Footer -->
<?php 
    include("footer.php");
?>