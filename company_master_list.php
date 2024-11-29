<?php 
    include("header.php");
    //include 'handler/pricelist_form.php'; 
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

    .hidetd{
        display: none;
    }
    .hidetxt{
        display: none;
    }

</style>

<div class="container-xxl flex-grow-1 container-p-y ">

    <div class="row">     
        <div class="col-md-2">       
        <h4 class="fw-bold mb-4" style="padding-top:2px;">Comapny Master</h4>
        </div>
        <div class="col-md-2">
        <?php if((CheckCreateMenu())==1) { ?>

            <!-- <input type="button" class="add_new btn btn-primary btn-sm  " onclick="hideshow();" id="add_new" name="add_new" value="Add New" /> -->
            <button type="button" class="add_new btn btn-primary btn-sm" onclick="hideshow();" id="add_new" name="add_new">
				<i class="fas fa-plus-circle fa-lg"></i>
			</button>
        <?php } ?>

        <?php if((CheckDeleteMenu())==1) { ?>

            <!-- <input type="button" class=" btn btn-danger  btn-sm"  onclick="CheckDelete();" id="delete" name="delete" value="Delete" /> -->
            <button type="button" class="btn btn-danger btn-sm" onclick="CheckDelete();" id="delete" name="delete">
				<i class="fas fa-trash fa-lg" style="color: #ffffff;"></i>
			</button>
        <?php } ?>
        </div>
    </div>

    <!-- ----------------------- Main List ----------------------- -->
    <div id="u_table" style="display:block">
        <div class="card">
			<div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
                    <thead>
                        <tr>
                            <th width="5%"><input type='checkbox' value='0' id='select_all' onclick="select_all();" /> Sr.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>State</th>
                            <?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
                        </tr>
                    </thead>
            
                    <tbody>
                    <?php
                        $i=1;
                        $data=$utilObj->getMultipleRow("company_master","1");
                        foreach($data as $info){
                                
                            $href= 'production_list.php?id='.$info['id'].'&PTask=view';
                            
                        ?>
                        <tr>
                            <td  width="5%" class='controls'><input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/> &nbsp&nbsp<?php echo $i; ?>
                            </td> 

                            <td> <a href="<?php echo $href; ?>"><?php echo $info['name']; ?></a>  </td>

                            <td> <?php echo $info['email']; ?> </td>

                            <td><?php echo $info['mobile_no']; ?></td>
                            
                            <?php $rows=$utilObj->getSingleRow("states","code ='".$info['state']."'"); ?>
                            <td><?php echo $rows['name']; ?></td>

                            <td>
                            <?php 
                                if($d1==0) { ?>
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                    <?php if((CheckEditMenu())==1) {  ?>
                                    <a class="dropdown-item" href="company_master_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <?php } ?>
                                    <?php if((CheckDeleteMenu())==1){ ?>
                                        <a class="dropdown-item" href="company_master_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
                                        <?php } ?>
                                    </div>
                                    <!--/div-->
                            <?php } ?>
                            </td>
                        </tr>
                        <?php 
                        $i=$i+1;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ----------------------- Main Form ----------------------- -->
    <?php

        $date=date('d-m-Y');	

        if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){
            $id=$_REQUEST['id'];
            $rows=$utilObj->getSingleRow("company_master","id ='".$id."'"); // change here
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
            <div class="col-12">
				<div class="card">
                    <div class="card-body" >
                        <form id="demo-form2" data-parsley-validate class="row g-3" action="company_master_list.php"  method="post" data-rel="myForm">

                            <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
                            <input type="hidden" name="id" id="id" value="<?php echo $rows['id'];?>"/>	
                            <input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
                            <input type="hidden" name="table" id="table" value="<?php echo "company_master"; ?>"/>
                            
                            <div class="col-md-4">
								<h3>Company Details</h3>
							</div>

                            <div class="col-md-4">
								<label class="form-label">Company Name <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="name" class="required form-control"  placeholder="Enter Name" name="name" value="<?php echo $rows['name'] ;?>"/>
							</div>

                            <div class="col-md-4">
								<label class="form-label">Mailing Name <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="mailing_name" class="required form-control" placeholder="Enter Mailing Name" name="mailing_name" value="<?php echo $rows['mailing_name'] ;?>"/>
							</div>

                            <div class="col-md-4">
								
							</div>

                            <div class="col-md-4">
								<label class="form-label">Email ID <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="email" class="required form-control" placeholder="Enter Email" name="email" value="<?php echo $rows['email'] ;?>"/>
							</div>

                            <div class="col-md-2">
								<label class="form-label">Contact NO <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="mobile_no" class="required form-control"  placeholder="Enter Number" name="mobile_no" value="<?php echo $rows['mobile_no'] ;?>"/>
							</div>

                            <div class="col-md-2">
								<label class="form-label">Alertnative NO <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="alt_mobile_no" class="required form-control"  placeholder="Enter Number" name="alt_mobile_no" value="<?php echo $rows['alt_mobile_no'] ;?>"/>
							</div>

                            <div class="col-md-4">

                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Accounting Period <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="acc_period" class="required form-control"  placeholder="Enter" name="acc_period" value="<?php echo $rows['acc_period'] ;?>"/>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Currency Symbol <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="currency_symbol" class="required form-control"  placeholder="Enter Symbol" name="currency_symbol" value="<?php echo $rows['currency_symbol'] ;?>"/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Decimal <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="acc_decimal" class="required form-control"  placeholder="Enter" name="acc_decimal" value="<?php echo $rows['acc_decimal'] ;?>"/>
                            </div>
                            
                            <div class="col-md-2">
                                
                            </div>
                            
                            <div class="col-md-2">
								<h4>Mailing Details</h4>
							</div>

                            <div class="col-md-4">
								<label class="form-label">Address <span class="required required_lbl" style="color:red;">*</span></label>
								<textarea name='address' id="address" placeholder="Enter Address" <?php echo $readonly;?> <?php echo $disabled;?> class="form-control "><?php echo $rows['address'];?></textarea>
							</div>

                            <div class="col-md-2">
                                <label class="form-label">State <span class="required required_lbl" style="color:red;">*</span></label>
                                <select id="state" name="state" onchange="getstatecode(this.value);" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
                                <?php 
                                    echo '<option value="">Select State</option>';
                                    $record=$utilObj->getMultipleRow("states","1 ORDER BY `id` ASC");
                                    foreach($record as $e_rec)
                                    {
                                        if($rows['state']==$e_rec["code"]) echo $select='selected'; else $select='';
                                        echo  '<option value="'.$e_rec["code"].'" '.$select.'>'.$e_rec["name"] .'</option>';
                                    }
                                ?>  
                                </select>
							</div>

                            <div class="col-md-2">
								<label class="form-label">State Code <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="state_code" class="required form-control"  placeholder="Enter Quantity" name="state_code" value="<?php echo $rows['state_code'] ;?>"/>
							</div>

                            <div class="col-md-2">
								<label class="form-label">Pin Code <span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pin_code" class="required form-control"  placeholder="Enter Quantity" name="pin_code" value="<?php echo $rows['pin_code'] ;?>"/>
							</div>

                            <br>
                            <div class="col-12 text-center">
								<?php 
									if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ ?>
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?>
								<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close"  onClick="remove_urldata(0);">Cancel</button>
							</div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script>

    <?php 
        if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){ ?>	
        window.onload=function(){
            // alert('hiii');
            // document.getElementById("add_new").click();
            $("#add_new").val("Show List"); 
            hideshow();
            
        };  
    <?php } ?>

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
            window.location="company_master_list.php";
            
        }
        
    }

    function getstatecode(val) {

        var value = val;
        $("#state_code").val(value);

    }

    function remove_urldata()
    {
        window.location="company_master_list.php";
    }

    function mysubmit(a)
    {
        return _isValid2(a);
        // savedata();
    }

    function savedata()
    {
        var PTask = $("#PTask").val();
        var table = $("#table").val();
        var LastEdited = $("#LastEdited").val();
        var id = $("#id").val();

        var name = $("#name").val();
        var mailing_name = $("#mailing_name").val();
        var email = $("#email").val();
        var mobile_no = $("#mobile_no").val();
        var alt_mobile_no = $("#alt_mobile_no").val();
        var acc_period = $("#acc_period").val();
        var currency_symbol = $("#currency_symbol").val();
        var acc_decimal = $("#acc_decimal").val();
        var address = $("#address").val();
        var state = $("#state").val();
        var state_code = $("#state_code").val();
        var pin_code = $("#pin_code").val();
        
        
        //alert('hiii');
                
        jQuery.ajax({url:'handler/company_master_form.php', type:'POST',
            data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,name:name,mailing_name:mailing_name,email:email,mobile_no:mobile_no,alt_mobile_no:alt_mobile_no,acc_period:acc_period,currency_symbol:currency_symbol,acc_decimal:acc_decimal,address:address,state:state,state_code:state_code,pin_code:pin_code},
            success:function(data)
            {	
                if(data!="")
                {	
                    // alert(data);	
                    window.location='company_master_list.php';
                }else{
                    alert('error in handler');
                }
            }
        });			 
    }

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
                window.location="company_master_list.php";
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
            window.location="company_master_list.php?PTask=delete&id="+val; 
                
        }
    }

    function deletedata(id) {
        var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
            
        jQuery.ajax({url:'handler/company_master_form.php', type:'POST',
            data: { PTask:PTask,id:id},
            success:function(data)
            {	
                if(data!="")
                {
                    window.location='company_master_list.php';
                    alert(data);
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