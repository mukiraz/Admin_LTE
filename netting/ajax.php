<?php 

require_once 'class.crud.php';
require_once 'mailing.php';
$db=new crud();
$mailinigs=new mailing();

if (isset($_GET['login'])) {
	
	$sonuc=	$db->kullanicigiris(htmlspecialchars($_POST['email']),htmlspecialchars($_POST['password']));	
}

if (isset($_GET['forgot_password'])) {
	
	$sonuc=$mailinigs->resetPassword($_POST['email']);
	echo $sonuc;
}

if (isset($_GET['account_insert'])) {
	
	$sonuc=$db->insert("accounts",$_POST,
		[
			"form_name"=>"accounts_insert",
			"pass"=>"accounts_pass",
			"dir"=>"../dimg/accounts",
			"file_name"=>"accounts_file"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['accounts_data'])) {
	$sql=$db->wread("accounts","accounts_id",$_GET['accounts_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	unset($row["accounts_pass"]);
	unset($row["accounts_time"]);
	echo json_encode($row);
}

if (isset($_GET['accounts_update'])) {

	$sonuc=$db->update("accounts",$_POST,
		[
			"form_name"=>"accounts_update",
			"columns"=>"accounts_id",
			"file_delete"=>"delete_file",               
			"dir"=>"../dimg/accounts",
			"file_name"=>"accounts_file"
		]

	);

	if ($_SESSION['accounts']['accounts_username']==$_POST["accounts_username"]) {
		session_regenerate_id();
		$_SESSION['accounts']=[				
			"accounts_tc"=>$_POST["accounts_tc"],
			"accounts_ad"=>$_POST["accounts_ad"],
			"accounts_soyad"=>$_POST["accounts_soyad"],
			"accounts_gsm"=>$_POST["accounts_gsm"],
			"accounts_yetki"=>$_POST["accounts_yetki"],
			"accounts_adres"=>$_POST["accounts_adres"],
			"accounts_il"=>$_POST["accounts_il"],
			"accounts_ilce"=>$_POST["accounts_ilce"],
			"accounts_status"=>$_POST["accounts_status"]
		];
		
	}




	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['accounts_delete'])) {	

	if ($_POST["accounts_deleteid"]==1) {
		echo "Bu kullanıcı silinemez";
	} else {
		$sonuc=$db->delete("accounts","accounts_id",$_POST["accounts_deleteid"],$_POST["accounts_deletefile"]);

		if ($sonuc['status']) {
			echo 1;
		} else {
			echo "Kayıt Başarısız";
		}
	}
}

if (isset($_GET['settings_data'])) {
	$sql=$db->wread("settings","settings_id",$_GET['settings_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}


if (isset($_GET['settings_update'])) {

	if (isset($_FILES["settings_value"]["name"])) {
		$sonuc=$db->newupdate("settings",$_POST,
			[
				"columns"=>"settings_id",
				"file_name"=>"settings_value",
				"file_delete"=>"delete_file",
				"dir"=>"../../assets/img/settings"
			]

		);
	} else
	{
		unset($_POST["delete_file"]);
		$sonuc=$db->newupdate("settings",$_POST,
			[
				"columns"=>"settings_id"
			]
		);
	} 

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}


}


if (isset($_GET['bannerslider_insert'])) {
	/*print_r($_POST);
	print_r($_FILES);
	exit;*/
	
	$sonuc=$db->insert("bannersliders",$_POST,
		[
			"form_name"=>"bannersliders_insert",
			"dir"=>"../../assets/img/bannersliders",
			"file_name"=>"bannersliders_file"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['bannersliders_data'])) {
	$sql=$db->wread("bannersliders","bannersliders_id",$_GET['bannersliders_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['bannersliders_delete'])) {	

	$sonuc=$db->delete("bannersliders","bannersliders_id",$_POST["bannersliders_deleteid"],$_POST["bannersliders_deletefile"]);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['bannersliders_update'])) {

	$sonuc=$db->update("bannersliders",$_POST,
		[
			"form_name"=>"bannersliders_update",
			"columns"=>"bannersliders_id",
			"file_delete"=>"delete_file",               
			"dir"=>"../../assets/img/bannersliders",
			"file_name"=>"bannersliders_file"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['servicesphoto_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile"]['name'][0],
		"service-bg3.png",
		$_FILES["settings_valuefile"]['size'][0],
		$_FILES["settings_valuefile"]['tmp_name'][0],
		"../../assets/img/servicesphoto",
		"service-bg3.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['servicesphoto_update2'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile2"]['name'][0],
		"service-bg2.png",
		$_FILES["settings_valuefile2"]['size'][0],
		$_FILES["settings_valuefile2"]['tmp_name'][0],
		"../../assets/img/servicesphoto",
		"service-bg2.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['services_data'])) {
	$sql=$db->wread("services","services_id",$_GET['services_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['services_update'])) {

	$sonuc=$db->update("services",$_POST,
		[
			"form_name"=>"services_update",
			"columns"=>"services_id"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['slogan_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile"]['name'][0],
		"explore.png",
		$_FILES["settings_valuefile"]['size'][0],
		$_FILES["settings_valuefile"]['tmp_name'][0],
		"../../assets/img/mainphoto",
		"explore.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['numbers_data'])) {
	$sql=$db->wread("numbers","numbers_id",$_GET['numbers_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['numbers_update'])) {

	$sonuc=$db->update("numbers",$_POST,
		[
			"form_name"=>"numbers_update",
			"columns"=>"numbers_id"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['brand_insert'])) {
	
	$sonuc=$db->insert("brands",$_POST,
		[
			"form_name"=>"brands_insert",
			"dir"=>"../../assets/img/brand",
			"file_name"=>"brands_file"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['brands_data'])) {
	$sql=$db->wread("brands","brands_id",$_GET['brands_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['brands_delete'])) {	

	if ($_POST["brands_deleteid"]<=5) {
		echo "Bu kullanıcı silinemez";
	} else {
		$sonuc=$db->delete("brands","brands_id",$_POST["brands_deleteid"],$_POST["brands_deletefile"]);

		if ($sonuc['status']) {
			echo 1;
		} else {
			echo "Kayıt Başarısız";
		}
	}
}

if (isset($_GET['brands_update'])) {
/*print_r($_POST);
	print_r($_FILES);
	exit;*/


	$sonuc=$db->update("brands",$_POST,
		[
			"form_name"=>"brands_update",
			"columns"=>"brands_id",
			"file_delete"=>"delete_file",               
			"dir"=>"../../assets/img/brand",
			"file_name"=>"brands_file"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['footertwo_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile"]['name'][0],
		"footer.png",
		$_FILES["settings_valuefile"]['size'][0],
		$_FILES["settings_valuefile"]['tmp_name'][0],
		"../../assets/img/footer",
		"footer.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['messageimage_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile"]['name'][0],
		"contact-top.png",
		$_FILES["settings_valuefile"]['size'][0],
		$_FILES["settings_valuefile"]['tmp_name'][0],
		"../../assets/img/bg",
		"contact-top.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['blogstags_insert'])) {
	
	$sonuc=$db->insert("blogstags",$_POST,
		[
			"form_name"=>"blogstags_insert"			
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['blogstags_data'])) {
	$sql=$db->read("blogstags",[
		"columns_name"=>"blogstags_id",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['blogs_data'])) {
	$sql=$db->read("blogs",[
		"columns_name"=>"blogs_time",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['blogs_data2'])) {
	$sql=$db->read("blogs",[
		"columns_name"=>"blogs_time",
		"columns_sort"=>"ASC",
		"limit"=>"6"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['blogs_category'])) {
	$sql=$db->read("blogscategory",[
		"columns_name"=>"blogsCategory_id",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($row);
}


if (isset($_GET['blogstagsupt_data'])) {
	$sql=$db->wread("blogstags","blogstags_id",$_GET['blogstags_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);	
	echo json_encode($row);
}

if (isset($_GET['blogstags_update'])) {

	$sonuc=$db->update("blogstags",$_POST,
		[
			"form_name"=>"blogstags_update",
			"columns"=>"blogstags_id"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['blogstags_delete'])) {	
	
	$sonuc1=$db->delete("blogstags","blogstags_id",$_POST["blogstags_deleteid"]);
	$sonuc2=$db->delete("blogstagstables","blogstags_id",$_POST["blogstags_deleteid"]);
	if ($sonuc1['status'] and $sonuc2['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
	
}

if (isset($_GET['blogsCategory_data'])) {
	$sql=$db->read("blogsCategory",[
		"columns_name"=>"blogsCategory_id",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($row);
}

if (isset($_GET['blogsCategoryupt_data'])) {
	$sql=$db->wread("blogsCategory","blogsCategory_id",$_GET['blogsCategory_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);	
	echo json_encode($row);
}

if (isset($_GET['blogsCategory_update'])) {

	$sonuc=$db->update("blogsCategory",$_POST,
		[
			"form_name"=>"blogscat_update",
			"columns"=>"blogsCategory_id"
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['blogsCategory_delete'])) {	

	$sonuc=$db->delete("blogsCategory","blogsCategory_id",$_POST["blogsCategory_id"]);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
	
}

if (isset($_GET['blogscat_insert'])) {

	$sonuc=$db->insert("blogsCategory",$_POST,
		[
			"form_name"=>"blogscategory_insert"			
		]

	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}


if (isset($_GET['blogs_insert'])) {
	$id=uniqid();	
	
	foreach ($_POST["blogs_tags"] as $key => $value) {
		$sonuc1=$db->insert("blogstagstables",["blogs_id"=>$id,"blogstags_id"=>$value,"blogstags_insert"=>"null"],
			[
				"form_name"=>"blogstags_insert"			
			]

		);

	}
	unset($_POST["blogs_tags"]);
	$_POST+=["blogs_id"=>$id];

	$sonuc2=$db->insert("blogs",$_POST,
		[
			"form_name"=>"blogs_insert",
			"dir"=>"../../assets/img/blogs",
			"file_name"=>"blogs_file"
		]

	);

	if ($sonuc1['status'] and $sonuc2['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['blogsupt_data'])) {
	$sql=$db->wread("blogs","blogs_id",$_GET['blogs_id']);
	$row=$sql->fetch(PDO::FETCH_ASSOC);	
	echo json_encode($row);
}

if (isset($_GET['blogs_delete'])) {	


	$sonuc1=$db->delete("blogs","blogs_id",$_POST["blogs_id"]);
	$sonuc2=$db->delete("blogstagstables","blogs_id",$_POST["blogs_id"]);

	if ($sonuc1['status'] and $sonuc2['status'] ) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
	
}

if (isset($_GET['blogsupttag_data'])) {

	$sql=$db->wread("blogstagstables","blogs_id",$_GET['blogs_id']);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);	
	echo json_encode($row);
	
}


if (isset($_GET['blogs_update'])) {

/*	print_r($_POST);
	exit;*/

	$sonuc1=$db->delete("blogstagstables","blogs_id",$_POST["blogs_id"]);

	foreach ($_POST["blogs_tags"] as $key => $value) {
		$sonuc2=$db->insert("blogstagstables",["blogs_id"=>$_POST["blogs_id"],"blogstags_id"=>$value,"blogstags_insert"=>"null"],
			[
				"form_name"=>"blogstags_insert"			
			]

		);

	}
	unset($_POST["blogs_tags"]);

	

	if (isset($_FILES["blogs_file"])) {
		$options=[
			"form_name"=>"blogs_update",
			"columns"=>"blogs_id",
			"file_delete"=>"delete_file",               
			"dir"=>"../../assets/img/blogs",
			"file_name"=>"blogs_file"
		];
	} else {
		$options=[
			"form_name"=>"blogs_update",
			"columns"=>"blogs_id"
		];
		unset($_POST["delete_file"]);	
		//unset($_POST["blogs_file"]);		
	}




	$sonuc3=$db->update("blogs",$_POST,$options);


	if ($sonuc1['status'] or $sonuc2['status'] or $sonuc3['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız. Değişiklik yaptığınıza emin misiniz?";
	}
}

if (isset($_GET['blogstagshasrow_data'])) {	
	$sql=$db->wreadinnerjoin("blogstagstables","blogs","blogs_id","blogs_id","blogs.blogs_title","blogstags_id",$_GET["blogstags_id"],[
		"columns_name"=>"blogs_title",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);	
	echo json_encode($row);

}

if (isset($_GET['blogscatshasrow_data'])) {	
	$sql=$db->wread("blogs","blogs_category",$_GET["blogsCategory_id"],[
		"columns_name"=>"blogs_title",
		"columns_sort"=>"ASC"
	]);
	$row=$sql->fetchAll(PDO::FETCH_ASSOC);	
	echo json_encode($row);

}

if (isset($_GET['gezginiletisim_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile"]['name'][0],
		"one.png",
		$_FILES["settings_valuefile"]['size'][0],
		$_FILES["settings_valuefile"]['tmp_name'][0],
		"../../assets/img/modal",
		"one.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}

if (isset($_GET['gezginiletisim2_update'])) {


	$sonuc=$db->imageuploaddir(
		$_FILES["settings_valuefile2"]['name'][0],
		"three.png",
		$_FILES["settings_valuefile2"]['size'][0],
		$_FILES["settings_valuefile2"]['tmp_name'][0],
		"../../assets/img/modal",
		"three.png"
	);

	if ($sonuc['status']) {
		echo 1;
	} else {
		echo "Kayıt Başarısız";
	}
}


?>