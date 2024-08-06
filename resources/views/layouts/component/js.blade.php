<!-- Bootstrap JS -->
<script src="{{URL::to('assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{URL::to('assets/js/jquery.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{URL::to('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{URL::to('assets/plugins/chartjs/js/chart.js')}}"></script>
<script src="{{URL::to('assets/js/index.js')}}"></script>
<script src="{{URL::to('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{URL::to('assets/plugins/select2/js/select2-custom.js')}}"></script>
<script src="{{URL::to('assets/plugins/fancy-file-uploader/jquery.ui.widget.js')}}"></script>
    <script src="{{URL::to('assets/plugins/fancy-file-uploader/jquery.fileupload.js')}}"></script>
	<script src="{{URL::to('assets/plugins/fancy-file-uploader/jquery.iframe-transport.js')}}"></script>
	<script src="{{URL::to('assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js')}}"></script>
    <script src="{{URL::to('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js')}}"></script>
<!--app JS-->
    <script>
		$('#fancy-file-upload').FancyFileUpload({
			params: {
				action: 'fileuploader'
			},
		});
	</script>
	<script>
		$(document).ready(function () {
			$('#image-uploadify').imageuploadify();
		})
	</script>
<script src="{{URL::to('assets/js/app.js')}}"></script>
<script>
	new PerfectScrollbar(".app-container")
</script>
<script>
	$(document).ready(function() {
		$('#example').DataTable();
	  } );
</script>
<script>
	$(document).ready(function() {
		var table = $('#example2').DataTable( {
			lengthChange: false,
			buttons: [ 'copy', 'excel', 'pdf', 'print']
		} );

		table.buttons().container()
			.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
	} );
</script>
<script>
	$(document).ready(function() {
		var table = $('#example3').DataTable( {
			lengthChange: false,
			buttons: [ 'pdf', 'print']
		} );

		table.buttons().container()
			.appendTo( '#example3_wrapper .col-md-6:eq(0)' );
	} );
</script>
<script>
	$(function () {
		$('[data-bs-toggle="popover"]').popover();
		$('[data-bs-toggle="tooltip"]').tooltip();
	})
</script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace()
</script>
@stack('js')
