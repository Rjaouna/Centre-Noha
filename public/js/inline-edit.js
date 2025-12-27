// assets/js/inline-edit.js
document.addEventListener("click", (e) => {
    const el = e.target.closest(".inline-edit");
    if (!el || el.classList.contains("editing")) return;

    el.classList.add("editing");

    const valueEl = el.querySelector(".value");
    const oldValue = valueEl.textContent.trim();

    const input = document.createElement(
        el.dataset.field === "observation" || el.dataset.field === "traitement"
            ? "textarea"
            : "input"
    );

    input.className = "form-control form-control-sm";
    input.value = oldValue === "—" ? "" : oldValue;

    valueEl.replaceWith(input);
    input.focus();

    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && input.tagName !== "TEXTAREA")
            save(el, input, oldValue);
        if (e.key === "Escape") cancel(el, oldValue);
    });

    input.addEventListener("blur", () => save(el, input, oldValue));
});

function save(el, input, oldValue) {
    fetch(`/fiche-client/${el.dataset.id}/inline-update`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            field: el.dataset.field,
            value: input.value,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (!data.success) throw data;
            replace(el, data.value);
        })
        .catch((err) => {
            alert(err.message || "Erreur");
            replace(el, oldValue);
        });
}

function cancel(el, value) {
    replace(el, value);
}

function replace(el, value) {
    el.classList.remove("editing");
    el.innerHTML = `
        <span class="value">${value || "—"}</span>
        <i class="bi bi-pencil text-muted ms-1"></i>
    `;
}
