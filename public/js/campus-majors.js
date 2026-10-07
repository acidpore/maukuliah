// Perilaku: saring pilihan jurusan sesuai kampus yang dipilih pada formulir pendaftaran.
// Atribut: form [data-campus-majors] berisi JSON peta id kampus ke daftar id jurusan.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('[data-campus-majors]');

    if (!form) {
        return;
    }

    var map = JSON.parse(form.getAttribute('data-campus-majors'));
    var campus = form.querySelector('#field-campus_id');
    var major = form.querySelector('#field-major_id');

    if (!campus || !major) {
        return;
    }

    function filter() {
        var allowed = map[campus.value] || null;

        Array.prototype.forEach.call(major.options, function (option) {
            if (option.value === '') {
                return;
            }

            var isAllowed = allowed === null || allowed.indexOf(parseInt(option.value, 10)) !== -1;
            option.disabled = !isAllowed;
            option.hidden = !isAllowed;
        });

        if (major.selectedOptions[0] && major.selectedOptions[0].disabled) {
            major.value = '';
        }
    }

    campus.addEventListener('change', filter);
    filter();
});
