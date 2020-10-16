<?php 
require_once 'dbconfig.php';

$conn=mysqli_connect(DBHOST,DBUSER,DBPWD,DBNAME);

if (!$conn) {
	die("Connection failed: ".mysqli_connect_error());
}
 ?>