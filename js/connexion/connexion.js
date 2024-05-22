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