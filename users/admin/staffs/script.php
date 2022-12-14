<script src="../../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- /form validatejs -->

<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- Bootstrap 4 -->
<script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- <script src="../../../plugins/datatables/jquery.dataTables.js"></script> -->
<script src="../../../plugins/datatables/datatables.min.js"></script>

<!-- <script src="../../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> -->
<script src="../../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../../plugins/dist/js/adminlte.min.js"></script>
<script src="../../../plugins/moment/moment.min.js"></script>
<script src="../../../plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>

<script src="../../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- <script src="../../../plugins/katex/katex.min.js"></script> -->
<script src="../../../plugins/summernote/summernote-bs4.min.js"></script>

<script src="../../../script.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="../../dist/jspdf.min.js"></script>
<script src="../../dist/jspdf.plugin.autotable.min.js"></script> -->
<script>
$(document).ready(function(){
    <?php $timeout = 300;
    $_SESSION['adminlogin'] = time() ;
    ?>
    availabletime = 60 * <?php echo 0.6 ?>;
 
     mytime = <?php echo (time()+$timeout) - $_SESSION['adminlogin'] ?>;
     setInterval(() => {
       mytime -= 1;
       if(mytime < 0){
       window.location.assign('../logout.php');
       }
 
     }, 1000);
  })
     </script>