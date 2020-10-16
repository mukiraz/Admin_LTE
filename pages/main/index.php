<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <?php 
		require_once "partials/header.php";
   ?>  

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

<?php 
require_once "partials/navbar.php";
?>

<?php 
require_once "partials/sidebar.php";
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        	<!--Kodlar buraya gelecek-->

          <div class="col-md-12">





          </div>











        	<!--/Kodlar buraya gelecek-->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
<?php 
require_once "partials/controlsidebar.php";
 ?>

<?php 
require_once "partials/footer.php";
 ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<?php 
require_once "partials/scripts.php";
 ?>

 <!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>



<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": true,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

</body>
</html>
