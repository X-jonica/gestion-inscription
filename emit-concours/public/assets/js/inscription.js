// Annimation javascript dans le inscription.js

// Animation d'apparition progressive des éléments :
document.addEventListener("DOMContentLoaded", function () {
    // Animation d'apparition des éléments du formulaire
    const formGroups = document.querySelectorAll(".form-group");
    formGroups.forEach((group, index) => {
        group.style.opacity = "0";
        group.style.transform = "translateY(20px)";
        group.style.transition = "all 0.5s ease-out";

        setTimeout(() => {
            group.style.opacity = "1";
            group.style.transform = "translateY(0)";
        }, 100 + index * 100);
    });

    // Animation du titre du formulaire
    const formTitle = document.querySelector(".form-title");
    formTitle.style.opacity = "0";
    formTitle.style.transform = "translateY(-20px)";
    formTitle.style.transition = "all 0.5s ease-out";

    setTimeout(() => {
        formTitle.style.opacity = "1";
        formTitle.style.transform = "translateY(0)";
    }, 50);
});

// Animation des champs au focus :
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll("input, select");

    inputs.forEach((input) => {
        input.addEventListener("focus", function () {
            this.parentElement.style.transform = "scale(1.02)";
            this.parentElement.style.transition = "transform 0.3s ease";
        });

        input.addEventListener("blur", function () {
            this.parentElement.style.transform = "scale(1)";
        });
    });
});

// Animation du bouton de soumission :
document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.querySelector('button[type="submit"]');

    submitButton.addEventListener("mouseenter", function () {
        this.style.letterSpacing = "2px";
    });

    submitButton.addEventListener("mouseleave", function () {
        this.style.letterSpacing = "1px";
    });

    submitButton.addEventListener("click", function (e) {
        if (this.form.checkValidity()) {
            this.innerHTML = "Envoi en cours...";
            this.style.backgroundColor = "#16a085";
        }
    });
});

// Animation du message de succès/erreur :
document.addEventListener("DOMContentLoaded", function () {
    const messages = document.querySelectorAll(".message");

    messages.forEach((message) => {
        message.style.opacity = "0";
        message.style.maxHeight = "0";
        message.style.overflow = "hidden";
        message.style.transition = "all 0.5s ease";

        setTimeout(() => {
            message.style.opacity = "1";
            message.style.maxHeight = "100px";
        }, 500);
    });
});
