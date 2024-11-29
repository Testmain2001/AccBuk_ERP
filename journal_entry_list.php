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
<style>
.hidetd{
	    display: none;
}
</style>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-2">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Journal Entry</h4>
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
			<th>Date</th>
			<th>Account Name</th>
			<th>Debit Amount</th>
			<th>Credit Amount</th>
			<?php if((CheckEditMenu())==1) {  ?> <th>Actions</th><?php } ?>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=1;
			$data1=$utilObj->getMultipleRow("journal_entry"," 1 group by parent_id ");
			
			foreach($data1 as $info1) {

				   	$j=0;
				  
					$href= 'journal_entry_list.php?parent_id='.$info1['parent_id'].'&PTask=view';
					$data=$utilObj->getMultipleRow("journal_entry","parent_id='".$info1['parent_id']."'");
					
				foreach($data as $info) {

					// echo $info['parent_id'];			
					$j++;

					if($j==1) {

						$rowspan = count($data);
						$hidetd = "";
						// $hidetxt = "";
					} else {

						$rowspan = "";
						$hidetd = "hidetd";
						// $hidetxt = "##none";
					} 
					$accountnm=$utilObj->getSingleRow("account_ledger","id='".$info['account']."'");  
		
		?>
		<tr>
			<td width='3%' class='controls <?php echo $hidetd; ?>' rowspan='<?php echo $rowspan ;?>'><input type='checkbox' class='checkboxes' name='check_list' value='<?php echo $info['parent_id']; ?>'/></td> 
			<td class='controls <?php echo $hidetd ;?>' rowspan='<?php echo $rowspan ;?>'><?php echo $i.$hidetxt ; ?></td>
			<td class='controls <?php echo $hidetd ;?>' rowspan='<?php echo $rowspan ;?>'> <?php echo date('d-m-Y',strtotime($info['date'])).$hidetxt ; ?> </td>
			<td> <a href="<?php echo $href; ?>"><?php echo $accountnm['name']; ?></a> </td>
			<td><?php echo $info['debit_amount']; ?></td>
			<td><?php echo $info['credit_amount']; ?></td>
			<td class='controls <?php echo $hidetd ;?>' rowspan='<?php echo $rowspan ;?>' >
				<!--div class="dropdown"-->
			
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
				<div class="dropdown-menu">
				<?php if((CheckEditMenu())==1) {  ?>
				<a class="dropdown-item" href="journal_entry_list.php?parent_id=<?php echo $info['parent_id'];?>&PTask=update"><i class="bx bx-edit-alt me-1"></i> Edit</a>
				<?php } ?>
				<?php if((CheckDeleteMenu())==1){ ?>
					<a class="dropdown-item" href="journal_entry_list.php?parent_id=<?php echo $info['parent_id'];?>&PTask=delete"><i class="bx bx-trash me-1"></i> Delete</a>
					<?php } ?>
				</div>
				<!--/div-->
			
			</td>
		</tr>
		<?php 
			}
			$i=$i+1;
		} ?>
		</tbody>
	   </table>
  </div>
</div>


<?php 
include("form/journal_entry_form.php");
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
if($_REQUEST['PTask']=='delete') { ?>
	window.onload=function(){
		var r=confirm("Are you sure to delete?");
		if (r==true)
		{
			deletedata("<?php echo $_REQUEST['parent_id'];?>");
			}
		else
		{
			window.location="journal_entry_list.php";
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
			window.location="journal_entry_list.php?PTask=delete&parent_id="+val; 
			
	}
}
function mysubmit(a)
{
	return _isValidpopup(a);	
}
function remove_urldata()
{	
    
	window.location="journal_entry_list.php";
} 
 
function savedata(){
	
	var PTask = $("#PTask").val();
	var parent_id = $("#parent_id").val();
	var table = $("#table").val();
	var LastEdited = $("#LastEdited").val();
	var cnt = $("#cnt").val();
	var recordnumber = $("#recordnumber").val();
	var voucher_type = $("#voucher_type").val();
	
	var date = $("#date").val();
	var total_of_debitamt = $("#total_of_debitamt").val();
	var total_of_creditamt = $("#total_of_creditamt").val();
	
	var record_array=[];
	var account_array=[];
	var debit_amount_array=[];
	var credit_amount_array=[];
		
	for(var i=1;i<=cnt;i++)
	{
		var record = $("#record_"+i).val();	
		var account = $("#account_"+i).val();	
		var debit_amount = $("#debit_amount_"+i).val();	
		var credit_amount = $("#credit_amount_"+i).val();	
		
		record_array.push(record);
		account_array.push(account);
		debit_amount_array.push(debit_amount);
		credit_amount_array.push(credit_amount);
		
	}

	
	
	jQuery.ajax({url:'handler/journal_entry_form.php', type:'POST',
		data: {PTask:PTask,parent_id:parent_id,table:table,LastEdited:LastEdited,cnt:cnt,recordnumber:recordnumber,date:date,total_of_debitamt:total_of_debitamt,total_of_creditamt:total_of_creditamt,record_array:record_array,account_array:account_array,debit_amount_array:debit_amount_array,credit_amount_array:credit_amount_array,voucher_type:voucher_type },
		success:function(data)
		{
			if(data!="")
			{		
				window.location='journal_entry_list.php';
			}
		}
	});
}


function deletedata(id){
	var PTask =	"<?php echo $_REQUEST['PTask']; ?>";
	//var id = "<?php echo $_REQUEST['id']; ?>";
	
	jQuery.ajax({url:'handler/journal_entry_form.php', type:'POST',
		data: { PTask:PTask,id:id},
		success:function(data)
		{	
			if(data!="")
			{
					alert(data);					
					window.location='journal_entry_list.php';
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
