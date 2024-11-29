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
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Payables Simple Report</h4>
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
                        <th width='10%'>Supplier</th>
						<th width='10%'>Invoice Amount</th>
                        <th width='10%'>1-15 DAYS</th>
						<th width='10%'>16-30 DAYS</th>
						<th width='10%'>31-45 DAYS</th>
						<th width='10%'>> 45 DAYS</th>
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
					
					// $data=$utilObj->getMultipleRow("purchase_invoice","$cnd");
                    $data=$utilObj->getMultipleRow("account_ledger","group_name=18 group by id"); 

					foreach($data as $info) {

						$i++;
						$href= 'purchase_invoice_list.php?id='.$info['id'].'&PTask=view';

						$curpuchase = $utilObj->getSum("purchase_invoice","supplier='".$info['id']."' ","grandtotal");

                        $curDate = date('Y-m-d');

                        // Interval 1: 1-15 days
                        $startDate1 = date('Y-m-d', strtotime('-15 days', strtotime($curDate)));
                        $endDate1 = $curDate;
                        $curpayment1 = $utilObj->getSum(
                            "purchase_payment",
                            "supplier='".$info['id']."' AND paymentdate BETWEEN '".$startDate1."' AND '".$endDate1."'",
                            "amt_pay"
                        );

                        // Interval 2: 16-30 days
                        $startDate2 = date('Y-m-d', strtotime('-30 days', strtotime($curDate)));
                        $endDate2 = date('Y-m-d', strtotime('-16 days', strtotime($curDate)));
                        $curpayment2 = $utilObj->getSum(
                            "purchase_payment",
                            "supplier='".$info['id']."' AND paymentdate BETWEEN '".$startDate2."' AND '".$endDate2."'",
                            "amt_pay"
                        );

                        // Interval 3: 31-45 days
                        $startDate3 = date('Y-m-d', strtotime('-45 days', strtotime($curDate)));
                        $endDate3 = date('Y-m-d', strtotime('-31 days', strtotime($curDate)));
                        $curpayment3 = $utilObj->getSum(
                            "purchase_payment",
                            "supplier='".$info['id']."' AND paymentdate BETWEEN '".$startDate3."' AND '".$endDate3."'",
                            "amt_pay"
                        );

                        // Interval 4: Above 45 days
                        $endDate4 = date('Y-m-d', strtotime('-46 days', strtotime($curDate))); // 46 days ago
                        $curpayment4 = $utilObj->getSum(
                            "purchase_payment",
                            "supplier='".$info['id']."' AND paymentdate <= '".$endDate4."'",
                            "amt_pay"
                        );

						if($curpuchase==0 || $curpuchase=='') {

							$curpuchase = 0;
						}

						if($curpayment1==0 || $curpayment1=='') {

							$curpayment1 = 0;
						}

						if($curpayment2==0 || $curpayment2=='') {

							$curpayment2 = 0;
						}

						if($curpayment3==0 || $curpayment3=='') {

							$curpayment3 = 0;
						}

						if($curpayment4==0 || $curpayment4=='') {

							$curpayment4 = 0;
						}
				?>
					<tr>
						<td  class='controls'><!---input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/-->&nbsp&nbsp<?php echo $i; ?></td>
                        <td><?php echo $info['name']; ?></td>
                        <td><?php echo $curpuchase; ?></td>
						<td><?php echo $curpayment1; ?></td>
						<td><?php echo $curpayment2; ?></td>
						<td><?php echo $curpayment3; ?></td>
						<td><?php echo $curpayment4; ?></td>
						
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
