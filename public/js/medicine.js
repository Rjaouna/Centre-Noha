document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("medicineForm");
    const alertBox = document.getElementById("alertBox");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            code: document.getElementById("code").value,
            name: document.getElementById("name").value,
            dci: document.getElementById("dci").value,
            dosage: document.getElementById("dosage").value,
            uniteDosage: document.getElementById("uniteDosage").value,
            forme: document.getElementById("forme").value,
            presentation: document.getElementById("presentation").value,
            ppv: document.getElementById("ppv").value,
            ph: document.getElementById("ph").value,
            isGeneric: document.getElementById("isGeneric").checked,
            tauxRembourssement:
                document.getElementById("tauxRembourssement").value,
        };

        fetch("/api/medicine", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
        })
            .then((res) => res.json())
            .then((response) => {
                if (response.success) {
                    alertBox.innerHTML = `
					<div class="alert alert-success">
						Médicament ajouté avec succès
					</div>
				`;
                    form.reset();
                } else {
                    alertBox.innerHTML = `
					<div class="alert alert-danger">
						${response.error}
					</div>
				`;
                }
            })
            .catch(() => {
                alertBox.innerHTML = `
				<div class="alert alert-danger">
					Erreur serveur
				</div>
			`;
            });
    });
});
