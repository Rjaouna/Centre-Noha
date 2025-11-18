/**
 * Import jQuery
 */
import $ from "jquery";
window.$ = $;
window.jQuery = $;

/**
 * Import DataTables (compatible BS5 + Responsive)
 * Version validée pour Webpack Encore / AssetMapper
 */
import DataTable from "datatables.net-bs5";
import "datatables.net-responsive-bs5";

// Initialise DataTables pour jQuery
DataTable($);

/**
 * Debug pour vérifier que tout est OK
 */
console.log("jQuery loaded:", typeof $);
console.log("DataTables loaded:", typeof $().DataTable);

/**
 * Import de ton CSS principal
 */
import "./styles/app.css";

/**
 * Import de tes scripts audio
 */
import {
    buttonSoundError,
    buttonSoundSuccess,
    buttonSoundClick,
    sleep,
} from "./js/AudioPlayer.js";

/**
 * Import de tes scripts animations
 */
import { moveElement, enableShakeLoop } from "./js/MoveElement.js";

/**
 * Expose les fonctions globales (nécessaire pour utilisation dans Twig)
 */
window.buttonSoundError = buttonSoundError;
window.buttonSoundSuccess = buttonSoundSuccess;
window.buttonSoundClick = buttonSoundClick;
window.enableShakeLoop = enableShakeLoop;
window.moveElement = moveElement;
window.sleep = sleep;

/**
 * Activation auto des sons sur les éléments cliquables
 */
document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll(
            "button[id], a[id], [role='button'][id], .clickable-alert[id]"
        )
        .forEach((el) => {
            buttonSoundClick(el.id);
        });
});
