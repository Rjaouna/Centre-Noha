/**
 * Import de ton CSS principal
 */
import "./styles/app.css";
/**
 * Import de ton CSS principal
 */
import "./styles/app.scss";

/**
 * Import jQuery
 */
import $ from "jquery";
window.$ = $;
window.jQuery = $;

/**
 * Import DataTables (Bootstrap 5 + Responsive)
 * Version OFFICIELLE pour Webpack Encore (ES Modules)
 */
import DataTable from "datatables.net-bs5";
import DataTableResponsive from "datatables.net-responsive-bs5";

// Styles DataTables (optionnel, car déjà en CDN dans Twig)
import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";

/**
 * Initialisation DataTables
 * (avec ES Modules, DOIT être fait ainsi — pas avec $(...).DataTable)
 */
document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#patientsTable");

    if (table) {
        new DataTable(table, {
            responsive: true,
            pageLength: 10,
            order: [[0, "desc"]],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json",
            },
        });
    }
});

/**
 * Debug
 */
console.log("jQuery loaded:", typeof $);
console.log("DataTables loaded:", typeof DataTable);

/**
 * Import de tes scripts audio et animation
 */
import {
    buttonSoundError,
    buttonSoundSuccess,
    playSuccessSound,
    buttonSoundClick,
    sleep,
} from "./js/AudioPlayer.js";
// import {
//     getNewAdmissions,
//     validateAllAdmissions,
//     jsLog,
// } from "./js/getNewAdmissions.js";
import { moveElement, enableShakeLoop } from "./js/moveElement.js";

/**
 * Expose les fonctions globalement
 */
// window.jsLog = jsLog;
window.buttonSoundError = buttonSoundError;
window.buttonSoundSuccess = buttonSoundSuccess;
window.buttonSoundClick = buttonSoundClick;
window.sleep = sleep;
window.moveElement = moveElement;
window.enableShakeLoop = enableShakeLoop;
// window.getNewAdmissions = getNewAdmissions;
// window.validateAllAdmissions = validateAllAdmissions;
window.playSuccessSound = playSuccessSound;

/**
 * Activation automatique des sons sur tous les IDs cliquables
 */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[id]").forEach((el) => {
        buttonSoundClick(el.id);
    });
});


function loadNotifications() {
    fetch("/api/notifications/feeds", { cache: "no-store" })
        .then((res) => res.json())
        .then((data) => {
            // BADGES
            const desktopBadge = document.querySelector("#notifDesktop .badge");
            const mobileBadge = document.getElementById("notifMobileBadge");

            if (desktopBadge) desktopBadge.textContent = data.total ?? 0;
            if (mobileBadge) mobileBadge.textContent = data.total ?? 0;

            // ------------------------------
            // 📘 NOUVEAUX CLIENTS
            // ------------------------------
            const clientsList = document.getElementById("notifClientsList");
            clientsList.innerHTML = "";

            if (!data.newClients || data.newClients.length === 0) {
                clientsList.innerHTML = `
                    <div class="alert alert-secondary text-center py-2 mb-0">
                        Aucun nouveau client
                    </div>
                `;
            } else {
                data.newClients.forEach((c) => {
                    clientsList.innerHTML += `
                        <div class="card border">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <h6 class="notify-title">
                                            <i class="bi bi-person-circle me-1"></i>
                                            ${c.nom} — ${c.ville}
                                        </h6>
                                        <div class="text-muted small">
                                            📞 ${c.telephone ?? "—"}<br>
                                            🕒 ${c.createdAt}
                                        </div>
                                    </div>

                                    <a href="/fiche/client/${c.id}" 
                                        class="btn btn-outline-primary btn-sm">
                                        Voir
                                    </a>
                                </div>

                            </div>
                        </div>
                    `;
                });
            }

            // ------------------------------
            // 📗 RDV DU JOUR
            // ------------------------------
            const rdvList = document.getElementById("notifRdvList");
            rdvList.innerHTML = "";

            if (!data.rdvToday || data.rdvToday.length === 0) {
                rdvList.innerHTML = `
                    <div class="alert alert-secondary text-center py-2 mb-0">
                        Aucun RDV aujourd’hui
                    </div>
                `;
            } else {
                data.rdvToday.forEach((r) => {
                    rdvList.innerHTML += `
                        <div class="card border">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start">

                                    <div>
                                        <h6 class="fw-bold">
                                            <i class="bi bi-clock-fill me-1"></i>
                                            ${r.heure} — ${r.client}
                                        </h6>
                                        <div class="text-muted small">
                                            📝 ${r.motif ?? "—"}<br>
                                            <span class="badge bg-info text-dark mt-1">
                                                ${r.statut ?? "En attente"}
                                            </span>
                                        </div>
                                    </div>

                                    <a href="/rdv" 
                                        class="btn btn-outline-success btn-sm">
                                        Ouvrir
                                    </a>

                                </div>

                            </div>
                        </div>
                    `;
                });
            }
        })
        .catch((err) => console.error("Notif error:", err));
}

// 2️⃣ Clic = ouvre le modal (Bootstrap s'en occupe)
document
    .getElementById("notifDesktop")
    .addEventListener("click", loadNotifications);

// 3️⃣ Refresh auto toutes les 2 secondes
setInterval(loadNotifications, 15000);

loadNotifications();