<?php
require_once '../cfg.php';


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: admin.php');
    exit();
}

// ============================================================
// Funkcja: ListaPodstron
// Opis: Wyświetla listę podstron w formie tabeli wraz z przyciskami "usuń" oraz "edytuj"
// Parametry:
//   - Brak
// Zwraca:
//   - Zwraca pusty return jeśli nie znaleziono podstrony, w celu przerwania skryptu
// ============================================================
function ListaPodstron()
{
    global $link;

    $query = "SELECT id, page_title FROM page_list LIMIT 100";
    $result = mysqli_query($link, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo '<p>Brak podstron do wyświetlenia.</p>';
        return;
    }

    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        echo '<td>
            <form method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
                <input type="hidden" name="identyfikator" value="' . $row['id'] . '">
                <input type="submit" name="usun" value="Usuń">
                <input type="submit" name="edytuj" value="Edytuj">
            </form>
            </td>';
        echo '</tr>';
    }

    echo '</table>';
}


// ============================================================
// Funkcja: PokazFormularzEdycji
// Opis: Wyświetla formularz edycji z aktulnymi danymi danej podstrony
// Parametry:
//   - $id (var): Identyfikator podstrony w bazie danych
// Zwraca:
//   - Zwraca pusty return jeśli nie znaleziono podstrony, w celu przerwania skryptu
// ============================================================
function PokazFormularzEdycji($id)
{
    global $link;

    $query = "SELECT page_title, page_content, status FROM page_list WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    if (!$row) {
        echo '<p>Nie znaleziono podstrony do edycji.</p>';
        return;
    }

    $tytul = htmlspecialchars($row['page_title']);
    $tresc = htmlspecialchars($row['page_content']);
    $aktywna = $row['status'];

    echo '<h1>Edytuj Podstronę</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <input type="hidden" name="identyfikator" value="' . $id . '">
        <label for="tytul">Tytuł:</label><br>
        <input type="text" id="tytul" name="page_title" value="' . $tytul . '" required><br><br>
        
        <label for="tresc">Treść:</label><br>
        <textarea id="tresc" name="page_content" rows="10" cols="50" required>' . $tresc . '</textarea><br><br>
        
        <label for="aktywna">Aktywna:</label>
        <input type="checkbox" id="aktywna" name="status" ' . ($aktywna ? 'checked' : '') . '><br><br>
        
        <input type="submit" name="zapisz_edytuj" value="Zapisz zmiany">
    </form>';
}


// ============================================================
// Funkcja: DodajNowaPodstrone
// Opis: Wyświetla formularz dodający nową podstronę
// Parametry:
//   - Brak
// Zwraca:
//   - Brak
// ============================================================
function DodajNowaPodstrone()
{
    echo '<h1>Dodaj Nową Podstronę</h1>
    <form method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <label for="tytul">Tytuł:</label><br>
        <input type="text" id="tytul" name="page_title" required><br><br>

        <label for="tresc">Treść:</label><br>
        <textarea id="tresc" name="page_content" rows="10" cols="50" required></textarea><br><br>

        <label for="aktywna">Aktywna:</label>
        <input type="checkbox" id="aktywna" name="status"><br><br>

        <input type="submit" name="zapisz_dodaj" value="Zapisz nową podstronę">
    </form>';
}


// ============================================================
// Funkcja: UsunPodstrone
// Opis: Usuwa podstronę z bazy danych 
// Parametry:
//   - $id (var): Identyfikator podstrony w bazie danych
// Zwraca:
//   - Brak
// ============================================================
function UsunPodstrone($id)
{
    global $link;

    $deleteQuery = "DELETE FROM page_list WHERE id = ?";
    $deleteStmt = mysqli_prepare($link, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, 'i', $id);

    if (mysqli_stmt_execute($deleteStmt)) {
        echo '<p>Podstrona została usunięta.</p>';
        header('Location: admin_panel.php');
        exit();
    } else {
        echo '<p>Błąd podczas usuwania podstrony: ' . mysqli_error($link) . '</p>';
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mechanika obsługi przycisku "usun" z formularza w funkcji ListaPodstron
    if (isset($_POST['usun'])) {
        $id = $_POST['identyfikator'] ?? 0;
        if ($id) {
            UsunPodstrone($id);
        } else {
            echo '<p>Nieprawidłowy identyfikator do usunięcia.</p>';
        }
        // Mechanika obsługi przycisku "edytuj" z formularza w funkcji ListaPodstron
    } elseif (isset($_POST['edytuj'])) {
        $id = $_POST['identyfikator'] ?? 0;
        if ($id) {
            PokazFormularzEdycji($id);
        } else {
            echo '<p>Nieprawidłowy identyfikator do edycji.</p>';
        }
        // Mechanika przycisku "zapisz_edytuj" w formularzu edycji podstrony
    } elseif (isset($_POST['zapisz_edytuj'])) {
        $id = $_POST['identyfikator'] ?? 0;
        $nowyTytul = $_POST['page_title'] ?? '';
        $nowaTresc = $_POST['page_content'] ?? '';
        $nowaAktywna = isset($_POST['status']) ? 1 : 0;

        $updateQuery = "UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($link, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ssii', $nowyTytul, $nowaTresc, $nowaAktywna, $id);

        if (mysqli_stmt_execute($updateStmt)) {
            echo '<p>Podstrona została zaktualizowana.</p>';
            header('Location: admin_panel.php');
            exit();
        } else {
            echo '<p>Błąd podczas aktualizacji podstrony: ' . mysqli_error($link) . '</p>';
        }
        // Mechanika przycisku zapisz_dodaj, dodaje nową podstronę do bazy danych
    } elseif (isset($_POST['zapisz_dodaj'])) {
        global $link;

        $nowyTytul = $_POST['page_title'] ?? '';
        $nowaTresc = $_POST['page_content'] ?? '';
        $nowaAktywna = isset($_POST['status']) ? 1 : 0;

        $insertQuery = "INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)";
        $insertStmt = mysqli_prepare($link, $insertQuery);

        mysqli_stmt_bind_param($insertStmt, 'ssi', $nowyTytul, $nowaTresc, $nowaAktywna);

        if (mysqli_stmt_execute($insertStmt)) {
            echo '<p>Nowa podstrona została dodana.</p>';
            header('Location: admin_panel.php');
            exit();
        } else {
            echo '<p>Błąd podczas dodawania podstrony: ' . mysqli_error($link) . '</p>';
        }
    }
}


echo '
    <h1>Witaj w panelu administracyjnym!</h1>
    <a href="logout.php">Wyloguj</a>
    <br>
    <a href="categories.php">Zarząrzaj kategoriami</a>
    <br>
    <a href="products.php">Zarząrzaj produktami</a>
    <h1>Lista Podstron</h1>';
ListaPodstron();
DodajNowaPodstrone();

?>