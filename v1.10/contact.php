<?php

// ============================================================
// Funkcja: WyslijMailKontakt
// Opis: Obsługuje wysyłanie wiadomości z formularza kontaktowego.
// Parametry:
//   - $odbiorca (string): Adres e-mail, na który ma zostać wysłana wiadomość.
// Zwraca:
//   - Komunikat potwierdzenia lub błąd
// ============================================================
function WyslijMailKontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']) || empty($_POST['name'])) {
        echo 'Proszę wypełnić wszystkie pola formularza.';
        exit();
    }

    $mail['subject'] = $_POST['temat'];
    $mail['body'] = "Imię nadawcy: {$_POST['name']}\n\nTreść wiadomości:\n{$_POST['tresc']}";
    $mail['sender'] = $_POST['email'];
    $mail['reciptient'] = $odbiorca;

    $header = "From: {$_POST['name']} <" . $mail['sender'] . ">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: text/plain; charset=utf-8\n";
    $header .= "Content-Transfer-Encoding: 8bit\n";
    $header .= "X-Mailer: PHP/" . phpversion() . "\n";

    if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
        echo 'Wiadomość została wysłana pomyślnie.';
    } else {
        echo 'Błąd podczas wysyłania wiadomości. Spróbuj ponownie później.';
    }
}

// ============================================================
// Funkcja: PrzypomnijHaslo
// Opis: Obsługuje przypomnienie hasła administratora wysyłane na e-mail.
// Parametry:
//   - Brak
// Zwraca:
//   - Komunikat potwierdzenia lub błąd
// ============================================================
function PrzypomnijHaslo()
{
    $adminEmail = 'admin@example.com';
    $adminPassword = 'Admin123';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['przypomnij'])) {
        echo 'Niepoprawna metoda przesłania danych.';
        exit();
    }

    $userEmail = $_POST['email'] ?? '';
    if (empty($userEmail)) {
        echo 'Proszę podać adres e-mail.';
        exit();
    }

    $mail['subject'] = 'Przypomnienie hasła do panelu administracyjnego';
    $mail['body'] = "Twoje hasło do panelu administracyjnego to: $adminPassword";
    $mail['sender'] = $adminEmail;
    $mail['reciptient'] = $userEmail;

    $header = "From: Panel Administracyjny <$adminEmail>\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: text/plain; charset=utf-8\n";
    $header .= "Content-Transfer-Encoding: 8bit\n";
    $header .= "X-Mailer: PHP/" . phpversion() . "\n";

    if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
        echo 'Hasło zostało wysłane na Twój adres e-mail.';
    } else {
        echo 'Błąd podczas wysyłania hasła.';
    }
}

// ============================================================
// Sekcja główna: Obsługa żądań POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['wyslij'])) {
        $odbiorca = 'email2@localhost';
        WyslijMailKontakt($odbiorca);
    } elseif (isset($_POST['przypomnij'])) {
        PrzypomnijHaslo();
    }
} else {
    header('Location: kontakt.html');
    exit();
}

?>
