<script src="../../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../../plugins/jquery.countdown/jquery.countdown.min.js"></script>

<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- /form validatejs -->

<!-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> -->
<!-- Bootstrap 4 -->
<script src="../../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../plugins/datatables/datatables.min.js"></script>
<!-- <script src="../../../plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="../../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> -->
<script src="../../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../../plugins/dist/js/adminlte.min.js"></script>
<script src="../../../plugins/moment/moment.min.js"></script>

<script src="../../../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<script src="../../../script.js"></script>
<script src="../../../plugins/summernote/summernote-bs4.min.js"></script>
<script src="../../../plugins/katex/katex.min.js"></script>
<script src="../../../js/summernote-math.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script> -->

<script>
  
  $(document).ready(function(){
    <?php $timeout = 60000;
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