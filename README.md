# 🍦 Scoopl - Ice Cream eCommerce
¡Bienvenido a Scoopl! El portal definitivo para personalizar tus helados favoritos. Esto no es solo otro sitio de comercio electrónico; es una muestra de código modular, lógica de carrito personalizada y una interfaz dinámica construida sobre WordPress.

✨ [**¡Preciosa AQUI para verlo en directo!**](https://www.semana.tumarcaagencia.com/) ✨

https://github.com/user-attachments/assets/dfb037f7-bb93-422d-b903-0b3fe853d40a

---
Un moderno comercio electrónico de venta de helados diseñado para ofrecer una experiencia de usuario fluida y altamente personalizable. 

Este repositorio contiene la estructura completa del sitio en WordPress, destacando un desarrollo a medida (plugin propio) para gestionar la lógica compleja de selección de sabores y límites de compra en el carrito.

## 🚀 Características Principales

*   **"Arma tu Scoopl":** Interfaz dinámica desarrollada con JavaScript para que el usuario seleccione sus sabores y cantidades exactas.
*   **Gestión Avanzada del Carrito:** Lógica en PHP interceptando los procesos de WooCommerce para validar límites de compra y combinaciones de productos.
*   **Diseño Visual:** Interfaz maquetada e integrada mediante Elementor.

## 📁 Arquitectura del Proyecto (Para Desarrolladores)

Al ser un proyecto basado en WordPress, gran parte del núcleo son archivos estándar. **El código a medida (Core Logic) se encuentra exclusivamente en el siguiente directorio:**

`👉 /wp-content/plugins/scoopl-core/`

Dentro de este plugin personalizado encontrarás:
*   `/assets/js/sabores.js`: Lógica del lado del cliente (Frontend) para la vista interactiva.
*   `/includes/carrito-logica.php`: Funciones backend para la manipulación de datos en WooCommerce.
*   `/includes/limites-cantidades.php`: Reglas de validación y seguridad del lado del servidor.
*   `scoopl-core.php`: Archivo principal que inicializa e inyecta los módulos del plugin.

## 🛠️ Tecnologías y Herramientas

*   **Core:** WordPress / WooCommerce
*   **Frontend:** HTML5, CSS3, JavaScript (Vanilla), Elementor
*   **Backend:** PHP
*   **Control de Versiones:** Git / GitHub

## 💻 Instalación Local

Si deseas clonar y probar este proyecto en tu entorno local:

1. Clona este repositorio en la carpeta `public` de tu entorno servidor (LocalWP, XAMPP, etc.).
2. Importa la base de datos (Nota: Asegúrate de solicitar el archivo `.sql` con los datos de prueba).
3. Asegúrate de tener activado el plugin `scoopl-core` en el panel de administración para que la lógica de compra funcione correctamente.
