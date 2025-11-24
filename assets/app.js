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
 * Import de ton CSS principal
 */
import "./styles/app.css";

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
