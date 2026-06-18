<?php
/**
 * Genera la URL dinámica para el Builder de Scoopl
 * Uso en Elementor: [url_builder_scoopl]
 */
add_shortcode('url_builder_scoopl', function() {
    $post_id = get_the_ID();
    
    // Obtenemos tu Custom Field manual
    $limite = get_post_meta($post_id, 'limite_de_sabores', true);
    
    // Obtenemos el nombre de la categoría (título del post)
    $nombre = get_the_title($post_id);
    
    // Si no hay límite, ponemos 1 por defecto
    if (!$limite) $limite = 1;

    // Construimos la URL limpia (Apple-style)
    $url_base = home_url('/index.php/builder/');
    $url_final = add_query_arg([
        'cantidad' => $limite,
        'nombre'   => urlencode($nombre)
    ], $url_base);

    return esc_url($url_final);
});