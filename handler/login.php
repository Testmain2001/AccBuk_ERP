<?php 
unset($_SESSION['Ck_User_Login']);
unset($_SESSION['Client_Id']);
$utilObj=new util();


if(isset($_REQUEST['username']) || isset($_REQUEST['password']))
{
	
	$pswrd = encryptIt($_REQUEST['password']);
	$Get_Login1=$utilObj->getSingleRow('employee',"mobile='".$_REQUEST['username']."' ");	
	//var_dump($Get_Login1);die;
	if($Get_Login1['mobile']!='')
	{		
			
		if($Get_Login1['password']==$pswrd)
		{	

			$_SESSION['Client_Id']=$Get_Login1['ClientID'];
			$_SESSION['Ck_User_id']=$Get_Login1['id'];
			$_SESSION['Ck_User_Login']=$Get_Login1['mobile'];	
			$_SESSION['Ck_User_role']=$Get_Login1['role'];
			$_SESSION['Role']='Admin';
			$_SESSION['Client_name']=$Get_Login1['name'];
			
			$Get_Login=$utilObj->getSingleRow('role_master',"id='".$Get_Login1['role']."'  ");

			//Create
			$create=explode(',',$Get_Login['createMenu']);
			
			$create_array=array();
			foreach($create as $create1)
			{
				$getsub = $utilObj->getSingleRow("submenu","id='".$create1."' ");
				if($getsub!=''){
					array_push($create_array,$getsub['page']);
					if($getsub['subpage']!=''){
						$subpage=explode(',',$getsub['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($create_array,$sub_page);
						}
					}				
				}

				$getsub2 = $utilObj->getSingleRow("sub_child","id='".$create1."' ");
				if($getsub2!=''){
					array_push($create_array,$getsub2['page']);
					if($getsub2['subpage']!=''){
						$subpage=explode(',',$getsub2['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($create_array,$sub_page);
						}
					}				
				}

				$getsub3 = $utilObj->getSingleRow("subsub_child","id='".$create1."' ");
				if($getsub3!=''){
					array_push($create_array,$getsub3['page']);
					if($getsub3['subpage']!=''){
						$subpage=explode(',',$getsub3['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($create_array,$sub_page);
						}
					}				
				}

				$getsub1 = $utilObj->getSingleRow("menu","id='".$create1."' ");
				if($getsub1!=''){
					array_push($create_array,$getsub1['page']);
					if($getsub1['subpage']!=''){
						$subpage=explode(',',$getsub1['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($create_array,$sub_page);
						}
					}	
				}
			}
			$_SESSION['Ck_User_create_array']=$create_array;
		
		
			// Edit
			$edit=explode(',',$Get_Login['editMenu']);
			
			$edit_array=array();
			foreach($edit as $edit1)
			{
				$getsub = $utilObj->getSingleRow("submenu","id='".$edit1."' ");
				if($getsub!=''){
					array_push($edit_array,$getsub['page']);
					if($getsub['subpage']!=''){
						$subpage=explode(',',$getsub['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($edit_array,$sub_page);
						}
					}	
				}

				$getsub2 = $utilObj->getSingleRow("sub_child","id='".$edit1."' ");
				if($getsub2!=''){
					array_push($edit_array,$getsub2['page']);
					if($getsub2['subpage']!=''){
						$subpage=explode(',',$getsub2['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($edit_array,$sub_page);
						}
					}				
				}

				$getsub3 = $utilObj->getSingleRow("subsub_child","id='".$edit1."' ");
				if($getsub3!=''){
					array_push($edit_array,$getsub3['page']);
					if($getsub3['subpage']!=''){
						$subpage=explode(',',$getsub3['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($edit_array,$sub_page);
						}
					}				
				}

				$getsub1 = $utilObj->getSingleRow("menu","id='".$edit1."' ");
				if($getsub1!=''){
					array_push($edit_array,$getsub1['page']);
					if($getsub1['subpage']!=''){
						$subpage=explode(',',$getsub1['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($edit_array,$sub_page);
						}
					}
				}
			}
			$_SESSION['Ck_User_edit_array']=$edit_array;
			
			
			//Delete
			$delete=explode(',',$Get_Login['deleteMenu']);
			
			$delete_array=array();
			foreach($delete as $delete1)
			{
				$getsub = $utilObj->getSingleRow("submenu","id='".$delete1."' ");
				if($getsub!=''){
					array_push($delete_array,$getsub['page']);
					if($getsub['subpage']!=''){
						$subpage=explode(',',$getsub['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($delete_array,$sub_page);
						}
					}
				}

				$getsub2 = $utilObj->getSingleRow("sub_child","id='".$delete1."' ");
				if($getsub2!=''){
					array_push($delete_array,$getsub2['page']);
					if($getsub2['subpage']!=''){
						$subpage=explode(',',$getsub2['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($delete_array,$sub_page);
						}
					}				
				}

				$getsub3 = $utilObj->getSingleRow("subsub_child","id='".$delete1."' ");
				if($getsub3!=''){
					array_push($delete_array,$getsub3['page']);
					if($getsub3['subpage']!=''){
						$subpage=explode(',',$getsub3['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($delete_array,$sub_page);
						}
					}				
				}

				$getsub1 = $utilObj->getSingleRow("menu","id='".$delete1."' ");
				if($getsub1!=''){
					array_push($delete_array,$getsub1['page']);
					if($getsub1['subpage']!=''){
						$subpage=explode(',',$getsub1['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($delete_array,$sub_page);
						}
					}
				}
			}
			$_SESSION['Ck_User_delete_array']=$delete_array;
			

			//Print
			$print=explode(',',$Get_Login['printMenu']);
			
			$print_array=array();
			foreach($print as $print1)
			{
				$getsub = $utilObj->getSingleRow("submenu","id='".$print1."' ");
				if($getsub!=''){
					array_push($print_array,$getsub['page']);
					if($getsub['subpage']!=''){
						$subpage=explode(',',$getsub['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($print_array,$sub_page);
						}
					}
				}

				$getsub2 = $utilObj->getSingleRow("sub_child","id='".$print1."' ");
				if($getsub2!=''){
					array_push($print_array,$getsub2['page']);
					if($getsub2['subpage']!=''){
						$subpage=explode(',',$getsub2['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($print_array,$sub_page);
						}
					}				
				}

				$getsub3 = $utilObj->getSingleRow("subsub_child","id='".$print1."' ");
				if($getsub3!=''){
					array_push($print_array,$getsub3['page']);
					if($getsub3['subpage']!=''){
						$subpage=explode(',',$getsub3['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($print_array,$sub_page);
						}
					}				
				}

				$getsub1 = $utilObj->getSingleRow("menu","id='".$print1."' ");
				if($getsub1!=''){
					array_push($print_array,$getsub1['page']);
					if($getsub1['subpage']!=''){
						$subpage=explode(',',$getsub1['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($print_array,$sub_page);
						}
					}
				}
			}
			$_SESSION['Ck_User_print_array']=$print_array;
			
			
			//View
			$view=explode(',',$Get_Login['viewMenu']);
			
			$view_array=array();
			foreach($view as $view1)
			{
				$getsub = $utilObj->getSingleRow("submenu","id='".$view1."' ");
				if($getsub!=''){
					array_push($view_array,$getsub['page']);
					if($getsub['subpage']!=''){
						$subpage=explode(',',$getsub['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($view_array,$sub_page);
						}
					}
				}

				$getsub2 = $utilObj->getSingleRow("sub_child","id='".$view1."' ");
				if($getsub2!=''){
					array_push($view_array,$getsub2['page']);
					if($getsub2['subpage']!=''){
						$subpage=explode(',',$getsub2['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($view_array,$sub_page);
						}
					}				
				}

				$getsub3 = $utilObj->getSingleRow("subsub_child","id='".$view1."' ");
				if($getsub3!=''){
					array_push($view_array,$getsub3['page']);
					if($getsub3['subpage']!=''){
						$subpage=explode(',',$getsub3['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($view_array,$sub_page);
						}
					}				
				}

				$getsub1 = $utilObj->getSingleRow("menu","id='".$view1."' ");
				if($getsub1!=''){
					array_push($view_array,$getsub1['page']);
					if($getsub1['subpage']!=''){
						$subpage=explode(',',$getsub1['subpage']);
						foreach($subpage as $sub_page)
						{					
							array_push($view_array,$sub_page);
						}
					}
				}
			}
			$_SESSION['Ck_User_view_array']=$view_array;

			//print_r($create_array);print_r($edit_array);print_r($delete_array);
			//print_r($print_array);print_r($view_array);die();
			
			$menus=$Get_Login['menu'];
			$menuid=explode(",",$menus);
			
			$createMenu=$Get_Login['createMenu'];
			$create_Menu=explode(",",$createMenu);
			
			$editMenu=$Get_Login['editMenu'];
			$edit_Menu=explode(",",$editMenu);
			
			$deleteMenu=$Get_Login['deleteMenu'];
			$delete_Menu=explode(",",$deleteMenu);
			
			$printMenu=$Get_Login['printMenu'];
			$print_Menu=explode(",",$printMenu);
			
			$viewMenu=$Get_Login['viewMenu'];
			$view_Menu=explode(",",$viewMenu);
			
			//print_r(array_merge($create_Menu,$edit_Menu,$delete_Menu,$print_Menu,$view_Menu,$menuid));	
			//print_r($menuid);
			
			$menuid=array_merge($create_Menu,$edit_Menu,$delete_Menu,$print_Menu,$view_Menu,$menuid);
			$mid = "";
			$smid = "";
			foreach($menuid as $mn) {

				// echo "<br>".$mn;
				if(substr( $mn, 0, 2 )=='s_'){
					$getsub = $utilObj->getSingleRow("submenu","id='".$mn."' ");
					$mid .= "'".$getsub['mid']."',"; 
					$smid .= "'".$mn."',"; 
				}else if(substr( $mn, 0, 3 )=='ss_'){
					$get_sub = $utilObj->getSingleRow2("sub_child","id='".$mn."' ");
					$smid .= "'".$get_sub['mid']."',"; 
					$chmid .= "'".$mn."',"; 
				}else if(substr( $mn, 0, 4 )=='sss_'){
					// echo $mn;
					$get_sub1 = $utilObj->getSingleRow2("subsub_child","id='".$mn."' ");
					$smid .= "'".$get_sub1['mid']."',"; 
					$sbmid .= "'".$mn."',"; 
				}else{
					$mid .= "'".$mn."',"; 
				}
				
			}
			$mid = trim($mid, ","); 
			$smid = trim($smid, ",");
			$chmid = trim($chmid, ",");
			$sbmid = trim($sbmid, ",");



			$menu=$utilObj->getMultipleRow("menu","id in (".$mid.") order by id ASC");
					
			//print_r($menuid);
			$arr_menu = array();
			$arr_header = array();
			$i=0;
			foreach($menu as $e_rec) {

			if($e_rec['page']=='') {

				//if($rows['locationID']==$e_rec["id"]) echo $select='selected'; else $select='';
				
				$submenu=$utilObj->getMultipleRow("submenu","id in (".$smid.") AND mid='".$e_rec['id']."'  order by sequenceid ");
				$arr_sub = array();
				foreach($submenu as $e_rec1) {

					if($e_rec1['page']!="") {

						$arr_menu[$i] = $e_rec1['page']; 

						if($e_rec1['subpage']!=''){
							$subpage=explode(',',$e_rec1['subpage']);
							foreach($subpage as $subpage1)
							{
								$i++;
								$arr_menu[$i] = $subpage1; 
							}
						}

						$arr_sub[$e_rec1['name']] =  $e_rec1['page'];
						$i++;
					
					} else {

						// child menu table query goes here 
						$subchild=$utilObj->getMultipleRow2("sub_child","id in (".$chmid.") AND mid='".$e_rec1['id']."'  order by sequenceid ");
						$arr_child = array();
						
						// child menu foreach goes here
						foreach($subchild as $e_rec2) {

							if($e_rec2['page']!="") {

								$arr_menu[$i] = $e_rec2['page']; 

								if($e_rec2['subpage']!=''){
									
									$subpage11=explode(',',$e_rec2['subpage']);
									foreach($subpage11 as $subpage12)
									{
										$i++;
										$arr_menu[$i] = $subpage12; 
									}
								}

								$arr_child[$e_rec2['name']] =  $e_rec2['page'];
								$i++;

							} else {

								// subsubchild menu table query goes here 
								$subsubchild=$utilObj->getMultipleRow2("subsub_child","id in (".$sbmid.") AND mid='".$e_rec2['id']."'  order by sequenceid ");
								$arr_subchild = array();

								foreach($subsubchild as $e_rec3) {

									$arr_menu[$i] = $e_rec3['page']; 
									$arr_subchild[$e_rec3['name']] =  $e_rec3['page'];
									$i++;
								}

								$arr_child[$e_rec2['name']] =  $arr_subchild;
							}
						}

						$arr_sub[$e_rec1['name']] =  $arr_child;
					}
				
				}
				// array_push($arr_header, $arr_sub);
				$arr_header[$e_rec['name']] = $arr_sub;
			} else {
				
				
				
				$arr_menu[$i] = $e_rec['page'];
				if($e_rec['subpage']!='')
				{
					$subpage=explode(',',$e_rec['subpage']);
						foreach($subpage as $subpage1)
						{
							$i++;
							$arr_menu[$i] = $subpage1; 
						}
						
				}
				
				$arr_header[$e_rec['name']] = $e_rec['page'];
				
				$i++;	
			}
		
		}
		
			$_SESSION['Ck_User_menu']=$arr_menu;
			$_SESSION['Ck_User_header']=$arr_header;
			// print_r($arr_menu)."<br>";
			// print_r($arr_header)."<br>";
			// die();
			
			//var_dump($_SERVER['QUERY_STRING']);
			//echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
			//var_dump($arr_header);die;
			// print_r($arr_header)."<br/>";
			
			//header("location:index.php");
			echo "<script> window.location='index.php';</script>"; 
		}
		else
		{
			$msg='Invalid Username And Password';
		}
	}
	else
	{
		$msg='Invalid Username And Password';
		echo "<script> window.location='login.php?msg=$msg';</script>";
	}
}

?>