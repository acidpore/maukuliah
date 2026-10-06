// Perilaku: munculkan elemen saat masuk layar (fokus pada hierarki bagian). Atribut: data-reveal.
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('[data-reveal]');

    if (!('IntersectionObserver' in window)) {
        items.forEach(function (item) {
            item.classList.add('is-visible');
        });

        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    items.forEach(function (item) {
        observer.observe(item);
    });
});
