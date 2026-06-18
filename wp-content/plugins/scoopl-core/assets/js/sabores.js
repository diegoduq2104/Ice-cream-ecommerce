
document.addEventListener('DOMContentLoaded', function() {
    const PRODUCT_ID = 700;

    // 1. Detectamos los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const cantidadRequerida = parseInt(urlParams.get('cantidad')) || 1;
    // NUEVA LÍNEA: Capturamos el nombre desde la URL
    const nombreDesdeUrl = urlParams.get('nombre') || "Arma tu Scoopl"; 

    function obtenerDatosDesdeVisual() {
        let selecciones = [];
        let totalBolas = 0;
        const tarjetas = document.querySelectorAll('.tarjeta-sabor');

        tarjetas.forEach(tarjeta => {
            const nombreElemento = tarjeta.querySelector('.scoopl-nombre-item') || tarjeta.querySelector('h1, h2, h3, h4, h5, h6');
            const contadorElemento = tarjeta.querySelector('.txt-contador');
            
            const nombre = nombreElemento ? nombreElemento.innerText.trim() : "Sabor";
            const cant = contadorElemento ? parseInt(contadorElemento.innerText) : 0;

            if (cant > 0) {
                selecciones.push(`${cant}x ${nombre}`);
                totalBolas += cant;
            }
        });

        let precioFinal = 0;
        if (totalBolas === 1) precioFinal = 3;
        else if (totalBolas === 2) precioFinal = 7;
        else if (totalBolas === 3) precioFinal = 12;
        else if (totalBolas === 4) precioFinal = 20;
        else if (totalBolas > 4) precioFinal = 20 + ((totalBolas - 4) * 5);

        // Ya no buscamos en el HTML, usamos lo que vino de la URL
        const nombreCategoria = nombreDesdeUrl;

        return { selecciones, precioFinal, totalBolas, nombreCategoria };
    }
    
    // ... resto del código (btnComprar, fetch, etc.) se queda igual

    const btnComprar = document.getElementById('btn-comprar-scoopl');
    if (btnComprar) {
        btnComprar.addEventListener('click', function(e) {
            e.preventDefault();
            const { selecciones, precioFinal, totalBolas, nombreCategoria } = obtenerDatosDesdeVisual();

            // VALIDACIÓN CON ALERTA (Para que no se mueva nada del diseño)
            if (totalBolas < cantidadRequerida) {
                alert(`Para esta selección necesitas elegir ${cantidadRequerida} sabor(es). ¡Te falta(n) ${cantidadRequerida - totalBolas}!`);
                return;
            }

            // Cambiamos texto sin destruir la estructura interna
            const originalText = btnComprar.innerHTML;
            const spanTexto = btnComprar.querySelector('.elementor-button-text');
            if(spanTexto) spanTexto.innerText = "Preparando...";

            const data = new FormData();
            data.append('action', 'agregar_helado_custom');
            data.append('product_id', PRODUCT_ID);
            data.append('sabores_elegidos', selecciones.join(' | '));
            data.append('precio_calculado', precioFinal);
            data.append('total_bolas', totalBolas);
            // ENVIAMOS EL NOMBRE AL PHP
            data.append('nombre_categoria', nombreCategoria);

            fetch('/wp-admin/admin-ajax.php', { method: 'POST', body: data })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = 'http://scoopl.local/index.php/carrito/'; 
                    } else {
                        alert("Hubo un error al procesar.");
                        btnComprar.innerHTML = originalText;
                    }
                })
                .catch(err => {
                    console.error(err);
                    btnComprar.innerHTML = originalText;
                });
        });
    }
});