document.addEventListener('DOMContentLoaded', () => {
    // Cierra automáticamente las alertas después de unos segundos
    document.querySelectorAll('.alert').forEach((alerta) => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
            bsAlert.close();
        }, 4000);
    });

    // Validación simple en formularios de libro (título, autor, isbn requeridos)
    const form = document.querySelector('form[action*="libro-guardar"], form[action*="libro-actualizar"]');
    if (form) {
        form.addEventListener('submit', (e) => {
            const requeridos = form.querySelectorAll('[name="titulo"], [name="autor"], [name="isbn"]');
            let valido = true;

            requeridos.forEach((input) => {
                if (input.value.trim() === '') {
                    input.classList.add('is-invalid');
                    valido = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valido) {
                e.preventDefault();
            }
        });
    }
});
