<?php
include('cfg.php');
include('showpage.php');
$idp = isset($_GET['idp']) ? $_GET['idp'] : 'glowna';


error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
if ($idp == 'glowna') {
    $id = 1;
} else {
    if ($idp == 'burj') {
        $id = 3;
    } elseif ($idp == 'merdeka') {
        $id = 4;
    } elseif ($idp == 'sh_tower') {
        $id = 5;
    } elseif ($idp == 'abradz') {
        $id = 2;
    } elseif ($idp == 'filmy') {
        $id = 7;
    } elseif ($idp == 'kontakt') {
        $id = 6;
    } elseif ($idp == 'produkty') {
        $id = 10;
    } elseif ($idp == 'koszyk') {
        $id = 9;
    } else {
        $id = 8;
    }
}
$page_content = PokazPodstrone($id)


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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
            <li><a href="index.php?idp=produkty">Produkty</a></li>
            <li><a href="index.php?idp=koszyk">Koszyk</a></li>
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

    <?php echo $page_content; ?>

</body>
<?php
$nr_indeksu = '169391';
$nrGrupy = '4';

echo 'Autor: Michał Turczyn ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br/><br/>';
?>

</html>