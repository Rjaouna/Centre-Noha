// Durée d'affichage des notifs
const NOTIFICATION_DURATION = 8000;

// On attend que le DOM soit chargé
document.addEventListener("DOMContentLoaded", () => {
    // On lance la boucle seulement si l’élément existe
    const notifications = document.getElementById("notifications");
    if (!notifications) {
        console.warn(
            "⚠️ #notifications introuvable, pas de boucle admissions."
        );
        return;
    }

    startAdmissionsLoop();
});

async function getNewAdmissions() {
    const url = window.ADMISSIONS_URL;
    const notifications = document.getElementById("notifications");
    if (!notifications) return;

    try {
        const response = await fetch(url, {
            method: "GET",
            headers: { Accept: "application/json" },
            credentials: "same-origin",
        });

        if (!response.ok) throw new Error("Erreur serveur");

        const data = await response.json();
        console.log("Admissions reçues :", data);

        // on vide proprement sans casser le DOM global
        notifications.replaceChildren();

        data.forEach((element) => {
            const notif = document.createElement("div");
            notif.className =
                "notif-item bg-success text-white p-3 rounded-3 shadow";

            notif.innerHTML = `
                <p class="notif-title mb-1 d-flex justify-content-between align-items-center">
                    <span>
                        ${element.nom} —
                        <span class="text-light opacity-75">
                            ${element.typeMaladie ?? "Non spécifié"}
                        </span>
                    </span>
                </p>
                <small class="notif-details">
                    <i>Nouvelle admission</i> :
                    ${new Date().toLocaleString()}
                </small>
            `;

            notifications.appendChild(notif);

            // disparition douce
            setTimeout(() => {
                notif.classList.add("fade-out");
                setTimeout(() => notif.remove(), 700);
            }, NOTIFICATION_DURATION);
        });
    } catch (error) {
        console.error("Erreur getNewAdmissions():", error);
    }
}

async function validateAllAdmissions() {
    try {
        const res = await fetch("/api/plugins/admission/validate-all", {
            method: "POST",
        });

        const json = await res.json();
        console.log("Validation admissions :", json);
    } catch (e) {
        console.error("Erreur validateAllAdmissions():", e);
    }
}

async function refreshAdmissions() {
    await getNewAdmissions();

    // après l’affichage, on valide tout
    setTimeout(() => {
        validateAllAdmissions();
    }, NOTIFICATION_DURATION + 800);
}

function startAdmissionsLoop() {
    // toutes les X secondes on rafraîchit
    refreshAdmissions(); // premier appel
    setInterval(refreshAdmissions, NOTIFICATION_DURATION + 1500);
}
