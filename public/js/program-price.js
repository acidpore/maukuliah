// Perilaku: tampilkan harga & promo prodi sesuai kampus, jurusan, dan program yang dipilih.
// Atribut: form [data-program-prices] berisi JSON peta "kampus:jurusan:program" ke harga.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-program-prices]');

    if (!form) {
        return;
    }

    var map = JSON.parse(form.getAttribute('data-program-prices'));
    var campus = form.querySelector('#field-campus_id');
    var major = form.querySelector('#field-major_id');
    var programType = form.querySelector('#field-program_type');
    var panel = form.querySelector('#program-price');

    if (!campus || !major || !programType || !panel) {
        return;
    }

    function rupiah(amount) {
        return 'Rp' + amount.toLocaleString('id-ID');
    }

    function render() {
        var key = campus.value + ':' + major.value + ':' + programType.value;
        var program = map[key];

        if (!program) {
            panel.hidden = true;
            return;
        }

        panel.innerHTML = (program.has_discount
            ? '<s class="price__old">' + rupiah(program.original_monthly_installment) + '</s> '
            : '') + '<strong class="price__amount">' + rupiah(program.monthly_installment) + '</strong><span class="price__suffix"> / bulan</span>';
        panel.hidden = false;
    }

    [campus, major, programType].forEach(function (field) {
        field.addEventListener('change', render);
    });
    render();
});
