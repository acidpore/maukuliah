// Perilaku: buka tutup menu samping admin di layar kecil.
// Atribut: [data-admin-toggle] tombol, [data-admin-backdrop] lapisan latar, [data-admin] pembungkus.
document.addEventListener('DOMContentLoaded', function () {
    var shell = document.querySelector('[data-admin]');
    var toggle = document.querySelector('[data-admin-toggle]');
    var backdrop = document.querySelector('[data-admin-backdrop]');

    if (!shell || !toggle) {
        return;
    }

    function setOpen(open) {
        shell.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');

        if (backdrop) {
            backdrop.hidden = !open;
        }
    }

    toggle.addEventListener('click', function () {
        setOpen(!shell.classList.contains('is-open'));
    });

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            setOpen(false);
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
});
