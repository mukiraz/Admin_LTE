<?php 


class mailing 
{

	function resetPassword($userEmail){


		try {

			$selector= bin2hex(random_bytes(8));
			$token=random_bytes(32);


			$url="127.0.0.1/mukiraz/admin/pages/login/recover-password.php?selector=".$selector."&validator=".bin2hex($token);

			$expires=date("U")+ 3600;

			require 'dbh.inc.php';

			$sql="DELETE from pwdreset where pwdReset_email=?";
			$stmt=mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "Bir hata oluştu.";
				exit();
			} else {
				mysqli_stmt_bind_param($stmt,"s", $userEmail);
				mysqli_stmt_execute($stmt);
			}



			$sql="INSERT into pwdreset (pwdReset_email,pwdReset_selector,pwdReset_token,pwdReset_expires ) VALUES (?,?,?,?);";

			$stmt=mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "Bir hata oluştu.";
				exit();
			} else {
				$hashedToken=password_hash($token, PASSWORD_DEFAULT);
				mysqli_stmt_bind_param($stmt,"ssss", $userEmail,$selector,$hashedToken,$expires);
				mysqli_stmt_execute($stmt);
			}

			

			mysqli_stmt_close($stmt);
			mysqli_close($conn);


			$to=$userEmail;
			$subject="Admin sayfası parola yenileme.";
			$message="<p>Parola yenilenmesi için bir talep alınmıştır. Aşağıdaki bağlantı ile parolanızı yenileyebilirsiniz. Eğer bu talebi siz yapmadıysanız lütfen bu mesajı umursamayın.</p>";
			$message.="<p>Bağlantınız aşağıdadır:<br>";
			$message.='<a href=	"'.$url.'">'.$url. '</a></p>';

			$message="From: mukiraz <mukiraz@gmail.com>\r\n";
			$headers="Reply-To: mukiraz@gmail.com\r\n";
			$headers.="Content-type: text/html\r\n";

			//mail($to,$subject,$message,$message);

			echo $url;




		} catch (Exception $e) {
			return ['status'=> FALSE, 'error'=>$e->getMessage()];
		}

	}
	
	
}

?>