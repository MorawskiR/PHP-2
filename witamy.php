<?php

	session_start();
	
	if ((!isset($_SESSION['udanarejestracja'])))
	{
		header('Location: index.php');
		exit();
	}
	else{
		unset($_SESSION['udanarejestracja']);
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>
	
	dziekujemy za rejestracje w serwisie <br /><br />
	
    <a href="index.php" > zaloguj sie na swoje konto </a>
    <br /> <br />


</body>
</html>