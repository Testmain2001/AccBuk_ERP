<?php 
session_start();
error_reporting(1);
date_default_timezone_set('Asia/Kolkata');
$username='root';
$password='';
$server='localhost';  

//$con=mysql_connect($server,$username,$password);
//mysql_select_db("orgatma",$con)or die(mysql_error());
$con=mysqli_connect($server ,$username ,$password) or die("Cannot Connect");
mysqli_select_db($con,"accbuk_23");
mysqli_query($con,"SET SESSION sql_mode = '' ");
include 'lib/util.php';
//define('FIREBASE_API_KEY', 'AAAA_wjMF6A:APA91bFlvVltayBIjgZtdhga9E1BT7ci6ZqrP_T0RC6Hg5pElYb7UebNLw-wUJitw76xS6JYEkGIlE2YFSPm8_Aj74Dd3BVUVTuWlqhkV2bW-_LnxgCNAC1qTpsaEdN7vND7WjozG3Ik');

//require_once 'Firebase.php';
//require_once 'push.php';
define('APP_VERSION_CODE','12');
$utilObj=new util();
//$firebase = new Firebase();

function encryptIt( $q ) {
	$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	$qEncoded  = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	return( $qEncoded );
}

function decryptIt( $q ) 
{
	$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	$qDecoded  = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	return( $qDecoded );
}

function checkFile($filenm, $output_dir)
{
	$output_dir = $output_dir."/";
	$temp = str_replace(" ","_",$filenm);
	$temp = str_replace("(","",$temp);
	$temp = str_replace(")","",$temp);	
	if(file_exists("Upload/".$filenm))
	{
		$temp = explode(".",$temp);
		$newfilename = reset($temp)."_".rand(1,999) . '.' .end($temp);
		return checkFile($newfilename);
	}
	else return $temp;
}

// function formatNumber($num) {
	
//     $input = $num;
//     $n1 = $n2 = null;
//     $num = (string)$num;
//     // works for integer and floating as well
//     $n1 = explode('.', $num);
//     $n2 = isset($n1[1]) ? $n1[1] : null;
//     $n1 = number_format($n1[0]);

//     $num = $n2 ? $n1 . '.' . $n2 : $n1;

//     return $num;
// }

function formatNumber($num) {
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3);
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
        $expunit = str_split($restunits, 2);

        for ($i = 0; $i < sizeof($expunit); $i++) {
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ",";
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }

        $thecash = $explrestunits . $lastthree; // Concatenate '.00' to the formatted amount
    } 

    return $thecash;
}

include 'concurrency.php';
include 'stockfunction.php';
include 'functions.php';
// include 'noToWords.php';
?>