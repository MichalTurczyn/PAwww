<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
if($_GET['idp'] == 'glowna' || $_GET['idp'] == '') {
    $strona = 'html/glowna.html'; 
} else {
    if ($_GET['idp'] == 'burj') {
        $strona = 'html/burj.html';
    } elseif ($_GET['idp'] == 'merdeka') {
        $strona = 'html/merdeka.html';
    } elseif ($_GET['idp'] == 'sh_tower') {
        $strona = 'html/sh_tower.html';
    } elseif ($_GET['idp'] == 'abradz') {
        $strona = 'html/abradz.html';
    } elseif ($_GET['idp'] == 'filmy') {
        $strona = 'html/filmy.html';
    } elseif ($_GET['idp'] == 'kontakt') {
        $strona = 'html/kontakt.html';
    } else {
        $strona = 'html/404.html';
    }
}

if (!file_exists($strona)) {
    $strona = 'html/404.html';
}

?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Najwyższe budynki świata</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>


<body onload="startclock()">
<nav>
    <div id="watch">
        <div id="zegarek"></div>
        <div id="data"></div>
    </div>
    <ul>
        <li><a href="index.php?idp=glowna">Home</a></li>
        <li><a href="index.php?idp=burj">Burdż Chalifa</a></li>
        <li><a href="index.php?idp=merdeka">Merdeka 118</a></li>
        <li><a href="index.php?idp=sh_tower">Shanghai Tower</a></li>
        <li><a href="index.php?idp=abradz">Abradż al-Bajt</a></li>
        <li><a href="index.php?idp=filmy">Filmy</a></li>
        <li><a href="index.php?idp=kontakt">Kontakt</a></li>
        <li><input id="mode" type="button" value="Przełącz tryb" onclick="toggleBackground()"></li>
    </ul>
</nav>

<script>
        $("#mode").on("mouseover", function () {
            $(this).animate({
                width: 100
            }, 800);
        });

        $("#mode").on("mouseout", function () {
            $(this).animate({
                width: 90
            }, 800);
        });
    </script>

<?php include($strona); ?>

</body>
<?php
$nr_indeksu = '169391';
$nrGrupy = '4';

echo 'Autor: Michał Turczyn '.$nr_indeksu.' grupa '.$nrGrupy.'<br/><br/>';
?>
</html>
