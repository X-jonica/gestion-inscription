// Animation dans la page dashboard.php
document.addEventListener("DOMContentLoaded", function () {
    // 1. Animation d'apparition des cartes
    const cards = document.querySelectorAll(".dashboard-card");
    cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "all 1s ease-out";

        setTimeout(() => {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, 100 * index);
    });

    // 2. Effet de survol amélioré pour les liens de la sidebar
    const navLinks = document.querySelectorAll(".sidebar-nav a");
    navLinks.forEach((link) => {
        link.addEventListener("mouseenter", function () {
            this.style.transform = "translateX(5px)";
            this.style.transition = "transform 0.5s ease";
        });

        link.addEventListener("mouseleave", function () {
            this.style.transform = "translateX(0)";
        });
    });

    // 3. Animation du titre de bienvenue
    const welcomeHeader = document.querySelector(".welcome-header");
    welcomeHeader.style.opacity = "0";
    welcomeHeader.style.transition = "opacity 1.5s ease";

    setTimeout(() => {
        welcomeHeader.style.opacity = "1";
    }, 300);

    // 4. Confirmation de déconnexion
    const logoutLink = document.getElementById("logoutLink");
    if (logoutLink) {
        logoutLink.addEventListener("click", function (e) {
            if (!confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
                e.preventDefault();
            }
        });
    }
});
