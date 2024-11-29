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
if($_REQUEST['PTask']=='update'||$_REQUEST['PTask']=='view') {

	$rows=$utilObj->getSingleRow("role_master","id='".$_REQUEST['id']."' ");
}
?>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<!--div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold py-3 mb-4"> Role Master</h4>
	</div>
	<div class="col-md-2">
	<button class=" btn btn-primary mr-2" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="addnew">Add New</button>
	</div>
</div-->
<div class="row">     
<div class="col-md-2">       
<h4 class="fw-bold mb-4" style="padding-top:2px;"> Role Master</h4>
</div>
<div class="col-md-2">
<?php if((CheckCreateMenu())==1){  ?>
<button class=" btn btn-primary mr-2 btn-sm" data-bs-target="#addRecordModal" data-bs-toggle="modal" data-bs-dismiss="modal" id="addnew"><i class="fas fa-plus-circle fa-lg"></i></button>
<?php } ?>
<?php if((CheckDeleteMenu())==1){ ?>
<button class=" btn btn-danger  btn-sm" onclick="CheckDelete();"><i class="fas fa-trash fa-lg" style="color: #ffffff;"></i></button>
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
								
			<th>Role</th>
			<th>Date</th>
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			$data=$utilObj->getMultipleRow("role_master","1");
			foreach($data as $info){
				
				if($info['id']==1){
				   $dasable="disabled";	
				}else{
					$dasable="";	
				}
				$href= 'role_master_list.php?id='.$info['id'].'&PTask=view';
		?>
		<tr>
		<td width='3%' class='controls'><input type='checkbox' class='checkboxes' <?php echo $dasable ;?> name='check_list' value='<?php echo $info['id']; ?>' /> </td> 
		<td> <a href="<?php echo $href; ?>"> <?php echo $info['role']; ?></a> </td>
		<td> <?php echo date('d-m-Y ',strtotime($info['created'])); ?> </td>
		
		<td>
            <!--div class="dropdown"-->
           
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
			  <?php if((CheckEditMenu())==1) {  ?>
                <a class="dropdown-item" href="role_master_list.php?PTask=update&id=<?php  echo $info['id']; ?>"><i class="bx bx-edit-alt me-1"></i> Edit</a>
				<?php } ?>
				<?php if((CheckDeleteMenu())==1){ ?>
                <a class="dropdown-item" href="role_master_list.php?PTask=delete&id=<?php  echo $info['id']; ?>"><i class="bx bx-trash me-1"></i> Delete</a>
				<?php } ?>
              </div>
            <!--/div-->
        </td>
		</tr>
		<?php 
		$i=$i+1;
		} ?>
	  </tbody>
	   </table>
  </div>
</div>
 <!--/ Content -->
<!-- Add Role Modal -->
<?php 
include("form/role_master_form.php");
?>
<script>
//add update and submit script function
<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
  document.getElementById("addnew").click();
  $("#addnew").val("Show List"); 
}; 

<?php }
if($_REQUEST['PTask']=='delete'){?>	
 window.onload=function(){
	var r=confirm("Are you sure to delete?");
		if (r==true)
		  {
		 deletedata("<?php echo $_REQUEST['id']; ?>");
		  }
		else
		  {
			  window.location="role_master_list.php"; 
		     //return false;
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
			//if(this.value==='on'){ continue;}
			val +=this.value+",";
		}
	});
 // alert(val);
	if(val=='')
	{
		alert('Please Select Atleast 1 record!!!!');
	}
	else
	{
		
			val = val.substring(0, val.length - 1);
			window.location="role_master_list.php?PTask=delete&id="+val; 
			/* var array = [val];
			alert('array'+array);
			deletedata(array); */
		 
	}
}	
function mysubmit(a)
{   
	
	var val='';
	$('.same').each(function()
	{	
		if(this.checked==true)
		{
			val +=this.value+",";
			
		}
	}); 
	//alert(val);
	//var a=$("#menu").val();
	$("#menuselect").val(val);
	
	var val='';
	$('.create').each(function()
	{	
		if(this.checked==true)
		{
			val +=this.value+",";
		}
	}); 
	$("#createMenu").val(val);
	
	var val='';
	$('.edit').each(function()
	{	
		if(this.checked==true)
		{
			val +=this.value+",";
		}
	}); 
	$("#editMenu").val(val);
	
	var val='';
	$('.delete1').each(function()
	{	
		if(this.checked==true)
		{
			val +=this.value+",";
		}
	}); 
	$("#deleteMenu").val(val);
	
	
	var val='';
	$('.view').each(function()
	{	
		if(this.checked==true)
		{
			val +=this.value+",";
		}
	}); 
	$("#viewMenu").val(val);
	return _isValidpopup(a);	
}
function remove_urldata()
{	
    
	window.location="role_master_list.php";
} 
function savedata(){
	
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var role = $("#role").val();
	var menuselect = $("#menuselect").val();
	var createMenu = $("#createMenu").val();
	var editMenu = $("#editMenu").val();
	var deleteMenu = $("#deleteMenu").val();
	var viewMenu = $("#viewMenu").val();
	
	
	jQuery.ajax({url:'handler/role_master_form.php', type:'POST',
				data: { PTask:PTask,id:id,role:role,menuselect:menuselect,createMenu:createMenu,editMenu:editMenu,deleteMenu:deleteMenu,viewMenu:viewMenu},
				success:function(data)
				{	
					if(data!="")
					{
						alert(data);
						///alert("Record Inserted Successfully!!!");					
						window.location='role_master_list.php';
					}
				}
			});
}
function deletedata(id){
	
	var PTask = "<?php echo $_REQUEST['PTask']; ?>";
	//var id = "<?php echo $_REQUEST['id']; ?>";
	//alert("deletedata="+id);
	jQuery.ajax({url:'handler/role_master_form.php', type:'POST',
				data: { PTask:PTask,id:id},
				success:function(data)
				{	
					if(data!="")
					{
						alert(data);
						//alert("Record has been Deleted Sucessfully!!!!");					
						window.location='role_master_list.php';
					}else{
						alert('faiel');
					}
				}
			});
}
</script>
<script>
function getrowchk(cname){	
var cname_new=cname.split("_");
var j=cname_new[1];
	if ($("#"+cname).is(':checked')) {
		$(".chkCreate_"+j).prop("checked", true);
		$(".chkEdit_"+j).prop("checked", true);
		$(".chkDelete_"+j).prop("checked", true);
		$(".chkView_"+j).prop("checked", true);
		
		
	} else {
		$(".chkCreate_"+j).prop("checked", false);
		$(".chkEdit_"+j).prop("checked", false);
		$(".chkDelete_"+j).prop("checked", false);
		$(".chkView_"+j).prop("checked", false);
		
		
	}
}
	
function getcheck(cname){
	if ($("#"+cname).is(':checked')) {
		$("."+cname).prop("checked", true);
		getrowchk(cname);
	} else {
		$("."+cname).prop("checked", false);
		getrowchk(cname);
	}
}	

function getCheckAll(cname){	

	var cname_new=cname.split("_");
	var j=cname_new[1];
	var chkcount=$("#chkcount").val();
	for(var k=j; k<chkcount; k++){
		var id=parseFloat(k+1);
		//alert(id);	
		if ($("#"+cname).is(':checked')) {
			  // alert(id);					
			$(".chk_"+id).prop("checked", true);
			getcheck("chk_"+id);					
		} else {
			$(".chk_"+id).prop("checked", false);
			getcheck("chk_"+id);
		}				
	}				
}

function get_Check_All(selectid){
	
	//alert(selectid);
	if ($("#"+selectid).is(':checked')) { //alert("1");
			$("."+selectid).prop("checked", true);
	} else { //alert("2");
			$("."+selectid).prop("checked", false);
	}
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
