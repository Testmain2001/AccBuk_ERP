
 <?php
include 'config.php';

include('lib/phpqrcode/qrlib.php'); 

/* function decryptIt( $q ) {
								$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
								$qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
								return( $qDecoded );
							} */
//include 'noToWords.php';

?>			
<html>
<Style>
 @media print {
                .dontPrint {
                display:none;
                }
				
            }
			
	@page { size: auto;  margin: 3mm; }
</style>
<style>
.rcorners2 
{

    padding: 0px;
   width: 750px;
	
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

<script > 
//alert('hi');
$(document).ready(function(){
//alert('hi');
	  var rowCount = $('#tblData tr').length;
	  var rowCount1 = $('#tblData1 tr').length;
	  var rowCountT=rowCount+rowCount1;
	 //alert(rowCountT);
	  var height=(400-(20*rowCountT));
	 //alert(height);
	
$(".assignht").css('height', height);
	
	});
	
</script>
<center>
<a href="javascript:window.print()" ><button name="myform" value="Print" style=" margin-bottom: 10px; padding:5px; " onclick="" class="dontPrint " ><b>Print</b></button></a>
 <a href="javascript:history.go(-1)" ><button name="cancel" id='cancel' value="Cancel" style="margin-left: 18px; margin-bottom: 10px; padding:5px;" onclick="" class="dontPrint " ><b>Cancel</b></button></a>
 
</center>
 <center>

<div class="rcorners2" >

	<?php
    $tempDir = 'QRcode/'; 
	$product=$utilObj->getSingleRow("stock_ledger","id='".$_REQUEST['id']."'");
	$filename =$product['id'];
	$product_name=$product['name'];
    //$codeContents ="http://192.168.2.101/suprint_offset/login.php?user_name=$username&password=$password"; 
	 $codeContents =$product['id']."#".$product['name']; 
	
	//QRcode::png($codeContents, $tempDir.'.png', QR_ECLEVEL_L, 5);
    QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);


	
 ?>
<div   style="border:1px solid black; width:300px; height:350px ; ">

		<?php echo '<img src="QRcode/'. @$filename.'.png" style="width:300px; height:300px;">'; ?>
		<br>
			
		<table>
			
		<td><span style="font-size:20px;"><b><?php echo  $product['name']; ?></b><span></td>
		<td></td>
			</table>
			

	</div>
					


</div>

</center>
</html>