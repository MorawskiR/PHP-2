<?php
session_start();

if(isset($_POST['email']))
{
	//udana walidacja
	$wszystko_OK=true;

	//sprawdz poprawnosc nickname
	$nick=$_POST['nick'];

	//sprawdzenie dlugosci nicka
	if(strlen($nick) <3 || strlen($nick) >20 )
	{
		$wszystko_OK=false;
		$_SESSION['e_nick']="nick musi posiadac od 2 do 20  znaków";
	}

	if(ctype_alnum($nick)==false)
	{
		$wszystko_OK = false;
		$_SESSION['e_nick']= "nick  moze skladac sie tylko z liter i cyfr";
	}

	//sprawdz poprawnosc adresu email
	$email = $_POST['email'];

	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

	if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false)||$emailB!=$email)
	{
		$wszystko_OK = false;
		$_SESSION['e_email']="Podaj poprawny adres email";
	}

	//sprawdz haslo poprawnosc
	$haslo1= $_POST['haslo1'];
	$haslo2= $_POST['haslo2'];

	if((strlen($haslo1)<8) || strlen($haslo1)>20)
	{
		$wszystko_OK = false;
		$_SESSION['e_haslo']= "hasla nie sa identyczne";
	}
	if($haslo1 != $haslo2)
	{
		
			$wszystko_OK = false;
			$_SESSION['e_haslo']= "haslo musi miec id 8 do 20 znakow";
		
	}

	$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
	
	//czy zaakceptowano regulamin
	if(!isset($_POST['regulamin']))
	{
		
		$wszystko_OK = false;
		$_SESSION['e_regulamin']= "potwierdz akceptacje regulaminu";
	}
	
	require_once "connect.php";

	mysqli_report(MYSQLI_REPORT_STRICT);

	try{
		
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errorno());
		}
		else{

			//czy email juz istnieje
			$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

			if(!$rezultat) throw new Exception($polaczenie->error);

			$ile_takich_maili = $rezultat->num_rows;
			if($ile_takich_maili>0)
			{
				$wszystko_OK = false;
				$_SESSION['e_email']= "istnieje juz konto przpisane do tego adresu emailu";
			}

			//czy nick juz istnieje
			$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

			if(!$rezultat) throw new Exception($polaczenie->error);

			$ile_takich_nickow = $rezultat->num_rows;
			if($ile_takich_nickow>0)
			{
				$wszystko_OK = false;
				$_SESSION['e_nick']= "istnieje juz taki nick";
			}
						

			if($wszystko_OK==true)
			{
				//hurra, wszystkie testy zaliczone , dodajemy gracza do bazy
				if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL,'$nick', '$haslo_hash', '$email',100,100,100,14)"))
				{
					$_SESSION['udanarejestracja'] = true;
					header('Location: witamy.php');
				}
				else 
				{
					throw new Exception($polaczenie->error);
				}
			}

			$polaczenie->close();

		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;"> Blad serwera , przepraszamy za nidogodnoisci i prosimy o rejestracje w nnym terminie </span>';
		echo '<br /> Informacja developerska: '.$e;
	}


}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - gra przeglądarkowa</title>

	<script src="https://www.google.com/recaptcha/api.js"></script>
	<style>
		.error{
			color: red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
		</style>
	<script>
   function onSubmit(token) {
     document.getElementById("demo-form").submit();
   }
 </script>
</head>

<body>
	
	<form method="post"> 

	Nickname: <br /> <input types="text" name="nick" /> <br />

   	<?php 
	if(isset($_SESSION['e_nick']))
	{
		echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
		unset($_SESSION['e_nick']);
	}
	?>

	E-mail: <br /> <input type="text" name="email" /> <br />

	<?php 
	if(isset($_SESSION['e_email']))
	{
		echo '<div class="error">'.$_SESSION['e_email'].'</div>';
		unset($_SESSION['e_email']);
	}
	?>

	Twoje hasło : <br /> <input type="password" name="haslo1" /> <br />


	<?php 
	if(isset($_SESSION['e_haslo']))
	{
		echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
		unset($_SESSION['e_haslo']);
	}
	?>

	Powtórz hasło : <br /> <input type="password" name="haslo2" /> <br />
	<label>
		<input type="checkbox" name="regulamin" /> Akceptuje reglamin
	</label>
	<?php 
	if(isset($_SESSION['e_regulamin']))
	{
		echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
		unset($_SESSION['e_regulamin']);
	}
	?>
	<!-- <button class="g-recaptcha" 
        data-sitekey="reCAPTCHA_site_key" 
        data-callback='onSubmit' 
        data-action='submit'>Submit</button> -->

	<br />
	<br />
	<input type="submit" value="Zarejestruj sie"> 
</form>

</body>
</html>