<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
     <!-- ©  -->
	  <script>
     // document.write(new Date().getFullYear())
      </script>
       <strong style="float:left;margin-left:-60px;">Developed by <?php echo date('Y');?> <a href="http://www.webotix.in" target="_blank">&nbsp;Webotix Technology.</a></strong>
    </div>
    <!-- <div>
      
      <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
      <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
      
      <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>
      
      
      <a href="https://themeselection.com/support/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
      
    </div> -->
  </div>
</footer>
<!-- / Footer -->

          
          <div class="content-backdrop fade"></div>
        </div>
        <!--/ Content wrapper -->
      </div>

      <!--/ Layout container -->
    </div>

  </div>

  
  
  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>
  
  
  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>
  
  <!--/ Layout wrapper -->

  
  

  

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  
  <script src="assets/vendor/libs/hammer/hammer.js"></script>
  <script src="assets/vendor/libs/i18n/i18n.js"></script>
  <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
  
  <script src="assets/vendor/js/menu.js"></script>
  
  <!-- endbuild -->
  <script src="assets/vendor/libs/moment/moment.js"></script>
  <script src="assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

  <script src="assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>


  <!-- <script src="assets/vendor/libs/datatables-buttons/datatables-buttons.js"></script> -->


  <script src="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js"></script>

  <!-- ------------------------------------------------------------------------  -->

  <script src="assets/vendor/libs/datatables-buttons/buttons.html5.js"></script>
  <script src="assets/vendor/libs/datatables-buttons/buttons.print.js"></script>

  <script src="assets/vendor/libs/jszip/jszip.js"></script>

  <script src="assets/vendor/libs/pdfmake/pdfmake.js"></script>

  <!-- <script src="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js"></script> -->

  <!-- ------------------------------------------------------------------------- -->
  <!-- Vendors JS -->
  <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="assets/vendor/libs/select2/select2.js"></script> 
  <script src="assets/vendor/libs/moment/moment.js"></script>
  
  <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
  <script src="assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
  <script src="assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
  <script src="assets/vendor/libs/jquery-timepicker/jquery-timepicker.js"></script>
  <script src="assets/vendor/libs/pickr/pickr.js"></script>

  <!-- <script src="assets/js/tables-datatables-basic.js"></script> -->
  
  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="assets/js/forms-selects.js"></script>
  <script src="assets/js/dashboards-analytics.js"></script>
  <script src="assets/js/forms-pickers.js"></script>
  <!-- <script src="assets/js/tables-datatables-basic.js"></script> -->
  <!-- include the script -->
  <!-- <script src="assets/vendor/alertify.min.js"></script> -->


  <!-- for validation -->
  <script src="validation/customvalidation.js"></script>

  <!-- Alertify JavaScript -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

  

</body>

<script>

$(document).ready(function() {
  $('#datatable-buttons').DataTable({ 
    dom: 'Bfrtip',
    // buttons: [
    //   'pageLength', 'copy', 'excel', 'pdf', 'csv', 'print' 
    // ]
    "order": [],
    
    buttons: [
      {
        extend: 'pageLength',
        // text: '<i class="fas fa-list fa-lg"></i>',
      },
      {
        extend: 'copy',
        text: '<i class="fas fa-copy fa-lg"></i>',
      },
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel fa-lg"></i>',
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf fa-lg"></i>',
      },
      {
        extend: 'csv',
        text: '<i class="fas fa-file-csv fa-lg"></i>',
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print fa-lg"></i>',
      } 
    ]
  });
});

// $(document).ready(function() {
//     $('#datatable-buttons').DataTable();
//   });

</script>
<script>
document.addEventListener("keydown", function(event) {
//alert("hii");
  if (event.key === "+") {
	//alert("hii");
       $("#addRecordModal").modal('show');
  }
});
</script>

</html>
