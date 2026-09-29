<!--end header -->
<!--start page wrapper -->

<!--end page wrapper -->
<!--start overlay-->

<div class="search-overlay"></div>
<div class="overlay toggle-icon"></div>
<!--end overlay--> 
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a> 
<!--End Back To Top Button-->
<footer class="page-footer">
  <p class="mb-0">Copyright © 2021. All right reserved.</p>
</footer>
</div>
<!--end wrapper--> 

<!-- Bootstrap JS --> 
<script src="<?= site_url('assets/js/bootstrap.bundle.min.js'); ?>"></script> 
<!--plugins--> 
<script src="<?= site_url('assets/js/jquery.min.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js'); ?>"></script> 

<!--<script src="<?= site_url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script> -->
<!--<script src="<?= site_url('assets/plugins/highcharts/js/highcharts.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/highcharts/js/exporting.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/highcharts/js/variable-pie.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/highcharts/js/export-data.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/highcharts/js/accessibility.js'); ?>"></script> 
<script src="<?= site_url('assets/plugins/apexcharts-bundle/js/apexcharts.min.js'); ?>">
</script> -->
<script src="<?= site_url('assets/plugins/datatable/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= site_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js'); ?>">
</script>
<script src="<?= site_url('assets/plugins/select2/js/select2.min.js'); ?>">
</script>
<script src="<?= site_url('assets/plugins/ckeditor/ckeditor.js'); ?>">
</script>

<script src="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js'); ?>">
</script>
<script src="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js'); ?>">
</script>


<script>
		//new PerfectScrollbar('.dashboard-top-countries');
		$(document).ready(function() {
			$('.datatable').DataTable();
			/*Data Table*/
			var table = $('.dt_with_btn').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
			table.buttons().container()
				.appendTo( '.datatable .col-md-6:eq(0)' );
				
			/*Date Time picker*/	
		 $('.datetime').bootstrapMaterialDatePicker({
				format: 'YYYY-MM-DD HH:mm'
			});
			$('.date').bootstrapMaterialDatePicker({
				time: false,
				format: 'YYYY-MM-DD'
			});
			$('.time').bootstrapMaterialDatePicker({
				date: false,
				format: 'HH:mm'
			});
		});
				
			/*$('select').select2({
			theme: 'bootstrap4',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			placeholder: $(this).data('placeholder'),
			allowClear: Boolean($(this).data('allow-clear')),
		});*/	
		 /*File upload script*/
		 
		   var $fileInput = $('.file-input');
			var $droparea = $('.file-drop-area');
			
			// highlight drag area
			$fileInput.on('dragenter focus click', function() {
			  $droparea.addClass('is-active');
			});
			
			// back to normal state
			$fileInput.on('dragleave blur drop', function() {
			  $droparea.removeClass('is-active');
			});
			
			// change inner text
			$fileInput.on('change', function() {
			  var filesCount = $(this)[0].files.length;
			  var $textContainer = $(this).prev();
			
			  if (filesCount === 1) {
				// if single file is selected, show file name
				var fileName = $(this).val().split('\\').pop();
				$textContainer.text(fileName);
			  } else {
				// otherwise show number of files
				$textContainer.text(filesCount + ' files selected');
			  }
			});	
			/*Editor*/	
			
			 CKEDITOR.replace( 'editor1' );
			 CKEDITOR.replace( 'editor2' );

	</script>
   <!-- <script src="<?= site_url('assets/js/index.js'); ?>"></script>-->
   <script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js'); ?>"></script> 
    <script src="<?= site_url('assets/js/app.js'); ?>"></script>

</body>
</html>

<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <?= $modal_header; ?>
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
