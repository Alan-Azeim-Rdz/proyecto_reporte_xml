document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', (e) => {
            const nombre = document.getElementById('nombre').value.trim();
            const edad = parseInt(document.getElementById('edad').value);
            const genero = document.getElementById('genero').value;
            const estudio = document.getElementById('nivel_estudio').value;
            const interes = document.getElementById('area_interes').value;

            let errors = [];

            if (nombre === "") {
                errors.push("El nombre completo es obligatorio.");
            }

            if (isNaN(edad) || edad < 1 || edad > 120) {
                errors.push("Por favor, ingresa una edad válida (1-120).");
            }

            if (genero === "") {
                errors.push("Debes seleccionar un género.");
            }

            if (estudio === "") {
                errors.push("Debes seleccionar un nivel educativo.");
            }

            if (interes === "") {
                errors.push("Debes seleccionar un área de interés.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert("Errores en el formulario:\n\n" + errors.join("\n"));
            }
        });
    }
});
