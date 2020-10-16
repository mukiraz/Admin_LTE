<?php
//Oturumu başlatıyoruz
session_start();
ob_start();
//Veri tabanına bağlantı saysasını dahil ettik
include 'class.crud.php';
//Veritabanından kullanıcıları çektik

$db=new crud();
if (isset($_SESSION['accounts']["accounts_username"])) {
} else {
	header('Location: ../login/logout.php');
  exit;
}

$sql=$db->qSql("SELECT * FROM settings");
$row=$sql->fetchAll(PDO::FETCH_ASSOC);
foreach ($row as $key) {
	$settings[$key['settings_key']]=$key['settings_value'];
}
 
?>