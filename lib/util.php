<?php
class util
{
    function util (){

    }
	   
    function insertRecord($strTable, $arrValue){

      $strQuery = "   INSERT INTO $strTable (";
      reset($arrValue);
      while(list ($strKey, $strVal) = each($arrValue))
      {
        $strQuery .= $strKey . ",";
        if($strKey=='BookingDate' || $strKey=='payment_date' || $strKey=='Date'){ $date=$strVal; }
      }
      // remove last comma
      $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
      $strQuery .= ") VALUES (";
      reset($arrValue);
      while(list ($strKey, $strVal) = each($arrValue))
      {
        $strQuery .= "'" . $this->fixString($strVal) . "',";
      }
      // remove last comma
      $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);
      $strQuery .= ");";
      $strQuery."<br>";
      // execute query
      //  echo $strQuery; die;
      //echo "<br/>";
      mysqli_query($GLOBALS['con'],$strQuery) or die("Query '$strQuery' failed with error message: \"" . mysqli_error () . '"');
      $last_inserted_id = mysqli_insert_id();
      $this->lastentry($strTable,$date);
      return mysqli_affected_rows($GLOBALS['con']);	
    }
    // end of function
     
    function fixString($strString){        
			$strString = trim($strString);
			$strString = str_replace("'", "''", $strString);
			$strString = str_replace("\'", "'", $strString);
			$strString = str_replace("", ",", $strString);
			$strString = str_replace("\\", "", $strString);
			$strString = str_replace("\"", "&#34;", $strString);
			$strString = str_replace('\"', '"', $strString);
			return $strString;
    }//endof function
	 
     
    function updateRecord($strTable, $strWhere, $arrValue){

                        $strQuery = "	UPDATE $strTable SET ";	
                        reset($arrValue);	
                        while (list ($strKey, $strVal) = each ($arrValue))
                        {
                        $strQuery .= $strKey . "='" . $this->fixString($strVal) . "',";
                        }	
                        // remove last comma
                        $strQuery = substr($strQuery, 0, strlen($strQuery) - 1);	
                        $strQuery .= " WHERE $strWhere;";	
                        // execute query
                        //echo $strQuery;
                        //echo "<br />";
                        //echo"quary fail";
                        mysqli_query($GLOBALS['con'],$strQuery) or die("Query '$strQuery' failed with error message: \"" . mysqli_error () . '"');     
                        return mysqli_affected_rows($GLOBALS['con']);	
                        }
    function deleteRecord($strTable, $strCriteria){
		
                        $strQuery = "DELETE FROM $strTable WHERE $strCriteria";
                        //echo"vcvcvcvc";		
                        mysqli_query($GLOBALS['con'],$strQuery) or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');	
                        return mysqli_affected_rows($GLOBALS['con']);
                        }//endof function
  
    function getClient($tbl){
      $query="SELECT * FROM $tbl";
      $result = $this->ResultSet($query);
      $row=mysqli_fetch_array($result);
      return $row;
    }
    
    function getSingleRow($tbl,$my_cnddition) {
		
      if($tbl=='menu'||$tbl=='submenu'||$tbl=='sub_child'||$tbl=='subsub_child'||$tbl=='states'||$tbl=='group_master'||$tbl=='fixed_vouchertype'||$tbl=='client'||$tbl=='employee'||$tbl=='unit_details'||$tbl=='gst_data'){
        $cnd="$my_cnddition";
      }else{
      $cnd=" ClientID='".$_SESSION['Client_Id']."' AND $my_cnddition  "; 
      }

      $query="SELECT * FROM $tbl WHERE $cnd";
      $result = $this->ResultSet($query);
      $row=mysqli_fetch_array($result);
      return $row;
    }

    function getSingleRow2($tbl,$my_cnddition){

      if($tbl=='menu'||$tbl=='submenu'||$tbl=='states'||$tbl=='group_master'||$tbl=='fixed_vouchertype'||$tbl=='client'||$tbl=='employee'){
        $cnd="$my_cnddition";
      }else{
        $cnd=" $my_cnddition  "; 
      }
      
      $query="SELECT * FROM $tbl WHERE $cnd";
      $result = $this->ResultSet($query);
      $row=mysqli_fetch_array($result);
      return $row;
    }
						
	function getMax($value,$tbl,$my_cnddition) {

    $cnd="$my_cnddition";

    $query="SELECT MAX($value) AS value FROM $tbl WHERE $cnd";
    $result = $this->ResultSet($query);
    $row=mysqli_fetch_array($result);
    return $row['value'];
  }
						
	function getMin($value,$tbl,$my_cnddition){
					
    $cnd="$my_cnddition";

    $query="SELECT MIN($value) AS value FROM $tbl WHERE $cnd";
    $result = $this->ResultSet($query);
    $row=mysqli_fetch_array($result);
    return $row['value'];
  }
                        
    function getMultipleRowAssoc($tbl,$my_cnddition){
	
      $query="SELECT * FROM $tbl WHERE $my_cnddition";
      // echo "$query";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      // $row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_assoc($result)){
        array_push($arr,$row);
      }
      return $arr;
    }
    
    function getMultipleRow($tbl,$my_cnddition){
		
      if($tbl=='menu'||$tbl=='submenu'||$tbl=='sub_child'||$tbl=='subsub_child'||$tbl=='states'||$tbl=='group_master'||$tbl=='fixed_vouchertype'||$tbl=='client'||$tbl=='employee'||$tbl=='unit_details'||$tbl=='gst_data'){
      $query="SELECT * FROM $tbl WHERE $my_cnddition ";
      }else{
        $query="SELECT * FROM $tbl WHERE ClientID='".$_SESSION['Client_Id']."' AND $my_cnddition  ";
        // $query="SELECT * FROM $tbl WHERE  $my_cnddition  ";
      }
  
                  
      // echo $my_cnddition; 
      // echo "<br>";
      // echo $query;

      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      //$row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_array($result)){
        array_push($arr,$row);
      }
      return $arr;
    }

    function getMultipleRow2($tbl,$my_cnddition){

      if($tbl=='menu'||$tbl=='submenu'||$tbl=='states'||$tbl=='group_master'||$tbl=='fixed_vouchertype'||$tbl=='client'||$tbl=='employee'){
        $query="SELECT * FROM $tbl WHERE $my_cnddition ";
      } else {
        // $query="SELECT * FROM $tbl WHERE ClientID='".$_SESSION['Client_Id']."' AND $my_cnddition  ";
        $query="SELECT * FROM $tbl WHERE  $my_cnddition  ";
      }

          
      // echo "$query";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      // $row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_array($result)){
      array_push($arr,$row);
      }
      return $arr;
    }
                               
                        	
    function getAllRows($tbl){
	       
      $query="SELECT * FROM $tbl";

      //echo "$query";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      //$row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_array($result)){
      array_push($arr,$row);
      }
      return $arr;
    }

    function getMultipleRowWithFields($tbl,$my_cnddition,$field){
		 		
      $query="SELECT $field FROM $tbl WHERE $my_cnddition";
      //echo "$query";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      //$row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_array($result)){
      array_push($arr,$row);
      }
      return $arr;
    }	

    function getMultipleRowAssocWithFields($tbl,$my_cnddition,$field){
	 		
      $query="SELECT $field FROM $tbl WHERE $my_cnddition";
      //echo "$query";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      //$row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_assoc($result)){
      array_push($arr,$row);
      }
      return $arr;
    }	

    function getDistinctMultipleRow($tbl,$my_cnddition,$field){
      $query="SELECT distinct($field) FROM $tbl WHERE $my_cnddition";

      $result = $this->ResultSet($query);
      //$row=$this->FetchObject($result);
      $arr = array();
      while($row=mysqli_fetch_array($result)){
      array_push($arr,$row);
      }
      return $arr;
    }

    function getSingleValue($tbl,$my_cnddition,$field){
				
      $query="SELECT $field FROM $tbl WHERE $my_cnddition";
      $result=mysqli_query($GLOBALS['con'],$query) or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      if(mysqli_num_rows($result))
      {
      return $name=mysqli_fetch_assoc($result,0,"$field");
      }

    }
    // endof 	 

    function ResultSet($query){
      //$query=$this->sql_quote($query);
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"');
      return $result;
    }

    function FetchObject($result){
      $row=mysqli_fetch_object($result);
      return $row;
    }

    function getCount($table,$my_cnddition){
	
      $query="SELECT count(*) as total FROM $table WHERE $my_cnddition";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"') ;
      if(mysqli_num_rows($result))
      {
        $row = mysqli_fetch_assoc($result);
        return $row["total"];
        // return $name=mysqli_fetch_assoc($result,0,"total");
      }	
    }
						
    function getSum($table,$my_cnddition,$field){
				
      $query="SELECT SUM($field) AS total FROM $table WHERE $my_cnddition";
      $result=mysqli_query($GLOBALS['con'],$query)  or die("Query '$query' failed with error message: \"" . mysqli_error () . '"') ;
      if(mysqli_num_rows($result))
      {
        $name=mysqli_fetch_assoc($result);
        return $name["total"];
      }
    }
    
    function sendMail($to,$from,$subject,$message){	
                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= "To: $to <".$to.">\r\n";
                        $headers .= "'From: WebsiteBuilder <".$from.">'\r\n";	
                        $message_body = util::getMailHeader();
                        $message_body.= $message;
                        $message_body.= util::getMailFooer();
                        //echo $message_body; 
                        return mail($to,$subject,$message_body,$headers) ;
                        }		
    function getMailHeader(){
                        $header.="<table align='center' width='779'>";	
                        $header.="<tr>";
                        $header.="<td>";
                        $header.="<tr>";
                        $header.="<td >";
                        return $header;	
                        }  
    function getMailFooer(){	
                        $header.="</td>";
                        $header.="</tr>";
                        $header.="<tr>";
                        $header.="<td>";
                        $header.="&nbsp;</td>";
                        $header.="</tr>";
                        $header.="<tr>";
                        $header.="<td>";
                        $header.="&nbsp;</td>";
                        $header.="</tr>";
                        $header.="<tr>";
                        $header.="<td >";
                        $header.="Thanks,";
                        $header.="</td>";
                        $header.="</tr>";
                        $header.="<tr>";
                        $header.="<td>";
                        $header.="&nbsp;</td>";
                        $header.="</tr>";	
                        $header.="<tr>";
                        $header.="<td >";
                        $header.="Site Administrator ";
                        $header.="</td>";
                        $header.="</tr>";	
                        $header.="</table>";
                        return $header;
                        }

 /* function copyFile($FilePath,&$_FILES,$fieldName,$fileTypes=array('image/jpeg', 'image/pjpeg', 'image/png', 'image/gif')){
//print_r($_FILES);		
if (!in_array($_FILES[$fieldName]['type'],$fileTypes)){	
return "INVLAIDFILETYPE";		
}
// check size
if ($_FILES[$fieldName]['size'] > 125542 ){			
return "INVLAIDFILESIZE";		    
}
//security error
if (strstr($_FILES[$fieldName]['name'], "..")!= ""){			
return "SECURITYERROR";			
}                
//echo "it is there";			 
$file_ext_array  = explode(".",$_FILES[$fieldName]['name']);
$file_ext = ".".$file_ext_array[1];
$pic_id = time();
$pic_id = $pic_id."_".$file_ext_array[0];
move_uploaded_file($_FILES[$fieldName]['tmp_name'], $FilePath.$pic_id.$file_ext) or die("Could not copy the file!");			
return $pic_id.$file_ext ;
 }*/
function checkEmail($email){
                        // checks proper syntax
                        if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
                        {
                          return false;
                        }     
                        return true;
                        // gets domain name
                        list($username,$domain)=split('@',$email);
                        // checks for if MX records in the DNS
                        $mxhosts = array();
                        if(!getmxrr($domain, $mxhosts))
                        {
                        // no mx records, ok to check domain
                        if (!fsockopen($domain,25,$errno,$errstr,30))
                        {
                          return false;
                        }
                        else
                        {
                          return true;
                        }
                        }
                        else
                        {
                        // mx records found
                        foreach ($mxhosts as $host)
                        {
                        if (fsockopen($host,25,$errno,$errstr,30))
                        {
                          return true;
                        }
                        }
                        return false;
                        }} 

function getPagerData($numHits, $limit, $page){  
                        $numHits  = (int) $numHits;  
                        $limit    = max((int) $limit, 1);  
                        $page     = (int) $page;  
                        $numPages = ceil($numHits / $limit);  

                        $page = max($page, 1);  
                        $page = min($page, $numPages);  

                        $offset = ($page - 1) * $limit;  

                        $ret = new stdClass;  

                        $ret->offset   = $offset;  
                        $ret->limit    = $limit;  
                        $ret->numPages = $numPages;  
                        $ret->page     = $page;  

                        return $ret;  
                        } 
						
						function lastentry($tblnm,$date){  
						$flg=0;
						if($tblnm=='booking_customer'){ $custstr='Customer Booking Entry';	$flg=1;}
						if($tblnm=='booking_payment'){ $custstr='Customer Booking Payment Entry';	$flg=1;}
						if($tblnm=='customer_refund'){ $custstr='Customer Refund Payment Entry';	$flg=1;}
						if($tblnm=='daily_trans'){ $custstr='Office Expense Payment Entry';	$flg=1;}
						if($tblnm=='income_payment'){ $custstr='Return Payment Entry';	$flg=1;}
						if($tblnm=='inv_detail'){ $custstr='Purchase Invoice Entry';	$flg=1;}
						if($tblnm=='inv_payment'){ $custstr='Purchase Invoice Payment Entry';	$flg=1;}
						if($tblnm=='loan'){ $custstr='Loan Payment Entry';	$flg=1;}
						if($tblnm=='partners_loan'){ $custstr='Partners Loan Payment Entry';	$flg=1;}
						if($tblnm=='po_detail'){ $custstr='Purchase Order Entry';	$flg=1;}
						if($tblnm=='project_payment'){ $custstr='Project Payment Entry';	$flg=1;}
						if($tblnm=='rejected_matrial_detail'){ $custstr='Return Material Entry';	$flg=1;}
						if($tblnm=='rent_property_detail'){ $custstr='Rent Property Entry';	$flg=1;}
						if($tblnm=='rent_property_payment'){ $custstr='Rent Property Payment Entry';	$flg=1;}
						if($tblnm=='salary'){ $custstr='Salary Payment Entry';	$flg=1;}
						if($tblnm=='site_expences'){ $custstr='Site Expense Entry';	$flg=1;}
						if($tblnm=='transfer_detail'){ $custstr='Material Transfer Entry';	$flg=1;}
						if($tblnm=='workorder_detail'){ $custstr='Work Order Entry';	$flg=1;}
						if($tblnm=='workorder_payment'){ $custstr='Wprk Order Payment Entry';	$flg=1;}
						if($flg==1){					
						$str='Last activity on system was '.$custstr.' dated '.date("d-m-Y",strtotime($date)).".";
						mysqli_query($GLOBALS['con'],"Delete From activity where ClientID='".$_SESSION['Client_Id']."'");
						mysqli_query($GLOBALS['con'],"Insert INTO activity(disc,ClientID) VALUES ('".$str."','".$_SESSION['Client_Id']."') ")or die("Query failed with error message: \"" . mysqli_error () . '"') ;;
						}
						}
}
?>