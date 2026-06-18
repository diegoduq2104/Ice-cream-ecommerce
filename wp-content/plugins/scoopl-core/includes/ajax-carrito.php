<?php

/**
 * 1. RECIBIR LOS DATOS DEL JS (Actualizado para recibir el nombre)
 */
add_action('wp_ajax_agregar_helado_custom', 'procesar_helado_scoopl');
add_action('wp_ajax_nopriv_agregar_helado_custom', 'procesar_helado_scoopl');

function procesar_helado_scoopl() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $sabores    = isset($_POST['sabores_elegidos']) ? sanitize_text_field($_POST['sabores_elegidos']) : 'Sin sabores';
    $precio     = isset($_POST['precio_calculado']) ? floatval($_POST['precio_calculado']) : 0;
    $bolas      = isset($_POST['total_bolas']) ? intval($_POST['total_bolas']) : 0;
    // Capturamos el nombre que viene del JS
    $nombre_cat = isset($_POST['nombre_categoria']) ? sanitize_text_field($_POST['nombre_categoria']) : 'Arma tu Scoopl';

    $cart_item_data = array(
        'scoopl_detalle'   => $sabores,
        'scoopl_precio'    => $precio,
        'total_bolas'      => $bolas,
        'scoopl_nombre'    => $nombre_cat // Guardamos el nombre dinámico
    );

    WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);

    wp_send_json_success();
    wp_die();
}

/**
 * 2. CAMBIAR EL NOMBRE DEL PRODUCTO EN EL CARRITO
 */
add_filter('woocommerce_cart_item_name', 'personalizar_nombre_carrito_scoopl', 10, 3);
function personalizar_nombre_carrito_scoopl($name, $cart_item, $cart_item_key) {
    if (isset($cart_item['scoopl_nombre'])) {
        return $cart_item['scoopl_nombre'];
    }
    return $name;
}

/**
 * 3. FORZAR PRECIO Y MOSTRAR DETALLES (Sin cambios mayores)
 */
add_action('woocommerce_before_calculate_totals', 'aplicar_precio_scoopl', 99, 1);
function aplicar_precio_scoopl($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    foreach ($cart->get_cart() as $item) {
        if (isset($item['scoopl_precio'])) {
            $item['data']->set_price($item['scoopl_precio']);
        }
    }
}

add_filter('woocommerce_get_item_data', 'mostrar_detalle_scoopl_carrito', 10, 2);
function mostrar_detalle_scoopl_carrito($item_data, $cart_item) {
    if (isset($cart_item['scoopl_detalle'])) {
        $item_data[] = array('name' => 'Selección', 'value' => $cart_item['scoopl_detalle']);
    }
    return $item_data;
}

/**
 * 4. CAMBIAR IMAGEN SEGÚN LAS BOLAS
 * Asegúrate de que los IDs (705, 710, etc.) correspondan a las fotos 
 * de las categorías (Single, 2-Sabores, etc.) en tu biblioteca de medios.
 */
/**
 * 4. CAMBIAR IMAGEN SEGÚN LAS BOLAS (Versión Reforzada)
 */
/**
 * 4. CAMBIAR IMAGEN SEGÚN LAS BOLAS (VERSIÓN DEFINITIVA)
 */
add_filter('woocommerce_cart_item_thumbnail', 'cambiar_imagen_scoopl', 999, 3);
function cambiar_imagen_scoopl($thumbnail, $cart_item, $cart_item_key) {
    
    // 1. Verificamos si es nuestro producto de helados o si tiene los datos de bolas
    if (isset($cart_item['total_bolas']) || (isset($cart_item['product_id']) && $cart_item['product_id'] == 700)) {
        
        $bolas = isset($cart_item['total_bolas']) ? intval($cart_item['total_bolas']) : 0;
        $img_id = 0;

        // 2. Asignación estricta de IDs
        if ($bolas === 1) $img_id = 736; // Single
        elseif ($bolas === 2) $img_id = 158; // 2 Sabores
        elseif ($bolas === 3) $img_id = 135; // 3 Sabores
        elseif ($bolas >= 4) $img_id = 160;  // 4 o más

        // 3. Si encontramos un ID, forzamos la imagen
        if ($img_id > 0) {
            $image_array = wp_get_attachment_image_src($img_id, 'woocommerce_thumbnail');
            if ($image_array) {
                return '<img src="' . esc_url($image_array[0]) . '" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail scoopl-cart-img" alt="Helado Personalizado">';
            }
        }
    }
    
    return $thumbnail;
}