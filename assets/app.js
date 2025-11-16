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
import { moveElement } from "./js/moveElement.js";
import { sleep } from "./js/AudioPlayer.js";

// 👉 Pour AssetMapper : expose correctement la fonction dans globalThis
globalThis.buttonSoundError = buttonSoundError;
globalThis.buttonSoundSuccess = buttonSoundSuccess;
globalThis.buttonSoundClick = buttonSoundClick;
globalThis.moveElement = moveElement;

window.sleep = sleep;

console.log("JS chargé via AssetMapper 🎉");





console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
