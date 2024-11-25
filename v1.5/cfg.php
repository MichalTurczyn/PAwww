<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
if (!$link) {
    die('Przerwane połączenie: ' . mysqli_connect_error());
}

//echo 'Połączenie z bazą danych udane!';
?>