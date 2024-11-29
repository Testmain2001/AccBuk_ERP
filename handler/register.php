<?php
if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					$id=uniqid();
					$arrValue=array('id'=>$id,'name'=>$_REQUEST['compnay_name'],
					'email'=>$_REQUEST['compnay_email'],'mobile'=>$_REQUEST['comp_mobile'],'address'=>$_REQUEST['address'],'gst_no'=>$_REQUEST['gst_no'],
					'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('client', $arrValue);
					
					
					$password = encryptIt($_REQUEST['password']);
					
					
					$arrValue1=array('id'=>uniqid(),'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$id,'name'=>$_REQUEST['owner_name'],
					'email'=>$_REQUEST['email'],'mobile'=>$_REQUEST['mobile'],'password'=>$password,'role'=>1,
					'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'));
					 $insertedId=$utilObj->insertRecord('employee', $arrValue1);
		
		}	
	echo "<script>window.top.location='login.php'</script>";
	}
?>
