<?php

function PokazKontakt() {
    echo '
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formularz Kontaktowy</title>
    </head>
    <body>
        <h1>Formularz Kontaktowy</h1>
        <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
            <label for="email">Twój e-mail:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="temat">Temat:</label><br>
            <input type="text" id="temat" name="temat" required><br><br>

            <label for="tresc">Treść wiadomości:</label><br>
            <textarea id="tresc" name="tresc" rows="5" cols="40" required></textarea><br><br>

            <input type="submit" name="wyslij" value="Wyślij">
        </form>
    </body>
    </html>
    ';
}



function WyslijMailKontakt($odbiorca) {
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        PokazKontakt();
    } else {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz Kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: text/plain; charset=utf-8\n";
        $header .= "Content-Transfer-Encoding: 8bit\n";
        $header .= "X-Sender: <" . $mail['sender'] . ">\n";
        $header .= "X-Mailer: PRapWWW mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <" . $mail['sender'] . ">\n";

        if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
            echo '[wiadomosc_wyslana]';
        } else {
            echo '[blad_wysylania]';
        }
    }
}



function PrzypomnijHaslo() {
    $adminEmail = 'admin@example.com';
    $adminPassword = 'Admin123';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['przypomnij'])) {
        echo '
        <h1>Przypomnienie hasła</h1>
        <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">
            <label for="email">Twój e-mail (wprowadzony w systemie):</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <input type="submit" name="przypomnij" value="Przypomnij hasło">
        </form>
        ';
    } else {
        $mail['subject'] = 'Przypomnienie hasła do panelu administracyjnego';
        $mail['body'] = "Twoje hasło do panelu administracyjnego to: $adminPassword";
        $mail['sender'] = $adminEmail;
        $mail['reciptient'] = $_POST['email'];

        $header = "From: Panel Administracyjny <$adminEmail>\n";
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: text/plain; charset=utf-8\n";
        $header .= "Content-Transfer-Encoding: 8bit\n";
        $header .= "X-Mailer: PRapWWW mail 1.2\n";

        if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
            echo '[haslo_wyslane]';
        } else {
            echo '[blad_wysylania_hasla]';
        }
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['wyslij'])) {
        $odbiorca = 'email2@localhost';
        WyslijMailKontakt($odbiorca);
    } elseif (isset($_POST['przypomnij'])) {
        PrzypomnijHaslo();
    }
} else {
    PokazKontakt();
}

?>