<?php

if (!isset($_SESSION)) {
	session_start();
}


require_once 'dbconfig.php';


class crud 
{

	private $db;
	private $dbhost=DBHOST;
	private $dbuser=DBUSER;
	private $dbpwd=DBPWD;
	private $dbname=DBNAME;
	
	function __construct()
	{
		try {
			$this->db=new PDO("mysql:host=".$this->dbhost.";dbname=".$this->dbname.';charset=utf8',$this->dbuser,$this->dbpwd);			

			
		} catch (Exception $e) {
			die("Bağlantı başarısız:".$e->getMessage());
		}
	}


	public function kullaniciGiris($accounts_username,$accounts_pass) {

		try {

			$failattemps = $this->db->query("SELECT count(*) FROM logs WHERE logs_status='1' AND logs_ip='{$_SERVER['REMOTE_ADDR']}' and logs_user='{$_POST['email']}' and `logs_time` >=NOW()- INTERVAL 10 MINUTE")->fetchColumn();

			//Kullanıcı hesapları tablosundan giriş yapan kullanıcının parolasını sorguluyoruz.
			$kullanicisor=$this->db->prepare("SELECT * FROM accounts WHERE accounts_username=:accounts_username");
			$kullanicisor->execute(array(
				'accounts_username'=>$accounts_username,		

			));
			//Kullanıcı varsa bize bir değer döndürüyor ve bu değeri bir değişkene alıyoruz.


			//Eğer bize bir değer dönerse işlem yapıyıruz. Dönmezse "Kullanıcı Adı veya Parola Hatalı." değerini ekrana bastırıyoruz.
			if ($kullanicisor->rowCount()==1) 
			{	
				$row=$kullanicisor->fetch(PDO::FETCH_ASSOC);
		//10 dakika içerisinde 10'dan daha az hatalı giriş yapmışsa parola doğrulama işlemine geçiyoruz.
				if ($failattemps<10) 
				{
					if ($row["accounts_status"]==1) {
						//Girilen parola, veri tabanındaki parola ile işleşiyor mu ona bakıyoruz. Her iki durumda da veri tabanına giriş kaydını tutuyoruz.
						if (password_verify($accounts_pass, $row["accounts_pass"])) {
						//Geçerli oturum kimliğini yenisiyle değiştiriyoruz.
							
							session_regenerate_id();
							$_SESSION['accounts']=[
								"loggedin"=>TRUE,
								"accounts_id"=>$row['accounts_id'],
								"accounts_tc"=>$row['accounts_tc'],
								"accounts_ad"=>$row['accounts_ad'],
								"accounts_soyad"=>$row['accounts_soyad'],
								"accounts_gsm"=>$row['accounts_gsm'],
								"accounts_yetki"=>$row['accounts_yetki'],
								"accounts_file"=>$row['accounts_file'],
								"accounts_time"=>$row['accounts_time'],
								"accounts_adres"=>$row['accounts_adres'],
								"accounts_il"=>$row['accounts_il'],
								"accounts_ilce"=>$row['accounts_ilce'],
								"accounts_status"=>$row['accounts_status'],
								"accounts_username"=>$accounts_username							

							];

						//IP adresini, kullanıcı adını ve başarılı girişi kaydediyoruz.
							$logkayit=$this->db->prepare("INSERT INTO logs SET logs_ip=?, logs_user=?, logs_status=?");
							$logkayit->execute([$_SERVER['REMOTE_ADDR'],$accounts_username,2]);
							echo 1;							
						} else
						{
				//Eğer 10 dan daha az başarısız giriş denemesi olursa olayı kayıt altına almak için aşağıdaki kodu kullanıyoruz.
							if ($failattemps<10) 
							{
								$logkayit=$this->db->prepare("INSERT INTO logs SET logs_ip=?, logs_user=?, logs_status=?");
								$logkayit->execute([$_SERVER['REMOTE_ADDR'],$accounts_username,1]);
					//Ekrana durumu bastırıyoruz. 
								if ($failattemps!=0) {
									echo "Kullanıcı Adı veya Parola Hatalı.";
								} 
								else 
								{
									echo "Kullanıcı Adı veya Parola Hatalı.";
								}

							} 
						}
						
					} else
					{
						echo "Hesabınız bloke edilmiş. Lütfen yöneticinizle görüşün.";
					}		


				} else
				{
			//10 dakika içerisinde 10 defadan fazla aynı IP ile giriş yapmaya çalışmışsa bu uyarıyı veriyoruz.
					echo "Size belirlenen sürede 10 defadan fazla yanlış parola girdiğiniz için bir süreliğine hesabınız askıya alındı.";
				}

			} else
			{
				echo "Kullanıcı Adı veya Parola Hatalı.";
			}
			



			
		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}

	}

	public function wread($table,$columns,$values,$options=[]){

		try {			
			if (isset($options['columns_name']) && empty($options['limit']) ) {
				$stmt=$this->db->prepare("SELECT * FROM $table WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']}");
			} else if (isset($options['columns_name']) && isset($options['limit']) ) {

				$stmt=$this->db->prepare("SELECT * FROM $table WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limit']}");
			} else {
				$stmt=$this->db->prepare("SELECT * FROM $table WHERE $columns=?");

			}
			$stmt->execute([htmlspecialchars($values)]);

			return $stmt;
		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}
	}

	public function wreadinnerjoin($table1,$table2,$table1key,$table2key,$select,$columns,$values,$options=[]){

		try {

			
				
			if (isset($options['columns_name']) && empty($options['limit']) ) {
				$stmt=$this->db->prepare("SELECT $select FROM $table1 INNER JOIN $table2 ON $table1.$table1key=$table2.$table2key WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']}");
				
			} else if (isset($options['columns_name']) && isset($options['limit']) ) {

				$stmt=$this->db->prepare("SELECT $select FROM $table1 INNER JOIN $table2 ON $table1.$table1key=$table2.$table2key WHERE $columns=? order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limit']}");
			} else {
				$stmt=$this->db->prepare("SELECT $select FROM $table1 INNER JOIN $table2 ON $table1.$table1key=$table2.$table2key WHERE $columns=?");

			}
			
			$stmt->execute([htmlspecialchars($values)]);

			return $stmt;
		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}
	}

	public function delete($table,$columns,$values,$filename=null) {
		try {
			if (isset($options['file_name'])) {
				if (!empty($filename)) {
					unlink("dimg/$table/".$filename);
				}
			}

			$stmt=$this->db->prepare("DELETE FROM $table WHERE $columns=?");
			$stmt->execute([htmlspecialchars($values)]);

			return ['status' => TRUE];


		} catch (Exception $e) {
			return ['status' => FALSE, 'error' => $e->getMessage()];
		}
	}
	public function read($table,$options=[]){

		try {


			if (isset($options['columns_name']) && empty($options['limit']) ) {
				$stmt=$this->db->prepare("SELECT * FROM $table order by {$options['columns_name']} {$options['columns_sort']}");
			} else if (isset($options['columns_name']) && isset($options['limit']) ) {

				$stmt=$this->db->prepare("SELECT * FROM $table order by {$options['columns_name']} {$options['columns_sort']} limit {$options['limit']}");
			} else {
				$stmt=$this->db->prepare("SELECT * FROM $table");

			} 
			$stmt->execute();

			return $stmt;
		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}
	}

	public function addvalue($argse){
		//implode fonksiyonu dizileri birleştiriyor.
		$values=implode(',',array_map(function ($item){
			return $item."=?";
		},array_keys($argse)));

		return $values;
	}

	public function insert($table,$values,$options=[]){

		try {

			if (isset($options['slug'])) {
				if (empty($values[$options['slug']])) {
					$values[$options['slug']]=$this->seo($values[$options['title']]);
				} else {
					$values[$options['slug']]=$this->seo($values[$options['slug']]);

				}
			}

			if (isset($options['file_name'])) {

				if (!empty($_FILES[$options['file_name']]['name'][0])) {

					$name_y=$this->imageupload(
						$_FILES[$options['file_name']]['name'][0],
						$_FILES[$options['file_name']]['size'][0],
						$_FILES[$options['file_name']]['tmp_name'][0],
						$options['dir']
					);

					$values+=[$options['file_name']=>$name_y] ;
				}
			}

			unset($values[$options['form_name']]);

			
			$stmt=$this->db->prepare("INSERT INTO $table SET {$this->addValue($values)}");

			$stmt->execute(array_values($values));

			return ['status'=> TRUE];


		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}

	}

	public function imageupload($name,$size,$tmp_name,$dir,$file_delete=null){

		try {

			$izinli_uzantılar=[
				'jpg',
				'jpge',
				'png',
				'ico',
			];

			$ext=strtolower(substr($name, strpos($name, '.')+1));

			if (in_array($ext, $izinli_uzantılar)===false) {
				throw new Exception('Bu dosya türü kabul edilmemektedir.');
			} 

			if ($size>1048576) {
				throw new Exception('Dosya boyutu çok büyük.');

			} 

			$name_y=uniqid().".".$ext;

			if (!@move_uploaded_file($tmp_name, "$dir/$name_y")) {
				throw new Exception('Dosya yükleme hatası.');
			}

			if (!empty($file_delete)) {
				unlink("$dir/$file_delete");

			}

			return $name_y;

		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}

	}

	public function imageuploaddir($name,$name_x,$size,$tmp_name,$dir,$file_delete=null){

		try {

			$izinli_uzantılar=[
				'jpg',
				'jpge',
				'png',
				'ico',
			];

			$ext=strtolower(substr($name, strpos($name, '.')+1));

			if (in_array($ext, $izinli_uzantılar)===false) {
				throw new Exception('Bu dosya türü kabul edilmemektedir.');
			} 

			if ($size>1048576) {
				throw new Exception('Dosya boyutu çok büyük.');

			}
			$name_y=uniqid().".".$ext;

			if (!empty($file_delete)) {
				unlink("$dir/$file_delete");

			}

			if (!@move_uploaded_file($tmp_name, "$dir/$name_x")) {
				throw new Exception('Dosya yükleme hatası.');
			}
			

			return ['status' => TRUE];

		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}

	}

	public function update($table, $values, $options = [])
	{

		try {

		// print_r($_FILES);
		// print_r($_POST);
		// print_r($options);
		// exit;		
		if (isset($options['slug'])) {
			if (empty($values[$options['slug']])) {
				$values[$options['slug']]=$this->seo($values[$options['title']]);
			} else {
				$values[$options['slug']]=$this->seo($values[$options['slug']]);

			}
		}


		if (isset($options['file_name'])) {
			if (isset($_FILES[$options['file_name']]['name'][0])) {

				$name_y = $this->imageUpload(
					$_FILES[$options['file_name']]['name'][0],
					$_FILES[$options['file_name']]['size'][0],
					$_FILES[$options['file_name']]['tmp_name'][0],
					$options['dir'],
					$values[$options['file_delete']]
				);

/*            	if (!$name_y['status']) {
            		return ['status' => FALSE, 'error' => $name_y['error']];
            		exit;
            	} else {
            		$values += [$options['file_name'] => $name_y];
            	}*/

            	$values += [$options['file_name'] => $name_y];
            	if (isset($values["accounts_username"])) {
            		if ($values["accounts_username"]==$_SESSION['accounts']['accounts_username']) {
            			$_SESSION['accounts']['accounts_file']=$name_y;
            		}
            	}
            	


            }
        }

			            //Eski Resim Dosyasının Değerini Temizleme...
        if (isset($options['file_name'])) {
        	unset($values[$options['file_delete']]);
        }          

        $columns_id = $values[$options['columns']];
        unset($values[$options['form_name']]);
        unset($values[$options['columns']]);
        $valuesExecute = $values;
        $valuesExecute += [$options['columns'] => $columns_id];


//             echo "<pre>";
/*          print_r(array_values($valuesExecute));
            exit;*/
//



        $stmt = $this->db->prepare("UPDATE $table SET {$this->addValue($values)} WHERE {$options['columns']}=?");
        $stmt->execute(array_values($valuesExecute));

        if ($stmt->rowCount() > 0) {
        	return ['status' => TRUE];
        } else {
        	throw new Exception('İşlem Başarısız');
        }

    } catch (Exception $e) {

    	return ['status' => FALSE, 'error' => $e->getMessage()];
    }

}


public function newupdate($table, $values, $options = [])
{

	try {
		
		/*print_r(isset($options['file_name']));
		print_r($values);
		print_r($options);
		exit;*/		

		if (isset($options['file_name'])) {
			if (isset($_FILES[$options['file_name']]['name'])) {

				$name_y = $this->imageUpload(
					$_FILES[$options['file_name']]['name'],
					$_FILES[$options['file_name']]['size'],
					$_FILES[$options['file_name']]['tmp_name'],
					$options['dir'],
					$values[$options['file_delete']]
				);

				$values += [$options['file_name'] => $name_y];

			}
		}

		//Eski Resim Dosyasının Değerini Temizleme...
		if (isset($options['file_name'])) {
			unset($values[$options['file_delete']]);
		}          

		$columns_id = $values[$options['columns']];
        //unset($values[$options['form_name']]);
		unset($values[$options['columns']]);
		$valuesExecute = $values;
		$valuesExecute += [$options['columns'] => $columns_id];


//             echo "<pre>";

//



		$stmt = $this->db->prepare("UPDATE $table SET {$this->addValue($values)} WHERE {$options['columns']}=?");
		$stmt->execute(array_values($valuesExecute));

		if ($stmt->rowCount() > 0) {
			return ['status' => TRUE];
		} else {
			throw new Exception('İşlem Başarısız');
		}

	} catch (Exception $e) {

		return ['status' => FALSE, 'error' => $e->getMessage()];
	}

}

public function qSql($sql,$options=[]) {
	try {

		$stmt=$this->db->prepare($sql);
		$stmt->execute();
		return $stmt;


	} catch (Exception $e) {
		return ['status'=> FALSE, 'error'=>$e->getMessage()];
	}
}


}





?>