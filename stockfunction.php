<?php

function getstock($product,$unit,$date,$id,$location)
{
	
	$product=$product;
	$unit=$unit;
	$date=date('Y-m-d', strtotime($date));
	$id=$id;
	$location=$location;
	// $type=$type; ,$type
	

	// ---------------------------------------------------Inward Stock---------------------------------------------------

	//-------------------GRN-------------------

	$grn_row=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM  grn inner join grn_details on grn.id=grn_details.parent_id WHERE grn_details.product='".$product."' AND grn_details.unit='".$unit."'  AND grn.date<='".$date."'AND grn.location='".$location."' ");
    $rowm=mysqli_fetch_array($grn_row);
    $grn =$rowm['total']; 
	
	//-------------------Purchase Invoice-------------------
	
	$purchase_invoice_row=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM  purchase_invoice inner join purchase_invoice_details on purchase_invoice.id=purchase_invoice_details.parent_id WHERE purchase_invoice_details.product='".$product."' AND purchase_invoice_details.unit='".$unit."' AND purchase_invoice.date<='".$date."' AND purchase_invoice.location='".$location."' AND purchase_invoice.type='Direct_Purchase' ");
    $rowm=mysqli_fetch_array($purchase_invoice_row);
    $purchase_invoice =$rowm['total']; 

	//--------------Production IN Quantity---------------
	$production_in=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM production WHERE production.location='".$location."' AND production.product='".$product."' AND production.unit='".$unit."' AND production.date<='".$date."'AND production.id IN (select parent_id from production_details where 1 )   ");
    $productionrow_in=mysqli_fetch_array($production_in);
	//var_dump($productionrow_in);
	$pquantityr_in =$productionrow_in['total'];

	//--------------Packaging IN Quantity---------------
	$packaging_in=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM packaging WHERE packaging.location='".$location."' AND packaging.product='".$product."' AND packaging.unit='".$unit."' AND packaging.date<='".$date."'AND packaging.id IN (select parent_id from packaging_details where 1 )   ");
    $packagingrow_in=mysqli_fetch_array($packaging_in);
	//var_dump($productionrow_in);
	$packagingqty_in =$packagingrow_in['total'];

	//-------------------Sale Returns-------------
	$sale_return_row=mysqli_query($GLOBALS['con'],"Select sum(rejectedqty) as total FROM  sale_return inner join sale_return_details on sale_return.id=sale_return_details.parent_id WHERE sale_return_details.product='".$product."' AND sale_return_details.unit='".$unit."'  AND sale_return.date<='".$date."'AND sale_return.location='".$location."' ");
    $rowm=mysqli_fetch_array($sale_return_row);
    $sale_return =$rowm['total'];

	

	//---------------- stock_transfer_details IN Quantity------------
	$stock_transfer_in=mysqli_query($GLOBALS['con'],"Select sum( stock_transfer_details.tostock) as total FROM stock_transfer inner join  stock_transfer_details on stock_transfer.id= stock_transfer_details.parent_id WHERE   stock_transfer_details.product='".$product."' AND  stock_transfer_details.unit='".$unit."' AND  stock_transfer_details.parent_id NOT IN ('".$id."') AND stock_transfer.date<='".$date."'  AND stock_transfer_details.location='".$location."'");
    $stock_transfer_row_in=mysqli_fetch_array($stock_transfer_in);
    $stock_transferstock_in =$stock_transfer_row_in['total']; 
	
	//---------------- physical_stock_details IN Quantity------------
	$physical_stock_in=mysqli_query($GLOBALS['con'],"Select sum( physical_stock_details.addstock) as total FROM physical_stock inner join  physical_stock_details on physical_stock.id= physical_stock_details.parent_id WHERE   physical_stock_details.product='".$product."' AND  physical_stock_details.unit='".$unit."'  AND physical_stock.date<='".$date."'  AND physical_stock.location='".$location."'");
    $physical_stock_row_in=mysqli_fetch_array($physical_stock_in);
    $physical_stock_add =$physical_stock_row_in['total'];

	//---------------- stock_journal IN Quantity------------
	$stock_journal_in=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM stock_journal inner join  stock_journal_details on stock_journal.id= stock_journal_details.parent_id WHERE   stock_journal_details.product='".$product."' AND  stock_journal_details.unit='".$unit."'  AND stock_journal.date<='".$date."'  AND stock_journal_details.location='".$location."' AND stock_journal_details.type='production' ");
    $stock_journal_row_in=mysqli_fetch_array($stock_journal_in);
    $stock_journal_add =$stock_journal_row_in['total'];



	// ---------------------------------------------------Outward Stock---------------------------------------------------

	//-------------------GRN Return-------------------

	$grn_return_row=mysqli_query($GLOBALS['con'],"Select sum(return_qty) as total FROM grn_return inner join grn_return_details on grn_return.id=grn_return_details.parent_id WHERE grn_return_details.product='".$product."' AND grn_return_details.unit='".$unit."'  AND grn_return.date<='".$date."'AND grn_return.location='".$location."' ");
    $rowm=mysqli_fetch_array($grn_return_row);
    $grn_return =$rowm['total']; 
	
	//--------------------Delivery Challan------------
	$delivery_sale=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM  delivery_challan inner join delivery_challan_details on delivery_challan.id=delivery_challan_details.parent_id WHERE delivery_challan_details.product='".$product."' AND delivery_challan_details.unit='".$unit."'  AND delivery_challan.date<='".$date."'AND delivery_challan.location='".$location."' ");
    $rowm=mysqli_fetch_array($delivery_sale);
    $delivery =$rowm['total'];

	//-------------------Purchase Returns-------------------
	$purchase_return_row=mysqli_query($GLOBALS['con'],"Select sum(rejectedqty) as total FROM  purchase_return inner join purchase_return_details on purchase_return.id=purchase_return_details.parent_id WHERE purchase_return_details.product='".$product."' AND purchase_return_details.unit='".$unit."'  AND purchase_return.date<='".$date."'AND purchase_return.location='".$location."' ");
    $rowm=mysqli_fetch_array($purchase_return_row);
    $purchase_return =$rowm['total']; 
	
	//----------------Production_details OUT Quantity------------
	
	$production_out=mysqli_query($GLOBALS['con'],"Select sum(production_details.qty) as total FROM production inner join production_details on production.id=production_details.parent_id WHERE  production_details.product='".$product."' AND production_details.unit='".$unit."' AND production_details.parent_id NOT IN ('".$id."') AND production.date<='".$date."'  AND production.location='".$location."'");
    $productionrow_out=mysqli_fetch_array($production_out);
    $pquantityr_out =$productionrow_out['total']; 
	
	//---------------- packaging_details OUT Quantity------------
	$packaging_out=mysqli_query($GLOBALS['con'],"Select sum( packaging_details.qty) as total FROM packaging inner join  packaging_details on packaging.id= packaging_details.parent_id WHERE   packaging_details.product='".$product."' AND  packaging_details.unit='".$unit."' AND  packaging_details.parent_id NOT IN ('".$id."') AND packaging.date<='".$date."'  AND packaging.location='".$location."'");
    $packagingrow_out=mysqli_fetch_array($packaging_out);
    $packagingqty_out =$packagingrow_out['total']; 
	
	
	//---------------------sale invoice-----------
	$sale_invoice_row=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM  sale_invoice inner join sale_invoice_details on sale_invoice.id=sale_invoice_details.parent_id WHERE sale_invoice_details.product='".$product."' AND sale_invoice_details.unit='".$unit."'  AND sale_invoice.date<='".$date."' AND sale_invoice.location='".$location."' AND sale_invoice.id NOT IN('".$id."') AND sale_invoice.type!='Against_delivery' ");
    $rowm=mysqli_fetch_array($sale_invoice_row);
    $sale_invoice =$rowm['total']; 
	
	//--------------stock_transfer OUT Quantity---------------
	$stock_transfer_out=mysqli_query($GLOBALS['con'],"Select sum( stock_transfer_details.tostock) as total FROM stock_transfer inner join  stock_transfer_details on stock_transfer.id= stock_transfer_details.parent_id WHERE   stock_transfer_details.product='".$product."' AND  stock_transfer_details.unit='".$unit."' AND  stock_transfer_details.parent_id NOT IN ('".$id."') AND stock_transfer.date='".$date."'  AND stock_transfer.location='".$location."'");
    $stock_transfer_row_out=mysqli_fetch_array($stock_transfer_out);
    $stock_transferstock_out =$stock_transfer_row_out['total']; 

	//---------------- physical_stock_details Out Quantity------------
	$physical_stock_out=mysqli_query($GLOBALS['con'],"Select sum( physical_stock_details.lessstock) as total FROM physical_stock inner join  physical_stock_details on physical_stock.id= physical_stock_details.parent_id WHERE   physical_stock_details.product='".$product."' AND  physical_stock_details.unit='".$unit."' AND  physical_stock_details.parent_id NOT IN ('".$id."') AND physical_stock.date<='".$date."'  AND physical_stock.location='".$location."' ");
    $physical_stock_row_out=mysqli_fetch_array($physical_stock_out);
    $physical_stock_less =$physical_stock_row_out['total']; 

	//---------------- stock_journal Out Quantity------------
	$stock_journal_out=mysqli_query($GLOBALS['con'],"Select sum(qty) as total FROM stock_journal inner join  stock_journal_details on stock_journal.id= stock_journal_details.parent_id WHERE   stock_journal_details.product='".$product."' AND  stock_journal_details.unit='".$unit."'  AND stock_journal.date<='".$date."'  AND stock_journal_details.location='".$location."' AND stock_journal_details.type='consumption' ");
    $stock_journal_row_out=mysqli_fetch_array($stock_journal_out);
    $stock_journal_less =$stock_journal_row_out['total'];
	
	  
	$remainqty=($purchase_invoice+$pquantityr_in+$sale_return+$packagingqty_in+$stock_transferstock_in+$physical_stock_add+$grn+$stock_journal_add)-($sale_invoice+$pquantityr_out+$purchase_return+$packagingqty_out+$stock_transferstock_out+$physical_stock_less+$grn_return+$delivery+$stock_journal_less);
  
    return round($remainqty,3);
}


// ------------------------------------- Batchwise Stock -------------------------------------
function getbatchstock($id,$product,$date,$location){

	$product=$product;
	$id=$id;
	$date=date('Y-m-d', strtotime($date));
	$location=$location;

	// -----------Purchase Batch-----------
	$purchase_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE id='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' ");
    $rowm=mysqli_fetch_array($purchase_batch);
    $purchase =$rowm['total']; 

	// -----------Purchase Invoice-----------
	// $purchase_invoice_row=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE id='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='purchase_invoice' ");
    // $rowm=mysqli_fetch_array($purchase_invoice_row);
    // $purchase_invoice =$rowm['total']; 

	// -----------Purchase Return-----------
	$purchase_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='purchase_return' ");
    $rowm=mysqli_fetch_array($purchase_return_batch);
    $pur_return =$rowm['total']; 

	//-----------GRN Return-----------
	$grn_return_batch = mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='grn_return' ");
    $rowm=mysqli_fetch_array($grn_return_batch);
    $grn_return =$rowm['total']; 

	// //-----------Sale Batch-----------
	// $sale_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' ");
    // $rowm=mysqli_fetch_array($sale_invoice_batch);
    // $sale_invoice =$rowm['total'];

	//-----------Sale Batch-----------
	$sale_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='sale_invoice' ");
    $rowm=mysqli_fetch_array($sale_invoice_batch);
    $sale_invoice =$rowm['total'];
	
	// ----------- Delivery Challan Batch -----------
	$delivery_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='sale_delivery' ");
    $rowm=mysqli_fetch_array($delivery_batch);
    $deliverychalan =$rowm['total'];

	//-----------Sale Return Batch-----------
	$sale_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='sale_return'");
    $rowm=mysqli_fetch_array($sale_return_batch);
    $sale_return =$rowm['total'];

	//-----------Delivery Return Batch-----------
	$delivery_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='delivery_return'");
    $rowm=mysqli_fetch_array($delivery_return_batch);
    $delivery_return =$rowm['total'];

	//-----------Production OUT Batch-----------
	$productionout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='production_out' ");
    $rowm=mysqli_fetch_array($productionout_batch);
    $production_out =$rowm['total'];

	//-----------Production IN Batch-----------
	$productionin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='production_in' ");
    $rowm=mysqli_fetch_array($productionin_batch);
    $production_in =$rowm['total'];

	//-----------Packaging OUT Batch-----------
	$packagingout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='packaging_out' ");
    $rowm=mysqli_fetch_array($packagingout_batch);
    $packaging_out =$rowm['total'];

	//-----------Packaging IN Batch-----------
	$packagingin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='packaging_in' ");
    $rowm=mysqli_fetch_array($packagingin_batch);
    $packaging_in =$rowm['total'];

	//--------------Batch stock_transfer OUT Quantity ---------------
	$stock_transfer_out=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM sale_batch WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='transfer_batch_out'");
    $stock_transfer_row_out=mysqli_fetch_array($stock_transfer_out);
    $batch_transferstock_out =$stock_transfer_row_out['total']; 


	//--------------Batch stock_transfer IN Quantity ---------------
	$stock_transfer_in=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM sale_batch WHERE purchase_batch='".$id."' AND product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='transfer_batch_in'");
    $stock_transfer_row_in=mysqli_fetch_array($stock_transfer_in);
    $batch_transferstock_in =$stock_transfer_row_in['total'];

	//--------------Batch Physical Stock IN Quantity ---------------
	$physical_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='physical_batch_in'");
    $physical_batch_row_in=mysqli_fetch_array($physical_batch_in);
    $batch_physicalstock_in =$physical_batch_row_in['total'];

	//--------------Batch Physical Stock Out Quantity ---------------
	$physical_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='physical_batch_out'");
    $physical_batch_row_out=mysqli_fetch_array($physical_batch_out);
    $batch_physicalstock_out =$physical_batch_row_out['total'];

	//--------------Batch Stock Journal IN Quantity ---------------
	$stockjournal_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='stockj_batch_in'");
    $stockjournal_batch_row_in=mysqli_fetch_array($stockjournal_batch_in);
    $batch_stockjournal_in =$stockjournal_batch_row_in['total'];

	//--------------Batch Stock Journal Out Quantity ---------------
	$stockjournal_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE purchase_batch='".$id."' AND product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='stockj_batch_out'");
    $stockjournal_batch_row_out=mysqli_fetch_array($stockjournal_batch_out);
    $batch_stockjournal_out =$stockjournal_batch_row_out['total'];

	$remainqty=(($purchase+$sale_return+delivery_return+$batch_transferstock_in+$batch_physicalstock_in+$batch_stockjournal_in+$production_in+$packaging_in)-($sale_invoice+$pur_return+$grn_return+$batch_transferstock_out+$batch_physicalstock_out+$batch_stockjournal_out+$production_out+$packaging_out+$deliverychalan));

    return round($remainqty,3);
}

// ------------------------------------- Location Wise Stock -------------------------------------
function getlocationstock($id,$product,$date,$location) {
	
	if($id!='') {
		$cmd = "parent_id='".$id."' AND";
	} else {
		$cmd = "";
	}

	$product=$product;
	$date=date('Y-m-d', strtotime($date));
	$location=$location;

	// ----------------------------- GRN -----------------------------
	$grn_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE $cmd product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='grn' ");
    $rowm=mysqli_fetch_array($grn_batch);
    $grn = $rowm['total'];

	// ----------------------------- PURCHASE INVOICE -----------------------------
	$purchase_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE $cmd product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='purchase_invoice' ");
    $rowm=mysqli_fetch_array($purchase_invoice_batch);
    $pinvoice_batch = $rowm['total'];

	// ----------------------------- PURCHASE RETURN -----------------------------
 	$purchase_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='purchase_return' ");
    $rowm=mysqli_fetch_array($purchase_return_batch);
    $pur_return =$rowm['total'];

	// ----------------------------- GRN RETURN -----------------------------
	$grn_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='grn_return' ");
    $rowm=mysqli_fetch_array($grn_return_batch);
    $greturn =$rowm['total'];

	// ----------------------------- SALE BATCH -----------------------------
	$sale_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='sale_invoice' ");
    $rowm=mysqli_fetch_array($sale_invoice_batch);
    $sale_invoice =$rowm['total'];

	// ----------- Delivery Challan Batch -----------
	$delivery_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='sale_delivery' ");
    $rowm=mysqli_fetch_array($delivery_batch);
    $deliverychalan =$rowm['total'];

	// ----------------------------- Production OUT Batch -----------------------------
	$productionout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='production_out' ");
    $rowm=mysqli_fetch_array($productionout_batch);
    $production_out =$rowm['total'];

	// ----------------------------- Production IN Batch -----------------------------
	$productionin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='production_in' ");
    $rowm=mysqli_fetch_array($productionin_batch);
    $production_in =$rowm['total'];

	// ----------------------------- Packaging OUT Batch -----------------------------
	$packagingout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='packaging_out' ");
    $rowm=mysqli_fetch_array($packagingout_batch);
    $packaging_out =$rowm['total'];

	// ----------------------------- Packaging IN Batch -----------------------------
	$packagingin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='packaging_in' ");
    $rowm=mysqli_fetch_array($packagingin_batch);
    $packaging_in =$rowm['total'];

	// ----------------------------- Sale Return Batch -----------------------------
	$sale_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='sale_return'");
    $rowm=mysqli_fetch_array($sale_return_batch);
    $sale_return =$rowm['total'];
	
	//-----------Delivery Return Batch-----------
	$delivery_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='delivery_return'");
    $rowm=mysqli_fetch_array($delivery_return_batch);
    $delivery_return =$rowm['total'];

	// ----------------------------- Batch stock_transfer OUT Quantity -----------------------------
	$stock_transfer_out=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM sale_batch WHERE product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='transfer_batch_out'");
    $stock_transfer_row_out=mysqli_fetch_array($stock_transfer_out);
    $batch_transferstock_out =$stock_transfer_row_out['total'];

	// ----------------------------- Batch stock_transfer IN Quantity -----------------------------
	$stock_transfer_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."'   AND date<='".$date."' AND location='".$location."' AND type='transfer_batch_in'");
    $stock_transfer_row_in=mysqli_fetch_array($stock_transfer_in);
    $batch_transferstock_in =$stock_transfer_row_in['total'];

	// ----------------------------- Batch Physical Stock IN Quantity -----------------------------
	$physical_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='physical_batch_in'");
    $physical_batch_row_in=mysqli_fetch_array($physical_batch_in);
    $batch_physicalstock_in =$physical_batch_row_in['total'];

	// ----------------------------- Batch Physical Stock Out Quantity -----------------------------
	$physical_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='physical_batch_out'");
    $physical_batch_row_out=mysqli_fetch_array($physical_batch_out);
    $batch_physicalstock_out =$physical_batch_row_out['total'];

	// ----------------------------- Batch Stock Journal IN Quantity -----------------------------
	$stockjournal_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='stockj_batch_in'");
    $stockjournal_batch_row_in=mysqli_fetch_array($stockjournal_batch_in);
    $batch_stockjournal_in =$stockjournal_batch_row_in['total'];

	// ----------------------------- Batch Stock Journal Out Quantity -----------------------------
	$stockjournal_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<='".$date."' AND location='".$location."' AND type='stockj_batch_out'");
    $stockjournal_batch_row_out=mysqli_fetch_array($stockjournal_batch_out);
    $batch_stockjournal_out =$stockjournal_batch_row_out['total'];

	$remainqty=(($grn+$pinvoice_batch+$production_in+$packaging_in+$sale_return+$delivery_return+$batch_transferstock_in+$batch_physicalstock_in+$batch_stockjournal_in)-($pur_return+$greturn+$sale_invoice+$production_out+$packaging_out+$batch_transferstock_out+$batch_physicalstock_out+$batch_stockjournal_out+$deliverychalan));

	return round($remainqty,3);
	
}

// ------------------------------------- FromDate & ToDate -------------------------------------
function getstocksummary($product,$fromdate,$todate) {

	$product=$product;
	$fromdate=date('Y-m-d', strtotime($fromdate));
	$todate=date('Y-m-d', strtotime($todate));
	// $location=$location;
	

	// ----------------------------- GRN -----------------------------
	$grn_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='grn' ");
    $rowm=mysqli_fetch_array($grn_batch);
    $grn = $rowm['total'];

	// ----------------------------- PURCHASE INVOICE -----------------------------
	$purchase_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='purchase_invoice' ");
    $rowm=mysqli_fetch_array($purchase_invoice_batch);
    $pinvoice_batch = $rowm['total'];

	// ----------------------------- PURCHASE RETURN -----------------------------
 	$purchase_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='purchase_return' ");
    $rowm=mysqli_fetch_array($purchase_return_batch);
    $pur_return =$rowm['total'];

	// ----------------------------- GRN RETURN -----------------------------
	$grn_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='grn_return' ");
    $rowm=mysqli_fetch_array($grn_return_batch);
    $greturn =$rowm['total'];

	// ----------------------------- SALE BATCH -----------------------------
	$sale_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='sale_invoice' ");
    $rowm=mysqli_fetch_array($sale_invoice_batch);
    $sale_invoice =$rowm['total'];

	//-----------Production OUT Batch-----------
	$productionout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='production_out' ");
    $rowm=mysqli_fetch_array($productionout_batch);
    $production_out =$rowm['total'];

	// ----------- Delivery Challan Batch -----------
	$delivery_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."' AND type='sale_delivery' ");
    $rowm=mysqli_fetch_array($delivery_batch);
    $deliverychalan =$rowm['total'];

	//-----------Production IN Batch-----------
	$productionin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."' AND type='production_in' ");
    $rowm=mysqli_fetch_array($productionin_batch);
    $production_in =$rowm['total'];

	//-----------Packaging OUT Batch-----------
	$packagingout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='packaging_out' ");
    $rowm=mysqli_fetch_array($packagingout_batch);
    $packaging_out =$rowm['total'];

	//-----------Packaging IN Batch-----------
	$packagingin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."' AND type='packaging_in' ");
    $rowm=mysqli_fetch_array($packagingin_batch);
    $packaging_in =$rowm['total'];

	//-----------Sale Return Batch-----------
	$sale_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='sale_return'");
    $rowm=mysqli_fetch_array($sale_return_batch);
    $sale_return =$rowm['total'];
	
	//-----------Delivery Return Batch-----------
	$delivery_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='delivery_return'");
    $rowm=mysqli_fetch_array($delivery_return_batch);
    $delivery_return =$rowm['total'];

	// -----------------------------------------------------------------------------------------

	// -------------- Batch stock_transfer OUT Quantity --------------
	$stock_transfer_out=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM sale_batch WHERE product='".$product."'   AND date>='".$fromdate."' AND date<='".$todate."'  AND type='transfer_batch_out'");
    $stock_transfer_row_out=mysqli_fetch_array($stock_transfer_out);
    $batch_transferstock_out =$stock_transfer_row_out['total'];

	// --------------Batch stock_transfer IN Quantity ---------------
	$stock_transfer_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."'   AND date>='".$fromdate."' AND date<='".$todate."'  AND type='transfer_batch_in'");
    $stock_transfer_row_in=mysqli_fetch_array($stock_transfer_in);
    $batch_transferstock_in =$stock_transfer_row_in['total'];

	// --------------Batch Physical Stock IN Quantity ---------------
	$physical_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='physical_batch_in'");
    $physical_batch_row_in=mysqli_fetch_array($physical_batch_in);
    $batch_physicalstock_in =$physical_batch_row_in['total'];

	//--------------Batch Physical Stock Out Quantity ---------------
	$physical_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='physical_batch_out'");
    $physical_batch_row_out=mysqli_fetch_array($physical_batch_out);
    $batch_physicalstock_out =$physical_batch_row_out['total'];

	// -------------- Batch Stock Journal IN Quantity --------------
	$stockjournal_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='stockj_batch_in'");
    $stockjournal_batch_row_in=mysqli_fetch_array($stockjournal_batch_in);
    $batch_stockjournal_in =$stockjournal_batch_row_in['total'];

	//--------------Batch Stock Journal Out Quantity ---------------
	$stockjournal_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date>='".$fromdate."' AND date<='".$todate."'  AND type='stockj_batch_out'");
    $stockjournal_batch_row_out=mysqli_fetch_array($stockjournal_batch_out);
    $batch_stockjournal_out =$stockjournal_batch_row_out['total'];

	$remainqty=(($grn+$pinvoice_batch+$production_in+$packaging_in+$sale_return+$delivery_return+$batch_transferstock_in+$batch_physicalstock_in+$batch_stockjournal_in)-($pur_return+$greturn+$sale_invoice+$production_out+$packaging_out+$batch_transferstock_out+$batch_physicalstock_out+$batch_stockjournal_out+$deliverychalan));

	return round($remainqty,3);
	
}


function gettotalstock($product,$date) {

	$product=$product;
	$date=date('Y-m-d', strtotime($date));

	// ----------------------------- GRN -----------------------------
	$grn_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='grn' ");
    $rowm=mysqli_fetch_array($grn_batch);
    $grn = $rowm['total'];

	// ----------------------------- PURCHASE INVOICE -----------------------------
	$purchase_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='purchase_invoice' ");
    $rowm=mysqli_fetch_array($purchase_invoice_batch);
    $pinvoice_batch = $rowm['total'];

	// ----------------------------- PURCHASE RETURN -----------------------------
 	$purchase_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='purchase_return' ");
    $rowm=mysqli_fetch_array($purchase_return_batch);
    $pur_return =$rowm['total'];

	// ----------------------------- GRN RETURN -----------------------------
	$grn_return_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='grn_return' ");
    $rowm=mysqli_fetch_array($grn_return_batch);
    $greturn =$rowm['total'];

	// ----------------------------- SALE BATCH -----------------------------
	$sale_invoice_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch WHERE product='".$product."' AND date<'".$date."' AND type='sale_invoice' ");
    $rowm=mysqli_fetch_array($sale_invoice_batch);
    $sale_invoice =$rowm['total'];

	// ----------- Production OUT Batch -----------
	$productionout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<'".$date."' AND type='production_out' ");
    $rowm=mysqli_fetch_array($productionout_batch);
    $production_out =$rowm['total'];

	// ----------- Delivery Challan Batch -----------
	$delivery_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<'".$date."' AND type='sale_delivery' ");
    $rowm=mysqli_fetch_array($delivery_batch);
    $deliverychalan =$rowm['total'];

	// ----------- Production IN Batch -----------
	$productionin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date<'".$date."' AND type='production_in' ");
    $rowm=mysqli_fetch_array($productionin_batch);
    $production_in =$rowm['total'];

	// ----------- Packaging OUT Batch -----------
	$packagingout_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."' AND date<'".$date."' AND type='packaging_out' ");
    $rowm=mysqli_fetch_array($packagingout_batch);
    $packaging_out =$rowm['total'];

	// ----------- Packaging IN Batch -----------
	$packagingin_batch=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM  purchase_batch  WHERE product='".$product."' AND date<'".$date."' AND type='packaging_in' ");
    $rowm=mysqli_fetch_array($packagingin_batch);
    $packaging_in =$rowm['total'];

	// ----------- Sale Return Batch -----------
	$sale_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."'   AND date<'".$date."' AND type='sale_return'");
    $rowm=mysqli_fetch_array($sale_return_batch);
    $sale_return =$rowm['total'];
	
	// ----------- Delivery Return Batch -----------
	$delivery_return_batch=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM  sale_batch  WHERE product='".$product."'   AND date<'".$date."' AND type='delivery_return'");
    $rowm=mysqli_fetch_array($delivery_return_batch);
    $delivery_return =$rowm['total'];

	// -------------- Batch stock_transfer OUT Quantity --------------
	$stock_transfer_out=mysqli_query($GLOBALS['con'],"Select sum(quantity) as total FROM sale_batch WHERE product='".$product."'   AND date<'".$date."' AND type='transfer_batch_out'");
    $stock_transfer_row_out=mysqli_fetch_array($stock_transfer_out);
    $batch_transferstock_out =$stock_transfer_row_out['total'];

	// -------------- Batch stock_transfer IN Quantity ---------------
	$stock_transfer_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."'   AND date<'".$date."' AND type='transfer_batch_in'");
    $stock_transfer_row_in=mysqli_fetch_array($stock_transfer_in);
    $batch_transferstock_in =$stock_transfer_row_in['total'];

	// -------------- Batch Physical Stock IN Quantity ---------------
	$physical_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='physical_batch_in'");
    $physical_batch_row_in=mysqli_fetch_array($physical_batch_in);
    $batch_physicalstock_in =$physical_batch_row_in['total'];

	// -------------- Batch Physical Stock Out Quantity ---------------
	$physical_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='physical_batch_out'");
    $physical_batch_row_out=mysqli_fetch_array($physical_batch_out);
    $batch_physicalstock_out =$physical_batch_row_out['total'];

	// -------------- Batch Stock Journal IN Quantity --------------
	$stockjournal_batch_in=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='stockj_batch_in'");
    $stockjournal_batch_row_in=mysqli_fetch_array($stockjournal_batch_in);
    $batch_stockjournal_in =$stockjournal_batch_row_in['total'];

	// -------------- Batch Stock Journal Out Quantity ---------------
	$stockjournal_batch_out=mysqli_query($GLOBALS['con'],"Select sum(batqty) as total FROM purchase_batch WHERE product='".$product."' AND date<'".$date."' AND type='stockj_batch_out'");
    $stockjournal_batch_row_out=mysqli_fetch_array($stockjournal_batch_out);
    $batch_stockjournal_out =$stockjournal_batch_row_out['total'];

	$remainqty=(($grn+$pinvoice_batch+$production_in+$packaging_in+$sale_return+$delivery_return+$batch_transferstock_in+$batch_physicalstock_in+$batch_stockjournal_in)-($pur_return+$greturn+$sale_invoice+$production_out+$packaging_out+$batch_transferstock_out+$batch_physicalstock_out+$batch_stockjournal_out+$deliverychalan));

	return round($remainqty,3);

}



function getbalance($matid,$payid,$date,$utilObj){
	
	$_REQUEST['Matid']=$matid;
	$_REQUEST['PayID']=$payid;
	$_REQUEST['Date']=$date;	
	
	//if($_REQUEST['PayID']==''){ $_REQUEST['PayID']='0000';}

	if($_REQUEST['Matid']=='All'){
		
		//$mname_record=$utilObj->getSum("accounts"," id!='Cash In Hand' ","OBalance");
		
		//PurPay,PurRetPay,SaleRetPay,
		$my_cnddition="1";
		
		
		
		//SalePay
		$sale_condition="1";			
	
		//bank Transfer
		$transfer_condition="1 ";					
			$transfer_condition_to="1";					
	
		
		
	}
	else{
		
		/* $accbal=$utilObj->getSingleRow("accounts","id='".$_REQUEST['Matid']."'");
		$mname_record=$accbal['OBalance']; */
		
		//PurPay,PurRetPay,SaleRetPay,emp_adv,emp_adv_pay
		$my_cnddition="bankid='".$_REQUEST['Matid']."'AND id!='".$_REQUEST['PayID']."' ";
	
		//SalePay
		$sale_condition="bankid='".$_REQUEST['Matid']."' AND id!='".$_REQUEST['PayID']."' ";					
		
		//bank Transfer
		$transfer_condition="account_from='".$_REQUEST['Matid']."' AND id!='".$_REQUEST['PayID']."' ";					
		$transfer_condition_to="account_to='".$_REQUEST['Matid']."' AND id!='".$_REQUEST['PayID']."' ";	
			
	}
	
	$PurchaseDate_cnd="AND paymentdate <='".$_REQUEST['Date']."' ";
	//SalePay,emp_adv,emp_adv_pay
	$payment_date_cnd="AND receiptdate  <='".$_REQUEST['Date']."' ";
	//PurRetPay,SaleRetPay,
	$Date_cnd="AND date <='".$_REQUEST['Date']."' ";
	
	//---Debit---
	
	// return "$my_cnddition  AND paymentdate<='".$_REQUEST['Date']."' AND ClientID='".$_SESSION['Client_Id']."' ";
		$getExp_PurPay=$utilObj->getSum("purchase_payment","$my_cnddition  AND paymentdate<='".$_REQUEST['Date']."' AND ClientID='".$_SESSION['Client_Id']."' ","amt_pay");		
	$gettransfer=$utilObj->getSum("bank_transfer","$transfer_condition $Date_cnd","amt_pay");
	
	//---Credit---	
	$getExp_SalePay=$utilObj->getSum("sale_receipt","$sale_condition AND receiptdate <='".$_REQUEST['Date']."' AND  ClientID='".$_SESSION['Client_Id']."' ","amt_pay");	
	$gettranfer_to=$utilObj->getSum("bank_transfer","$transfer_condition_to $Date_cnd","amt_pay");
	//=========================
	$totalbalance=($getExp_SalePay+$gettranfer_to)
					-($getExp_PurPay+$getExp_SaleRetPay+$gettransfer);
		
	return round($totalbalance,2);	
}

?>