// Perilaku: salin isi kolom ke papan klip.
// Atribut: [data-copy-source] pada input, [data-copy-button] pada tombol dalam pembungkus yang sama.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-copy-button]').forEach(function (button) {
        var source = button.parentElement.querySelector('[data-copy-source]');

        if (!source || !navigator.clipboard) {
            button.hidden = true;

            return;
        }

        button.addEventListener('click', function () {
            navigator.clipboard.writeText(source.value).then(function () {
                var label = button.textContent;

                button.textContent = 'Tersalin';
                setTimeout(function () {
                    button.textContent = label;
                }, 1800);
            });
        });
    });
});
