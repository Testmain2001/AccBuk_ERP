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
	<h4 class="fw-bold mb-4" style="padding-top:2px;"> Group Master</h4>
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
			<th>Parent Group</th>
			
			<?php if((CheckEditMenu())==1) {  ?><th>Actions</th> <?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data=$utilObj->getMultipleRow("group_master","1");
			foreach($data as $info){
				
					$href= 'group_master_list.php?id='.$info['id'].'&PTask=view';
					
					
		
		?>
		<tr>
		<td width='3%' class='controls'><?php if($info['flag']==1){ ?><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/><?php } ?></td> 
		<td><?php echo $i; ?></td>
		<td> <a href="<?php echo $href; ?>"><?php echo $info['group_name']; ?></a> </td>
		<td> <a href="<?php echo $href; ?>"><?php echo $info['parent_group']; ?></a> </td>
		
		<td>
            <!--div class="dropdown"-->
        
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
			  <?php if((CheckDeleteMenu())==1){ ?>
               <a class="dropdown-item" href="group_master_list.php?id=<?php echo $info['id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
			   <?php } ?>
			<?php if($info['flag']==1){ ?>
				<?php if((CheckDeleteMenu())==1){ ?>
                <a class="dropdown-item" href="group_master_list.php?id=<?php echo $info['id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
				<?php } ?>
              </div>
            <!--/div-->
		<?php } ?>
        </td>
		</tr>
		<?php 
		$i=$i+1;
		} ?>
	  </tbody>
	   </table>
  </div>
</div>


<?php 
include("form/group_master_form.php");
?>

          </div>
          <!--/ Content -->
		  

<script>

<?php 
if($_REQUEST['PTask']=='update' || $_REQUEST['PTask']=='view'){?>	
window.onload=function(){
  document.getElementById("add_new").click();
   $("#add_new").val("Show List"); 
   get_nature_group(); 
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
			window.location="group_master_list.php";
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
			window.location="group_master_list.php?PTask=delete&id="+val; 
			
	}
}
function mysubmit(a)
{
	return _isValidpopup(a);	
}
function remove_urldata()
{	
    
	window.location="group_master_list.php";
} 
 
function savedata(){
	
	var PTask = $("#PTask").val();
	var id = $("#id").val();
	var parent_id = $("#grp_id").val();
	var name = $("#name").val();
	var parent_group = $("#parent_group").val();
	var act_group = $("#act_group").val();
	var nature_group = $("#nature_group").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	
	jQuery.ajax({url:'handler/group_master_form.php', type:'POST',
		data: { PTask:PTask,id:id,name:name,parent_group:parent_group,nature_group:nature_group,table:table,LastEdited:LastEdited,parent_id:parent_id,act_group:act_group },
		success:function(data)
		{
			if(data!="")
			{
				alert(data);			
				window.location='group_master_list.php';
			}
		}
	});
}


function deletedata(id){
		var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
		//var id = "<?php echo $_REQUEST['id']; ?>";
		
		jQuery.ajax({url:'handler/group_master_form.php', type:'POST',
					data: { PTask:PTask,id:id},
					success:function(data)
					{	
						if(data!="")
						{
								alert(data);					
								window.location='group_master_list.php';
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

function get_nature_group(){  
	var parent = $("#parent_group").val();
	jQuery.ajax({url:'get_ajax_values.php',type:'POST',
		data: { Type:'get_nature_group',parent:parent},
		success:function(data)
		{
			$('#group_div').html(data);
			//$('#nature_group').val(data);
		
		}
	});
}

function get_grp_id(id){ 
	jQuery.ajax({url:'get_ajax_values.php',type:'POST',
		data: { Type:'get_grp_id',id:id},
		success:function(data)
		{
			$('#grp_id').val(data);
		
		}
	});
}

</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
