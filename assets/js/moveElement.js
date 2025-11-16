export function moveElement(tag) {
    const element = document.getElementById(tag);
    element.classList.add("shake");
    setInterval(() => {
        element.classList.remove("shake");
    }, 2000);
}


export function enableShakeLoop(inputId) {
    const input = document.getElementById(inputId);
    console.log(input)
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

