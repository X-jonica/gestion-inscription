// Partie Javascript de la page list_concours.php

// Script pour remplir le modal avec les données du concours
document.addEventListener("DOMContentLoaded", function () {
    var concoursModal = document.getElementById("concoursModal");
    concoursModal.addEventListener("show.bs.modal", function (event) {
        var button = event.relatedTarget;

        // Récupérer les données des attributs data-*
        document.getElementById("modal-id").textContent =
            button.getAttribute("data-id");
        document.getElementById("modal-mention").textContent =
            button.getAttribute("data-mention");
        document.getElementById("modal-date").textContent =
            button.getAttribute("data-date");

        // Gestion du statut avec badge coloré
        var statut = button.getAttribute("data-statut");
        var statutBadge = document.getElementById("modal-statut");
        statutBadge.textContent = statut;
        statutBadge.className =
            "badge badge-statut " +
            (statut === "ouvert" ? "badge-ouvert" : "badge-ferme");

        // Mettre à jour le titre du modal
        document.getElementById("concoursModalLabel").textContent =
            "Concours: " + button.getAttribute("data-mention");
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
