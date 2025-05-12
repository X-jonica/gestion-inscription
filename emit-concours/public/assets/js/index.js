// Effet de typewriter pour le titre :
document.addEventListener("DOMContentLoaded", function () {
    const h1 = document.querySelector("h1");
    const originalText = h1.textContent;
    h1.textContent = "";
    let i = 0;

    function typeWriter() {
        if (i < originalText.length) {
            h1.textContent += originalText.charAt(i);
            i++;
            setTimeout(typeWriter, 50);
        }
    }

    typeWriter();
});

// Animation des boutons
const buttons = document.querySelectorAll(".buttons a");

buttons.forEach((button) => {
    button.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-5px)";
        this.style.boxShadow = "0 10px 20px rgba(0,0,0,0.2)";
    });

    button.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0)";
        this.style.boxShadow = "none";
    });
});
