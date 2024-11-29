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

	if($_REQUEST['Task']=='filter') {

		$from=$_REQUEST['FromDate'];
		$Date1=date('Y-m-d',strtotime($from));
		
		$to=$_REQUEST['ToDate'];
		$Date=date('Y-m-d',strtotime($to));
		
		
		$_SESSION['FromDate']=date($Date1);
		$_SESSION['ToDate']=date($Date);
		$inputfrom=date('d-m-Y',strtotime($from));
		$inputto=date('d-m-Y',strtotime($to));
		//$_SESSION['cname']=$_REQUEST['cname'];

		
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
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">Ledger Statement Report</h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">

        <form id="" class="form-horizontal" method="get" data-rel="myForm">
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
                    <label class="form-label" >Ledger: <span class="required required_lbl" style="color:red;">*</span></label>
                    <select id="ledger" name="ledger" class="required form-select select2" data-allow-clear="true">
                    <option value="">Select</option>
                    <!-- <option value="All" <?php if($_REQUEST['ledger']=="All"){ echo "selected"; }else{ echo "";} ?>>All</option> -->
                        <?php	
                            $data=$utilObj->getMultipleRow("account_ledger","1 group by name"); 
                            foreach($data as $info){
                                if($info["id"]==$_REQUEST['ledger']){echo $select="selected";}else{echo $select="";}
                                echo  '<option value="'.$info["id"].'" '.$select.'>'.$info["name"].'</option>';
                            }  
                        ?>
                    </select>
                </div>

                <div class="col-md-2" style="padding-top:25px;">
                    <input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success btn-sm" value="Search" style="margin-top: 2px;" />
                </div>
            </div>
        </form>
    </div>

	<div class="card">
		<div class="card-datatable table-responsive pt-0" style="overflow-x: auto;">
			<table class="datatables-basic table table-striped border-top" id="datatable-buttons" role="grid">
				<thead>
					<tr>
						<th width='8%'>Date</th>
						<th width='15%'>Particular</th>
						<!-- <th width='8%'>Vch Type</th> -->
						<th width='8%'>Vch Code</th>
						<th width='8%'>Debit</th>
						<th width='8%'>Credit</th>
						<!-- <th width='8%'>Balance</th> -->
					</tr>
				</thead>

				<tbody>
				<?php
					
					$totcredit=0;
					$totdebit=0;

					$parti=$utilObj->getSingleRow("account_ledger","id='".$_REQUEST['ledger']."' ");
					$accledger = $utilObj->getSingleRow("account_ledger","id='".$_REQUEST['ledger']."' ");

					if($_REQUEST['Task']=='filter' && $_REQUEST['ledger']!="") {

						$cndo="dat<'".$_SESSION['FromDate']."' ";
					} else {

						$cndo="dat<'".$_SESSION['FromDate']."' ";
					}

					echo "<pre>";
					$actgrp = $accledger['actgrp'];

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
							WHERE supplier = '".$_REQUEST['ledger']."' AND $cndo
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
							WHERE supplier = '".$_REQUEST['ledger']."' AND $cndo
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
							WHERE supplier = '".$_REQUEST['ledger']."' AND $cndo
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
							WHERE supplier = '".$_REQUEST['ledger']."' AND $cndo
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
					
					echo $totcredit."_Credit";
					echo "<br>";
					echo $totdebit."_Debit";
					echo "<br>";
					echo $opbalres;
					echo "<br>";

					// if($totcredit>$parti['op_balance']) {

					// 	$clbalold = $totcredit - ($totdebit+$parti['op_balance']);
					// } else {

					// 	$clbalold = ($totdebit+$parti['op_balance'])-$totcredit;
					// } 
				?>
					<tr>
						<td></td>
						<td>Opening Balance :</td>
						<!-- <td></td> -->
						<td></td>
						<?php if($parti['op_method']=='Credit' && $totdebit==0 && $totcredit==0) { ?>

							<td></td>
							<td><?php echo $parti['op_balance']; ?></td>
						<?php } elseif($parti['op_method']=='Debit' && $totdebit==0 && $totcredit==0) { ?>
							
							<td><?php echo $parti['op_balance']; ?></td>
							<td></td>
						<?php } elseif($opbalres==2 || $opbalres==3) { ?>

							<td></td>
							<td><?php echo $clbalold; ?></td>
						<?php } else { ?>
							
							<td><?php echo $clbalold; ?></td>
							<td></td>
						<?php } ?>
					</tr>
				<?php ?>

				<?php
					$i=0;

					if($_REQUEST['Task']=='filter' && $_REQUEST['ledger']!="All" && $_REQUEST['ledger']!="") {

						$cnd="dat>='".$_SESSION['FromDate']."'AND dat<='".$_SESSION['ToDate']."' ";
					} else {

						$cnd="dat>='".$_SESSION['FromDate']."' AND dat<='".$_SESSION['ToDate']."' ";
					}

					// echo $cnd;
					// echo "<br>";
					// -----------------------------------------------------------------------------

					$actgrp = $accledger['actgrp'];

					if($actgrp=='Sundry Creditors') {

						$ledgerdata=mysqli_query($GLOBALS['con'],"SELECT * FROM (
							SELECT supplier, sup, record, vcode, dat, amt, mid FROM (
								SELECT supplier, bank_ledger AS sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay AS amt, id AS mid FROM cash_payment
								UNION ALL
								SELECT supplier, cgstledger AS sup, record, pur_invoice_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM purchase_invoice
								UNION ALL
								SELECT supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM purchase_invoice_service
								UNION ALL
								SELECT supplier, cgst_ledger AS sup, record, voucher_code AS vcode, date AS dat, grandtotal AS amt, id AS mid FROM debitnote_acc
								UNION ALL
								SELECT supplier, bank_ledger AS sup, record, purpay_code AS vcode, paymentdate AS dat, amt_pay AS amt, id AS mid FROM purchase_payment
							) AS combined_tables
							WHERE supplier = '".$_REQUEST['ledger']."'
							AND $cnd
							ORDER BY dat ASC
						) AS subquery");

						while ($row = mysqli_fetch_array($ledgerdata, MYSQLI_ASSOC)) {
							
							$ldata[]=$row;
						}
					}
					elseif($actgrp=='Sundry Debtors') {

						$ledgerdata=mysqli_query($GLOBALS['con'],"SELECT * FROM (
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
							WHERE supplier = '".$_REQUEST['ledger']."'
							AND $cnd
							ORDER BY dat ASC
						) AS subquery");

						while ($row = mysqli_fetch_array($ledgerdata, MYSQLI_ASSOC)) {
							
							$ldata[]=$row;
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

					// -----------------------------------------------------------------------------

					
					// echo "<pre>";
					// print_r($ldata);

					foreach($ldata as $info) {

						$i++;

						$parti1=$utilObj->getSingleRow("account_ledger","id='".$info['sup']."' ");

						if($info['record']=='Cr') {

							$rcd = Dr;
						} else {

							$rcd = Cr;
						}

						$dbamt='';
						$cdamt='';
						if($rcd == 'Dr') {

							$cdamt=$info['amt'];
						} else {

							$dbamt=$info['amt'];
						}

						$totcdamt1 += $cdamt;
						$totdbamt1 += $dbamt;


						if($parti['op_method']=='Credit' && $totdebit==0 && $totcredit==0) {

							$totcdamt = $parti['op_balance'] + $totcdamt1;
							$totdbamt = $totdbamt1;
						} elseif($parti['op_method']=='Debit' && $totdebit==0 && $totcredit==0) {

							$totdbamt = $parti['op_balance'] + $totdbamt1;
							$totcdamt = $totcdamt1;
						}
						elseif($totdebit>$totcredit && $parti['op_method']=='Credit') {

							$totcdamt = $clbalold + $totcdamt1;
							$totdbamt = $totdbamt1;
						}
						elseif($totdebit<$totcredit && $parti['op_method']=='Debit') {

							$totdbamt = $clbalold + $totdbamt1;
							$totcdamt = $totcdamt1;
						}
						elseif($totdebit>$totcredit) {

							$totdbamt = $clbalold + $totdbamt1;
							$totcdamt = $totcdamt1;
						}
						else {

							$totcdamt = $clbalold + $totcdamt1;
							$totdbamt = $totdbamt1;
						}
				?>
					

					<tr>
						<td>
							<?php echo date('d-m-Y',strtotime($info['dat'])); ?> &nbsp;&nbsp;&nbsp; <?php echo $rcd; ?>
						</td>

						<td>
							<?php echo $parti1['name']; ?>
						</td>

						<!-- <td>
							<?php echo $info['vcode']; ?>
						</td> -->

						<td>
							<?php echo $info['vcode']; ?>
						</td>

						<td>
							<?php echo $dbamt; ?>
						</td>
						
						<td>
							<?php echo $cdamt; ?>
						</td>

					</tr>
				<?php } ?>
					<input type="hidden" name="cnt" id="cnt" value="<?php echo $i; ?>">
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<!-- <td></td> -->
						<td></td>
						<td><?php echo $totdbamt; ?></td>
						<td><?php echo $totcdamt; ?></td>
					</tr>

					<?php
						$clbal = '';
						if($totdbamt>$totcdamt) {

							$clbal = $totdbamt - $totcdamt;
						} else {

							$clbal = $totcdamt - $totdbamt;
						}
					?>
					<tr>
						<td></td>
						<td> Closing Balance :</td>
						<!-- <td></td> -->
						<td></td>

						<?php if($totdbamt>$totcdamt) { ?>

							<td></td>
							<td><?php echo $clbal; ?></td>
						<?php } else { ?>
							
							<td><?php echo $clbal; ?></td>
							<td></td>
						<?php } ?>
						
					</tr>

					<?php
						$totbaldb = '';
						$totbalcd = '';
						if($totdbamt>$totcdamt) {

							$totbaldb = $totdbamt;
							$totbalcd = $totcdamt + $clbal;
						} else {

							$totbaldb = $totdbamt + $clbal;
							$totbalcd = $totcdamt;
						}
					?>
					<tr>
						<td ></td>
						<td ></td>
						<!-- <td ></td> -->
						<td ></td>
						<td><?php echo $totbaldb; ?></td>
						<td><?php echo $totbalcd; ?></td>
					</tr>
				</tfoot>
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

		var fromdate=$('#fromdate').val();
		var todate=$('#todate').val();
		var ledger=$('#ledger').val();

		window.location="ledger_statement_report.php?FromDate="+fromdate+"&ToDate="+todate+"&ledger="+ledger+"&Task=filter";
	}

</script>

<?php 
	include("footer.php");
?>