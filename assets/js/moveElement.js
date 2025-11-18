export function moveElement(tag) {
    const element = document.getElementById(tag);

    // Sécurité : si l’élément n’existe pas, on quitte proprement
    if (!element) return;

    element.classList.add("shake");

    // Retire l'effet après 2 secondes (SEULEMENT UNE FOIS)
    setTimeout(() => {
        element.classList.remove("shake");
    }, 2000);
}

export function enableShakeLoop(inputId) {
    const input = document.getElementById(inputId);
    console.log(input);
    let shakeInterval = null;

    if (!input) return; // sécurité

    input.addEventListener("input", () => {
        if (input.value.length > 10) {
            // Evite plusieurs intervalles
            if (!shakeInterval) {
                shakeInterval = setInterval(() => {
                    input.classList.add("invalidInput");

                    setTimeout(() => {
                        input.classList.remove("invalidInput");
                    }, 350);
                }, 2000);
            }
        } else {
            clearInterval(shakeInterval);
            shakeInterval = null;
            input.classList.remove("invalidInput");
        }
    });
}
