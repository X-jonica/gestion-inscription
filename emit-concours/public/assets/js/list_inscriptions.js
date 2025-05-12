// Partie Javascript de la page list_inscriptions.php 

// Script pour remplir le modal avec les données de l'inscription
document.addEventListener("DOMContentLoaded", function () {
    var inscriptionModal = document.getElementById("inscriptionModal");
    inscriptionModal.addEventListener("show.bs.modal", function (event) {
        var button = event.relatedTarget;

        // Remplir les champs du formulaire
        document.getElementById("modal-inscription-id").value =
            button.getAttribute("data-id");
        document.getElementById("modal-candidat").value =
            button.getAttribute("data-candidat");
        document.getElementById("modal-concours").value =
            button.getAttribute("data-concours");
        document.getElementById("modal-date").value =
            button.getAttribute("data-date");

        // Sélectionner le statut actuel
        var currentStatus = button.getAttribute("data-statut");
        var statusSelect = document.getElementById("modal-statut");
        for (var i = 0; i < statusSelect.options.length; i++) {
            if (statusSelect.options[i].value === currentStatus) {
                statusSelect.selectedIndex = i;
                break;
            }
        }

        // Mettre à jour le titre du modal
        document.getElementById("inscriptionModalLabel").textContent =
            "Modifier inscription #" + button.getAttribute("data-id");
    });
});

// deconnexion
document.getElementById("logoutLink").addEventListener("click", function (e) {
    e.preventDefault();
    const confirmLogout = confirm("Voulez-vous vraiment vous déconnecter ?");
    if (confirmLogout) {
        window.location.href = "logout.php";
    }
});
