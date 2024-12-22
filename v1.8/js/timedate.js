// ============================================================
// Funkcja: gettheDate
// Opis: Pobiera dzisiejszą datę i formatuje ją w formacie MM/DD/YY.
//       Następnie wyświetla datę w elemencie HTML o id "data".
// Parametry:
//   - Brak
// Zwraca:
//   - Wyświetla datę w elemencie HTML.
// ============================================================
function gettheDate() {
    Todays = new Date();
    TheDate = "" + (Todays.getMonth() + 1) + " / " + Todays.getDate() + " / " + (Todays.getYear() - 100);
    document.getElementById("data").innerHTML = TheDate;
}

var timerID = null;
var timerRunning = false;


// Funkcja: stopclock
// Opis: Zatrzymuje zegar, jeśli jest uruchomiony, i czyści timer.
// Parametry:
//   - Brak
// Zwraca:
//   - Zatrzymuje działanie timera.
// ============================================================
function stopclock() {
    if (timerRunning) {
        clearTimeout(timerID);
    }
    timerRunning = false;
}


// ============================================================
// Funkcja: startclock
// Opis: Uruchamia zegar, wyświetlając datę i aktualizując godzinę.
//       Zatrzymuje wcześniej uruchomiony zegar przed ponownym uruchomieniem.
// Parametry:
//   - Brak
// Zwraca:
//   - Rozpoczyna działanie zegara.
// ============================================================
function startclock() {
    stopclock();
    gettheDate();
    showtime();
}


// ============================================================
// Funkcja: showtime
// Opis: Pobiera bieżący czas, formatuje go w formacie 12-godzinnym 
//       (z oznaczeniem A.M./P.M.), a następnie wyświetla w elemencie
//       HTML o id "zegarek". Aktualizuje czas co sekundę.
// Parametry:
//   - Brak
// Zwraca:
//   - Wyświetla sformatowany czas w elemencie HTML.
// ============================================================
function showtime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeValue = "" + ((hours > 12) ? hours - 12 : hours);
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    timeValue += (hours >= 12) ? " P.M." : " A.M.";
    document.getElementById("zegarek").innerHTML = timeValue;
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}