var computed = false;
var decimal = 0;
var isDark = false;

function convert(entryform, from, to) {
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

function addChar(input, character) {
    if ((character == '.' && decimal == "0") || character != '.') {
        (input.value == "" || input.value == "0") ? input.value = character : input.value += character
        convert(input.form,input.form.measure1, input.form.measure2)
        computed = true;
        if (character == '.') {
            decimal = 1;
        }
    }
}

function openVothcom() {
    window.open("", "Display window", "toolbar=no, directories=no,menubar=no");
}

function clear(form) {
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}

function toggleBackground() {
    const body = document.body;
    const section = document.querySelector('section');  // Pobiera pierwszy element <section>

    if (isDark) {
        body.style.backgroundColor = '#FFFFFF';      // Zmiana t³a strony na jasny
        body.style.color = '#000000';                // Zmiana koloru tekstu na ciemny
        section.style.backgroundColor = '#F0F0F0';   // Zmiana t³a sekcji na jasny
        section.style.color = '#000000';             // Zmiana koloru tekstu w sekcji na ciemny
    } else {
        body.style.backgroundColor = '#222222';      // Zmiana t³a strony na ciemny
        body.style.color = '#FFFFFF';                // Zmiana koloru tekstu na jasny
        section.style.backgroundColor = '#333333';   // Zmiana t³a sekcji na ciemny
        section.style.color = '#FFFFFF';             // Zmiana koloru tekstu w sekcji na jasny
    }
    isDark = !isDark;  // Prze³¹czenie stanu ciemnego/jasnego trybu
}
