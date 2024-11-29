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
        $Date=date('Y-m-d',strtotime($to));
        
        
        $_SESSION['FromDate']=date($Date1);
        $_SESSION['ToDate']=date($Date);
        $inputfrom=date('d-m-Y',strtotime($from));
        $inputto=date('d-m-Y',strtotime($to));
        // $_SESSION['cname']=$_REQUEST['cname'];
    }
    else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='') {

        $_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
        $_SESSION['ToDate']=date("Y-m-d");
        $inputfrom=date("01-m-Y");
        $inputto=date("d-m-Y");
    }
?>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Receivable Simple Report</h4>
		</div>
	</div>
	<div class="row" style="margin-bottom:12px;">
		<form id=""   class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
				<!-- <div class="col-md-3 ">
					<label  class="form-label">FromDate</label>
					<input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
				</div> 
				<div class="col-md-3 ">
					<label  class="form-label">ToDate</label>
					<input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
				</div> -->
				<!-- <div class="col-md-3">
					<label class="form-label" >Supplier: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="supplier" name="supplier"   class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
						<option value="All" <?php if($_REQUEST['supplier']=="All"){ echo "selected";}else{ echo ""; } ?>>All</option>
						<?php	
							$data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['supplier']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
							}  
						?>
					</select>
				</div> -->
				<!-- <div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div> -->
			</div>
		</form>
	</div>


	<div class="card">
		<div class="card-datatable table-responsive pt-0">
			<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='3%'><!--input type='checkbox' value='0' id='select_all' onclick="select_all();" /-->&nbsp Sr.No.</th>
						<!-- <th width='10%'>Date</th> -->
                        <th width='10%'>Supplier</th>
						<th width='10%'>Opening Balance</th>
						<th width='10%'>Debit Amount</th>
						<th width='10%'>Credit Amount</th>
						<th width='10%'>Balance</th>
					</tr>
				</thead>
			
				<tbody>

                
				<?php
					$i=0;
					if($_REQUEST['Task']=='filter') {

						$cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."' ";
						// echo $cnd;
					} else {

						$cnd=" date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."' ";
						// echo $cnd;
					}
					
					// $data=$utilObj->getMultipleRow("sale_invoice","$cnd");
                    $data=$utilObj->getMultipleRow("account_ledger","group_name=14 group by id"); 

					foreach($data as $info) {

						$i++;
						$href= 'sale_invoice_list.php?id='.$info['id'].'&PTask=view';

                        $currentYear = date('Y');
                        $currentMonth = date('m');

                        if ($currentMonth >= 4) {
                            $lastyearstart = date('Y-m-d', strtotime('first day of April last year'));

                            // echo "<br>";
                            $lastyearend = date('Y-m-d', strtotime('last day of March this year'));
                            $yearstart = date('Y-m-d', strtotime('first day of April this year'));
                        }

                        $lastpayment = $utilObj->getSum("sale_receipt", "customer='".$info['id']."' AND receiptdate BETWEEN '". $lastyearstart."' AND '". $lastyearend."' ", "amt_pay");

                        // echo "<br>";
                        $lastpinvoice = $utilObj->getSum("sale_invoice", "customer='".$info['id']."' AND date BETWEEN '". $lastyearstart."' AND '". $lastyearend."' ", "grandtotal");

                        // echo "<br>";
                        $curpayment = $utilObj->getSum("sale_receipt","customer='".$info['id']."' AND receiptdate > '".$yearstart."' ","amt_pay");

                        $curpuchase = $utilObj->getSum("sale_invoice","customer='".$info['id']."' AND date > '".$yearstart."' ","grandtotal");

                        if($info['op_method']=='Credit') {

                            $opbal = ($info['op_balance']+$lastpayment)-$lastpinvoice;
                        } else {

                            $opbal = ($info['op_balance']+$lastpinvoice)-$lastpayment;
                        }

                        $remain = ($opbal+$curpuchase)-$curpayment;
						
				?>
					<tr>
						<td  class='controls'><!---input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/-->&nbsp&nbsp<?php echo $i; ?></td> 
						<!-- <td> <?php echo $info['date']; ?> </td> -->
                        <td><?php echo $info['name']; ?></td>
						<td><?php echo $opbal; ?></td>
						<td><?php echo $curpuchase; ?></td>
						<td><?php echo $curpayment; ?></td>
						<td><?php echo $remain; ?></td>
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

	function Search(){
		var fromdate=$('#fromdate').val();
		var todate=$('#todate').val();
		var supplier=$('#supplier').val();
		window.location="customer_payables_report.php?FromDate="+fromdate+"&ToDate="+todate+"&Task=filter";
	}

	/* function select_all() {
	
		// select all checkboxes
		// $("#select_all").change(function(){  //"select all" change

		// 	var status = this.checked; // "select all" checked status
		// 	$('.checkboxes').each(function(){ //iterate all listed checkbox items
		// 		if(this.disabled==false)
		// 		{
		// 			this.checked = status; //change ".checkbox" checked status
		// 			//alert(this.disabled);
		// 		}
		// 	});
		// });

		// //uncheck "select all", if one of the listed checkbox item is unchecked
		// $('.checkboxes').change(function(){ //".checkbox" change

		// 	if(this.checked == false){ //if this item is unchecked
		// 		$("#select_all")[0].checked = false; //change "select all" checked status to false
		// 	}
		// });

	} */
</script>


<?php 
	include("footer.php");
?>
