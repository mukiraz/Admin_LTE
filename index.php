<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <?php 
		require_once "header.php";
   ?>  

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

<?php 
require_once "navbar.php";
?>

<?php 
require_once "sidebar.php";
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">


        	<!--Kodlar buraya gelecek-->











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
require_once "controlsidebar.php";
 ?>

<?php 
require_once "footer.php";
 ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<?php 
require_once "scripts.php";
 ?>

</body>
</html>
