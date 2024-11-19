const hiddenColumns = new Set();
function toggleColumnVisibility(columnIndex, columnName) {
    const cells = document.querySelectorAll(`td:nth-child(${columnIndex}), th:nth-child(${columnIndex})`);
    const hiddenColumnsContainer = document.getElementById('hidden-columns');

    if (hiddenColumns.has(columnIndex)) {

        cells.forEach(cell => cell.style.display = '');
        hiddenColumns.delete(columnIndex);

        const button = document.querySelector(`button[data-column="${columnIndex}"]`);
        if (button) hiddenColumnsContainer.removeChild(button);
    } else {

        cells.forEach(cell => cell.style.display = 'none');
        hiddenColumns.add(columnIndex);


        const button = document.createElement('button');
        button.textContent = `Show: ${columnName}`;
        button.dataset.column = columnIndex;
        button.onclick = () => toggleColumnVisibility(columnIndex, columnName);
        hiddenColumnsContainer.appendChild(button);
    }
    if (hiddenColumns.size === 0) document.getElementById('hidden-columns-header').style.display = 'none';
    else document.getElementById('hidden-columns-header').style.display = '';
}
function keepChecked(checkbox) {
    checkbox.checked = true;
}
function submitForm(form) {
    // Leidme vorm ID järgi ja kutsuge meetod submit
    document.getElementById(form).submit();
}