<?php 
    include("header.php");
    $task=$_REQUEST['PTask'];
    if($task=='') { $task='Add'; }

    if($_REQUEST['PTask']=='view') {

        $readonly="readonly";
        $disabled="disabled";
    } else {

        $readonly="";
        $disabled="";
    }

    unset($_SESSION['FromDate']);
    unset($_SESSION['ToDate']);

    if($_REQUEST['Task']=='filter') {

        $from=$_REQUEST['FromDate'];
        $Date1=date('Y-m-d',strtotime($from));
        
        $to=$_REQUEST['ToDate'];
        $Date=date('Y-m-d',strtotime($to));
        
        $_SESSION['FromDate']=date($Date1);
        $_SESSION['ToDate']=date($Date);
        $inputfrom=date('d-m-Y',strtotime($from));
        $inputto=date('d-m-Y',strtotime($to));
    } else if($_SESSION['FromDate']=='' && $_SESSION['ToDate']==''&& $_REQUEST['Task']=='') {

        $_SESSION['FromDate']=date('Y-m-d',strtotime('-7 day'));
        $_SESSION['ToDate']=date("Y-m-d");
        $inputfrom=date("01-m-Y");
        $inputto=date("d-m-Y");
    }
?>


<div class="container-xxl flex-grow-1 container-p-y ">
    <div class="row">     
		<div class="col-md-3">       
		    <h4 class="fw-bold mb-4" style="padding-top:2px;">GST Maintaince</h4>
		</div>
	</div>

    <div class="row" style="margin-bottom:12px;">
        <form id=""   class=" form-horizontal " method="get" data-rel="myForm">
            <div class="row">

                <div class="col-md-2 ">
                    <label  class="form-label">FromDate</label>
                    <input type="text" id="fromdate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputfrom;?>" />
                </div> 

                <div class="col-md-2 ">
                    <label  class="form-label">ToDate</label>
                    <input type="text" id="todate" placeholder="DD/MM/YYYY" class="form-control" value="<?php echo $inputto;?>">
                </div>

                <div class="col-md-3" style="padding-top:25px;">
					<input type="button"  name="Submit" onClick="Search();" id="Submit" class="btn btn-success" value="Search" />
				</div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table border-top" id="datatable-buttons" role="grid">
                <thead>
                    <tr>
                        <th width='3%'>Sr.No.</th>
                        <th>Requisition Date</th>
                        <th>Requisition No</th>
                        <th>Close Date</th>
                        <th>Days for Completion</th>
                        <!-- <th>User</th> -->
                    </tr>
                </thead>

                <tbody>
                <?php
                    $i=0;
                    if($_REQUEST['Task']=='filter') {

                        $cnd="date>='".$_SESSION['FromDate']."'AND date<='".$_SESSION['ToDate']."' ";
                    } else { 

                        $cnd="date>='".$_SESSION['FromDate']."' AND date<='".$_SESSION['ToDate']."'";
                    }

                    $data=$utilObj->getMultipleRow("production_requisition","$cnd AND requi_flag='1' ");

                    foreach($data as $info) {

                        $i++;

                        $date1 = date('d-m-Y', strtotime($info['date']));
                        $date2 = date('d-m-Y', strtotime($info['close_date']));

                        $dateTime1 = DateTime::createFromFormat('d-m-Y', $date1);
                        $dateTime2 = DateTime::createFromFormat('d-m-Y', $date2);

                        $interval = $dateTime1->diff($dateTime2);

                        $daysDifference = $interval->days;

                        if($_REQUEST['Task']=='filter') {
                ?>
                    <tr>
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php echo date('d-m-Y',strtotime($info['date'])); ?>
                        </td>
                        <td>
                            <?php echo $info['record_no']; ?>
                        </td>
                        <td>
                            <?php echo date('d-m-Y',strtotime($info['close_date'])); ?>
                        </td>
                        <td>
                            <?php echo $daysDifference." days"; ?>
                        </td>

                        <!-- <td></td> -->
                    </tr>
                <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

	window.onload=function() {

		$("#fromdate").flatpickr({
			dateFormat: "d-m-Y"
		});

		$("#todate").flatpickr({
			dateFormat: "d-m-Y"
		});
	}

	function Search() {

		var fromdate=$('#fromdate').val();
		var todate=$('#todate').val();

		window.location="stock_requisition_report.php?FromDate="+fromdate+"&ToDate="+todate+"&Task=filter";
	}

</script>


<?php 
	include("footer.php");
?>