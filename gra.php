<?php
session_start();
if(!isset($_SESSION['zalogowany']))
{
    header('Location:index.php');
    exit();
}

?>
<!DOCTYPE HTML>
<html lang="pl">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title> osadnicy gra przeglądarkowa</title>
</head>

<body>

    <?php

    echo "<p> Witaj".$_SESSION['user'].'! [<a href="logout.php">log out </a>]</p>';
    echo "<p> <b>Drewno</b>:".$_SESSION['drewno'];
    echo "| <b>Kamien</b>:".$_SESSION['kamien'];
    echo "| <b>Drewno</b>:".$_SESSION['zboze']."</p>";

    echo "<p> <b>email</b>:".$_SESSION['email'];
    echo "<br />Dni premium </b>:".$_SESSION['dnipremium']."</p>";

    ?>

</body>
</html>
