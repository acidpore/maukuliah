// Perilaku: kirim form saat nilai berubah. Atribut: data-auto-submit pada field.
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-auto-submit]').forEach(function (field) {
        field.addEventListener('change', function () {
            if (field.form) {
                field.form.submit();
            }
        });
    });
});
