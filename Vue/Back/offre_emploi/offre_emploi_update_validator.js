function validform() {
    var valid = true;
    var titre = document.getElementById('titre');
    var description = document.getElementById('description');
    var entreprise = document.getElementById('entreprise');
    var lieu = document.getElementById('lieu');
    var salaire = document.getElementById('salaire');
    var dateLimit = document.getElementById('date_limit');

    var titreErr = document.getElementById('titreErr');
    var descErr = document.getElementById('descErr');
    var entErr = document.getElementById('entErr');
    var lieuErr = document.getElementById('lieuErr');
    var salErr = document.getElementById('salErr');
    var dateErr = document.getElementById('dateErr');

    titreErr.innerHTML = '';
    descErr.innerHTML = '';
    entErr.innerHTML = '';
    lieuErr.innerHTML = '';
    salErr.innerHTML = '';
    dateErr.innerHTML = '';
    titre.classList.remove('is-invalid');
    description.classList.remove('is-invalid');
    entreprise.classList.remove('is-invalid');
    lieu.classList.remove('is-invalid');
    salaire.classList.remove('is-invalid');
    dateLimit.classList.remove('is-invalid');

    var titreVal = titre.value.trim();
    if (titreVal === '' || titreVal.length < 3 || titreVal.length > 100) {
        titreErr.innerHTML = 'Titre doit avoir entre 3 et 100 caractères';
        titre.classList.add('is-invalid');
        valid = false;
    }
    var descVal = description.value.trim();
    if (descVal === '' || descVal.length < 3 || descVal.length > 255) {
        descErr.innerHTML = 'Description doit avoir entre 3 et 255 caractères';
        description.classList.add('is-invalid');
        valid = false;
    }
    var entVal = entreprise.value.trim();
    if (entVal === '' || entVal.length < 3 || entVal.length > 100) {
        entErr.innerHTML = 'Entreprise doit avoir entre 3 et 100 caractères';
        entreprise.classList.add('is-invalid');
        valid = false;
    }
    var lieuVal = lieu.value.trim();
    if (lieuVal === '' || lieuVal.length < 3 || lieuVal.length > 100) {
        lieuErr.innerHTML = 'Lieu doit avoir entre 3 et 100 caractères';
        lieu.classList.add('is-invalid');
        valid = false;
    }
    var salVal = salaire.value.trim();
    if (salVal === '' || isNaN(salVal) || Number(salVal) < 100) {
        salErr.innerHTML = 'Salaire doit être un nombre positif >= 100';
        salaire.classList.add('is-invalid');
        valid = false;
    }
    var dateVal = dateLimit.value;
    if (!dateVal) {
        dateErr.innerHTML = 'Date limite est requise';
        dateLimit.classList.add('is-invalid');
        valid = false;
    } else {
        var today = new Date();
        var selected = new Date(dateVal);
        if (selected <= today) {
            dateErr.innerHTML = 'Date limite doit être après aujourd\'hui';
            dateLimit.classList.add('is-invalid');
            valid = false;
        }
    }
    return valid;
}
