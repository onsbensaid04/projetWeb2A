document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function (e) {
        let isValid = true;
        clearErrors();

        const email = document.getElementById('email').value.trim();
        const mot_de_passe = document.getElementById('mot_de_passe').value.trim();

        if (!email || !validateEmail(email)) {
            showError('error-email', "Email invalide");
            isValid = false;
        }

        if (!mot_de_passe || mot_de_passe.length < 6) {
            showError('error-mot_de_passe', "Mot de passe trop court");
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(id, message) {
        document.getElementById(id).innerText = message;
    }

    function clearErrors() {
        const errors = document.querySelectorAll('.error-message');
        errors.forEach(error => error.innerText = '');
    }

    function validateEmail(email) {
        const re = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        return re.test(email);
    }
});
