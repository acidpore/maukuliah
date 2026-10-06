// Perilaku: sorot tab anchor sesuai bagian yang sedang terlihat. Atribut: [data-tabs] dan a[data-tab-link].
document.addEventListener('DOMContentLoaded', function () {
    var links = document.querySelectorAll('[data-tabs] [data-tab-link]');

    if (!links.length || !('IntersectionObserver' in window)) {
        return;
    }

    var byId = {};

    links.forEach(function (link) {
        byId[link.getAttribute('href').slice(1)] = link;
    });

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }

            links.forEach(function (link) {
                link.classList.remove('is-active');
            });

            byId[entry.target.id].classList.add('is-active');
        });
    }, { rootMargin: '-30% 0px -60% 0px' });

    Object.keys(byId).forEach(function (id) {
        var section = document.getElementById(id);

        if (section) {
            observer.observe(section);
        }
    });
});
