export function moveElement(tag) {
    const element = document.getElementById(tag);
    element.classList.add("shake");
    setInterval(() => {
        element.classList.remove("shake");
    }, 2000);
}
