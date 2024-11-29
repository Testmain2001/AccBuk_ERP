

<?php 
 include("header.php");
$task=$_REQUEST['PTask'];
if($task==''){ $task='Add';}
if($_REQUEST['PTask']=='view')
{
$readonly="readonly";
$disabled="disabled";
}
else
{
$readonly="";
$disabled="";
}
?>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;"> Employee Master</h4>
	</div>
	<div class="col-md-2">
	<?php if((CheckCreateMenu())==1){  ?>
	<button class=" btn btn-primary mr-2  btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="add_new"><i class="fas fa-plus-circle fa-lg"></i></button>
	<?php } ?>
	<?php if((CheckDeleteMenu())==1){ ?>
	<button class=" btn btn-danger  btn-sm"  onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
	<?php } ?>
	</div>
</div>
<!-- Invoice List Table -->


<div class="card">
	<div class="card-datatable table-responsive pt-0">
		
		<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
			<thead>
				<tr>
					<th><input type='checkbox' value='0' id='select_all' onclick="select_all();" /></th>
					<th>Sr.No.</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Role</th>
					<?php if((CheckEditMenu())==1) {  ?><th>Actions</th> <?php } ?>
				</tr>
			</thead>
   
			<tbody>
			<?php
				$i=1;
				$data=$utilObj->getMultipleRow("employee","1");
				foreach($data as $info){
						
					$role=$utilObj->getSingleRow("role_master","id='".$info['role']."'");
					$href= 'employee_master_list.php?id='.$info['id'].'&PTask=view';
							
			?>
				<tr>
					<td width='3%' class='controls'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>' /> </td> 
					<td><?php echo $i; ?></td>
					<td> <a href="<?php echo $href; ?>"><?php echo $info['name']; ?></a> </td>
					<td> <?php echo $info['email']; ?> </td>
					<td> <?php echo $info['mobile']; ?> </td>
					<td> <?php echo $role['role']; ?> </td>
					<td>
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
						<div class="dropdown-menu">
							<?php if((CheckEditMenu())==1) {  ?>
							<a class="dropdown-item" href="employee_master_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
							<?php } ?>
							<?php if((CheckDeleteMenu())==1){ ?>
							<a class="dropdown-item" href="employee_master_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
							<?php } ?>
						</div>
						<!--/div-->
					</td>
				</tr>
				<?php $i=$i+1; ?>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>


<?php 
include("form/employee_master_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
  document.getElementById("add_new").click();
   $("#add_new").val("Show List"); 
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
			window.location="employee_master_list.php";
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
			window.location="employee_master_list.php?PTask=delete&id="+val; 
			
	}
}
function mysubmit(a)
{
	return _isValidpopup(a);	
}
function remove_urldata()
{	
    
	window.location="employee_master_list.php";
} 
 
function savedata() {
	
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var name = $("#name").val();
	var mobile = $("#mobile").val();
	var email = $("#email").val();
	var password = $("#password").val();
	var role = $("#role").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var multiloc = $("#multiloc").val();
	
	jQuery.ajax({url:'handler/employee_master_form.php', type:'POST',
		data: { PTask:PTask,id:id,name:name,mobile:mobile,email:email,password:password,role:role,table:table,LastEdited:LastEdited,multiloc:multiloc },
		success:function(data)
		{	
		
			if(data!="")
			{	alert(data);			
				window.location='employee_master_list.php';
			}
		}
	});
}


function deletedata(id){
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		//var id = "<?php echo $_REQUEST['id']; ?>";
		
		jQuery.ajax({url:'handler/employee_master_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
								alert(data);					
								window.location='employee_master_list.php';
						}else{
							//alert('huihu');
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
