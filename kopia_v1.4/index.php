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
            <li><a href="index.html">Home</a></li>
            <li><a href="html/burj.html">Burdż Chalifa</a></li>
            <li><a href="html/merdeka.html">Merdeka 118</a></li>
            <li><a href="html/sh_tower.html">Shanghai Tower</a></li>
            <li><a href="html/abradz.html">Abradż al-Bajt</a></li>
            <li><a href="html/kontakt.html">Kontakt</a></li>
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


    <section>
        <h1>Witamy na naszej stronie</h1>
        <p>W ostatnich dekadach rozwój technologii budowlanych umożliwił konstrukcję niesamowicie wysokich wieżowców, które stały się symbolami potęgi i nowoczesności. Strona o najwyższych budynkach przedstawia listę rekordowych konstrukcji, z których najbardziej rozpoznawalne to:.</p>
        <p>
            <b>Burj Khalifa (Dubaj, ZEA)</b> – najwyższy budynek na świecie o wysokości 828 m.</br>
            Jego futurystyczna konstrukcja przyciąga turystów i architektów z całego globu.


            <b>Shanghai Tower (Szanghaj, Chiny)</b> – wieżowiec mierzący 632 m,</br>
            znany z unikalnej spiralnej formy i zaawansowanych rozwiązań ekologicznych.


            <b>Abraj Al-Bait (Mekka, Arabia Saudyjska)</b> – monumentalny kompleks o wysokości 601 m,</br>
            w pobliżu świętego miejsca islamu.

        </p>
        <p>
            Każdy z tych budynków nie tylko wyznacza nowe granice w architekturze, ale również staje się ikoną miast, w których się znajduje.
        </p>
    </section>

</body>
<?php
$nr_indeksu = '169391';
$nrGrupy = '4';

echo 'Autor: Michał Turczyn '.$nr_indeksu.' grupa '.$nrGrupy.'<br/><br/>';
?>
</html>
