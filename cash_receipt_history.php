<?php 
include 'header.php';
	if( $_REQUEST['Task']=='history'||$_REQUEST['Task']=='view')
			{
				
			$id=$_REQUEST['customer'];
			$vendr=$utilObj->getSingleRow("account_ledger","id='".$id."' ");
			} 
				
 ?>

<div class="container-xxl flex-grow-1 container-p-y ">
<style>
.taxtbl td{
	padding:5px;
}
</style>
            
<div class="row">     
	<div class="col-md-4">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Receipt History Of -  <?php echo $vendr['name'];?> </h4>
	</div>
	<div class="col-md-2">
	
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();">Delete</button>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
				<thead>
				<tr>
				 <th id='check'><input type='checkbox' value='0' class='group-checkable' id='select_all' onclick="select_all();" data-set='#datatable-buttons.checkboxes' /></th>
				<th style="width: 15%;">Date</th>
				<th style="width: 20%;">Payment Method</th>
				<!--th style="width: 20%;">Account No.</th-->	
				<th style="width: 15%;">Amount</th>											  
				<th style="width: 17%;">Narration</th>				
				<th>User</th>
				
				<?php if($_SESSION['Ck_User_role']=='Admin'){ ?>
				<!--th>Location</th-->
				<?php } ?>
				
				<?php if($_REQUEST['Task']!='view')
					{
				echo "<th >Action</th>"; 
					}?>
				</tr></thead><tbody>
				<?php
			
				
				$i=0;
				if($_REQUEST['Task']!='update' && $_REQUEST['Task']!='view')
				{
				$data=$utilObj->getMultipleRow("cash_receipt","ClientID='".$_SESSION['Client_Id']."' AND customer ='".$id."'  order by receiptdate DESC,id DESC");
				}
				foreach($data as $info)	
				{
					$i++;
					$total=$info['amt_pay']; 
					$date=date_create($info['paymentdate']);
					$Date=date_format($date,"d-m-Y");  
					
					//$bankname=$utilObj->getSingleRow("accounts","id='".$info['bankid']."' ");
					
					/* $getadvancecount=$utilObj->getCount("purchase_advance_used","paymentid='".$info['ID']."'");
					if($getadvancecount>0){
						$dasable="disabled";
					}else{
						$dasable="";
					}   */    
						   
					echo "<tr class='even'>  ";
					echo "<td style='width:3%;' class='controls'><input type='checkbox' class='checkboxes' $dasable   name='check_list' value='".$info['id']."' /> </td> ";
				
					echo "<td style='width:10%;' class='controls'><a href='cash_receipt_list.php?id=".$info['id']."&PTask=view' >".$Date."</a></td>";
					echo "<td style='width:10%;'>".$info['payment_method']. "</td>";
					//echo "<td style='width:10%;'>Cash</td>";
					echo "<td style='width:10%;'>".$total."</td>";  
					echo "<td style='width:10%;'>".$info['narration'] . "</td>";

					$username=$utilObj->getSingleRow("employee","id='".$info['user']."'  ");
					echo "<td style='width:10%;'>".$username['name'] . "</td>";
					
					
					
					if(empty($dasable))
					{
					 echo "<td style='width:5%;'><a href='cash_receipt_list.php?id=".$info['id']."&PTask=update' ata-content='Edit' title='Edit' class='popovers' data-placement='top' data-trigger='hover'  ><i class='fa fa-edit' ></i></a>";
						   
					}else{
						echo "<td style='width:5%;'><a ata-content='Edit' title='Edit Disabled' class='popovers' data-placement='top' data-trigger='hover' ><i class='fa fa-edit' ></i></a>";
					}
					
					
						if($info['Created']!='')
						{
							//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['user']."'");
							$username = mysqli_fetch_array($query);
							$created=date('d-m-Y h:i A',strtotime($info['Created']));	
							$user = $username['fname'] . "  ".  $username['lname'];
							$createuser = "Created : ".$user." ".$created;
						}
						else{
							$createuser="";	
						}	
						
						//User - Updated Entry
						if($info['updateduser']!='')
						{
							//$query = mysqli_query($GLOBALS['con'],"select * from employee where id='".$info['updateduser']."'");
							$username = mysqli_fetch_array($query);											
							$created=date('d-m-Y h:i A',strtotime($info['LastEdited']));	
							$user = $username['fname'] . "  ".  $username['lname'];
							$createuser.= "&#10; Updated : ".$user." ".$created;
						}
						else{
							$createuser.="";	
						}
						?>
						<a $dasable ata-content='clock' title='<?php echo $createuser;?>' class='popovers' data-placement='top' style='color:brown;' data-trigger='hover'  href='#' ><i class='fa fa-clock-o' ></i></a>
						<?php 
						
					echo "</td></tr>";
				} 
				 ?>
				</table>
				<div class="form-actions">
					<center>
					
						<input type="button" class="btn btn-warning cancel" style="padding-left: 12px; margin-right: 200px; margin-left: 234px;" name="cancel" value="Cancel" onclick="remove_urldata();"/>
				   
					</center>
			   </div>
  </div>
</div>
<div id="handler_data" value="<?php echo $_REQUEST['handler_data'] ; ?>">
</div>



<?php 
include("form/cash_receipt_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view' || $_REQUEST['PTask']=='makepayment'){?>	
window.onload=function(){
	alert('hii');
  document.getElementById("makepayment").click();
   $("#makepayment").val("Show List"); 
   chk_type();
    
};  
<?php } ?>



<?php
if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		{
		    deletedata("<?php echo $_REQUEST['id'];?>");
		 }
		else
		{
			window.location="cash_receipt_history.php";
		}
  
};
<?php } ?>	
function CheckDelete()
{
	    
	var val='';
	$('input[type="checkbox"]').each(function()
	{	
		if(this.checked==true && this.value!='on')
		{
			val +=this.value+",";
		}
	});
	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
        val = val.substring(0, val.length - 1);
        window.location="cash_receipt_history.php?PTask=delete&id="+val; 
			
	}
}

function remove_urldata()
{	 
	window.location="cash_receipt_list.php";
} 
 

function deletedata(id){
	
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		
		jQuery.ajax({url:'handler/cash_receipt_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
								alert(data);					
								//window.location='cash_receipt_history.php';
						}else{
							
						}
					}
				});
   
}

function select_all(){	

	//select all checkboxes
	$("#select_all").change(function(){  //"select all" change

		var status = this.checked; // "select all" checked status
		$('.checkboxes').each(function(){ //iterate all listed checkbox items
			if(this.disabled==false)
			{
				this.checked = status; //change ".checkbox" checked status
				//alert(this.disabled);
			}
		});
	});

	//uncheck "select all", if one of the listed checkbox item is unchecked
	$('.checkboxes').change(function(){ //".checkbox" change

		if(this.checked == false){ //if this item is unchecked
			$("#select_all")[0].checked = false; //change "select all" checked status to false
		}
	});

}


</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
