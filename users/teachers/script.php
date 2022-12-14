<script src="<?= BASE_URL ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= BASE_URL ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- /form validatejs -->


<!-- Bootstrap 4 -->
<script src="<?= BASE_URL ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>plugins/datatables/datatables.min.js"></script>
<script src="<?= BASE_URL ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="<?= BASE_URL ?>plugins/dist/js/adminlte.min.js"></script>
<script src="<?= BASE_URL ?>plugins/summernote/summernote-bs4.min.js"></script>
<script src="<?= BASE_URL ?>assets/loaders/pageloader.js"></script>


<!-- <script src="<?= BASE_URL ?>plugins/datatables/jquery.dataTables.js"></script> -->
<!-- <script src="<?= BASE_URL ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script> -->
<script src="<?= BASE_URL ?>script.js"></script>


<!-- <script src="<?= BASE_URL ?>js/summernote-math.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script> -->
<!-- <script src="<?= BASE_URL ?>plugins/katex/katex.min.js"></script> -->

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="<?= BASE_URL ?>dist/jspdf.min.js"></script>
<script src="<?= BASE_URL ?>dist/jspdf.plugin.autotable.min.js"></script> -->
<script>
  pageLoader.show();
  $(document).ready(function(){
    pageLoader.hide();

    <?php $timeout = 300;
    $_SESSION['login'] = time() ;
    ?>
    availabletime = 60 * <?php echo 0.6 ?>;
 
     mytime = <?php echo (time()+$timeout) - $_SESSION['login'] ?>;
     setInterval(() => {
       mytime -= 1;
       if(mytime < 0){
       window.location.assign('<?= BASE_URL ?>logout.php');
       }
 
     }, 1000);
  })
     </script>