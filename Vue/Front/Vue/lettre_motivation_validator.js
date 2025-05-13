function validateLettreMotivationModal() {
    var textarea = document.getElementById('modalLettreMotivation');
    var value = textarea.value.trim();

    var error = '';
    if (value.length < 15) {
        error = 'La lettre de motivation doit contenir au moins 15 caractères.';
    } else if (value.length > 1000) {
        error = 'La lettre de motivation ne doit pas dépasser 1000 caractères.';
    }
    var errSpan = document.getElementById('lettreMotivationError');
    if (!errSpan) {
        errSpan = document.createElement('div');
        errSpan.id = 'lettreMotivationError';
        errSpan.style.color = '#e53935';
        errSpan.style.marginBottom = '10px';
        textarea.parentNode.insertBefore(errSpan, textarea.nextSibling);
    }
    if (error) {
        errSpan.textContent = error;
        return false;
    } else {
        errSpan.textContent = '';
        return true;
    }
}

