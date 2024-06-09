document.addEventListener('DOMContentLoaded', () => {
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 2000); // Affiche le message pendant 2 secondes
    }
});