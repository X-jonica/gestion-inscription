// Javascript de la page list_candidat.php

// Script pour remplir le modal avec les données du candidat
document.addEventListener('DOMContentLoaded', function() {
    var candidatModal = document.getElementById('candidatModal');
    candidatModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        
        // Remplir les champs du formulaire
        document.getElementById('modal-id').value = button.getAttribute('data-id');
        document.getElementById('modal-nom').value = button.getAttribute('data-nom');
        document.getElementById('modal-prenom').value = button.getAttribute('data-prenom');
        document.getElementById('modal-email').value = button.getAttribute('data-email');
        document.getElementById('modal-telephone').value = button.getAttribute('data-telephone');
        document.getElementById('modal-type_bacc').value = button.getAttribute('data-type_bacc');
        document.getElementById('modal-annee_bacc').value = button.getAttribute('data-annee_bacc');
        document.getElementById('modal-paiement').value = button.getAttribute('data-paiement');
        
        // Mettre à jour le titre du modal
        document.getElementById('candidatModalLabel').textContent = 
            'Détails: ' + button.getAttribute('data-prenom') + ' ' + button.getAttribute('data-nom');
    });
});

// deconnexion
document.getElementById('logoutLink').addEventListener('click', function(e) {
    e.preventDefault();
    const confirmLogout = confirm("Voulez-vous vraiment vous déconnecter ?");
    if (confirmLogout) {
        window.location.href = "logout.php";
    }
});