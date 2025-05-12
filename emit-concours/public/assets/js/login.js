// Partie js de la page login.php

// Animation d'apparition du formulaire
document.addEventListener("DOMContentLoaded", function () {
    const loginBox = document.getElementById("loginBox");

    // Délai pour que l'animation soit visible
    setTimeout(() => {
        loginBox.classList.add("loaded");
    }, 500);

    // Animation des champs au focus
    const inputs = document.querySelectorAll("input");
    inputs.forEach((input) => {
        input.addEventListener("focus", function () {
            this.style.transform = "scale(1.02)";
        });

        input.addEventListener("blur", function () {
            this.style.transform = "scale(1)";
        });
    });

    // Animation du bouton de soumission
    const loginButton = document.querySelector(".login-button");
    if (loginButton) {
        loginButton.addEventListener("click", function (e) {
            if (this.form.checkValidity()) {
                this.style.transform = "translateY(0) scale(0.98)";
                this.style.opacity = "0.8";
            }
        });
    }

});
