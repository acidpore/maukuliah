// Perilaku: perbarui kemajuan pengerjaan tes saat jawaban dipilih.
// Atribut: form [data-test-form], bilah [data-test-progress], angka [data-test-answered].
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-test-form]');

    if (!form) {
        return;
    }

    var bar = form.querySelector('[data-test-progress]');
    var counter = form.querySelector('[data-test-answered]');
    var questions = form.querySelectorAll('.question');

    function update() {
        var answered = 0;

        questions.forEach(function (question) {
            if (question.querySelector('input:checked')) {
                answered += 1;
                question.classList.remove('question--error');
            }
        });

        if (bar) {
            bar.value = answered;
        }

        if (counter) {
            counter.textContent = answered;
        }
    }

    form.addEventListener('change', update);
    update();
});
