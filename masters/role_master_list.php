<?php 
 include("header.php");
?>


        
 <div class="container-xxl flex-grow-1 container-p-y">
            
            
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Invoice /</span> List
</h4>

<!-- Invoice List Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-permissions table border-top">
      <thead>
        <tr>
          <th></th>
          <th></th>
          <th>Name</th>
          <th>Assigned To</th>
          <th>Created Date</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>


            

          </div>
          <!--/ Content -->
<!-- Add Role Modal -->
<?php 
include("role_master_form.php");
?>
<script>
function getcheck(cname){
//alert("hii");
//alert(cname);
		if ($("#"+cname).is(':checked')) {
				$("."+cname).prop("checked", true);
			} else {
				$("."+cname).prop("checked", false);
			}
}	

function getCheckAll(cname){				
	var cname_new=cname.split("_");
	var j=cname_new[1];
	
	var chkcount=$("#chkcount").val();
	//alert(cname);alert(chkcount);
	
	for(var k=j; k<chkcount; k++){
		var id=parseFloat(k+1);
		//alert(id);	
		if ($("#"+cname).is(':checked')) {								
			$(".chk_"+id).prop("checked", true);
			getcheck("chk_"+id);					
		} else {
			$(".chk_"+id).prop("checked", false);
			getcheck("chk_"+id);
		}				
	}				
}
</script>

<!-- Footer -->
<?php 
include("footer.php");
?>
