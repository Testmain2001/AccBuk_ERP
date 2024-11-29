<?php 
include '../config.php'; 
$utilObj=new util();
$output_dir="Upload/";

	if(isset($_REQUEST['PTask']))
	{
		switch($_REQUEST['PTask'])	
		{ 
			case "Add": 
				$id=uniqid(); 
				$role=$_REQUEST['role'];
				$menu=trim($_REQUEST['menuselect'],",");
				$createMenu=trim($_REQUEST['createMenu'],",");				
				$editMenu=trim($_REQUEST['editMenu'],",");
				$deleteMenu=trim($_REQUEST['deleteMenu'],",");
				$viewMenu=trim($_REQUEST['viewMenu'],",");
				
				$arrValue=array('id'=>$id,'ClientID'=>$_SESSION['Client_Id'],'created'=>date('Y-m-d H:i:s'),'LastEdited'=>date('Y-m-d H:i:s'),'role'=>$role,'createMenu'=>$createMenu,'editMenu'=>$editMenu,'deleteMenu'=>$deleteMenu,'viewMenu'=>$viewMenu,'menu'=>$menu,);			
				$insertedId=$utilObj->insertRecord('role_master', $arrValue);
				if($insertedId)
				echo $Msg='Record has been Added Sucessfully! ';
			break;
			
			case "update": 
			 
			/*  echo ">>".$_REQUEST['iid']; */
			    $role=$_REQUEST['role'];
				$menu=trim($_REQUEST['menuselect'],",");
				$createMenu=trim($_REQUEST['createMenu'],",");				
				$editMenu=trim($_REQUEST['editMenu'],",");
				$deleteMenu=trim($_REQUEST['deleteMenu'],",");
				$viewMenu=trim($_REQUEST['viewMenu'],",");
				
				$arrValue=array('ClientID'=>$_SESSION['Client_Id'],'LastEdited'=>date('Y-m-d H:i:s'),'role'=>$role,'createMenu'=>$createMenu,'editMenu'=>$editMenu,'deleteMenu'=>$deleteMenu,'viewMenu'=>$viewMenu,'menu'=>$menu,);	
				
				$strWhere="id='".$_REQUEST['id']."' ";
				$Updaterec=$utilObj->updateRecord('role_master', $strWhere, $arrValue); 
				
				if($Updaterec) 
				echo $Msg='Record has been Updated Sucessfully! '; 
			break;
			
			case"delete":	
			$pids=explode(",",$_REQUEST['id']);
			foreach($pids as $pid)
			{
				$strWhere="id='".$pid."' ";
				$Deleterec=$utilObj->deleteRecord('role_master', $strWhere);
			}
			
				echo $Msg='Record has been Deleted Sucessfully! '; 
			break;

			//echo "<script>window.top.location='role_master_list.php?suc=$Msg&savetype=".$_REQUEST['savetype']."'</script>";
		}
	}
?>