<?php 
    include("header.php");
    $task=$_REQUEST['PTask'];
    if($task=='') { $task='Add'; }

    if($_REQUEST['PTask']=='view') {

        $readonly="readonly";
        $disabled="disabled";
    } else {

        $readonly="";
        $disabled="";
    }

    unset($_SESSION['FromDate']);
    unset($_SESSION['ToDate']);
    // unset($_SESSION['cname']);

    if($_REQUEST['Task']=='filter') {
        
        $from=$_REQUEST['FromDate'];
        $Date1=date('Y-m-d',strtotime($from));
        
        $to=$_REQUEST['ToDate'];
        // $Date=date('Y-m-d',strtotime($to));
        $Date=date('Y-m-d',strtotime($to.'+1 day'));
        
        $_SESSION['FromDate']=date($Date1);
        $_SESSION['ToDate']=date($Date);
        $inputfrom=date('d-m-Y',strtotime($from));
        $inputto=date('d-m-Y',strtotime($to));
        // $_SESSION['cname']=$_REQUEST['cname'];
        $location = $_REQUEST['location'];
    } else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']=='' && $_REQUEST['Task']=='') {

        $_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
        $_SESSION['ToDate']=date("Y-m-d");
        $inputfrom=date("01-m-Y");
        $inputto=date("d-m-Y");
    }

    $user=$utilObj->getSingleRow("employee","id ='".$_SESSION['Ck_User_id']."' ");
?>

<div class="container-xxl flex-grow-1 container-p-y ">

    <div class="row">     
        <div class="col-md-4">       
            <h4 class="fw-bold mb-4" style="padding-top:2px;">Production Requisition Report</h4>
        </div>
    </div>

    <div class="row" style="margin-bottom:12px;">
        <form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
                <div class="col-md-3 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 

				<div class="col-md-3 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div>

                <div class="col-md-3">
					<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="location" name="location"   class="required form-select select2" data-allow-clear="true">
					    <option value="">Select</option>
						<?php
                            $user=$utilObj->getSingleRow("employee","id ='".$_SESSION['Ck_User_id']."' ");
							$place = explode(",",$user['multiloc']);
							foreach($place as $pid) {

								$data=$utilObj->getMultipleRow("location","id = '".$pid."' group by id");
								foreach($data as $info) {

									$loc=$utilObj->getSingleRow("location","1 ");

									if($info['id']==$_REQUEST['location']){ echo $select="selected"; } else { echo $select=""; }
									echo  '<option value="'.$info['id'].'" '.$select.'>'.$info["name"].'</option>';
								}  
							}
						?>
					</select>
				</div>

				<div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
        </form>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
	        <table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
                <thead>
                    <tr>
                        <th width='2%'>Sr.No.</th>
                        <th width='15%'>Product</th>
                        <th class="tdalign" width='8%'>Order Quantity</th>
                        <th class="tdalign" width='8%'>Closing Stock</th>
                        <th class="tdalign" width='8%'>Difference QTY</th>
                        <th class="tdalign" width='8%'>Re-Order Level</th>
                        <th class="tdalign" width='8%'>Required Requisition Quantity</th>
                        <th width='20%' style="text-align:center;">Send Requisition</th>
                    </tr>
                </thead>

	            <tbody>
	            <?php
                    $i=0;

                    if($_REQUEST['Task']=='filter' ) {

						$cnd=" Created>='".$_SESSION['FromDate']."' AND Created<='".$_SESSION['ToDate']."' ";
					} else {

						$cnd=" Created>='".$_SESSION['FromDate']."' AND Created<='".$_SESSION['ToDate']."'";
					}

                    $data1=$utilObj->getMultipleRow("production_requisition_details","$cnd ");

                    foreach($data1 as $info1) {

                        if($_REQUEST['Task']=='filter') {

                        $product=$utilObj->getSingleRow("stock_ledger","id='".$info1['product']."'");
                        $info=$utilObj->getSingleRow("production_requisition","id='".$info1['parent_id']."'");

                        // $totstock = gettotalstock($info1['product'],date('Y-m-d'));
                        $totstock = getlocationstock('',$info1['product'],date('Y-m-d'),$location);

                        $rqqty='';
                        $difqty = $info1['qty']-$totstock;
                        
                        if($difqty>=0) {

                            $rqqty = $product['reorderlvl']+$difqty;
                        } else {

                            $difrqqty = $product['reorderlvl']+($difqty);
                            if($difrqqty>0) {

                                $rqqty = $difrqqty;
                                $disable = "";
                            } else {

                                $rqqty = 0;
                                // $disable = "disabled";
                            }
                        }

                        if($info['dep_type']=='packaging') {
                            $i++;
			    ?>
                    <tr>
                        <td><input type='checkbox' <?php echo $dis; ?> <?php echo $disable; ?> class='checkboxes' name='check_list' value='<?php echo $info1['id']; ?>'/>&nbsp
                        &nbsp;<?php echo $i; ?></td>
                        <td ><?php echo $product['name']; ?></td>
                        <td class="tdalign"><?php echo $info1['qty']; ?></td>
                        <td class="tdalign"><?php echo $totstock; ?></td>
                        <td class="tdalign"><?php echo $difqty; ?></td>
                        <td class="tdalign"><?php echo $product['reorderlvl']; ?></td>
                        <td class="tdalign"><?php echo $rqqty; ?></td>
                        <td style="text-align:center;">
                        <?php

                            if($rqqty!=0) {
                        ?>
                            <!-- <button class="btn btn-primary mr-2 btn-sm" data-bs-target="#porequi" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new_<?php echo $i; ?>" onclick="packrequi('<?php echo $info1['id']; ?>');">Add New</button> -->
                            <div id = "BOMDIV_<?php echo $i; ?>" style="display:block;">
                                <button class="btn btn-primary mr-2 btn-sm" data-bs-target="#porequi" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new_<?php echo $i; ?>" onclick="get_bom(<?php echo $i; ?>);">Add New</button>
                            </div>
                        <?php } ?>
                            <div id = "DIVBOM_<?php echo $i; ?>" style="display:none;padding-left:30px;padding-right:30px;">
                                <select id="bomid_<?php echo $i; ?>" name="bomid_<?php echo $i; ?>"   class="required form-select select2" data-allow-clear="true">
                                <?php
                                    echo '<option value="">Select</option>';
                                    $record=$utilObj->getMultipleRow("bill_of_material","product='".$info1['product']."' ");
                                    foreach($record as $e_rec)
                                    {
                                        if($rows['product']==$e_rec["id"]) echo $select='selected'; else $select='';
                                        echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["bom_name"] .'</option>';
                                    }
                                ?>
                                </select>
                                <br>
                                <button class="btn btn-primary mr-2 btn-sm" data-bs-target="#porequi" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new_<?php echo $i; ?>" onclick="packrequi('<?php echo $info1['id']; ?>','<?php echo $i; ?>','<?php echo $rqqty; ?>');">Send</button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                <?php } ?>
                <input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <td colspan="6" style="text-align:right;">
                            Create Requisition :
                        </td>
                        <td>
                            <button class="btn btn-primary mr-2 btn-sm" id="add_new_<?php echo $i; ?>" onclick="updateledger();">Create</button>
                        </td>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>

    <?php 
	    // include("form/purchase_requisition_form.php");
	?>

    <div class="modal fade" style = "max-width=40%;" id="packrequi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
            <div class="modal-content" id="pkreq" >
                
            </div>
        </div>
    </div>

</div>

<script>

    window.onload=function(){
        $("#fromdate").flatpickr({
            dateFormat: "d-m-Y"
        });

        $("#todate").flatpickr({
            dateFormat: "d-m-Y"
        });
    }

    function Search() {

        var fromdate=$('#fromdate').val();
        var todate=$('#todate').val();
        var location=$('#location').val();
		// var stkgrp=$('#stkgrp').val();
        // var supplier=$('#supplier').val();

        window.location="production_requisition_report.php?FromDate="+fromdate+"&location="+location+"&ToDate="+todate+"&Task=filter";
		// window.location="production_requisition_report.php?location="+location+"&stkgrp="+stkgrp+"&Task=filter";
    }

    
    function updateledger() {

        var sid='';

        $('input[type="checkbox"]').each(function() {
            	
            if(this.checked==true && this.value!='on')
            {
                
                sid +=this.value+",";
            }
        });

        // alert(sid);

        if(sid=='') {

            alert('Please Select Atleast 1 record!!!!');
            location.reload();
        } else {

            packrequi(sid);
        }

    }

    function get_bom(i) {

        $("#DIVBOM_"+i).css('display', 'block');
        $(".bom").css('display', 'block');
        $("#BOMDIV_"+i).css('display', 'none');
    }

    function packrequi(sid,i,qty) {

        var PTask = "add";
        var bomid = $("#bomid_"+i).val();
        console.log(bomid);

        jQuery.ajax({ url: 'get_ajax_values.php', type: 'POST',
            data: { Type: 'packrequi', sid:sid, PTask:PTask, bomid:bomid, qty:qty },
            success: function (data) {

                $('#pkreq').html(data);
                $('#packrequi').modal('show');
            },
            error: function (xhr, status, error) {

                console.error("AJAX Error:", status, error);
            }
        });

    }

</script>

<?php 
    include("footer.php");
?>