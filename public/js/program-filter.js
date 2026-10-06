// Perilaku: saring baris tabel program studi di sisi klien.
// Atribut: [data-program-table] pembungkus, select [data-program-filter="programType|degreeLevel"],
// baris [data-program-row] dengan data-program-type dan data-degree-level, pesan kosong [data-program-empty].
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-program-table]').forEach(function (table) {
        var filters = table.querySelectorAll('[data-program-filter]');
        var rows = table.querySelectorAll('[data-program-row]');
        var emptyMessage = table.querySelector('[data-program-empty]');

        function apply() {
            var visible = 0;

            rows.forEach(function (row) {
                var matches = true;

                filters.forEach(function (filter) {
                    var key = filter.getAttribute('data-program-filter');
                    var rowValue = row.dataset[key];

                    if (filter.value !== '' && rowValue !== filter.value) {
                        matches = false;
                    }
                });

                row.hidden = !matches;

                if (matches) {
                    visible += 1;
                }
            });

            if (emptyMessage) {
                emptyMessage.hidden = visible > 0;
            }
        }

        filters.forEach(function (filter) {
            filter.addEventListener('change', apply);
        });
    });
});
