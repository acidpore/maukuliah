document.querySelectorAll('[data-slider]').forEach(function (slider) {
    const rail = slider.querySelector('[data-rail]');
    const next = slider.querySelector('[data-next]');
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    next.addEventListener('click', function () {
        const atEnd = rail.scrollLeft + rail.clientWidth >= rail.scrollWidth - 5;
        rail.scrollTo({left: atEnd ? 0 : rail.scrollLeft + rail.clientWidth * .8, behavior: reduced ? 'instant' : 'smooth'});
    });
});
