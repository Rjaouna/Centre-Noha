// -------------------------------------------------------------
// 🎵 Classe AudioPlayer (propre)
// -------------------------------------------------------------
class AudioPlayer {
    constructor(src) {
        this.audio = new Audio(src);
    }

    play() {
        return this.audio.play().catch((err) => {
            console.warn("Lecture audio bloquée :", err);
        });
    }
}

// -------------------------------------------------------------
// 🕒 Sleep (promise)
// -------------------------------------------------------------
function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

// -------------------------------------------------------------
// 🔊 1️⃣ Son simple + option désactivation + loader
// -------------------------------------------------------------
export function buttonSoundClick(id, disabled = "") {
    const tag = document.getElementById(id);
    if (!tag) return;

    tag.addEventListener("click", async () => {
        // 🎵 Son du clic
        const clickSound = new Audio("/assets/media/light-switch-382712.mp3");
        clickSound.play().catch(() => {});

        // Si disabled ≠ "disabled" → on joue juste le son
        if (disabled !== "disabled") return;

        const icon = tag.querySelector("i");

        // 🔒 Désactiver bouton
        tag.classList.add("disabled");

        // 🔄 Ajouter icône chargement
        if (icon) icon.className = "bi bi-arrow-repeat spin";

        // ⏳ Attendre 2 sec
        await sleep(2000);

        // ✔️ Retour à l'icone d'origine
        if (icon) icon.className = "bi bi-check-circle";

        // 🔓 Réactiver bouton
        tag.classList.remove("disabled");
    });
}

// -------------------------------------------------------------
// ❌ 2️⃣ Son erreur + loader rouge + réactivation
// -------------------------------------------------------------
export function buttonSoundError(id) {
    const tag = document.getElementById(id);
    if (!tag) return;

    const player = new Audio("/assets/media/error-08-206492.mp3");
    const icon = tag.querySelector("i");

    // 🔒 Désactivation
    tag.classList.add("disabled");

    // 🔄 Loader
    if (icon) icon.className = "bi bi-arrow-repeat spin text-danger";

    player.play().catch(() => {});

    // ⏳ Attendre avant retour
    setTimeout(() => {
        if (icon) icon.className = "bi bi-x-circle text-danger";

        tag.classList.remove("disabled");
    }, 2000);
}

// -------------------------------------------------------------
// ✅ 3️⃣ Son succès + réactivation
// -------------------------------------------------------------
export function buttonSoundSuccess(id) {
    const tag = document.getElementById(id);
    if (!tag) return;

    const successSound = new AudioPlayer("/assets/media/success-340660.mp3");
    successSound.play();

    tag.classList.remove("disabled");
}
