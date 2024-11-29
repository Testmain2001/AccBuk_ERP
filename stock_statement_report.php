<?php
	include("header.php");
	$task=$_REQUEST['PTask'];
	if($task==''){ $task='Add';}
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
		//$_SESSION['cname']=$_REQUEST['cname'];
		
	} else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='') {

		$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
		$_SESSION['ToDate']=date("Y-m-d");
		$inputfrom=date("01-m-Y");
		$inputto=date("d-m-Y");
	}

?>

<div class="container-xxl flex-grow-1 container-p-y ">

    <div class="row">
		<div class="col-md-3">       
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">Stock Led Statement Report</h4>
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
                            $data=$utilObj->getMultipleRow("stock_ledger","1"); 
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
            <table class="datatables-basic table table-striped " id="datatable-buttons" role="grid">
                <thead>
					<tr>
						<th width='4%'>Date</th>
						<th width='8%'>Particular</th>
						<th width='4%'>Vch Type</th>
						<th width='4%'>Vch Code</th>
						<th width='9%' >Inward</th>
						<th width='9%' >Outward</th>
					</tr>
                    <tr>
                        <th width='4%'></th>
                        <th width='8%'></th>
                        <th width='4%'></th>
                        <th width='4%'></th>
                        <th width='9%'>Quantity</th>
                        <th width='9%'>Quantity</th>
                    </tr>
				</thead>
                <tbody>
                <?php
                    $tostock = gettotalstock($_REQUEST['ledger'],$_SESSION['FromDate']);
                ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Opening Stock:</td>
                        <td>
                            <?php echo $tostock; ?>
                        </td>
                        <td></td>
                    </tr>
                <?php

                    $i=0;

                    // $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
                    //     SELECT pid, product, qty, id, LastEdited FROM (
                    //         SELECT parent_id as pid, product, qty, id, LastEdited FROM grn_details
                    //         UNION ALL
                    //         SELECT parent_id as pid, product, qty, id, LastEdited FROM purchase_invoice_details
                    //         UNION ALL
                    //         SELECT id as pid, product, qty, id, LastEdited FROM production
                    //         UNION ALL
                    //         SELECT id as pid, product, qty, id, LastEdited FROM packaging
                    //     ) AS combined_tables
                    //     WHERE product = '".$_REQUEST['ledger']."'
                    //     AND DATE(LastEdited) BETWEEN '".$_SESSION['FromDate']."' AND '".$_SESSION['ToDate']."' 
                    //     ORDER BY LastEdited ASC
                    // ) AS subquery
                    // WHERE pid NOT IN (SELECT purchaseorder_no FROM purchase_invoice)");

                    $in = mysqli_query($GLOBALS['con'],"SELECT * FROM (
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'grn' AS rtype FROM grn_details 
                        UNION ALL 
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'purchase_invoice' AS rtype FROM purchase_invoice_details 
                        UNION ALL 
                        SELECT id AS pid, product, qty, id, LastEdited, 'production' AS rtype FROM production 
                        UNION ALL 
                        SELECT id AS pid, product, qty, id, LastEdited, 'packaging' AS rtype FROM packaging 
                        UNION ALL 
                        SELECT parent_id AS pid, product, inqty AS qty, id, LastEdited, 'stock_journal' AS rtype FROM stock_journal_details
                        UNION ALL 
                        SELECT parent_id AS pid, product, addstock AS qty, id, LastEdited, 'physical_stock' AS rtype FROM physical_stock_details
                        UNION ALL 
                        SELECT parent_id AS pid, product, rejectedqty AS qty, id, LastEdited, 'delivery_return' AS rtype FROM delivery_return_details
                    ) AS combined_tables 
                    WHERE product = '".$_REQUEST['ledger']."'
                        AND DATE(LastEdited) BETWEEN '".$_SESSION['FromDate']."' AND '".$_SESSION['ToDate']."'
                        AND qty > 0 AND pid NOT IN (SELECT purchaseorder_no FROM purchase_invoice) 
                    ORDER BY LastEdited ASC");

                    while ($inward = mysqli_fetch_array($in, MYSQLI_ASSOC)) {
						
						$inwarddata[]=$inward;
					}

                    // $out = mysqli_query($GLOBALS['con'],"SELECT * FROM (
                    //     SELECT pid, product, qty, id, LastEdited FROM (
                    //         SELECT parent_id AS pid, product, qty, id, LastEdited FROM delivery_challan_details
                    //         UNION ALL
                    //         SELECT parent_id AS pid, product, qty, id, LastEdited FROM sale_invoice_details
                    //         UNION ALL
                    //         SELECT parent_id AS pid, product, qty, id, LastEdited FROM production_details
                    //         UNION ALL
                    //         SELECT parent_id AS pid, product, qty, id, LastEdited FROM packaging_details
                    //     ) AS combined_tables
                    //     WHERE product = '".$_REQUEST['ledger']."'
                    //     AND DATE(LastEdited) BETWEEN '".$_SESSION['FromDate']."' AND '".$_SESSION['ToDate']."'
                    //     ORDER BY LastEdited ASC
                    // ) AS subquery
                    // WHERE pid NOT IN (SELECT delivery_challan_no FROM sale_invoice)");

                    $out = mysqli_query($GLOBALS['con'],"SELECT * FROM (
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'delivery_challan' AS rtype FROM delivery_challan_details
                        UNION ALL
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'sale_invoice' AS rtype FROM sale_invoice_details
                        UNION ALL
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'production' AS rtype FROM production_details
                        UNION ALL
                        SELECT parent_id AS pid, product, qty, id, LastEdited, 'packaging' AS rtype FROM packaging_details
                        UNION ALL 
                        SELECT parent_id AS pid, product, outqty AS qty, id, LastEdited, 'stock_journal' AS rtype FROM stock_journal_details
                        UNION ALL 
                        SELECT parent_id AS pid, product, lessstock AS qty, id, LastEdited, 'physical_stock' AS rtype FROM physical_stock_details
                        UNION ALL 
                        SELECT parent_id AS pid, product, return_qty AS qty, id, LastEdited, 'grn_return' AS rtype FROM grn_return_details
                    ) AS combined_tables
                    WHERE product = '".$_REQUEST['ledger']."'
                        AND DATE(LastEdited) BETWEEN '".$_SESSION['FromDate']."' AND '".$_SESSION['ToDate']."'
                        AND qty > 0 AND pid NOT IN (SELECT delivery_challan_no FROM sale_invoice)
                    ORDER BY LastEdited ASC;");

                    while ($outward = mysqli_fetch_array($out, MYSQLI_ASSOC)) {
						
						$outwarddata[]=$outward;
					}

                    foreach ($inwarddata as &$indata) {

                        $indata['prefix'] = 'Inward';
                    }
                    unset($indata);

                    foreach ($outwarddata as &$outdata) {

                        $outdata['prefix'] = 'Outward';
                    }
                    unset($outdata);

                    echo "<pre>";

                    if(empty($outwarddata)) {

                        $mergedData = array_merge($inwarddata);
                        usort($mergedData, function($inwarddata) {

                            return strtotime($inwarddata['LastEdited']);
                        });
                    }
                    elseif(empty($inwarddata)) {

                        $mergedData = array_merge($outwarddata);
                        usort($mergedData, function($outwarddata) {

                            return strtotime($outwarddata['LastEdited']);
                        });
                    }
                    else {

                        $mergedData = array_merge($inwarddata, $outwarddata);
                        usort($mergedData, function($inwarddata, $outwarddata) {

                            return strtotime($inwarddata['LastEdited']) - strtotime($outwarddata['LastEdited']);
                        });
                    }

                    foreach ($mergedData as $mdata) {

                        // $date1  = date("Y-m-d", strtotime($mdata['LastEdited']));
                        $date1  = date("d-m-Y", strtotime($mdata['LastEdited']));

                        if($mdata['prefix']==Inward) {

                            $inqty = $mdata['qty'];
                            
                            $totinqty += $inqty;
                            // echo $totinqty;
                            $outqty = '';
                        } else {

                            $outqty = $mdata['qty'];
                            $totoutqty += $outqty;
                            $inqty = '';
                        }


                        $a1=$utilObj->getSingleRow("grn_details","id='".$mdata['id']."' ");
                        $a2=$utilObj->getSingleRow("purchase_invoice_details","id='".$mdata['id']."' ");
                        $a3=$utilObj->getSingleRow("production","id='".$mdata['id']."' ");
                        $a4=$utilObj->getSingleRow("packaging","id='".$mdata['id']."' ");
                        $a5=$utilObj->getSingleRow("delivery_challan_details","id='".$mdata['id']."' ");
                        $a6=$utilObj->getSingleRow("sale_invoice_details","id='".$mdata['id']."' ");
                        $a7=$utilObj->getSingleRow("production_details","id='".$mdata['id']."' ");
                        $a8=$utilObj->getSingleRow("packaging_details","id='".$mdata['id']."' ");

                        $a9=$utilObj->getSingleRow("delivery_return_details","id='".$mdata['id']."' ");
                        $a10=$utilObj->getSingleRow("stock_journal_details","id='".$mdata['id']."' ");
                        $a11=$utilObj->getSingleRow("physical_stock_details","id='".$mdata['id']."' ");
                        $a12=$utilObj->getSingleRow("grn_return_details","id='".$mdata['id']."' ");

                        $test = '';
                        $parti = '';
                        $voutype = '';
                        $voucode = '';

                        if(!empty($a1)) {

                            $test = $a1['parent_id'];
                            $b1=$utilObj->getSingleRow("grn","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b1['supplier']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b1['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b1['grn_code'];
                            $link = 'grn_form1.php?id='.$test.'&PTask=view';
                        } elseif (!empty($a2)) {
 
                            $test = $a2['parent_id'];
                            $b2=$utilObj->getSingleRow("purchase_invoice","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b2['supplier']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b2['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b2['pur_invoice_code'];
                            $link = 'purchase_invoiceform1.php?id='. $test.'&PTask=view';
                        } elseif (!empty($a3)) {

                            $test = $a3['id'];
                            $b3=$utilObj->getSingleRow("production_details","parent_id='".$test."' ");$ledger=$utilObj->getSingleRow("stock_ledger","id='".$b3['product']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b3['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $a3['production_code'];
                            $link = 'production_list.php?id='.$test.'&PTask=view';
                        }  elseif (!empty($a4)) {

                            $test = $a4['id'];
                            $b4=$utilObj->getSingleRow("packaging_details","parent_id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("stock_ledger","id='".$b4['product']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b4['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $a4['pack_code'];
                            $link = 'packaging_list.php?id='.$test.'&PTask=view';
                        } elseif (!empty($a5)) {

                            $test = $a5['parent_id'];
                            $b5=$utilObj->getSingleRow("delivery_challan","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b5['customer']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b5['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = 'Delivery Challan';
                            $voucode = $b5['challan_no'];
                            $link = 'delivery_challan_list.php?id='.$test.'&PTask=view';
                        } elseif (!empty($a6)) {

                            $test = $a6['parent_id'];
                            $b6=$utilObj->getSingleRow("sale_invoice","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b6['customer']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b6['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b6['saleino_code'];
                            $link = 'sale_invoice_list.php?id='.$test.'&PTask=view';
                        } elseif (!empty($a7)) {

                            $test = $a7['parent_id'];
                            $b7=$utilObj->getSingleRow("production","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("stock_ledger","id='".$b7['product']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b7['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b7['production_code'];
                            $link = 'production_list.php?id='.$test.'&PTask=view';
                        }
                        elseif (!empty($a8)) {

                            $test = $a8['parent_id'];
                            $b8=$utilObj->getSingleRow("packaging","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("stock_ledger","id='".$b8['product']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b8['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b8['pack_code'];
                            $link = 'packaging_list.php?id='.$test.'&PTask=view';
                        }
                        elseif (!empty($a9)) {

                            $test = $a9['parent_id'];
                            $b8=$utilObj->getSingleRow("delivery_return","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b8['customer']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b8['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b8['sreturn_code'];
                            $link = 'delivery_challan_return_list.php?id='.$test.'&PTask=view';
                        }
                        elseif (!empty($a10)) {

                            $test = $a10['parent_id'];
                            $b8=$utilObj->getSingleRow("stock_journal","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("employee","id='".$b8['user']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b8['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = 'Stock Journal';
                            $voucode = $b8['record_no'];
                            $link = 'stock_journal_list.php?id='.$test.'&PTask=view';
                        }
                        elseif (!empty($a11)) {

                            $test = $a11['parent_id'];
                            $b8=$utilObj->getSingleRow("physical_stock","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("employee","id='".$b8['user']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b8['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = 'Physical Stock';
                            $voucode = $b8['record_no'];
                            $link = 'physical_stock_list.php?id='.$test.'&PTask=view';
                        }
                        elseif (!empty($a12)) {

                            $test = $a12['parent_id'];
                            $b8=$utilObj->getSingleRow("grn_return","id='".$test."' ");
                            $ledger=$utilObj->getSingleRow("account_ledger","id='".$b8['supplier']."' ");
                            $vtype=$utilObj->getSingleRow("voucher_type","id='".$b8['voucher_type']."' ");
                            $parti = $ledger['name'];
                            $voutype = $vtype['name'];
                            $voucode = $b8['grnreturn_code'];
                            $link = 'grn_return_list.php?id='.$test.'&PTask=view';
                        }
                ?>
                    <tr>
                        <td>
                            <?php echo $date1; ?>
                        </td>
                        <td><?php echo $parti; ?></td>
                        <td><?php echo $voutype; ?></td>
                        <td><a href="<?php echo $link; ?>" target="_blank"><?php echo $voucode; ?></a></td>
                        <td><?php echo $inqty; ?></td>
                        <td><?php echo $outqty; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <?php echo $totinqty; ?>
                        </td>
                        <td>
                            <?php echo $totoutqty; ?>
                        </td>
                    </tr>
                    <?php
                        $clostock = getstocksummary($_REQUEST['ledger'],$_SESSION['FromDate'],$_SESSION['ToDate']);
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            Closing Stock:
                        </td>
                        <td>
                            <?php echo $clostock; ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>

	window.onload=function() {

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

		window.location="stock_statement_report.php?FromDate="+fromdate+"&ToDate="+todate+"&ledger="+ledger+"&Task=filter";
	}

</script>

<?php
	include("footer.php");
?>