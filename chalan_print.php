<?php
include 'config.php';
include 'noToWords.php';
$utilObj=new util();
$record1=$utilObj->getSingleRow("delivery_challan","id='".$_REQUEST["id"]."'");
$record2=$utilObj->getMultipleRow("delivery_challan_details","parent_id='".$record1["id"]."'");
$customer=$utilObj->getSingleRow("account_ledger","id='".$record1["customer"]."' AND group_name=18 group by id");
$client=$utilObj->getSingleRow("client","id='".$record1["ClientID"]."'");



$ttime=date('d-m-Y',strtotime($_REQUEST['tdate']));
 $fmonth=date("F",strtotime($_REQUEST['fdate']));
$fyear=date("Y",strtotime($_REQUEST['fdate']));

$tmonth=date("F",strtotime($_REQUEST['tdate']));
$tyear=date("Y",strtotime($_REQUEST['tdate']));



if($company1['logo']!='')
{
  // $imagelogo="Upload/".$company1['logo']."";
   $imagelogo="<img src='Upload/".$company1['logo']."' style='height:80px;margin-bottom: 10px;'>";
}
else{
   $imagelogo="<img src='images/logo.png' style='height:80px'>";

}
if($company1['sign']!='')
{
	 $imgsrc="<img src='Upload/".$company1['sign']."' style='height:40px'>";

	
}
else{
		$imgsrc="";

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
.rcorners2 
{
	 border: 1px solid ;
    border-radius: 25px;
    border: 0px solid;
    padding: 0px;
    width: 750px;
    
	
}
.head {
    margin:0px;
	float: left;
    border: 0px solid ;
    padding-left: 5px;
    height:10%;
 
}
.ta {
    float: left;
   
    border: 1px solid ;
    padding: 5px;
    height: 100px;
    width: 484px;
}

.head1tb td {
   
    border: 1px solid ;
    padding: 4px 6px 4px 6px;
    height: 15px;
    width: 120px;
	
}
.head1tb{
	margin-top:2px;
}
.head2 {
   
    float: left;
    border: 1px solid ;
    padding: 0px;
    height: 165px;
    width: 100%;
	border-bottom:none;
	border-collapse: collapse;
}


.tblbnk td{
	padding:6px;
}
.mytr td{
	border-bottom:none;
}
.mytr1 td{
	border-top:none;
}

.prodtbl th
{
	border:0.5px solid #00000045;
	border-collapse:collapse
	border-bottom:none;
	border-right:0.5px solid #00000045;
	padding:5px;
	padding-left:10px;
	font-size:16px;
	border-top:none;
	 
}

.prodtbl td{
	border-left:0.5px solid #00000045;
	padding:5px;
	border-right:0.5px solid #00000045;
	font-size:16px;
	
}

.prodtbl1 th
{
	border:0.5px solid #00000045;
	border-collapse:collapse
	border-bottom:none;
	border-right:0.5px solid #00000045;
	padding:5px;
	padding-left:10px;
	font-size:12px;
	border-top:none;
	 
}


.prodtbl1 td{
	
	padding:5px;
	
}

.lastsection
{
	height:10%;
}

.head1
{
	font-size:12px;
}

.tblbnk td
{
	padding:6px;
}
.assignht{
	border-bottom:0.5px solid #00000045;
}


.boder1{border-right:  1px solid #00000045; border-bottom:  1px solid #00000045;
}

.roundshape {
  height: 25px;
  width: 25px;
  border-radius: 47%;
}
.tableborder
{
	border-right:1px solid #00000045;
	border-left:1px solid #00000045;
}

 </style>
 
 <script>
function show_img(){
 if (document.getElementById("lhead").checked){
 document.getElementById("img").innerHTML="<img src='img/header.jpg' width='100%'>";
 }
 else
 {
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
//alert('hi');
$(document).ready(function(){
//alert('hi');
	  var rowCount = $('#tblData tr').length;
	  var rowCount1 = $('#tblData1 tr').length;
	  var rowCountT=rowCount+rowCount1;
	 //alert(rowCountT);
	  var height=(500-(20*rowCountT));
	 //alert(height);
	
$(".assignht").css('height', height);
	
	});
	
	
	
	
	
</script>

<style>
body,table
{
	font-family: Arial, Helvetica, sans-serif;
	font-size:15px;
}
.bankp{
	padding-top:4px;margin:0px
}
</style>
<center>


  <a href="javascript:window.print()" ><button name="myform" value="Print" style="margin-bottom: 5px;" onclick="" class="dontPrint" ><b>Print</b></button></a>

</center>
 <center>

<div class="rcorners2">
 <table id="datatable-buttons"  class="head2 table-striped table-bordered dataTable nowrap" cellspacing="0" width="100%">

<!-- design top
<tr>
	<td  id="color" height="" style=""><img src="images/logo.png" style="width:100px;height:100px"></td>
	
</tr>

design top--->
<table style="width:100%; border-collapse: collapse;font-family: Arial, Helvetica, sans-serif;" id="tblData" class="lastsection">

<tr>
<td style="width:60%">
<tr style="float:left;padding-top:10px">
<td><?php //echo $imagelogo; ?></td>
<td></td>
</tr>
<tr style="padding-top:10px">
<td><b>Registered Office</b><br>
<?php echo wordwrap($client['address'],'40',"<br>\n"); ?>
</td>
<td style="font-size:30px;text-align:center"> <b>Challan</b></td>
</tr>
<tr >
<td style="padding-top:10px"><b>Tele. : </b>+91<?php echo $client['mobile']; ?><br>
</td>
<td></td>
</tr>
<tr style="padding-top:0px">
<td><b>Email: </b><?php echo $client['email']; ?></td>
<td></td>
</tr>
</td>

</tr>






</table>
<table style="width:100%;border-top:3px solid #0000007a;margin-top: 10px;font-family: Arial, Helvetica, sans-serif;" id="tblData" class="lastsection">


</table>
<table style="width:100%;font-family: Arial, Helvetica, sans-serif;" id="tblData" class="lastsection">


<tr style="height:30px;border-right:2px solid #0000007a;">
<td style="width:50%;border-right:2px solid #0000007a;"><b>To,</b>
<br><b><?php echo $customer['mail_nameforprint']; ?></b>, 
<br> <?php echo $customer['address']; ?> <br>
<b>Email :</b><?php echo $customer['mail_emailno'];?><br>
<b>Contact No:</b><?php echo $customer['mail_contactno1'];?><br>


</td>
<td style="width:60%;float:right;"><b>Challan No</b> :&nbsp;&nbsp;<?php echo $record1['challan_no']; ?> <br><br> <b>Challan Date </b>: <?php echo date('d-m-Y',strtotime($record1['date'])); ?>   </td>
</tr>

</table>

<table style="width:100%; border-collapse: collapse;font-family: Arial, Helvetica, sans-serif;" id="tblData" class="lastsection">



</table>
<br>


<table style="width:100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif;border-top:1px solid #00000045;text-align:center;font-size:16px;" id="tblData" class="prodtbl">
<tr style="background-color:#80808096">
<th width="15px;" >Sr.No
</th>
<th width="" >Product - Description | SKU
</th>
<th width="" >Unit
</th>
<th width="" >Qty (Nos)
</th>


</tr>




<?php 
$subamount=0;
 $i=$totaldisc=$totlqty=0;
foreach($record2 as $info){
	//var_dump($info);
	$i++;
	
	$product=$utilObj->getSingleRow("stock_ledger","id='".$info["product"]."'");
	/* $product1=$utilObj->getSingleRow("product_master","id='".$product["parentID"]."'");
	$productamt=$utilObj->getSum("sale_return_mate","srid='".$info["ID"]."'","rate");
 if($product["img"]!='')
   {
	   $productimg="<img src='Upload/".$product['img']."' style='width:50px;'>";
   }
   else
   {
	   $productimg="";
   } */

echo"<tr class='' style='border-bottom:1px solid #00000045;font-family: Arial, Helvetica, sans-serif;'>";
echo "<td ><div style='border-radius: 0;border: 0 solid ;'>".$i."</div></td>";
echo "<td v-align='top' style='text-align:left;'><div style='border-radius: 0;border: 0 solid ;float:left;'>".$productimg."</div><div>"." &nbsp;&nbsp;". $product['name']."</div></td>";
echo "<td><div style='border-radius: 0;border: 0 solid ;'>".$product['unit']."</div></td>";

echo "<td><div style='border-radius: 0;border: 0 solid ;'>".$info['qty']."</div></td>";
echo "</tr>";

}
 
echo "<tr class='assignht' >";
	echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
	echo "<td></td></tr>";	
 
 
 ?>



</table>

<table style="width:100%; border-collapse: collapse;font-family: Arial, Helvetica, sans-serif;border-bottom:1px solid #00000045;" id="tblData" class="lastsection">

<?php //$bankdetail=$utilObj->getSingleRow("accounts","id='".$record["bank"]."'"); 
?>
<tr style="height:30px;">
<td style="border-left:1px solid #00000045; width:65%; padding:15px"><b><br><?php echo $customer['name'];?> </b><br><br>

<span style="">Thanking you and assuring you of our best services at all times.</span><br>
<br>

</td>
<td style="border-right:1px solid #00000045;border-bottom:1px solid #00000045; width:65%;text-align:center"><b><?php echo $customer['name'];?></b>  <br><br>  <?php echo $imgsrc; ?>   <br><br><b>AUTHORISED SIGNATORY</b></td>
</tr>

</table>



</table>


</div>

</center>
</html>

</script>