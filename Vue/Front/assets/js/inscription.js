document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');

    form.addEventListener('submit', function (e) {
        let isValid = true;

        clearErrors();

        const prenom = document.getElementById('prenom').value.trim();
        const nom = document.getElementById('nom').value.trim();
        const email = document.getElementById('email').value.trim();
        const telephone = document.getElementById('telephone').value.trim();
        const genre = document.getElementById('genre').value;
        const role = document.getElementById('role').value;
        const mot_de_passe = document.getElementById('mot_de_passe').value.trim();
        const confirm_mot_de_passe = document.getElementById('confirm_mot_de_passe').value.trim();

        if (!prenom) {
            showError('error-prenom', "Prénom requis");
            isValid = false;
        }

        if (!nom) {
            showError('error-nom', "Nom requis");
            isValid = false;
        }

        if (!email || !validateEmail(email)) {
            showError('error-email', "Email invalide");
            isValid = false;
        }

        if (!telephone || !/^\d{8,15}$/.test(telephone)) {
            showError('error-telephone', "Téléphone invalide (8 à 15 chiffres)");
            isValid = false;
        }

        if (!genre) {
            showError('error-genre', "Veuillez choisir un genre");
            isValid = false;
        }

        if (!mot_de_passe || mot_de_passe.length < 6) {
            showError('error-mot_de_passe', "Mot de passe trop court (min. 6 caractères)");
            isValid = false;
        }

        if (mot_de_passe !== confirm_mot_de_passe) {
            showError('error-confirm_mot_de_passe', "Les mots de passe ne correspondent pas");
            isValid = false;
        }

        if (!role) {
            showError('error-role', "Veuillez choisir un rôle");
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
