// Kui veebileht laetakse, käivitatakse see funktsioon
window.onload = function () {
    // Võetakse URL-i päringurealt (query string) otsinguvälja ja otsinguväärtuse parameetrid
    var searchField = new URLSearchParams(window.location.search).get('searchField');
    var searchValue = new URLSearchParams(window.location.search).get('searchValue');

    // Kui otsinguväli on määratud, seatakse see vastava HTML-valiku väärtuseks
    if (searchField) {
        document.getElementById("searchField").value = decodeURIComponent(searchField).trim();
    }

    // Kui otsinguväärtus on määratud, seatakse see vastava sisendvälja väärtuseks
    if (searchValue) {
        document.getElementById("searchValue").value = decodeURIComponent(searchValue).trim();
    }
};

// Funktsioon, mis saadab otsingupäringu
function sendSearchRequest() {
    // Võtab sisestatud väärtused otsinguväljast ja otsinguväärtusest
    var searchField = document.getElementById("searchField").value;
    var searchValue = document.getElementById("searchValue").value;

    // Eemaldab lisamärgid ja tühikud
    searchField = decodeURIComponent(searchField).trim();
    searchValue = decodeURIComponent(searchValue).trim();

    // Asendab mitmik-tühikud ühe tühikuga
    searchValue = searchValue.replace(/\s+/g, ' ');

    // Loob uue URL-i koos otsingupäringu parameetritega
    var queryString = "?searchField=" + encodeURIComponent(searchField) + "&searchValue=" + encodeURIComponent(searchValue);

    // Suunab kasutaja uuele URL-ile
    var newUrl = window.location.pathname + queryString;
    window.location.href = newUrl;
}

// Funktsioon, mis laadib kogu andmestiku ilma filtriteta
function sendAllDataRequest() {
    // Taaskäivitab lehe algse URL-i põhjal
    var url = window.location.pathname;
    window.location.href = url;
}

// Funktsioon tabeli sorteerimiseks veeru alusel
function sortTable(columnIndex) {
    var table = document.getElementById("drinkTable");
    // Võtab kõik tabeliread, välja arvatud päiserida
    var rows = Array.from(table.rows).slice(1);
    // Kontrollib, kas veerg on hetkel kasvavas järjestuses
    var isAscending = table.rows[0].cells[columnIndex].classList.contains('asc');

    // Sorteerib read vastavalt veeru andmetele
    rows.sort(function (a, b) {
        var cellA = a.cells[columnIndex].innerText;
        var cellB = b.cells[columnIndex].innerText;

        // Kui veerg sisaldab numbrilisi väärtusi, teisendatakse need enne sorteerimist
        if (columnIndex === 2 || columnIndex === 7 || columnIndex === 8) {
            cellA = parseFloat(cellA);
            cellB = parseFloat(cellB);
        }

        // Võrdlemise loogika sõltuvalt kasvavast või kahanevast järjestusest
        if (cellA < cellB) {
            return isAscending ? -1 : 1;
        } else if (cellA > cellB) {
            return isAscending ? 1 : -1;
        } else {
            return 0;
        }
    });

    // Lisab sorditud read tabelisse uuesti
    rows.forEach(function (row) {
        table.appendChild(row);
    });

    // Muudab päiserida, et näidata sorteerimise suunda
    table.rows[0].cells[columnIndex].classList.toggle('asc', !isAscending);
    table.rows[0].cells[columnIndex].classList.toggle('desc', isAscending);
}

// Funktsioon veergude valiku kuvamiseks või peitmiseks
function toggleColumnSelection() {
    const checkbox = document.getElementById("toggleColumnCheckbox");
    const columnSelection = document.getElementById("columnSelection");

    // Kuvab või peidab veergude valiku vastavalt linnukese olekule
    if (checkbox.checked) {
        columnSelection.style.display = "block";
    } else {
        columnSelection.style.display = "none";
    }
}

// Funktsioon, mis rakendab kasutaja valitud veergude kuvamise eelistused
function applyColumnSelection() {
    const checkboxes = document.querySelectorAll(".column-checkbox");

    // Käib läbi kõik veeruväljade linnukesed
    checkboxes.forEach(checkbox => {
        const columnIndex = checkbox.getAttribute("data-column");
        // Leiab tabelist kõik vastava veeru lahtrid (nii päised kui ka andmed)
        const columnCells = document.querySelectorAll(`#drinkTable td:nth-child(${parseInt(columnIndex) + 1}), #drinkTable th:nth-child(${parseInt(columnIndex) + 1})`);

        // Lisab või eemaldab klassi "hidden" vastavalt linnukese olekule
        columnCells.forEach(cell => {
            if (checkbox.checked) {
                cell.classList.remove("hidden");
            } else {
                cell.classList.add("hidden");
            }
        });
    });

    // Sulgeb valikumenüü (vajadusel saab selle funktsiooni defineerida eraldi)
    closeModal();
}