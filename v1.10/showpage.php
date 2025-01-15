<?php

// ============================================================
// Funkcja: PokazPodstrone
// Opis: Wyświetla podstronę o danym id, ładując jej zawartość z bazy danych
// Parametry:
//   - $id (string): Identyfikator podstrony w bazie danych
// Zwraca:
//   - Zwraca treść strony
// ============================================================
function PokazPodstrone($id)
{
    global $link;

    $id_clear = htmlspecialchars($id);

    if ($id === 10) {
        ob_start();
        include 'produkty.php';
        pokazProdukty();
        return ob_get_clean();
    }

    if ($id == 9) {
        ob_start();
        require_once 'koszyk.php';
        showCart();
        return ob_get_clean();
    }


    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);

    if (!$result) {
        return '[błąd zapytania]';
    }

    $row = mysqli_fetch_array($result);

    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}

?>