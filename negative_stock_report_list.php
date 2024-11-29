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
unset($_SESSION['FromDate']);
unset($_SESSION['ToDate']);
//unset($_SESSION['cname']);
if($_REQUEST['Task']=='filter')
{
  	$from=$_REQUEST['FromDate'];
	$Date1=date('Y-m-d',strtotime($from));
	
	$to=$_REQUEST['ToDate'];
	$Date=date('Y-m-d',strtotime($to));
	 
	
	$_SESSION['FromDate']=date($Date1);
    $_SESSION['ToDate']=date($Date);
	$inputfrom=date('d-m-Y',strtotime($from));
	$inputto=date('d-m-Y',strtotime($to));
	// $_SESSION['cname']=$_REQUEST['cname'];
	
}
else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='')
{
	$_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
	$_SESSION['ToDate']=date("Y-m-d");
	$inputfrom=date("01-m-Y");
	$inputto=date("d-m-Y");
}
?>

 <div class="container-xxl flex-grow-1 container-p-y ">
            
<div class="row">     
	<div class="col-md-4">       
	<h4 class="fw-bold mb-4" style="padding-top:2px;">Negative Stock Reports </h4>
	</div>
	
</div>
<div class="row" style="margin-bottom:12px;">

		<form id="" class=" form-horizontal " method="get" data-rel="myForm">
			<div class="row">
			<!--div class="col-md-3 ">
            <label  class="form-label">FromDate</label>
            <input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
            </div> 
		  <div class="col-md-3 ">
            <label  class="form-label">ToDate</label>
            <input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
          </div-->
			<div class="col-md-3">
				<label class="form-label">Product<span class="required required_lbl" style="color:red;">*</span></label>
				<select id="product" name="product" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" onchange="get_unit();" style="width:100%;">	
					<?php 
						echo '<option value="All">Select ALL</option>';
						$record=$utilObj->getMultipleRow("stock_ledger","bill_of_material=1 ");
						foreach($record as $e_rec)
						{
							if($_REQUEST['product']==$e_rec["id"]) echo $select='selected'; else $select='';
							echo '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
						}
					?> 
				</select>
			</div>
            <div class="col-md-3">
					<label class="form-label">Unit <span class="required required_lbl" style="color:red;">*</span></label>
						<div id='unitdiv'>
					<select id="unit" name="unit" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true"  style="width:100%;">	
							<?php 
								echo '<option value="All">Select ALL</option>';
								
								$record=$utilObj->getMultipleRow("stock_ledger","1 ");
								foreach($record as $e_rec)
								{
									if($_REQUEST['unit']==$e_rec["unit"]) echo $select='selected'; else $select='';
									echo '<option value="'.$e_rec["unit"].'" '.$select.'>'.$e_rec["unit"] .'</option>';
								}
							?> 
					</select>
					</div>
			</div>	
			<div class="col-md-3">
				<label class="form-label" >Location: <span class="required required_lbl" style="color:red;">*</span></label>
				<select id="location" name="location" onchange="" <?php echo $disabled;?> class="select2 form-select required" data-allow-clear="true" >	
									<?php 
										echo '<option value="All">Select All Location</option>';
										$record=$utilObj->getMultipleRow("location","1");
										foreach($record as $e_rec)
										{
											if($_REQUEST['location']==$e_rec["id"]) echo $select='selected'; else $select='';
											echo  '<option value="'.$e_rec["id"].'" '.$select.'>'.$e_rec["name"] .'</option>';
										}
									?>  
				</select>
			</div>
				<div class="col-md-3" style="padding-top:25px;">
				     
					  <input type="button"  name="Submit" onClick="Search();" id="Submit" onfocus="cleardate();" class="btn btn-success" value="Search" />
				</div>
			</div>
		 </form>
</div>
<!-- Invoice List Table -->


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    
	<table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
      <thead>
        <tr>
		  <th width='3%'><!--input type='checkbox' value='0' id='select_all' onclick="select_all();" /-->&nbsp Sr.No.</th>
		   <th width='10%'>location</th>
		   <th width='10%'>Product</th>
		   <th width='10%'>Unit</th>
          <th width='10%'>Stock</th>
        </tr>
      </thead>
   
	<tbody>
	   <?php
			$i=0;
			if($_REQUEST['Task']=='filter'&& $_REQUEST['location']!="All"&&$_REQUEST['location']!=""){
				$cnd="id='".$_REQUEST['location']."' ";
			}else{
				$cnd="1";
			}

			$data=$utilObj->getMultipleRow("location","$cnd");

			foreach($data as $info){
				    $i++;
                     $j=0;
					
						if($_REQUEST['Task']=='filter'&& $_REQUEST['product']!="All"&&$_REQUEST['product']!=""){
							$cnd="id='".$_REQUEST['product']."' ";
						}else{
							$cnd="1";
						}

                 $data1=$utilObj->getMultipleRow("stock_ledger","$cnd");
                $k=0;
			foreach($data1 as $info1){
				$j++;
				$k++;
				$stock=getstock($info1['id'],$info1['unit'],date('Y-m-d'),'',$info['id']);
		if($stock<=0){
		?>
		<tr>
		<td  class='controls'><!--input type='checkbox' <?php echo $dis; ?> class='checkboxes' name='check_list' value='<?php echo $info['id']; ?>'/-->&nbsp&nbsp<?php echo $k; ?></td> 
		<td> <?php echo $info['name']; ?> </td>
		<td> <?php echo $info1['name']; ?> </td>
		<td> <?php echo $info1['unit']; ?> </td> 
		<td> <?php echo $stock; ?> </td>
		</tr>
		<?php 
		}}
		} ?>
	  </tbody>
	   </table>
  </div>
</div>




</div>
          <!--/ Content -->
		  

<script>
<?php if($_REQUEST['Task']=='filter'){ ?>
get_unit();
<?php } ?>

window.onload=function(){
	$("#fromdate").flatpickr({
	dateFormat: "d-m-Y"
	});
	$("#todate").flatpickr({
	dateFormat: "d-m-Y"
	});
}

function Search(){
	//var fromdate=$('#fromdate').val();
	//var todate=$('#todate').val();
	var product=$('#product').val();
	var unit=$('#unit').val();
	var location=$('#location').val();
	window.location="negative_stock_report_list.php?product="+product+"&unit="+unit+"&location="+location+"&Task=filter";
}


function get_unit()
{	

	var product = $("#product").val();
	//alert(product);
	jQuery.ajax({url:'get_ajax_values.php', type:'POST',
		data: { Type:'get_unit_billofmaterial',product:product},
		success:function(data)
		{	
		//alert(data);
			$("#unitdiv").html(data);	
			//$(this).next().focus();
		}
	});	
}


</script>


<!-- Footer -->
<?php 
include("footer.php");
?>
