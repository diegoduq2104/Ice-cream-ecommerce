<?php
/**
 * Plugin Name: Scoopl Core Functions
 * Description: Validación de checkout, límites de compra e inyección dinámica de sabores.
 * Version: 1.0.0
 * Author: Diego
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Definir una constante con la ruta del plugin por comodidad
define( 'SCOOPL_CORE_PATH', plugin_dir_path( __FILE__ ) );

// Cargamos de forma limpia cada funcionalidad modular
require_once SCOOPL_CORE_PATH . 'includes/carrito-logica.php';
require_once SCOOPL_CORE_PATH . 'includes/logica-de-carrito.php';
require_once SCOOPL_CORE_PATH . 'includes/limites-cantidades.php';
// Inyectar de forma segura el archivo JavaScript en el Frontend
add_action( 'wp_enqueue_scripts', function() {
    
    // Solo cargamos el script si estamos en la página de Checkout de WooCommerce
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        
        wp_enqueue_script(
            'scoopl-sabores-js', // Nombre único identificador del script
            plugins_url( 'assets/js/sabores.js', __FILE__ ), // URL física del archivo JS
            array( 'jquery' ), // Dependencias (si tu JS usa jQuery, WordPress lo carga antes)
            '1.0.0', // Versión del archivo (ayuda a limpiar la caché del navegador)
            true // true = Cargar el script en el footer (abajo del todo) para mejor velocidad
        );
        
    }
});