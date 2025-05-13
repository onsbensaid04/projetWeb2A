// Bootstrap form validation
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var form = document.getElementById('modifyChannelForm');
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }, false);
})();

// Image preview function
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function () {
            preview.style.display = 'block';
            preview.src = reader.result;
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        preview.src = '';
    }
}
