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


// 1️⃣ Fonction qui récupère les notifications
function loadNotifications() {
    fetch("/api/notifications/feeds")
        .then((res) => res.json())
        .then((data) => {

            // --- Badge dynamique ---
            const badge = document.querySelector("#notifDesktop .badge");
            badge.textContent = data.total;

            // --- Clients non consultés ---
            let clientsHtml = "";
            data.newClients.forEach((c) => {
                clientsHtml += `
                    <li class="list-group-item d-flex justify-content-between">
                        <span>${c.nom} (${c.ville})</span>
                        <small class="text-muted">${c.createdAt}</small>
                    </li>`;
            });

            if (clientsHtml === "") {
                clientsHtml = "<li class='list-group-item text-muted'>Aucun nouveau client</li>";
            }

            document.getElementById("notifClientsList").innerHTML = clientsHtml;

            // --- RDV du jour ---
            let rdvHtml = "";
            data.rdvToday.forEach((r) => {
                rdvHtml += `
                    <li class="list-group-item d-flex justify-content-between">
                        <span>${r.heure} - ${r.client}</span>
                        <small class="text-muted">${r.motif ?? ""}</small>
                    </li>`;
            });

            if (rdvHtml === "") {
                rdvHtml = "<li class='list-group-item text-muted'>Aucun RDV aujourd’hui</li>";
            }

            document.getElementById("notifRdvList").innerHTML = rdvHtml;
        })
        .catch((err) => console.error(err));
}

// 2️⃣ Clic = ouvre le modal (Bootstrap s'en occupe)
document.getElementById("notifDesktop").addEventListener("click", loadNotifications);

// 3️⃣ Refresh auto toutes les 2 secondes
setInterval(loadNotifications, 2000);

// 4️⃣ Charger au démarrage
loadNotifications();
