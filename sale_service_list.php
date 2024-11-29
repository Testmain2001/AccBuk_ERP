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
        <h4 class="fw-bold mb-4" style="padding-top:2px;">Sale Invoice Service</h4>
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
                            <th>Customer</th>
                            
                            <th>User</th>
                            <?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
                        </tr>
                    </thead>
                
                    <tbody>
                    <?php
                    $i=0;
                    $data=$utilObj->getMultipleRow("sale_invoice_service","1");
                    foreach($data as $info)
                    {
                        $i++;
                        $j=0;
                        $href= 'sale_service_list.php?id='.$info['id'].'&PTask=view';
                        // $d1=$rows=$utilObj->getCount("grn","purchaseorder_no ='".$info['id']."'");
                        if($d1>0){
                            $dis="disabled";
                        }
                        $location=$utilObj->getSingleRow("location","id='".$info['location']."'");
                        $supplier=$utilObj->getSingleRow("account_ledger","id='".$info['supplier']."'");
                        $voucher=$utilObj->getSingleRow("voucher_type","id='".$info['voucher_type']."'");
                        $data1=$utilObj->getMultipleRow("sale_invoice_service_details","parent_id='".$info['id']."'");

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
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"> <a href="<?php echo $href; ?>"><?php echo $info['voucher_code']; ?></a> </td>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $supplier['name']; ?></td>
                            
                                <?php   $username=$utilObj->getSingleRow("employee","id='".$info['user']."'");?>
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>"><?php echo $username['name']; ?></td>
                                
                                <td class="<?php echo $hidetd; ?>" rowspan="<?php echo $rowspan; ?>">
                                    <!--div class="dropdown"-->
                                <?php 
                                // echo $d1;
                                    if($d1<=0){?>
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                    <?php if((CheckEditMenu())==1) {  ?>
                                        <a class="dropdown-item" href="sale_service_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <?php } ?>
                                    <?php if((CheckDeleteMenu())==1){ ?>
                                        <a class="dropdown-item" href="sale_service_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
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
            $rows=$utilObj->getSingleRow("sale_invoice_service","id ='".$id."'");
            $recordnumber=$rows['voucher_code'];	   
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

    <div class="container flex-grow-1 container-p-y " style="display:none; "  id="u_form">
        <div class="row form-validate">
            <div class="col-12">
				<div class="card">
                    <div class="card-body" >
                        <form id="demo-form2" data-parsley-validate class="row g-3" action="sale_service_list.php"  method="post" data-rel="myForm">

                            <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>" />  
                            <input type="hidden" name="id" id="id" value="<?php echo $rows['id']; ?>" />
                            <input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited']; ?>" />
                            <input type="hidden" name="table" id="table" value="<?php echo "sale_invoice_service"; ?>" />

                            <div class="col-md-3">
                                <label class="form-label">Voucher Type <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="voucher_type" name="voucher_type"  <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true" onchange = "get_sservice_code();">
                                    <option value="">Select</option>
                                    <?php	
                                        $data=$utilObj->getMultipleRow("voucher_type","parent_voucher=29 group by id"); 
                                        foreach($data as $info){
                                            if($info["id"]==$rows['voucher_type']){echo $select="selected";}else{echo $select="";}
                                            echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                    
                            <div class="col-md-3">
                                <label class="form-label">Record No.<span class="required required_lbl" style="color:red;">*</span></label>
                                <input type="text" id="recordnumber" class="required form-control" readonly <?php echo $readonly;?> placeholder="Record No." name="recordnumber" value="<?php echo $recordnumber;?>"/>
                            </div>
                    
                            <div class="col-md-3">
                                <label class="form-label">Date<span class="required required_lbl" style="color:red;">*</span></label>
                                <input type="text" class="form-control flatpickr" id="date" name="date" required value="<?php echo $date;?>" <?php echo $disabled;?>/>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Customer <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="supplier" name="supplier" onchange="" <?php  echo $disabled ;?> class="required form-select select2" data-allow-clear="true">
                                    <option value="">Select</option>
                                    <?php
                                        $data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 
                                        foreach($data as $info){
                                            if($info["id"]==$rows['supplier']){echo $select="selected";}else{echo $select="";}
                                            echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
                                        }  
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">POS State<span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="pos_state" name="pos_state" onchange="sservice_rowtable();" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true">
                                <?php
                                    echo '<option value="">Select Location</option>';
                                    $record=$utilObj->getMultipleRow("states","1");
                                    foreach($record as $e_rec)
                                    {
                                        if($rows['pos_state']==$e_rec["code"]) echo $select='selected'; else $select='';
                                        echo '<option value="'.$e_rec["code"].'" '.$select.'>'.$e_rec["name"].'</option>';
                                    }
                                ?>  
                                </select>
                            </div>
                            <br>

                            <div id="table_div" style="overflow: hidden;">
                    
                            </div>
                            
                            <div class="col-12 text-center" style="display:block;" id="adjustform">
                                <?php 
                                    if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
                                ?>	
                                    <input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="adjustentry();"/>
                                <?php } ?>
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
                            
                            </div>

                            <div id="table_adjust" style="overflow: hidden;">
                        

                            </div>

                            <div class="col-12 text-center" style="display:none;" id="submitform">
                            <?php 
                                if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ 
                            ?>	
                                <input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
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
            window.location="sale_service_list.php";
            
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
            sservice_rowtable();
        
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
                window.location="sale_service_list.php";
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
            window.location="sale_service_list.php?PTask=delete&id="+val; 
                
        }
    }

    function mysubmit(a)
    {
        // return _isValid2(a);
        savedata();
    }

    function remove_urldata()
    {	 
        window.location="sale_service_list.php";
    } 
    
    function savedata()
    {
    
        var PTask = $("#PTask").val();
        var table = $("#table").val();
        var LastEdited = $("#LastEdited").val();
        var id = $("#id").val();
        var cnt1 = $("#cnt1").val();
        
        var recordnumber = $("#recordnumber").val();
        var voucher_type = $("#voucher_type").val();
        var supplier = $("#supplier").val();
        var pos_state = $("#pos_state").val();
        var date = $("#date").val();

        var cgst_ledger = $("#cgst_ledger").val();
        var sgst_ledger = $("#sgst_ledger").val();
        var igst_ledger = $("#igst_ledger").val();

        var cgst_amt = $("#cgst_amt").val();
        var sgst_amt = $("#sgst_amt").val();
        var igst_amt = $("#igst_amt").val();

        var service_subtotal = $("#service_subtotal").val();
        var gst_subtotal = $("#gst_subtotal").val();
        var grandtotal = $("#grandtotal").val();

        var service_ledger_array=[];
        var service_amt_array=[];
        
        for(var i=1;i<=cnt1;i++)
        {
            var service_ledger = $("#serviceledger_"+i).val();	
            var service_amt = $("#serviceamt_"+i).val();
            
            service_ledger_array.push(service_ledger);
            service_amt_array.push(service_amt);
        } 

        var cntad = $("#cntad").val();
        var totalvalue = $("#totalvalue").val();
        var type_array=[];
        var billno_array=[];
        var invodate_array=[];
        var totalinvo_array=[];
        var pendingamt_array=[];
        var payamt_array=[];

        for(var i=1; i<=cntad; i++) {

            var typead = $("#type_"+i).val();
            var billno = $("#billno_"+i).val();
            var invodate = $("#invodate_"+i).val();
            var totalinvo = $("#totalinvo_"+i).val();
            var pendingamt = $("#pendingamt_"+i).val();
            var payamt = $("#payamt_"+i).val();
                
            type_array.push(typead);
            billno_array.push(billno);
            invodate_array.push(invodate);
            totalinvo_array.push(totalinvo);
            pendingamt_array.push(pendingamt);
            payamt_array.push(payamt);
        }

        jQuery.ajax({url:'handler/sale_service_form.php', type:'POST',
            data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,cnt1:cnt1,recordnumber:recordnumber,voucher_type:voucher_type,supplier:supplier,date:date,pos_state:pos_state,service_ledger_array:service_ledger_array,service_amt_array:service_amt_array,cgst_ledger:cgst_ledger,sgst_ledger:sgst_ledger,igst_ledger:igst_ledger,cgst_amt:cgst_amt,sgst_amt:sgst_amt,igst_amt:igst_amt,service_subtotal:service_subtotal,gst_subtotal:gst_subtotal,grandtotal:grandtotal,type_array:type_array,billno_array:billno_array,invodate_array:invodate_array,totalinvo_array:totalinvo_array,pendingamt_array:pendingamt_array,payamt_array:payamt_array,cntad:cntad,totalvalue:totalvalue },
            success:function(data)
            {	
                if(data!="") {	
                    // alert(data);
                    window.location='sale_service_list.php';
                    alert("Record added Successfully . . . .!");
                } else {
                    alert('error in handler');
                }
            }
        });			 
    } 


    function deletedata(id){
        var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
        
        jQuery.ajax({url:'handler/sale_service_form.php', type:'POST',
            data: { PTask:PTask,id:id},
            success:function(data)
            {	
                if(data!="")
                {
                    //alert(data);					
                    window.location='sale_service_list.php';
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

    function get_sservice_code() {

        var voucher_type = $("#voucher_type").val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_sservice_code',voucher_type:voucher_type},
            success:function(data)
            {	
                //alert(data);
                $("#recordnumber").val(data);	
                // $(this).next().focus();
            }
        });

    }

    function sservice_rowtable()
    {	
        var PTask = $("#PTask").val();
        var id = $("#id").val();
        var state = $("#pos_state").val();
        
        var supplier =$("#supplier").val();

        if(supplier==''){
            alert('Please Select Supplier !!!!');
            return false;
        }
        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'sservice_rowtable',id:id,PTask:PTask,supplier:supplier,state:state },
            success:function(data)
            {	
                // alert(data);
                $("#table_div").html(data);	
                $(".select2").select2();
            }
        }); 
                
    }

    function addRow1(tableID) 
	{ 
		var count=$("#cnt1").val();
		var i = parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row1_"+i+"'>";
		
		cell1 += "<td style='text-align:center;'>"+i+"</td>";

		cell1 += "<td style='text-align:center;'><select id='serviceledger_"+i+"' name='serviceledger_"+i+"' class='select2 form-select' onchange='get_gst_per(this.id);'>\
			<option value=''>Select Ledger</option>\
			<?php
				$record=$utilObj->getMultipleRow("account_ledger","1 group by name");
				foreach($record as $e_rec){	
				echo  "<option value='".$e_rec['id']."' >".$e_rec['name']." </option>";
				}
					
			?>
		</select></td>";

		cell1 += "<td style='text-align:center;'>\
            <input type='hidden' name='igstper_"+i+"' id='igstper_"+i+"' value='' >\
            <input type='hidden' name='sgstper_"+i+"' id='sgstper_"+i+"' value='' >\
            <input type='hidden' name='cgstper_"+i+"' id='cgstper_"+i+"' value='' >\
        </td>";
		
		cell1 += "<td style='text-align:center;'></td>";
		
		cell1 += "<td style='text-align:center;'><input type='text' id='serviceamt_"+i+"' class='number form-control tdalign' name='serviceamt_"+i+"' value='' onblur='get_service_subtot("+i+");getrowgst(this.id);gettotgst("+i+");' />\
        <input type='hidden' name='serviceigst_"+i+"' id='serviceigst_"+i+"' value='0' >\
        <input type='hidden' name='servicesgst_"+i+"' id='servicesgst_"+i+"' value='0' >\
        <input type='hidden' name='servicecgst_"+i+"' id='servicecgst_"+i+"' value='0' >\
        </td>";

		cell1 += "<td style='width:1%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row(this.id);'></i></td>";

		$("#myTable1").append(cell1);
		$("#cnt1").val(i);
		$("#serviceledger_"+i).select2(); 
		// $(".select2").select2();

	}

    function delete_row(rwcnt) {

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
                    
                    jQuery("#serviceledger_"+k).attr('name','serviceledger_'+newId);
                    jQuery("#serviceledger_"+k).attr('id','serviceledger_'+newId);
                    
                    jQuery("#serviceamt_"+k).attr('name','serviceamt_'+newId);
                    jQuery("#serviceamt_"+k).attr('id','serviceamt_'+newId);
                    
                    jQuery("#deleteRow_"+k).attr('id','deleteRow_'+newId);
                    
                }
                jQuery("#cnt1").val(parseFloat(count-1)); 
            }
        }
        else 
        {
            alert("Can't remove row Atleast one row is required");
            return false;
        }	 
    }		  	

    function get_service_subtot(id) {

        var totalquantity = 0;

        // Assuming batqty1_id elements are input fields
        $("[id^='serviceamt_']").each(function() {
            var quant = parseFloat($(this).val()) || 0;
            // Convert the value to a number, default to 0 if not a valid number
            totalquantity += quant;
        });

        $("#service_subtotal").val(totalquantity);

    }

    function gettotgst(id) {

        var totcgst = 0;
        var totsgst = 0;
        var totigst = 0;

        var gst_subtot = 0;
        var grandtotal = 0;

        var state = $("#pos_state").val();
        var service_subtotal = $("#service_subtotal").val();

        if(state==27) {

            // Assuming batqty1_id elements are input fields
            $("[id^='servicecgst_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totcgst += quant;
            });

            $("#cgst_amt").val(totcgst);

            // Assuming batqty1_id elements are input fields
            $("[id^='servicesgst_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totsgst += quant;
            });

            $("#sgst_amt").val(totsgst);

            gst_subtot = totcgst+totsgst;

            $("#gst_subtotal").val(gst_subtot);

        } else {

            // Assuming batqty1_id elements are input fields
            $("[id^='serviceigst_']").each(function() {
                var quant = parseFloat($(this).val()) || 0;
                // Convert the value to a number, default to 0 if not a valid number
                totigst += quant;
            });

            $("#igst_amt").val(totigst);

            gst_subtot=totigst;
            $("#gst_subtotal").val(gst_subtot);

        }

        grandtotal=parseFloat(service_subtotal)+parseFloat(gst_subtot);
        $("#grandtotal").val(grandtotal);

    }

    function get_gst_per(this_id) {

        var id=this_id.split("_");
        id=id[1];

        var service_ledger = $("#serviceledger_"+id).val();
        var state = $("#pos_state").val();

        jQuery.ajax({url:'get_ajax_values.php', type:'POST',
            data: { Type:'get_gst_per',service_ledger:service_ledger,state:state },
            success:function(data)
            {	
                // alert(data);
                var bdid=data.split("#");
                var meas=bdid[0].split(",");
                jQuery("#igstper_"+id).val(bdid[0]);
                jQuery("#sgstper_"+id).val(bdid[1]);
                jQuery("#cgstper_"+id).val(bdid[2]);
            }
        }); 

    }

    function getrowgst(this_id) {

        var id=this_id.split("_");
        id=id[1];

        var total = $("#serviceamt_"+id).val();
		
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

        var igst=$("#igstper_"+id).val();
		var igst_amt=parseFloat(total*igst)/100;

        var sgst=$("#sgstper_"+id).val();
		var sgst_amt=parseFloat(total*sgst)/100;

        var cgst=$("#cgstper_"+id).val();
		var cgst_amt=parseFloat(total*cgst)/100;

        // alert(total);
        // alert(sgst_amt);
        // alert(cgst_amt);

        $("#serviceigst_"+id).val(igst_amt);
        $("#servicesgst_"+id).val(sgst_amt);
        $("#servicecgst_"+id).val(cgst_amt);
    }

    function adjustentry() {

		var ad = $("#ad").val();
		var id = $("#id").val();
		var PTask = $("#PTask").val();
		var invoicenumber = $("#recordnumber").val();

		var supplier =$("#supplier").val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'adjust_sale_service',id:id,supplier:supplier, invoicenumber:invoicenumber,PTask:PTask },
			success:function(data)
			{	
				jQuery("#table_adjust").html(data);
				$('#adjustform').css('display', 'none');
				$('#submitform').css('display', 'block');
			}
		});	
	}


	function get_bill1(this_id) {

		var cust = $("#supplier").val();
		var recordnumber = $("#recordnumber").val();
		var supplier = $("#supplier").val();

		var id=this_id.split("_");
        id=id[1];

		var val = $("#type_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'get_bill', val:val, cust:cust, id:id, recordnumber:recordnumber, supplier:supplier },
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
		var count=$("#cntad").val();	
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
				jQuery("#cntad").val(parseFloat(count-1)); 
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
		var count=$("#cntad").val();	
		var state=$("#state").val();

		var i=parseFloat(count)+parseFloat(1);

		var cell1="<tr id='row_"+i+"'>";
		
		cell1 += "<td style='width:0%;text-align:center;'>"+i+"</td>";
	   
		cell1 += "<td style='width:7%' ><select name='type_"+i+"' class='required select2 form-select'  id='type_"+i+"' onchange='get_bill1(this.id);' style=''>\
			<option value=''>Select Type</option>\
			<option value='Advanced'>New Reference</option>\
			<option value='PO'>Against Bill</option>\
		</select></td>";

		cell1 += "<td style='width:8%'><div id='voucher_"+i+"'></div></td>";

		cell1 += "<td style='width:6%'><input name='invodate_"+i+"' id='invodate_"+i+"' readonly class='form-control number' type='text'/></td>";

		cell1 += "<td style='width:8%'><input name='totalinvo_"+i+"' readonly id='totalinvo_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='pendingamt_"+i+"' readonly id='pendingamt_"+i+"' class='form-control required tdalign' type='text'/>\
		</td>";

		cell1 += "<td style='width:8%'><input name='payamt_"+i+"' id='payamt_"+i+"' class='form-control required tdalign' type='text' onkeyup='gettotalamt(this.id);' />\
		</td>";
		
		cell1 += "<td style='width:0%'><i class='bx bx-trash me-1' id='deleteRow_"+i+"' style='cursor: pointer;'  onclick='delete_row_adjust(this.id);'></i></td>";



		$("#myTable1").append(cell1);
		$("#cntad").val(i);
		// $("#particulars_"+i).select2();
		// $(".select2").select2();
		 
	}

	function getinvo_info(this_id) {

		var cust = $("#supplier").val();

		var id=this_id.split("_");
        id=id[1];

		var billno = $("#billno_"+id).val();

		jQuery.ajax({url:'get_ajax_values.php', type:'POST',
			data: { Type:'getinvo_info1',billno:billno, cust:cust},
			success:function(data)
			{
				var bdid=data.split("#");
                var meas=bdid[0].split(",");
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
	}

</script>
<!-- Footer -->
<?php 
    include("footer.php");
?>