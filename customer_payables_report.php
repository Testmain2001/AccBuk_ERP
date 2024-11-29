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

        $inputfrom=date('d-m-Y', strtotime('first day of April this year'));
        $inputto=date("d-m-Y");
    }
?>

<div class="container-xxl flex-grow-1 container-p-y ">
            
	<div class="row">     
		<div class="col-md-3">       
			<h4 class="fw-bold mb-4" style="padding-top:2px;">Simple Report</h4>
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
					<label class="form-label" >Group: <span class="required required_lbl" style="color:red;">*</span></label>
					<select id="grp" name="grp"   class="required form-select select2" data-allow-clear="true">
						<option value="">Select</option>
						<?php	
							$data=$utilObj->getMultipleRow("group_master","1 ORDER BY group_name ASC"); 
							foreach($data as $info){
								if($info["id"]==$_REQUEST['grp']){echo $select="selected";}else{echo $select="";}
								echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["group_name"].'</option>';
							}  
						?>
					</select>
				</div>
				<div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success btn-sm" value="Search" />
				</div>
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
						<th width='10%'>Closing Balance</th>
					</tr>
				</thead>
			
				<tbody>

                
				<?php
					$i=0;
					if($_REQUEST['Task']=='filter') {

						$cnd="dat>='".$_SESSION['FromDate']."'AND dat<='".$_SESSION['ToDate']."' ";
						$cndo="dat<='".$_SESSION['FromDate']."' ";

					} else {

						$cnd=" dat>='".$_SESSION['FromDate']."' AND dat<='".$_SESSION['ToDate']."' ";
						$cndo="dat<='".$_SESSION['FromDate']."' ";

					}

					
					
					// $data=$utilObj->getMultipleRow("purchase_invoice","$cnd");
                    $data=$utilObj->getMultipleRow("account_ledger","group_name='".$_REQUEST['grp']."' group by id"); 

					foreach($data as $info) {

						$parti=$utilObj->getSingleRow("account_ledger","id='".$info['id']."' ");
						$actgrp = $parti['actgrp'];

						if($_REQUEST['Task']=='filter') {

							if($actgrp=='Sundry Creditors') {

								$totcreditsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt
								FROM (
									SELECT supplier, sup, record, vcode, dat, amt 
									FROM (
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM cash_payment
										UNION ALL
										SELECT supplier, cgstledger as sup, record, pur_invoice_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice_service
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM debitnote_acc
										UNION ALL
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM purchase_payment
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cndo
								) AS subquery
								WHERE record = 'Cr' ");
		
								if ($totcreditsum) {
		
									$row = mysqli_fetch_assoc($totcreditsum);
									if($row['total_amt']!='') {
		
										$totcredit = $row['total_amt'];
									} else {
		
										$totcredit = 0;
									}
									
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
		
								$totdebitsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt
								FROM (
									SELECT supplier, sup, record, vcode, dat, amt 
									FROM (
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM cash_payment
										UNION ALL
										SELECT supplier, cgstledger as sup, record, pur_invoice_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice_service
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM debitnote_acc
										UNION ALL
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM purchase_payment
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cndo
								) AS subquery
								WHERE record = 'Dr' ");
		
								if ($totdebitsum) {
		
									$row = mysqli_fetch_assoc($totdebitsum);
									$totdebit = $row['total_amt'];
									if($row['total_amt']!='') {
		
										$totdebit = $row['total_amt'];
									} else {
										
										$totdebit = 0;
									}
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
							}
							elseif($actgrp=='Sundry Debtors') {
		
								$totcreditsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt FROM (
									SELECT supplier, sup, record, vcode, dat, amt, mid FROM (
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM cash_receipt
										UNION ALL
										SELECT customer AS supplier, cgstledger AS sup, record, saleino_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice_service
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM creditnote_acc
										UNION ALL
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM sale_receipt
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cndo
									ORDER BY dat ASC
								) AS subquery
								WHERE record = 'Cr' ");
		
								if ($totcreditsum) {
		
									$row = mysqli_fetch_assoc($totcreditsum);
									if($row['total_amt']!='') {
		
										$totcredit = $row['total_amt'];
									} else {
		
										$totcredit = 0;
									}
									
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
		
								$totdebitsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt FROM (
									SELECT supplier, sup, record, vcode, dat, amt, mid FROM (
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM cash_receipt
										UNION ALL
										SELECT customer AS supplier, cgstledger AS sup, record, saleino_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice_service
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM creditnote_acc
										UNION ALL
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM sale_receipt
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cndo
									ORDER BY dat ASC
								) AS subquery
								WHERE record = 'Dr' ");
		
								if ($totdebitsum) {
		
									$row = mysqli_fetch_assoc($totdebitsum);
									$totdebit = $row['total_amt'];
									if($row['total_amt']!='') {
		
										$totdebit = $row['total_amt'];
									} else {
										
										$totdebit = 0;
									}
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
							}
							elseif($actgrp=='Sales Accounts') {
		
		
							}
							elseif($actgrp=='Purchase Accounts') {
		
		
							}
							elseif($actgrp=='Duties & Taxes') {
		
		
							}
							elseif($actgrp=='Purchase Accounts') {
		
		
							}
		
							$clbalold = 0;
							$opbalres = 0;
		
							
							if($totdebit>$totcredit && $parti['op_method']=='Debit') {
		
								$clbalold = ($parti['op_balance']+$totdebit) - $totcredit;
								$opbalres = 1;
							} else if($totdebit<$totcredit && $parti['op_method']=='Credit') {
		
								$clbalold = ($parti['op_balance']-$totdebit) + $totcredit;
								$opbalres = 2;
							}
							else if($totdebit>$totcredit && $parti['op_method']=='Credit') {
		
								$clbalold = ($parti['op_balance']-$totdebit) + $totcredit;
								$opbalres = 3;
							} else if($totdebit<$totcredit && $parti['op_method']=='Debit') {
		
								$clbalold = ($parti['op_balance']+$totdebit) - $totcredit;
								$opbalres = 4;
							}
							else if($totdebit==0 && $totcredit==0 && $parti['op_method']=='Credit') {
		
								$clbalold = $parti['op_balance'];
								$opbalres = 5;
							}
							else if($totdebit==0 && $totcredit==0 && $parti['op_method']=='Debit') {
		
								$clbalold = $parti['op_balance'];
								$opbalres = 5;
							}

						// ---------------------------------------------------------------------

							if($actgrp=='Sundry Creditors') {

								$currenttotcreditsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt
								FROM (
									SELECT supplier, sup, record, vcode, dat, amt 
									FROM (
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM cash_payment
										UNION ALL
										SELECT supplier, cgstledger as sup, record, pur_invoice_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice_service
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM debitnote_acc
										UNION ALL
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM purchase_payment
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cnd
								) AS subquery
								WHERE record = 'Cr' ");
		
								if ($currenttotcreditsum) {
		
									$row = mysqli_fetch_assoc($currenttotcreditsum);
									if($row['total_amt']!='') {
		
										$currenttotcredit = $row['total_amt'];
									} else {
		
										$currenttotcredit = 0;
									}
									
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
		
								$currenttotdebitsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt
								FROM (
									SELECT supplier, sup, record, vcode, dat, amt 
									FROM (
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM cash_payment
										UNION ALL
										SELECT supplier, cgstledger as sup, record, pur_invoice_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM purchase_invoice_service
										UNION ALL
										SELECT supplier, cgst_ledger as sup, record, voucher_code AS vcode, date AS dat, grandtotal as amt FROM debitnote_acc
										UNION ALL
										SELECT supplier, bank_ledger as sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay as amt FROM purchase_payment
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cnd
								) AS subquery
								WHERE record = 'Dr' ");
		
								if ($currenttotdebitsum) {
		
									$row = mysqli_fetch_assoc($currenttotdebitsum);
									if($row['total_amt']!='') {
		
										$currenttotdebit = $row['total_amt'];
									} else {
										
										$currenttotdebit = 0;
									}
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
							}
							elseif($actgrp=='Sundry Debtors') {
		
								$currenttotcreditsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt FROM (
									SELECT supplier, sup, record, vcode, dat, amt, mid FROM (
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM cash_receipt
										UNION ALL
										SELECT customer AS supplier, cgstledger AS sup, record, saleino_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice_service
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM creditnote_acc
										UNION ALL
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM sale_receipt
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cnd
									ORDER BY dat ASC
								) AS subquery
								WHERE record = 'Cr' ");
		
								if ($currenttotcreditsum) {
		
									$row = mysqli_fetch_assoc($currenttotcreditsum);
									if($row['total_amt']!='') {
		
										$currenttotcredit = $row['total_amt'];
									} else {
		
										$currenttotcredit = 0;
									}
									
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
		
								$currenttotdebitsum=mysqli_query($GLOBALS['con'],"SELECT SUM(amt) as total_amt FROM (
									SELECT supplier, sup, record, vcode, dat, amt, mid FROM (
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM cash_receipt
										UNION ALL
										SELECT customer AS supplier, cgstledger AS sup, record, saleino_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM sale_invoice_service
										UNION ALL
										SELECT supplier AS supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM creditnote_acc
										UNION ALL
										SELECT customer AS supplier, bankid AS sup, record, saler_code AS vcode, receiptdate AS dat, amt_pay AS amt, id AS mid FROM sale_receipt
									) AS combined_tables
									WHERE supplier = '".$info['id']."' AND $cnd
									ORDER BY dat ASC
								) AS subquery
								WHERE record = 'Dr' ");
		
								if ($currenttotdebitsum) {
		
									$row = mysqli_fetch_assoc($currenttotdebitsum);
									$totdebit = $row['total_amt'];
									if($row['total_amt']!='') {
		
										$currenttotdebit = $row['total_amt'];
									} else {
										
										$currenttotdebit = 0;
									}
								} else {
		
									echo "Query failed: " . mysqli_error($GLOBALS['con']);
								}
							}
							elseif($actgrp=='Sales Accounts') {
		
		
							}
							elseif($actgrp=='Purchase Accounts') {
		
		
							}
							elseif($actgrp=='Duties & Taxes') {
		
		
							}
							elseif($actgrp=='Purchase Accounts') {
		
		
							}
		
							$closing = 0;
							$balres = 0;
		
							
							if($currenttotdebit>$currenttotcredit && $parti['op_method']=='Debit') {
		
								$closing = ($parti['op_balance']+$currenttotdebit) - $currenttotcredit;
								$balres = 1;
							} else if($currenttotdebit<$currenttotcredit && $parti['op_method']=='Credit') {
		
								$closing = ($parti['op_balance']-$currenttotdebit) + $currenttotcredit;
								$balres = 2;
							}
							else if($currenttotdebit>$currenttotcredit && $parti['op_method']=='Credit') {
		
								$closing = ($parti['op_balance']-$currenttotdebit) + $currenttotcredit;
								$balres = 3;
							} else if($currenttotdebit<$currenttotcredit && $parti['op_method']=='Debit') {
		
								$closing = ($parti['op_balance']+$currenttotdebit) - $currenttotcredit;
								$balres = 4;
							}
							else if($currenttotdebit==0 && $currenttotcredit==0 ) {
		
								$closing = 0;
								$balres = 5;
							}

							$i++;

							// $href= 'purchase_invoice_list.php?id='.$info['id'].'&PTask=view';
							$href= 'ledger_statement_report.php?FromDate='.$_SESSION['FromDate'].'&ToDate='.$_SESSION['ToDate'].'&ledger='.$info['id'].'&Task=filter';

							if($totdebit>$totcredit) {

								$type = Dr;
							} elseif($totdebit<$totcredit) {

								$type = Cr;
							}
							elseif($totdebit==0 && $totcredit==0 && $parti['op_method']=='Credit') {

								$type = Cr;
							} elseif($totdebit==0 && $totcredit==0 && $parti['op_method']=='Debit') {

								$type = Dr;
							}
						
				?>
					<tr>
						<td  class='controls'><!---input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/-->&nbsp&nbsp<?php echo $i; ?></td> 
						<!-- <td> <?php echo $info['date']; ?> </td> -->
                        <td><a href="<?php echo $href; ?>" target="_blank"><?php echo $info['name']; ?></a></td>
						<td><?php echo $clbalold; ?>&nbsp;&nbsp;<?php echo $type; ?></td>
						<td><?php echo $currenttotdebit; ?></td>
						<td><?php echo $currenttotcredit; ?></td>
						<td><?php echo $closing; ?></td>
					</tr>
					<?php } ?>
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
		var grp=$('#grp').val();

		window.location="customer_payables_report.php?FromDate="+fromdate+"&ToDate="+todate+"&grp="+grp+"&Task=filter";
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
