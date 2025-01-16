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
// Funkcja: DodajKategorie
// Opis: Dodaje nową kategorię do bazy danych
// Parametry:
//   - $nazwa (var): Nazwa kategorii do dodania
//   - $matka (var): ID kategorii nadrzędnej (NULL jeśli kategoria główna)
// Zwraca:
//   - Brak
// ============================================================
function DodajKategorie($nazwa, $matka = 0)
{
    global $link;

    $nazwa = mysqli_real_escape_string($link, $nazwa);
    
    $query = "INSERT INTO categories (nazwa, matka) VALUES (?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'si', $nazwa, $matka);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: categories.php');
        echo '<p>Nowa kategoria została dodana.</p>';
    } else {
        echo '<p>Błąd podczas dodawania kategorii: ' . mysqli_error($link) . '</p>';
    }
}

// ============================================================
// Funkcja: UsunKategorie
// Opis: Usuwa kategorię z bazy danych
// Parametry:
//   - $id (var): ID kategorii do usunięcia
// Zwraca:
//   - Brak
// ============================================================
function UsunKategorie($id)
{
    global $link;

    $id = mysqli_real_escape_string($link, $id);
    
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: categories.php');
        echo '<p>Kategoria została usunięta.</p>';
    } else {
        echo '<p>Błąd podczas usuwania kategorii: ' . mysqli_error($link) . '</p>';
    }
}

// ============================================================
// Funkcja: EdytujKategorie
// Opis: Edytuje nazwę kategorii w bazie danych
// Parametry:
//   - $id (var): ID kategorii do edycji
//   - $nowaNazwa (var): Nowa nazwa kategorii
// Zwraca:
//   - Brak
// ============================================================
function EdytujKategorie($id, $nowaNazwa)
{
    global $link;

    $id = mysqli_real_escape_string($link, $id);
    $nowaNazwa = mysqli_real_escape_string($link, $nowaNazwa);

    $query = "UPDATE categories SET nazwa = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'si', $nowaNazwa, $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: categories.php');
        echo '<p>Kategoria została zaktualizowana.</p>';
    } else {
        echo '<p>Błąd podczas edycji kategorii: ' . mysqli_error($link) . '</p>';
    }
}

// ============================================================
// Funkcja: PokazKategorie
// Opis: Wyświetla wszystkie kategorie główne i ich podkategorie w formie drzewa
// Parametry:
//   - Brak
// Zwraca:
//   - Brak
// ============================================================
function PokazKategorie()
{
    global $link;

    $queryMatki = "SELECT id, nazwa FROM categories WHERE matka IS NULL";
    $resultMatki = mysqli_query($link, $queryMatki);

    if (!$resultMatki || mysqli_num_rows($resultMatki) == 0) {
        echo '<p>Brak kategorii do wyświetlenia.</p>';
        return;
    }

    echo '<ul>';
    while ($matka = mysqli_fetch_assoc($resultMatki)) {
        echo '<li>' . htmlspecialchars($matka['id'] . '. ' . $matka['nazwa']) .  '</li>';

        PokazPodkategorie($matka['id']);
    }
    echo '</ul>';
}

// ============================================================
// Funkcja: PokazKategorie
// Opis: Pobiera wszystkie dzieci danej kategorii i wyświetla je rekurencyjnie w formie drzewa
// Parametry:
//   - $parent_id: ID matki
// Zwraca:
//   - Brak
// ============================================================
function PokazPodkategorie($parent_id)
{
    global $link;

    $queryDzieci = "SELECT id, nazwa FROM categories WHERE matka = ?";
    $stmtDzieci = mysqli_prepare($link, $queryDzieci);
    mysqli_stmt_bind_param($stmtDzieci, 'i', $parent_id);
    mysqli_stmt_execute($stmtDzieci);
    $resultDzieci = mysqli_stmt_get_result($stmtDzieci);

    if ($resultDzieci && mysqli_num_rows($resultDzieci) > 0) {
        echo '<ul>';
        while ($dziecko = mysqli_fetch_assoc($resultDzieci)) {
            echo '<li>' . htmlspecialchars($dziecko['id'] . '. ' . $dziecko['nazwa']) . '</li>';

            PokazPodkategorie($dziecko['id']);
        }
        echo '</ul>';
    }
}


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
</head>
<body>
    <h1>Witaj w panelu administracyjnym!</h1>
    <a href="logout.php">Wyloguj</a>
    <br>
    <a href="admin_panel.php">Wróć do głównego panelu administracyjnego</a>
    <h1>Drzewo Kategorii</h1>
    <?php PokazKategorie(); ?>
    
    <h2>Dodaj nową kategorię</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <label for="kategoria_nazwa">Nazwa kategorii:</label><br>
        <input type="text" id="kategoria_nazwa" name="kategoria_nazwa" required><br><br>

        <label for="parent_id">Kategoria nadrzędna (opcjonalnie):</label><br>
        <select id="parent_id" name="parent_id">
            <option value="">Brak</option>
            <?php
            $query = "SELECT id, nazwa FROM categories";
            $result = mysqli_query($link, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id'] . '">' . $row['id'] . '. ' . htmlspecialchars($row['nazwa']) . '</option>';
            }
            ?>
        </select><br><br>

        <input type="submit" name="dodaj_kategorie" value="Dodaj kategorię">
    </form>

    <h2>Edytuj kategorię</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <label for="edytuj_id">ID kategorii do edycji:</label><br>
        <input type="number" id="edytuj_id" name="edytuj_id" required><br><br>

        <label for="nowa_nazwa">Nowa nazwa kategorii:</label><br>
        <input type="text" id="nowa_nazwa" name="nowa_nazwa" required><br><br>

        <input type="submit" name="edytuj_kategorie" value="Edytuj kategorię">
    </form>

    <h2>Usuń kategorię</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <label for="usun_id">ID kategorii do usunięcia:</label><br>
        <input type="number" id="usun_id" name="usun_id" required><br><br>

        <input type="submit" name="usun_kategorie" value="Usuń kategorię">
    </form>

</body>
</html>

<?php
// Obsługuje dodawanie, edycję oraz usuwanie kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['dodaj_kategorie'])) {
        $nazwa = $_POST['kategoria_nazwa'];
        $parent_id = $_POST['parent_id'] ? $_POST['parent_id'] : NULL;
        DodajKategorie($nazwa, $parent_id);
    }

    if (isset($_POST['edytuj_kategorie'])) {
        $id = $_POST['edytuj_id'];
        $nowaNazwa = $_POST['nowa_nazwa'];
        EdytujKategorie($id, $nowaNazwa);
    }

    if (isset($_POST['usun_kategorie'])) {
        $id = $_POST['usun_id'];
        UsunKategorie($id);
    }
}
?>
