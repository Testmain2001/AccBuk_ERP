<?php 
    include("header.php");
    $task=$_REQUEST['PTask'];
    if($task==''){ $task='Add';}
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
    unset($_SESSION['FromDate']);
    unset($_SESSION['ToDate']);
    // unset($_SESSION['cname']);
    if($_REQUEST['Task']=='filter')
    {
        $from=$_REQUEST['FromDate'];
        $Date1=date('Y-m-d',strtotime($from));
        
        $to=$_REQUEST['ToDate'];
        $Date=date('Y-m-d',strtotime($to));
        
        $_SESSION['FromDate']=date($Date1);
        $_SESSION['ToDate']=date($Date);
        $inputfrom=date('d-m-Y',strtotime($from));
        $inputto=date('d-m-Y',strtotime($to));
        // $_SESSION['cname']=$_REQUEST['cname'];
    }
    else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='')
    {
        $_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
        $_SESSION['ToDate']=date("Y-m-d");
        $inputfrom=date("01-m-Y");
        $inputto=date("d-m-Y");
    }
?>

<div class="container-xxl flex-grow-1 container-p-y ">
    <div class="row">     
		<div class="col-md-3">       
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">GST Maintaince</h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">
        <form id=""   class=" form-horizontal " method="get" data-rel="myForm">
            <div class="row">
                <div class="col-md-3">
					<label class="form-label" >Stock Item: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="product" name="product"   class="required form-select select2" data-allow-clear="true">
                        <option value="">Select</option>
                        <!-- <option value="All" <?php if($_REQUEST['supplier']=="All") { echo "selected"; } else { echo ""; } ?>>All</option> -->
						<?php	
							$data=$utilObj->getMultipleRow("stock_ledger","1 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['product']){echo $select="selected";} else {echo $select=""; }
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
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
                        <th width='3%'>Sr.No.</th>
                        <th>Product</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>IGST</th>
                        <th>CGST</th>
                        <th>SGST</th>
                        <!-- <th>User</th> -->
                    </tr>
                </thead>

                <tbody>
                <?php
                    $i=0;
                    if($_REQUEST['Task']=='filter' && $_REQUEST['product']!="" ){
                        $cnd="product='".$_REQUEST['product']."' ";
                    } else {
                        $cnd="1";
                    }

                    $data=$utilObj->getMultipleRow("ledger_gst_history","$cnd");

                    foreach($data as $info) {
                        $product=$utilObj->getSingleRow("stock_ledger","id='".$info['product']."' ");
                        $gst=$utilObj->getSingleRow("gst_data","id='".$info['igst']."' ");

                        if($info['todate']==NULL) {
                            $date = "TBD";
                        } else {
                            $date = date('d-m-Y',strtotime($info['todate']));
                        }

                        $i++;
                ?>
                    <tr>
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php echo $product['name']; ?>
                        </td>
                        <td>
                            <?php echo date('d-m-Y',strtotime($info['fromdate'])); ?>
                        </td>
                        <td>
                            <?php echo $date; ?>
                        </td>
                        <td>
                            <?php echo $gst['igst']; ?>
                        </td>
                        <td>
                            <?php echo $gst['cgst']; ?>
                        </td>
                        <td>
                            <?php echo $gst['sgst']; ?>
                        </td>

                        <!-- <td></td> -->
                    </tr>
                <?php } ?>
                </tbody>
            </table>
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

        var product=$('#product').val();

        window.location="gst_report.php?product="+product+"&Task=filter";
    }

</script>


<?php 
    include("footer.php");
?>