// Perilaku: efek ketik pada kata sorotan hero. Atribut: data-typed="kata satu|kata dua".
// Teks awal di markup tetap tampil bila gerakan dikurangi atau JS mati.
document.addEventListener('DOMContentLoaded', function () {
    var target = document.querySelector('[data-typed]');

    if (!target || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var words = target.dataset.typed.split('|');
    var label = target.querySelector('[data-typed-label]');
    var wordIndex = 0;
    var charIndex = label.textContent.length;
    var deleting = true;

    function tick() {
        var word = words[wordIndex];

        charIndex += deleting ? -1 : 1;
        label.textContent = word.slice(0, charIndex);

        var delay = deleting ? 40 : 85;

        if (!deleting && charIndex === word.length) {
            deleting = true;
            delay = 1800;
        } else if (deleting && charIndex === 0) {
            deleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            delay = 300;
        }

        setTimeout(tick, delay);
    }

    words.unshift(label.textContent);
    setTimeout(tick, 1800);
});
