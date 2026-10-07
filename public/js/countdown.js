// Perilaku: hitung mundur waktu tryout dan kirim form saat waktu habis.
// Atribut: form [data-countdown-form], penampil [data-countdown] berisi detik awal.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-countdown-form]');
    var display = document.querySelector('[data-countdown]');

    if (!form || !display) {
        return;
    }

    var remaining = parseInt(display.getAttribute('data-countdown'), 10);

    function pad(value) {
        return String(value).padStart(2, '0');
    }

    function render() {
        display.textContent = pad(Math.floor(remaining / 60)) + ':' + pad(remaining % 60);
    }

    var timer = setInterval(function () {
        remaining -= 1;
        render();

        if (remaining <= 0) {
            clearInterval(timer);
            form.submit();
        }
    }, 1000);

    render();
});
