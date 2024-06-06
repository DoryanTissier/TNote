document.getElementsByName('type-p').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var userType = this.value;
        if (userType === 'etudiant') {
            document.getElementById('connex').style.backgroundImage = "url('../../source/img/fond/f_etu.png')";
        } else if (userType === 'professeur') {
            document.getElementById('connex').style.backgroundImage = "url('../../source/img/fond/f_prof.png')";
        } else if (userType === 'admin') {
            document.getElementById('connex').style.backgroundImage = "url('../../source/img/fond/f_admin.png')";
        }
    });
});

// JavaScript pour fermer la notification
function closeNotification() {
    var notification = document.getElementById('errorNotification');
    notification.style.opacity = '0';
    setTimeout(function() {
        notification.style.display = 'none';
    }, 500);
}

// Disparition automatique des notifications après quelques secondes
setTimeout(function() {
    var notification = document.getElementById('errorNotification');
    if (notification) {
        notification.style.opacity = '0';
        setTimeout(function() {
            notification.style.display = 'none';
        }, 500);
    }
}, 5000);