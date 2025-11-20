const NOTIFICATION_DURATION = 8000; // 8000 ms = 8 sec

export async function getNewAdmissions() {
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

        notifications.replaceChildren(); // on vide avant d'afficher

        data.forEach((element) => {
            const notif = document.createElement("div");

            notif.classList.add(
                "notif-item",
                "bg-success",
                "text-white",
                "p-3",
                "rounded-3",
                "shadow"
            );
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
            playSuccessSound();

            notifications.appendChild(notif);

            // 🟣 Auto-disparition après X secondes
            setTimeout(() => {
                notif.classList.add("fade-out");
                setTimeout(() => notif.remove(), 700); // après l’animation
            }, NOTIFICATION_DURATION);
        });
    } catch (error) {
        console.error("Erreur getNewAdmissions():", error);
    }
}
export async function validateAllAdmissions() {
    const res = await fetch("/api/plugins/admission/validate-all", {
        method: "POST",
    });

    const json = await res.json();
    console.log(json);

    if (json.success) {
        // nettoyer le DOM
        document.getElementById("notifications").innerHTML = "";
    }
}

async function refreshAdmissions() {
    await getNewAdmissions();

    // attendre la fin de l'affichage des notifications
    await new Promise((resolve) => {
        setTimeout(resolve, NOTIFICATION_DURATION + 800);
    });

    await validateAllAdmissions();
}

setInterval(refreshAdmissions, NOTIFICATION_DURATION + 1500);
