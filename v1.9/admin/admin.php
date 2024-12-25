<?php
require_once '../cfg.php';


// ============================================================
// Funkcja: FormularzLogowania
// Opis: Wyświetla formularz logowania do panelu administratora
// Parametry:
//   - $error (string): Treść błędu w przypadku złych danych logowania.
// Zwraca:
//   - Zwraca formularz logowania
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
</div>
';

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