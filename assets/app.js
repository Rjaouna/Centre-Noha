/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";
import { buttonSoundError } from "./js/AudioPlayer.js";
import { buttonSoundSuccess } from "./js/AudioPlayer.js";
import { buttonSoundClick } from "./js/AudioPlayer.js";
import { moveElement, enableShakeLoop } from "./js/MoveElement.js";
import { sleep } from "./js/AudioPlayer.js";

// 👉 Pour AssetMapper : expose correctement la fonction dans globalThis
globalThis.enableShakeLoop = enableShakeLoop;
globalThis.buttonSoundError = buttonSoundError;
globalThis.buttonSoundSuccess = buttonSoundSuccess;
globalThis.buttonSoundClick = buttonSoundClick;
globalThis.moveElement = moveElement;

window.sleep = sleep;

document.addEventListener("DOMContentLoaded", () => {
    document
        .querySelectorAll(
            "button[id], a[id], [role='button'][id], .clickable-alert[id]"
        )
        .forEach((el) => {
            buttonSoundClick(el.id);
        });
});


