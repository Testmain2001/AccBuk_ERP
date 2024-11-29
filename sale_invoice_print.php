<?php
	include 'config.php';
	include 'noToWords.php';
	$utilObj=new util();
	$record1=$utilObj->getSingleRow("sale_invoice","id='".$_REQUEST["id"]."'");
	$record2=$utilObj->getMultipleRow("sale_invoice_details","parent_id='".$record1["id"]."'");
	$delivery_challan=$utilObj->getSingleRow("delivery_challan","id='".$record1["delivery_challan_no"]."'");
	$customer=$utilObj->getSingleRow("account_ledger","id='".$record1["customer"]."' AND group_name=14 group by id");
	$customer_address=$utilObj->getSingleRow("account_ledger_address","id='".$customer["id"]."'");
	$client=$utilObj->getSingleRow("client","id='".$record1["ClientID"]."'");
	$statenm=$utilObj->getSingleRow("states","code='".$customer['mail_state']."'");
	$state= $customer['mail_state']; 

	/* if( $state==21){
		$colspan=6;
	}else{
		$colspan=5;
	} */
	//$company=$utilObj->getSingleRow("create_company","id='".$record["company"]."'");
	//$contactsale=$utilObj->getSingleRow("create_contact","id='".$record["contactperson"]."'");


	//$firm=$utilObj->getSingleRow("firm","ClientID='".$record["ClientID"]."'");
	//$company1=$utilObj->getSingleRow("client","ClientID='".$_SESSION['Client_Id']."'");

	/* $city=$utilObj->getSingleRow("india_districts","id='".$company['district']."'");
	$taluka=$utilObj->getSingleRow("india_districts","id='".$company['taluka']."'");
	//echo $company['state'];
	$state=$utilObj->getSingleRow("india_districts","id='".$company['state']."'");

	$city1=$utilObj->getSingleRow("india_districts","id='".$firm['district']."'");
	$taluka1=$utilObj->getSingleRow("india_districts","id='".$firm['taluka']."'");
	$state1=$utilObj->getSingleRow("india_districts","id='".$firm['state']."'");
	//$atpost=$utilObj->getSingleRow("india_districts","id='".$company['aarea']."'"); */

	$ttime=date('d-m-Y',strtotime($_REQUEST['tdate']));
	$fmonth=date("F",strtotime($_REQUEST['fdate']));
	$fyear=date("Y",strtotime($_REQUEST['fdate']));

	$tmonth=date("F",strtotime($_REQUEST['tdate']));
	$tyear=date("Y",strtotime($_REQUEST['tdate']));

	/* if($firm['QR_code']!='')
	{
	// $imagelogo="Upload/".$company1['logo']."";
	$imageQR="<img src='Upload/".$firm['QR_code']."' style='width: 75px;'>";
	}
	else{
	$imageQR="";

	} */

	if($company1['logo']!='')
	{
		// $imagelogo="Upload/".$company1['logo']."";
		$imagelogo="<img src='Upload/".$company1['logo']."' style='width:200px'>";
	}
	else{
		$imagelogo="";
	}

?>
<html>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<Style>

 	@media print {
		.dontPrint {
			display:none;
		}
	}
</style>

<style>
    .rcorners2 {
        border: 1px solid;
        /* border-radius: 25px; */
        border-radius: 0px;
        padding: 0px;
        width: 750px;
    }

    .head {
        margin: 0px;
        float: left;
        border: 0px solid;
        padding-left: 5px;
        height: 10%;
    }

    .ta {
        float: left;
        border: 1px solid;
        padding: 5px;
        height: 100px;
        width: 484px;
    }

    .head1tb td {
        border: 1px solid;
        padding: 4px 6px 4px 6px;
        height: 15px;
        width: 120px;
    }

    .head1tb {
        margin-top: 2px;
    }

    .head2 {
        float: left;
        border: 1px solid;
        padding: 0px;
        height: 165px;
        width: 100%;
        border-bottom: none;
        border-collapse: collapse;
    }

    .tblbnk td {
        padding: 6px;
    }

    .mytr td {
        border-bottom: none;
    }

    .mytr1 td {
        border-top: none;
    }

    .prodtbl th {
        border: 0.5px solid black;
        border-collapse: collapse;
        border-bottom: none;
        border-right: 0.5px solid black;
        padding: 5px;
        padding-left: 10px;
        font-size: 15px;
        border-top: none;
    }

    .prodtbl td {
        border-left: 0.5px solid black;
        padding: 5px;
        border-right: 0.5px solid black;
        font-size: 15px;
    }

    .prodtbl1 th {
        border: 0.5px solid black;
        border-collapse: collapse;
        border-bottom: none;
        border-right: 0.5px solid black;
        padding: 5px;
        padding-left: 10px;
        font-size: 14px;
        border-top: none;
    }

    .prodtbl1 td {
        border-left: 0.5px solid black;
        padding: 5px;
        border-right: 0.5px solid black;
        font-size: 14px;
    }

    .lastsection {
        height: 10%;
    }

    .head1 {
        font-size: 12px;
    }

    .tblbnk td {
        padding: 6px;
    }

    .assignht {
        border-bottom: 0.5px solid black;
        height: 50px !important;
    }

    .boder1 {
        border-right: 1px solid black;
        border-bottom: 1px solid black;
    }

    .roundshape {
        height: 25px;
        width: 25px;
        border-radius: 47%;
    }

    .tableborder {
        border-right: 1px solid black;
        border-left: 1px solid black;
    }
</style>

 
<script>
    function show_img() {
        if (document.getElementById("lhead").checked) {

            document.getElementById("img").innerHTML="<img src='img/header.jpg' width='100%'>";
        } else {

            document.getElementById("img").innerHTML="<img src=''>";
        }
    }
</script>
<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

<script src="fancybox-master/dist/jquery.fancybox.js">  </script>
<script src="fancybox-master/dist/jquery.fancybox.min.js"></script>

<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>

<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="vendors/jszip/dist/jszip.min.js"></script>
<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
<script src='vendors/pdfmake/build/vfs_fonts.js'></script>

<script > 
    $(document).ready(function() {

        // alert('hi');
        var rowCount = $('#tblData tr').length;
        var rowCount1 = $('#tblData1 tr').length;
        var rowCountT=rowCount+rowCount1;
        var height=(500-(20*rowCountT));
        $(".assignht").css('height', height);
	});
	
</script>

<style>
    body,table
    {
        font-family: Arial, Helvetica, sans-serif;
        font-size:15px;
    }
    .bankp {
        padding-top:4px;margin:0px
    }
</style>

<center>
    <a href="javascript:window.print()" >
        <button name="myform" value="Print" style="margin-bottom: 5px;" onclick="" class="dontPrint" ><b>Print</b></button>
    </a>
</center>
<center>

    <div class="rcorners2">
        <table id="datatable-buttons"  class="head2 table-striped table-bordered dataTable nowrap" cellspacing="0" width="100%">

            <h3>Tax Invoice</h3>
            <span style='width:100%;'><?php echo $imagelogo; ?> </span>

            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;" id="tblData" class="lastsection">
                <tr>
                    <td></td>
                    <td></td>
                    <td><label style="text-align: right;">Original For Buyer</label></td>
                </tr>
                
                <tr>
                    <td rowspan="3" style="font-size: 15px; text-align: left; width: 30%; border: 1px solid black;">
                        <b style="margin-right: 0;"><?php echo $client['name']; ?></b><br>
                        <b style="margin-left: 0;">Address: </b><span style="margin-left: 0;"><?php echo wordwrap($client['address'], '40', "<br>\n"); ?></span><br>
                        <b style="margin-left: 0;">GSTIN/UIN: </b><?php echo $client['gstno']; ?><br>
                        <b style="margin-left: 0;">State Name: </b>Maharashtra<br>
                        <b style="margin-left: 0;">Tele.: </b>+91<?php echo $client['mobile']; ?><br>
                        <b style="margin-left: 0;">Email: </b><?php echo $client['email']; ?>
                    </td>
                    
                    <td style="width: 20%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Invoice No:<br> 
                        <b style="margin-left: 0;"><?php echo $record1['saleino_code']; ?></b>
                    </td>
                    
                    <td style="width: 50%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Dated:<br>
                        <b style="margin-left: 0;"><?php echo date('d-m-Y', strtotime($record1['date'])); ?></b>
                    </td>
                </tr>

                <tr>
                    <td style="width: 20%; border: 1px solid black; text-align: left; vertical-align: top;"></td>
                    <td style="width: 50%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Mode/Terms of Payment<br>
                        <b style="margin-left: 0;"><?php echo "Advance"; ?></b>
                    </td>
                </tr>

                <tr>
                    <td style="width: 20%; border: 1px solid black; text-align: left; vertical-align: top;"></td>
                    <td style="width: 50%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Mode/Terms of Payment<br>
                        <b style="margin-left: 0;"><?php echo "Advance"; ?></b>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="3" style="font-size: 15px; text-align: left; width: 30%; border: 1px solid black;"> 
                        <span style="margin-right: 0;">Buyer,</span><br>
                        <b style="margin-right: 0;"><?php echo $customer['mail_nameforprint']; ?></b><br>
                        <span style="margin-left: 0;"><?php echo $customer_address['address']; ?></span><br>
                        <b style="margin-left: 0;">GSTIN/UIN: </b><?php echo $customer['mail_gstno']; ?><br>
                        <b style="margin-left: 0;">State Name: </b><?php echo $statenm['name']; ?>,<b style="margin-left: 0;"> Code: </b><?php echo $customer['mail_pin']; ?><br>
                    </td>
<!-- 					
					<td style="width: 20%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Invoice No:<br> 
                        <b style="margin-left: 0;"><?php echo $record1['saleino_code']; ?></b>
                    </td>
                    
                    <td style="width: 50%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Dated:<br>
                        <b style="margin-left: 0;"><?php echo date('d-m-Y', strtotime($record1['date'])); ?></b>
                    </td> -->
                </tr>

				<!-- <tr>
                    <td style="width: 20%; border: 1px solid black; text-align: left; vertical-align: top;"></td>
                    <td style="width: 50%; border: 1px solid black; text-align: left; vertical-align: top;">
                        Mode/Terms of Payment<br>
                        <b style="margin-left: 0;"><?php echo "Advance"; ?></b>
                    </td>
                </tr> -->
            </table>


            <table style="width:100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; border-top:1px solid #00000045; text-align:center; font-size:16px;" id="tblData" class="prodtbl">
                <tr style="background-color:#80808096">
                    <th width="15px;">Sr.No</th>
                    <th>Description of Good</th>
                    <th>HSN/SAC</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Per</th>
                    <th>Amount</th>
                </tr>

                <?php 
                $subamount = 0;
                $i = $totaldisc = $totlqty = 0;

                foreach ($record2 as $info) {
                    $i++;
                    $tcgst += $info['cgst']; 
                    $tigst += $info['igst']; 
                    $tsgst += $info['sgst']; 
                    
                    $taxamtgst = $tcgst + $tigst + $tsgst;
                    $grandtotall = $record['grandtotal'] + $taxamtgst;

                    $product = $utilObj->getSingleRow("stock_ledger", "id='" . $info["product"] . "'");

                    $totalqty += $info['qty'];
                    $subtotal += ($info['qty'] * $info['rate']);
                    
                    $percentagecgst = $info['cgst'];
                    $percentagesgst = $info['sgst'];
                    $percentageigst = $info['igst'];
                    $totalamt = $info['rate'] * $info['qty'];

                    $new_amt_cgst = ($percentagecgst / 100) * $totalamt;
                    $new_amt_sgst = ($percentagesgst / 100) * $totalamt;
                    $new_amt_igst = ($percentageigst / 100) * $totalamt;

                    $taxperc[$percentagecgst]['perccgst'] = $percentagecgst;
                    $taxperc[$percentagecgst]['amountcgst'] += $new_amt_cgst;
                    
                    $taxperc[$percentagecgst]['percsgst'] = $percentagesgst;
                    $taxperc[$percentagecgst]['amountsgst'] += $new_amt_sgst;
                    
                    $taxperc[$percentagecgst]['percigst'] = $percentageigst;
                    $taxperc[$percentagecgst]['amountigst'] += $new_amt_igst;
                        
                    $new_amt_cgst_total += $new_amt_cgst;
                    $new_amt_sgst_total += $new_amt_sgst;
                    $new_amt_igst_total += $new_amt_igst;
                    
                    $taxableTotal += $totalamt;
                    
                    $grandT = $totalamt + $new_amt_cgst + $new_amt_sgst + $new_amt_igst;
                ?>
                    <tr class='' style='font-family: Arial, Helvetica, sans-serif;'>
                        <td><div style=''><?php echo $i; ?></div></td>
                        <td v-align='top' style='text-align:left;'><div><?php echo wordwrap($product['name'] . " " . $product['unit'], 35, "<br>\n"); ?></div></b></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $product1['hsn_sac']; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $info['qty']; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $info['rate']; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $product['unit']; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo round($totalamt, 2); ?></div></td>
                    </tr>
                    <?php $tot_grand += round($grandT, 2); ?>
                    <tr style=''>
                        <td colspan='1' style=''></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>

                <?php
                foreach ($taxperc as $info) {
                    $i++;
                ?>
                    <tr style=''>
                        <td colspan='1' style=''></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <?php
                    $percentagecgst = $info['perccgst'];
                    $new_amt_cgst = $info['amountcgst'];
                    $percentagesgst = $info['percsgst'];
                    $new_amt_sgst = $info['amountsgst'];
                    $percentageigst = $info['percigst'];
                    $new_amt_igst = $info['amountigst'];
                    
                    if ($percentagecgst != 0) { ?>
                        <tr style=''>
                            <td colspan='1' style=''></td>
                            <td style='text-align:right'><b>Out Put CGST @<?php echo $percentagecgst; ?>%</b></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $percentagecgst; ?></td>
                            <td>%</td>
                            <td><?php echo $new_amt_cgst; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($percentagesgst != 0) { ?>
                        <tr style=''>
                            <td colspan='1' style=''></td>
                            <td style='text-align:right'><b>Out Put SGST @<?php echo $percentagesgst; ?>%</b></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $percentagesgst; ?></td>
                            <td>%</td>
                            <td><?php echo $new_amt_sgst; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($percentageigst != 0) { ?>
                        <tr style=''>
                            <td colspan='1' style=''></td>
                            <td style='text-align:right'><b>Out Put IGST @<?php echo $percentageigst; ?>%</b></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $percentageigst; ?></td>
                            <td>%</td>
                            <td><?php echo $new_amt_igst; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                <tr style='border-bottom:1px solid black; border-top:1px solid black;'>
                    <td colspan='1' style='border:1px solid black;'></td>
                    <td><b>Total</b></td>
                    <td></td>
                    <td><b><?php echo $totalqty . " " . $product['unit']; ?></b></td>
                    <td></td>
                    <td><b><?php // round($taxableTotal, 2); ?></b></td>
                    <td><b><?php echo round($tot_grand, 2); ?></b></td>
                </tr>

                <?php if ($record['tcs_tds_amt'] != 0) { ?>
                    <tr style='border-bottom:1px solid black; border-top:1px solid black;'>
                        <td colspan='1' style='border:1px solid black;'></td>
                        <td><b><?php echo $record['tcs_tds'] . " @ " . $record['tcs_tds_percen'] . "%"; ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b><?php // round($taxableTotal, 2); ?></b></td>
                        <td><b><?php echo round($record['tcs_tds_amt'], 2); ?></b></td>
                    </tr>

                    <tr style='border-bottom:1px solid black; border-top:1px solid black;'>
                        <td colspan='1' style='border:1px solid black;'></td>
                        <td><b>Grand Total</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b><?php echo round(($tot_grand + $record['tcs_tds_amt']), 2); ?></b></td>
                    </tr>
                <?php } ?>
            </table>

            <table class="" style=" width:100%;float:left;font-size:16px;border-right: 1px solid black;
                border-left: 1px solid black;"><span style="float:left">
                <tr>
                    <td>
                        <center>
                            <b>Tax Details</b>
                        </center>
                    </td>
                </tr>
            </table>

            <table style="width:100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; border-top:1px solid black; text-align:center; font-size:12px !important;" id="tblData" class="prodtbl1">
                <tr style="background-color:">
                    <th rowspan="2">HSN/SAC</th>
                    <th rowspan="2">Taxable Value</th>
                    <?php if ($state == 21) { ?>
                        <th colspan="2">CGST</th>
                        <th colspan="2">SGST</th>
                    <?php } else { ?>
                        <th colspan="2">IGST</th>
                    <?php } ?>
                    <th rowspan="2">Total Tax Amount</th>
                </tr>
                <tr style="background-color:">
                    <?php if ($state == 21) { ?>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    <?php } else { ?>
                        <th>Rate</th>
                        <th>Amount</th>
                    <?php } ?>
                </tr>

                <?php 
                $subamount = 0;
                $i = $totaldisc = $totlqty = 0;

                foreach ($record2 as $info) {
                    $i++;
                    $tcgst += $info['cgst']; 
                    $tigst += $info['igst']; 
                    $tsgst += $info['sgst']; 

                    $taxamtgst = $tcgst + $tigst + $tsgst;
                    $grandtotall = $record['grandtotal'] + $taxamtgst;

                    $totalqty += $info['qty'];
                    $subtotal += ($info['qty'] * $info['rate']);
                    
                    $percentagecgst = $info['cgst'];
                    $percentagesgst = $info['sgst'];
                    $percentageigst = $info['igst'];
                    $totalamt = $info['rate'] * $info['qty'];

                    $new_amt_cgst1 = ($percentagecgst / 100) * $totalamt;
                    $new_amt_sgst1 = ($percentagesgst / 100) * $totalamt;
                    $new_amt_igst1 = ($percentageigst / 100) * $totalamt;

                    $new_amt_cgst_total1 += $new_amt_cgst1;
                    $new_amt_sgst_total1 += $new_amt_sgst1;
                    $new_amt_igst_total1 += $new_amt_igst1;

                    $taxableTotal1 += $totalamt;

                    $grandT = $new_amt_cgst1 + $new_amt_sgst1 + $new_amt_igst1;
                ?>

                <tr class='' style='border-bottom:1px solid black; font-family: Arial, Helvetica, sans-serif;'>
                    <td><div style='border-radius: 0; border: 0 solid;'><?php echo $product1['hsn_sac']; ?></div></td>
                    <td><div style='border-radius: 0; border: 0 solid;'><?php echo round($totalamt, 2); ?></div></td>

                    <?php if ($state == 21) { ?>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $percentagecgst . " %"; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $new_amt_cgst1; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $percentagesgst . "%"; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $new_amt_sgst1; ?></div></td>
                    <?php } else { ?>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $percentageigst . "%"; ?></div></td>
                        <td><div style='border-radius: 0; border: 0 solid;'><?php echo $new_amt_igst1; ?></div></td>
                    <?php } ?>

                    <td><div style='border-radius: 0; border: 0 solid;'><?php echo round($grandT, 2); ?></div></td>
                </tr>

                <?php $tot_grand1 += round($grandT, 2); ?>
                <?php } ?>
                
                <tr style='border-bottom:1px solid black;'>
                    <td colspan='1' style='border:1px solid black;'><b>Total</b></td>
                    <td><b><?php echo $taxableTotal1; ?></b></td>

                    <?php if ($state == 21) { ?>
                        <td></td>
                        <td><b><?php echo $new_amt_cgst_total1; ?></b></td>
                        <td></td>
                        <td><b><?php echo $new_amt_sgst_total1; ?></b></td>
                    <?php } else { ?>
                        <td></td>
                        <td><b><?php echo $new_amt_igst_total1; ?></b></td>
                    <?php } ?>

                    <td><b><?php echo round($tot_grand1, 2); ?></b></td>
                </tr>
            </table>

            <table style="width: 100%; float: left; font-size: 16px; border-right: 1px solid black; border-left: 1px solid black;">
                <tr>
                    <td>
                        <span style="float: left;">Amount Chargeable (in words)</span><br>
                        <strong>INR <?php echo noToWords(round($record1['grandtotal'])); ?></strong>
                    </td>
                </tr>
            </table>

            <table style="float: left; width: 100%; border: 1px solid black;">
                <tr>
                    <td style="width: 69%; padding: 10px; vertical-align: top;">
                        <span><b>Company Bank Details:</b> <?php echo $company1['bankname']; ?></span><br>
                        <span><b>Account No:</b> <?php echo $company1['accountnumber']; ?></span><br>
                        <span><b>IFSC Code:</b> <?php echo $company1['IFSC']; ?></span><br>
                        <span><b>MICR Code:</b> <?php echo $company1['MICR']; ?></span><br>
                        <span><b>Branch Name:</b> <?php echo $company1['branchname']; ?></span><br>
                        <span style="margin-bottom: 25px;">Company's PAN: <b><?php echo $company1['PIN']; ?></b></span><br>
                        <span style="margin-bottom: 25px;">Declaration:</span><br>
                        <span style="margin-bottom: 25px;">We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</span><br>
                    </td>

                    <td style="width: 40%; border-left: 1px solid black; vertical-align: top; padding-top: 20px;">
                        <center><b>FOR <?php echo $company1['name']; ?></b></center>
                        <br><br><br><br><br>
                        <center><b>AUTHORISED SIGNATORY</b></center>
                    </td>
                </tr>
            </table>

        </table>
    </div>
</center>
</html>

</script>