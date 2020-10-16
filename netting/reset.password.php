<?php 
if (isset($_GET["login"])) {


	$selector=$_POST["selector"];
	$validator=$_POST["validator"];
	$password=$_POST["password"];

	$currentdate=date("U");

	require 'dbh.inc.php';


	$sql="SELECT * FROM pwdreset WHERE pwdReset_selector=? AND pwdReset_expires>=?;";
	$stmt=mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "Bir hata oluştu.";
		exit();
	} else {
		mysqli_stmt_bind_param($stmt,"ss", $selector,$currentdate);
		mysqli_stmt_execute($stmt);

		$result =mysqli_stmt_get_result($stmt);

		if (!$row=mysqli_fetch_assoc($result)) {
			echo '"Parolamı unuttum " formunu yeniden doldurunuz.';
			exit();
		} else {
			$tokenBin =hex2bin($validator);	
			$tokenCheck=password_verify($tokenBin, $row["pwdReset_token"]);		

			if ($tokenCheck===false) {
				echo '"Parolamı unuttum " formunu yeniden doldurunuz.';
				exit();
			} elseif ($tokenCheck===true) {

				$tokenEmail=$row["pwdReset_email"];
				
				$sql="SELECT * FROM accounts WHERE accounts_username=?;";

				$stmt=mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "Bir hata oluştu.";
					exit();
				} else {


					mysqli_stmt_bind_param($stmt,"s",$tokenEmail);
					mysqli_stmt_execute($stmt);
					$result =mysqli_stmt_get_result($stmt);					
					if (!$row=mysqli_fetch_assoc($result)) {
						echo "Bir hata oluştu.";
						exit();
					} else {


						$sql="UPDATE accounts SET accounts_pass=? WHERE accounts_username=? ";
						$stmt=mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							echo "Bir hata oluştu.";
							exit();
						} else {
							$newPwdHash=password_hash($password, PASSWORD_DEFAULT);
							mysqli_stmt_bind_param($stmt,"ss",$newPwdHash,$tokenEmail);
							mysqli_stmt_execute($stmt);

							$sql="DELETE from pwdreset where pwdReset_email=?";
							$stmt=mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($stmt, $sql)) {
								echo "Bir hata oluştu.";
								exit();
							} else {
								mysqli_stmt_bind_param($stmt,"s", $tokenEmail);
								mysqli_stmt_execute($stmt);
								echo 1;
							}
						}
					}
				}
			}
		}
	}

} else {

	echo "Bir hata oldu.";
}
?>