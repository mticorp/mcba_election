<!-- jQuery -->
<script src="<?php echo e(asset('backend/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo e(asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('backend/js/adminlte.min.js')); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo e(asset('backend/js/demo.js')); ?>"></script>
<!-- DataTables -->
<script src="<?php echo e(asset('backend/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/datatables-select/js/dataTables.select.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/datatables-select/js/select.bootstrap4.min.js')); ?>"></script>
<!-- Toastr -->
<script src="<?php echo e(asset('backend/plugins/toastr/toastr.min.js')); ?>"></script>
<!-- Select 2 -->
<script src="<?php echo e(asset('backend/plugins/select2/js/select2.full.min.js')); ?>"></script>

<script src="<?php echo e(asset('backend/js/bootstrap-toggle.min.js')); ?>"></script>
<!-- jQuery Mapael -->
<script src="<?php echo e(asset('backend/plugins/jquery-mousewheel/jquery.mousewheel.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/raphael/raphael.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/jquery-mapael/jquery.mapael.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/plugins/jquery-mapael/maps/usa_states.min.js')); ?>"></script>

<!-- Summernote -->
<script src="<?php echo e(asset('backend/plugins/summernote/summernote-bs4.min.js')); ?>"></script>

<!-- bs-custom-file-input -->
<script src="<?php echo e(asset('backend/plugins/bs-custom-file-input/bs-custom-file-input.min.js')); ?>"></script>

<!-- Bootstrap Switch -->
<script src="<?php echo e(asset('backend/plugins/bootstrap-switch/js/bootstrap-switch.min.js')); ?>"></script>
 <!-- print page -->
<script src="<?php echo e(asset('backend/js/jQuery.print.js')); ?>"></script>

<script src="<?php echo e(asset('js/jquery-confirm.min.js')); ?>"></script>

<script src="<?php echo e(asset('js/jquery.blockUI.js')); ?>"></script>
<!-- ChartJS -->
<script src="<?php echo e(asset('backend/plugins/chart.js/Chart.min.js')); ?>"></script>
<!-- Jquery UI -->
<script src="<?php echo e(asset('backend/plugins/jquery-ui/jquery-ui.js')); ?>"></script>

<script src="<?php echo e(asset('backend/js/nrc_format.js')); ?>"></script>
<script>
     window._token = '<?php echo e(csrf_token()); ?>';

     $('.select2').select2();

   toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "5000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
</script>
<?php echo $__env->yieldContent('javascript'); ?>
<?php /**PATH /var/www/mcba_election/resources/views/back-partials/javascript.blade.php ENDPATH**/ ?>