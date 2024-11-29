<?php 
    include("header.php");
    //include 'handler/pricelist_form.php'; 
    $rows1=$utilObj->getSingleRow("statutory_master","id ='1'");
        if($rows1 == '') { $task='Add'; } else { $task='update'; }
    // $task=$_REQUEST['PTask'];
    // if($task==''){ $task='Add';}
    if($_REQUEST['Task']=='view') {
        $readonly="readonly";
        $disabled="disabled";
    } else {
        $readonly="";
        $disabled="";
    }


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
    

    <!-- ------------------------- Main List ------------------------- -->
    <?php

        $date=date('d-m-Y');	

        
            $id=$_REQUEST['id'];
            
            $rows=$utilObj->getSingleRow("statutory_master","id ='1'");
           
            $date=date('d-m-Y',strtotime($rows['date']));	
        
    ?>
    <div class="container-xxl flex-grow-1 container-p-y " style="background-color: white; background: #ffffff; " id="u_form">
        <div class="row form-validate">
            <div class="col-12">
				<div class="card">
                    <div class="card-body">
                        <form id="demo-form2" data-parsley-validate class="row g-3" action="statutory_master_list.php"  method="post" data-rel="myForm">
                            <input type="hidden" name="PTask" id="PTask" value="<?php echo $task; ?>"/>  
                            <input type="hidden" name="id" id="id" value="<?php echo $rows['id'];?>"/>	
                            <input type="hidden" name="LastEdited" id="LastEdited" value="<?php echo $rows['LastEdited'];?>"/>
                            <input type="hidden" name="table" id="table" value="<?php echo "statutory_master"; ?>"/>
                            
                            
                            <div class="col-md-2">
								<h5>Income Tax</h5>
							</div>
                            <div class="col-md-1"></div>

                            <div class="col-md-3">
								<label class="form-label">PAN No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pan_no" class="required form-control pan" placeholder="Enter Name" name="pan_no" value="<?php echo $rows['pan_no'] ;?>"/>
							</div>

                            <div class="col-md-3">
								<label class="form-label">TAN No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="tan_no" class="required form-control" placeholder="Enter Name" name="tan_no" value="<?php echo $rows['tan_no'] ;?>"/>
							</div>
                            <div class="col-md-3"></div>


                            <div class="col-md-2">
								<h5>Goods & Service Tax</h5>
							</div>
                            <div class="col-md-1"></div>

                            <div class="col-md-3">
								<label class="form-label">GSTIN<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="gstin" class="required form-control gst" placeholder="Enter Name" name="gstin" value="<?php echo $rows['gstin'] ;?>"/>
							</div>

                            <div class="col-md-3">
								<label class="form-label">LUT No<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="lut_no" class="required form-control" placeholder="Enter Name" name="lut_no" value="<?php echo $rows['lut_no'] ;?>"/>
							</div>
                            <div class="col-md-3"></div>


                            <div class="col-md-2">
								<h5>ROC Compliance</h5>
							</div>
                            <div class="col-md-1"></div>

                            <div class="col-md-3">
								<label class="form-label">CIN Number<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="cin_no" class="required form-control" placeholder="Enter Name" name="cin_no" value="<?php echo $rows['cin_no'] ;?>"/>
							</div>

                            <div class="col-md-6"></div>


                            <div class="col-md-2">
								<h5>Payroll Master</h5>
							</div>
                            
                            <div class="col-md-1"></div>

                            <div class="col-md-3">
								<label class="form-label">PF Number<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pf_no" class="required form-control" placeholder="Enter Name" name="pf_no" value="<?php echo $rows['pf_no'] ;?>"/>
							</div>
                            
                            <div class="col-md-3">
								<label class="form-label">ESIC Number<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="esic_no" class="required form-control" placeholder="Enter Name" name="esic_no" value="<?php echo $rows['esic_no'] ;?>"/>
							</div>

                            <div class="col-md-3">
								<label class="form-label">Professional Tax Number<span class="required required_lbl" style="color:red;">*</span></label>
								<input type="text" id="pro_tax_no" class="required form-control" placeholder="Enter Name" name="pro_tax_no" value="<?php echo $rows['pro_tax_no'] ;?>"/>
							</div>

                            <br>
                            <div class="col-12 text-center">
								<?php 
									if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']==''){ ?>
									<input type="button" class="btn btn-primary mr-2" name="sbumit" value="Submit"  onClick="mysubmit(0);"/>
								<?php } ?> &nbsp;&nbsp;&nbsp;&nbsp;
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
            window.location="statutory_master_list.php";
            
        }
        
    }

    function checkpan(a) {
        return _pan(a);
    }

    function remove_urldata()
    {
        window.location="statutory_master_list.php";
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

        var pan_no = $("#pan_no").val();
        var tan_no = $("#tan_no").val();
        var gstin = $("#gstin").val();
        var lut_no = $("#lut_no").val();
        var cin_no = $("#cin_no").val();
        var pf_no = $("#pf_no").val();
        var esic_no = $("#esic_no").val();
        var pro_tax_no = $("#pro_tax_no").val();

        //alert('hiii');
                
        jQuery.ajax({url:'handler/statutory_master_form.php', type:'POST',
            data: { PTask:PTask,table:table,LastEdited:LastEdited,id:id,pan_no:pan_no,tan_no:tan_no,gstin:gstin,lut_no:lut_no,cin_no:cin_no,pf_no:pf_no,esic_no:esic_no,pro_tax_no:pro_tax_no},
            success:function(data)
            {	
                if(data!="")
                {	
                    // alert(data);	
                    window.location='statutory_master_list.php';
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
                window.location="statutory_master_list.php";
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
            window.location="statutory_master_list.php?PTask=delete&id="+val; 
                
        }
    }

    function deletedata(id) {
        var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
            
        jQuery.ajax({url:'handler/statutory_master_form.php', type:'POST',
            data: { PTask:PTask,id:id},
            success:function(data)
            {	
                if(data!="")
                {
                    window.location='statutory_master_list.php';
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