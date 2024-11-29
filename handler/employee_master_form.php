<?php
include '../config.php'; 
$utilObj=new util();

if(isset($_REQUEST['PTask']))
	{
        switch($_REQUEST['PTask'])	
		{
			case "Add":
					
				$password = encryptIt($_REQUEST['password']);
				$id=uniqid();
				
				$arrValue=array('id'=>$id,'user'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'name'=>$_REQUEST['name'],'email'=>$_REQUEST['email'],'mobile'=>$_REQUEST['mobile'],'password'=>$password,'role'=>$_REQUEST['role'],'Created'=>date("Y-m-d H:i:s"),'LastEdited'=>date('Y-m-d H:i:s'),'multiloc'=>$_REQUEST['multiloc'] );
				$insertedId=$utilObj->insertRecord('employee', $arrValue);
				// echo $id."##".$_REQUEST['name'];
				
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;


			case "update":
				
				//echo $_REQUEST['LastEdited'];			Concurrency Error Checking
				
				echo $value = concurrencycontrol($utilObj,$_REQUEST['table'],$_REQUEST['LastEdited']);
				if($value>0)
				{
					$Msg = "Concurrency Error Occured"; 
					break;
				} 
				$password = encryptIt($_REQUEST['password']);
				
					$arrValue=array('updateduser'=>$_SESSION['Ck_User_id'],'ClientID'=>$_SESSION['Client_Id'],'name'=>$_REQUEST['name'], 'email'=>$_REQUEST['email'],'mobile'=>$_REQUEST['mobile'],'password'=>$password,'role'=>$_REQUEST['role'], 'LastEdited'=>date('Y-m-d H:i:s'),'multiloc'=>$_REQUEST['multiloc'] );

					$strWhere="id='".$_REQUEST['id']."'";
					$Updaterec=$utilObj->updateRecord('employee', $strWhere, $arrValue);
					
					if($Updaterec) 
					echo $Msg='Record has been Updated Sucessfully! '; 					
			break;	

	
		case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('employee', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;


			
		}	
	}
?>
