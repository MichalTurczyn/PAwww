<?php
require_once '../cfg.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #1e1e1e;
    color: #e0e0e0;
    line-height: 1.6;
}

h1, h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #f4f4f4;
}

.logowanie {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #2a2a2a;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
}

.logowanie h1 {
    margin-bottom: 15px;
    font-size: 24px;
}

.logowanie p {
    margin-bottom: 15px;
    color: #ff6b6b;
    font-size: 14px;
}

.logowanie input[type="text"],
.logowanie input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #444;
    background-color: #333;
    color: #e0e0e0;
    border-radius: 5px;
}

.logowanie input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.logowanie input[type="submit"]:hover {
    background-color: #388e3c;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #2a2a2a;
    color: #e0e0e0;
}

table th, table td {
    padding: 10px;
    border: 1px solid #444;
}

table th {
    background-color: #333;
    color: #f4f4f4;
}

table tr:hover {
    background-color: #333;
}

form button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

form button:hover {
    background-color: #388e3c;
}
    </style>
</head>
<body>

<?php

// ============================================================
// Funkcja: FormularzLogowania
// ============================================================
function FormularzLogowania($error = '')
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>';

    if (!empty($error)) {
        $wynik .= '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
    }

    $wynik .= '
        <div class="logowanie">
            <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
                <table class="logowanie">
                    <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie"/></td></tr>
                    <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie"/></td></tr>
                    <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj"/></td></tr>
                </table>
            </form>
        </div>
    </div>';

    echo $wynik;
}

// ============================================================
// Opis: Sprawdza poprawność danych logowania
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputLogin = $_POST['login_email'] ?? '';
    $inputPass = $_POST['login_pass'] ?? '';

    if ($inputLogin === $login && $inputPass === $pass) {
        $_SESSION['logged_in'] = true;
        header('Location: admin_panel.php');
        exit();
    } else {
        FormularzLogowania('Nieprawidłowy login lub hasło!');
    }
} elseif (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    FormularzLogowania();
} else {
    header('Location: admin_panel.php');
}
?>
</body>
</html>
