var computed = false;
var decimal = 0;
var isDark = false;


// ============================================================
// Funkcja: convert
// Opis: Przelicza wartość z jednej jednostki miary na inną.
// Parametry:
//   - entryform (object): Formularz, z którego pobierane są dane.
//   - from (object): Lista rozwijana zawierająca jednostki wejściowe.
//   - to (object): Lista rozwijana zawierająca jednostki wyjściowe.
// Zwraca:
//   - Przeliczoną wartość i wyświetla ją w polu formularza.
// ============================================================
function convert(entryform, from, to) {
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}


// ============================================================
// Funkcja: addChar
// Opis: Dodaje znak do pola wejściowego, obsługując znaki dziesiętne.
// Parametry:
//   - input (object): Pole wejściowe, do którego dodawany jest znak.
//   - character (string): Znak do dodania.
// Zwraca:
//   - Aktualizuje wartość w polu wejściowym i automatycznie przelicza jednostki.
// ============================================================
function addChar(input, character) {
    if ((character == '.' && decimal == "0") || character != '.') {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character
        convert(input.form, input.form.measure1, input.form.measure2)
        computed = true;
        if (character == '.') {
            decimal = 1;
        }
    }
}


// ============================================================
// Funkcja: openVothcom
// Opis: Otwiera nowe okno przeglądarki.
// Parametry:
//   - Brak
// Zwraca:
//   - Nowe okno przeglądarki z określonymi ustawieniami.
// ============================================================
function openVothcom() {
    window.open("", "Display window", "toolbar=no, directories=no,menubar=no");
}


// ============================================================
// Funkcja: clear
// Opis: Czyści pola formularza i resetuje zmienne.
// Parametry:
//   - form (object): Formularz, którego pola są czyszczone.
// Zwraca:
//   - Brak
// ============================================================
function clear(form) {
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}


// ============================================================
// Funkcja: toggleBackground
// Opis: Przełącza kolor tła i tekstu między trybem jasnym i ciemnym.
// Parametry:
//   - Brak
// Zwraca:
//   - Brak
// ============================================================
function toggleBackground() {
    const body = document.body;
    const section = document.querySelector('section');

    if (isDark) {
        body.style.backgroundColor = '#FFFFFF';
        body.style.color = '#000000';
        section.style.backgroundColor = '#F0F0F0';
        section.style.color = '#000000';
    } else {
        body.style.backgroundColor = '#222222';
        body.style.color = '#FFFFFF';
        section.style.backgroundColor = '#333333';
        section.style.color = '#FFFFFF';
    }
    isDark = !isDark;
}

