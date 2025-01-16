<?php
require_once '../cfg.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: admin.php');
    exit();
}
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
<?php

// ============================================================
// Funkcja: DodajProdukt
// Opis: Dodaje nowy produkt do bazy danych
// Parametry:
//   - Brak (pobiera dane z $_POST)
// Zwraca:
//   - Brak (przekierowuje na stronę products.php lub wyświetla błąd)
// ============================================================
function DodajProdukt()
{
    global $link;

    $tytul = $_POST['tytul'];
    $opis = $_POST['opis'];
    $data_utworzenia = date('Y-m-d');
    $data_modyfikacji = date('Y-m-d');
    $data_wygasniecia = $_POST['data_wygasniecia'] ?: NULL;
    $cena_netto = $_POST['cena_netto'];
    $podatek_vat = $_POST['podatek_vat'];
    $ilosc = $_POST['ilosc_sztuk_w_magazynie'];
    $status_dostepnosci = $_POST['status_dostepnosci'];
    $id_kategorii = $_POST['id_kategorii'];
    $gabaryt_produktu = $_POST['gabaryt_produktu'];
    $zdjecie = NULL;

    if (!empty($_FILES['zdjecie']['tmp_name'])) {
        $zdjecie = file_get_contents($_FILES['zdjecie']['tmp_name']);
    }


    $query = "INSERT INTO products (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc_sztuk_w_magazynie, status_dostepnosci, id_kategorii, gabaryt_produktu, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'sssssiissssb', $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status_dostepnosci, $id_kategorii, $gabaryt_produktu, $zdjecie);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href='products.php';</script>";
        exit();
    } else {
        echo '<p>Błąd podczas dodawania produktu: ' . mysqli_error($link) . '</p>';
    }
}




// ============================================================
// Funkcja: UsunProdukt
// Opis: Usuwa produkt z bazy danych na podstawie ID
// Parametry:
//   - $id (int): ID produktu do usunięcia
// Zwraca:
//   - Brak (przekierowuje na stronę products.php lub wyświetla błąd)
// ============================================================
function UsunProdukt($id)
{
    global $link;

    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href='products.php';</script>";
        exit();
    } else {
        echo '<p>Błąd podczas usuwania produktu: ' . mysqli_error($link) . '</p>';
    }
}



// ============================================================
// Funkcja: PokazFormularzEdycji
// Opis: Wyświetla formularz edycji produktu z wypełnionymi danymi
// Parametry:
//   - $id (int): ID produktu do edycji
// Zwraca:
//   - Brak (generuje formularz HTML z danymi produktu)
// ============================================================
function PokazFormularzEdycji($id)
{
    global $link;

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $produkt = mysqli_fetch_assoc($result);
    if (!$produkt) {
        echo '<p>Nie znaleziono produktu do edycji.</p>';
        return;
    }

    echo '
    <h2>Edytuj Produkt</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="produkt_id" value="' . htmlspecialchars($produkt['id']) . '">
        <label for="tytul">Tytuł:</label><br>
        <input type="text" id="tytul" name="tytul" value="' . htmlspecialchars($produkt['tytul']) . '" required><br><br>
        <label for="opis">Opis:</label><br>
        <textarea id="opis" name="opis" required>' . htmlspecialchars($produkt['opis']) . '</textarea><br><br>
        <label for="data_wygasniecia">Data wygaśnięcia:</label><br>
        <input type="date" id="data_wygasniecia" name="data_wygasniecia" value="' . htmlspecialchars($produkt['data_wygasniecia']) . '"><br><br>
        <label for="cena_netto">Cena netto:</label><br>
        <input type="number" step="0.01" id="cena_netto" name="cena_netto" value="' . htmlspecialchars($produkt['cena_netto']) . '" required><br><br>
        <label for="podatek_vat">Podatek VAT (%):</label><br>
        <input type="number" id="podatek_vat" name="podatek_vat" value="' . htmlspecialchars($produkt['podatek_vat']) . '" required><br><br>
        <label for="ilosc_sztuk_w_magazynie">Ilość sztuk w magazynie:</label><br>
        <input type="number" id="ilosc_sztuk_w_magazynie" name="ilosc_sztuk_w_magazynie" value="' . htmlspecialchars($produkt['ilosc_sztuk_w_magazynie']) . '" required><br><br>
        <label for="status_dostepnosci">Status dostępności:</label><br>
        <select id="status_dostepnosci" name="status_dostepnosci" required>
            <option value="dostępny" ' . ($produkt['status_dostepnosci'] === 'dostępny' ? 'selected' : '') . '>Dostępny</option>
            <option value="niedostępny" ' . ($produkt['status_dostepnosci'] === 'niedostępny' ? 'selected' : '') . '>Niedostępny</option>
        </select><br><br>
        <label for="zdjecie">Zdjęcie produktu:</label><br>
        <input type="file" id="zdjecie" name="zdjecie"><br><br>
        
        <input type="submit" name="zapisz_edycje" value="Zapisz zmiany">
    </form>';
}


// ============================================================
// Funkcja: EdytujProdukt
// Opis: Aktualizuje dane produktu w bazie danych
// Parametry:
//   - $id (int): ID produktu do edycji
// Zwraca:
//   - Brak (przekierowuje na stronę products.php lub wyświetla błąd)
// ============================================================
function EdytujProdukt($id, $data_modyfikacji)
{
    global $link;

    $tytul = $_POST['tytul'];
    $opis = $_POST['opis'];
    $data_wygasniecia = $_POST['data_wygasniecia'] ?: NULL;
    $nowa_data_modyfikacji = $data_modyfikacji;
    $cena_netto = $_POST['cena_netto'];
    $podatek_vat = $_POST['podatek_vat'];
    $ilosc = $_POST['ilosc_sztuk_w_magazynie'];
    $status_dostepnosci = $_POST['status_dostepnosci'];
    $id_kategorii = $_POST['id_kategorii'];
    $gabaryt_produktu = $_POST['gabaryt_produktu'];
    $zdjecie = NULL;
    if (!empty($_FILES['zdjecie']['name'])) {
        $upload_dir = 'uploads/';
        $zdjecie = $upload_dir . basename($_FILES['zdjecie']['name']);
        move_uploaded_file($_FILES['zdjecie']['tmp_name'], __DIR__ . '/' . $zdjecie);

    } else {
        $zdjecie = $_POST['stare_zdjecie'];
    }

    $query = "UPDATE products SET tytul = ?, opis = ?, data_modyfikacji = ?, data_wygasniecia = ?, cena_netto = ?, podatek_vat = ?, ilosc_sztuk_w_magazynie = ?, status_dostepnosci = ?, id_kategorii = ?, gabaryt_produktu = ?, zdjecie = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'ssssdiissibi', $tytul, $opis, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status_dostepnosci, $id_kategorii, $gabaryt_produktu, $zdjecie, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.location.href='products.php';</script>";
        exit();
    } else {
        echo '<p>Błąd podczas edycji produktu: ' . mysqli_error($link) . '</p>';
    }
}


// ============================================================
// Funkcja: PokazProdukty
// Opis: Wyświetla listę wszystkich produktów z opcjami edycji i usuwania
// Parametry:
//   - Brak
// Zwraca:
//   - Brak (generuje tabelę HTML z listą produktów)
// ============================================================
function PokazProdukty()
{
    global $link;

    $query = "SELECT * FROM products";
    $result = mysqli_query($link, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo '<p>Brak produktów do wyświetlenia.</p>';
        return;
    }

    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr>
            <th>ID</th>
            <th>Tytuł</th>
            <th>Opis</th>
            <th>Cena netto</th>
            <th>Podatek VAT</th>
            <th>Ilość sztuk</th>
            <th>Status</th>
            <th>Zdjęcie</th>
            <th>Akcje</th>
          </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        $status_dostepnosci = 'Dostępny';
        if ($row['ilosc_sztuk_w_magazynie'] <= 0) {
            $status_dostepnosci = 'Brak w magazynie';
        } elseif (!empty($row['data_wygasniecia']) && strtotime($row['data_wygasniecia']) < time()) {
            $status_dostepnosci = 'Wygasło';
        } elseif ($row['status_dostepnosci'] === 'niedostępny') {
            $status_dostepnosci = 'Niedostępny';
        }

        $zdjecie = $row['zdjecie'] ? 'data:image/jpeg;base64,' . base64_encode($row['zdjecie']) : null;
        $miniatura = $zdjecie ? '<img src="' . $zdjecie . '" alt="Zdjęcie" width="80" height="80">' : 'Brak zdjęcia';


        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tytul']) . '</td>';
        echo '<td>' . htmlspecialchars($row['opis']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cena_netto']) . ' zł</td>';
        echo '<td>' . htmlspecialchars($row['podatek_vat']) . '%</td>';
        echo '<td>' . htmlspecialchars($row['ilosc_sztuk_w_magazynie']) . '</td>';
        echo '<td>' . htmlspecialchars($status_dostepnosci) . '</td>';
        echo '<td>' . $miniatura . '</td>';
        echo '<td>
                <form method="post">
                    <input type="hidden" name="produkt_id" value="' . $row['id'] . '">
                    <input type="submit" name="edytuj" value="Edytuj">
                    <input type="submit" name="usun" value="Usuń">
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</table>';
}




// ============================================================
// Funkcja: PobierzKategorie
// Opis: Pobiera listę wszystkich kategorii z bazy danych
// Parametry:
//   - Brak
// Zwraca:
//   - Tablica z danymi kategorii (ID, matka, nazwa)
// ============================================================
function PobierzKategorie()
{
    global $link;
    $query = "SELECT id, matka, nazwa FROM categories ORDER BY matka, id";
    $result = mysqli_query($link, $query);

    $kategorie = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $kategorie[] = $row;
    }

    return $kategorie;
}


// ============================================================
// Funkcja: WyswietlKategorie
// Opis: Rekurencyjnie wyświetla kategorie jako opcje w formularzu
// Parametry:
//   - $kategorie (array): Tablica z kategoriami
//   - $parent_id (int|null): ID kategorii nadrzędnej (domyślnie NULL)
//   - $level (int): Poziom zagłębienia (domyślnie 0)
// Zwraca:
//   - Brak (wyświetla opcje <option> w formularzu HTML)
// ============================================================
function WyswietlKategorie($kategorie, $parent_id = NULL, $level = 0)
{
    foreach ($kategorie as $kategoria) {
        if ($kategoria['matka'] == $parent_id) {
            echo '<option value="' . $kategoria['id'] . '">' . str_repeat('--', $level) . ' ' . htmlspecialchars($kategoria['nazwa']) . '</option>';
            WyswietlKategorie($kategorie, $kategoria['id'], $level + 1);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Produktami</title>
</head>
<body>
    <h1>Panel Zarządzania Produktami</h1>
    <a href="admin_panel.php">Wróć do głównego panelu</a>
    <h2>Lista Produktów</h2>
    <?php PokazProdukty();?>
    <h2>Dodaj Nowy Produkt</h2>
    <form method="post" enctype="multipart/form-data">
    <label for="tytul">Tytuł:</label><br>
    <input type="text" id="tytul" name="tytul" required><br><br>

    <label for="opis">Opis:</label><br>
    <textarea id="opis" name="opis" required></textarea><br><br>

    <label for="data_wygasniecia">Data wygaśnięcia:</label><br>
    <input type="date" id="data_wygasniecia" name="data_wygasniecia" required><br><br>

    <label for="cena_netto">Cena netto:</label><br>
    <input type="number" step="0.01" id="cena_netto" name="cena_netto" required><br><br>

    <label for="podatek_vat">Podatek VAT (%):</label><br>
    <input type="number" id="podatek_vat" name="podatek_vat" required><br><br>

    <label for="ilosc_sztuk_w_magazynie">Ilość sztuk w magazynie:</label><br>
    <input type="number" id="ilosc_sztuk_w_magazynie" name="ilosc_sztuk_w_magazynie" required><br><br>

    <label for="status_dostepnosci">Status dostępności:</label><br>
    <select id="status_dostepnosci" name="status_dostepnosci" required>
        <option value="dostępny">Dostępny</option>
        <option value="niedostępny">Niedostępny</option>
    </select><br><br>

    <label for="id_kategorii">Kategoria:</label><br>
    <select id="id_kategorii" name="id_kategorii" required>
        <?php
        $kategorie = PobierzKategorie();
        WyswietlKategorie($kategorie);
        ?>
        <option value="$kategoria['id']">Wybierz kategorię</option>
    </select><br><br>

    <label for="gabaryt_produktu">Gabaryt produktu:</label><br>
    <input type="text" id="gabaryt_produktu" name="gabaryt_produktu" required><br><br>

    <label for="zdjecie">Zdjęcie produktu:</label><br>
    <input type="file" id="zdjecie" name="zdjecie" accept="image/*"><br><br>

    <input type="submit" name="dodaj_produkt" value="Dodaj produkt">
</form>
</body>
</html>
<?php
// ============================================================
// Obsługa formularzy
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj_produkt'])) {
        DodajProdukt();
    } elseif (isset($_POST['edytuj'])) {
        PokazFormularzEdycji($_POST['produkt_id']);
    } elseif (isset($_POST['zapisz_edycje'])) {
        $data_modyfikacji = date('Y-m-d');
        EdytujProdukt($_POST['produkt_id'], $data_modyfikacji);
    } elseif (isset($_POST['usun'])) {
        UsunProdukt($_POST['produkt_id']);
    }
}