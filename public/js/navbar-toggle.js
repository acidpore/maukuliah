// Perilaku: buka tutup menu navigasi di layar kecil. Pemicu: [data-nav-toggle], target: [data-nav].
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-nav]');

    if (!toggle || !nav) {
        return;
    }

    toggle.addEventListener('click', function () {
        var isOpen = nav.classList.toggle('is-open');

        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
});
