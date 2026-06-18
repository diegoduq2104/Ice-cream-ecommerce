<?php
/* ---------------------------------------------------------------- SESIÓN 1: DATOS PERSONALES */
add_shortcode('campos_pago_scoopl', 'scoopl_campos_cliente_shortcode');
function scoopl_campos_cliente_shortcode() {
    ob_start(); ?>
    <div class="scoopl-form-container" style="font-family:'Montserrat', sans-serif;">
        <input type="hidden" name="scoopl_metodo_entrega" id="scoopl_metodo_entrega" value="tienda">

        <div style="margin-bottom:15px;">
            <label style="display:block; font-weight:700; margin-bottom:8px;">¿Quién recogerá el pedido?</label>
            <input type="text" name="scoopl_full_name" placeholder="Nombre y apellido completo" 
                   style="width:100%; padding:15px; border:1px solid #e0e0e0; border-radius:10px;">
        </div>

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1; margin-bottom:15px;">
                <label style="display:block; font-weight:700; margin-bottom:8px;">Cédula</label>
                <input type="tel" name="scoopl_cedula" placeholder="Ej: 28123456" 
                       inputmode="numeric" pattern="[0-9]*"
                       style="width:100%; padding:15px; border:1px solid #e0e0e0; border-radius:10px;">
            </div>

            <div style="flex: 1; margin-bottom:15px;">
                <label style="display:block; font-weight:700; margin-bottom:8px;">Teléfono</label>
                <input type="tel" name="scoopl_phone" placeholder="Ej: 04140000" 
                       inputmode="numeric" pattern="[0-9]*"
                       style="width:100%; padding:15px; border:1px solid #e0e0e0; border-radius:10px;">
            </div>
        </div>
        <p style="font-size:12px; color:#666;">* Estos campos son obligatorios para la entrega.</p>
    </div>

    <script>
    jQuery(document).ready(function($){
        // BLOQUEO DE LETRAS Y CARACTERES: Solo permite números (0-9)
        $('input[name="scoopl_cedula"], input[name="scoopl_phone"]').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('.elementor-tab-title').on('click', function(){
            var tabText = $(this).text().toLowerCase();
            $('#scoopl_metodo_entrega').val(tabText.includes('delivery') ? 'delivery' : 'tienda');
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
/* ---------------------------------------------------------------- SESIÓN 2: NOTA DEL BOLSO */
add_shortcode('nota_bolso_scoopl', 'scoopl_nota_bolso_shortcode');
function scoopl_nota_bolso_shortcode() {
    ob_start(); ?>
    <div class="scoopl-nota-container" style="margin-top:20px; font-family:'Montserrat', sans-serif;">
        <label style="display:block; font-size:14px; color:#000000; margin-bottom:10px;">
            ¿Pides para alguien especial? ¡Añade una nota personal para poner en la caja!
        </label>
        <textarea name="scoopl_order_note" placeholder="Nota (opcional)" rows="2" maxlength="150"
                  style="width: 100%; box-sizing: border-box; display: block; padding: 10px; border: 1px solid #e0e0e0; border-radius: 10px; resize: none; font-family: 'Montserrat', sans-serif;"></textarea>
    </div>
    <?php
    return ob_get_clean();
}

/* ---------------------------------------------------------------- SESIÓN 3: GUARDADO MAESTRO */
add_action('woocommerce_checkout_update_order_meta', 'scoopl_guardar_datos_checkout_maestro');
function scoopl_guardar_datos_checkout_maestro($order_id) {
    $mapeo = [
        'scoopl_full_name'      => '_scoopl_full_name',
        'scoopl_cedula'         => '_scoopl_cedula',
        'scoopl_phone'          => '_scoopl_phone',
        'scoopl_order_note'     => '_scoopl_customer_note',
        'scoopl_payment_ref'    => '_scoopl_payment_ref',
        'scoopl_metodo_entrega' => '_scoopl_metodo_entrega'
    ];

    foreach ($mapeo as $post_key => $meta_key) {
        if (!empty($_POST[$post_key])) {
            update_post_meta($order_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }
}

/* ---------------------------------------------------------------- SESIÓN 4: ADMIN DISPLAY */
add_action('woocommerce_admin_order_data_after_billing_address', 'scoopl_mostrar_datos_admin', 10, 1);
function scoopl_mostrar_datos_admin($order){
    $id = $order->get_id();
    $datos = [
        'Persona que recibe' => get_post_meta($id, '_scoopl_full_name', true),
        'Cédula'             => get_post_meta($id, '_scoopl_cedula', true),
        'Teléfono'           => get_post_meta($id, '_scoopl_phone', true),
        'Método'             => get_post_meta($id, '_scoopl_metodo_entrega', true),
        'Referencia Pago'    => get_post_meta($id, '_scoopl_payment_ref', true),
        'Nota Especial'      => get_post_meta($id, '_scoopl_customer_note', true),
    ];

    echo '<div style="clear:both; margin-top:20px; padding-top:10px; border-top:1px solid #eee;">';
    echo '<h4 style="color:#d06d8a;">Detalles Scoopl</h4>';
    foreach($datos as $label => $valor) {
        if($valor) echo '<p><strong>'.$label.':</strong> '.esc_html($valor).'</p>';
    }
    echo '</div>';
}

/* ---------------------------------------------------------------- SESIÓN 5: LISTA DE PRODUCTOS */
add_shortcode('lista_bolso_scoopl', 'scoopl_render_lista_bolso');
function scoopl_render_lista_bolso() {
    if ( WC()->cart->is_empty() ) {
        return '<p style="text-align:center; color:#999; padding:20px;">Tu bolso está vacío.</p>';
    }
    ob_start();
    echo '<div class="scoopl-lista-productos" style="font-family:\'Montserrat\', sans-serif;">';
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $item_price = apply_filters( 'woocommerce_cart_item_subtotal', wc_price( $cart_item['line_subtotal'] ), $cart_item, $cart_item_key );
        
        $nombre_mostrar = !empty($cart_item['scoopl_nombre']) ? $cart_item['scoopl_nombre'] : $_product->get_name();
        $sabores = isset($cart_item['scoopl_detalle']) ? $cart_item['scoopl_detalle'] : '';
        $bolas = isset($cart_item['total_bolas']) ? intval($cart_item['total_bolas']) : 0;
        
        $img_id = 0;
        if ($bolas === 1) $img_id = 736;
        elseif ($bolas === 2) $img_id = 158;
        elseif ($bolas === 3) $img_id = 1187;
        elseif ($bolas >= 4) $img_id = 160;
        
        $url_imagen = ($img_id > 0) ? wp_get_attachment_image_src($img_id, 'woocommerce_thumbnail')[0] : "";
        if (empty($url_imagen)) $url_imagen = wp_get_attachment_url($_product->get_image_id());
        if (empty($url_imagen)) $url_imagen = wc_placeholder_img_src();
        ?>
        <div class="scoopl-item" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f0;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="<?php echo esc_url($url_imagen); ?>" style="width: 75px; height: 75px; border-radius: 12px; object-fit: contain; background: #FFC2D1;">
                <div>
                    <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #000;"><?php echo esc_html($nombre_mostrar); ?></h5>
                    <p style="margin: 4px 0 0; font-size: 13px; color: #d06d8a; font-weight: 500;"><?php echo esc_html($sabores); ?></p>
                    <span style="display: block; margin-top: 6px; font-size: 12px; color: #999;">Cantidad: <?php echo $cart_item['quantity']; ?></span>
                </div>
            </div>
            <div style="text-align: right;">
                <span style="font-weight: 600; font-size: 16px; color: #1a1a1a;"><?php echo $item_price; ?></span>
            </div>
        </div>
        <?php
    }
    echo '</div>';
    return ob_get_clean();
}

/* ---------------------------------------------------------------- SESIÓN 6: VACIAR BOLSO */
add_action('init', 'scoopl_vaciar_carrito_custom');
function scoopl_vaciar_carrito_custom() {
    if (isset($_GET['vaciar_bolso']) && $_GET['vaciar_bolso'] == 'si') {
        if ( WC()->cart ) { WC()->cart->empty_cart(); }
        wp_redirect(remove_query_arg('vaciar_bolso'));
        exit;
    }
}
add_shortcode('boton_vaciar_scoopl', function() {
    if ( WC()->cart->is_empty() ) return '';
    $url = add_query_arg('vaciar_bolso', 'si');
    return '<div style="text-align: right; margin-bottom: 15px;"><a href="'.esc_url($url).'" style="display: inline-block; padding: 8px 16px; background: #FFF0F3; color: #d06d8a; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; border: 1px solid #FFC2D1;">🗑️ Vaciar bolsa</a></div>';
});

/* ---------------------------------------------------------------- SESIÓN 7-8: PROPINAS */
add_action( 'woocommerce_cart_calculate_fees', function() {
    $propina = WC()->session->get( 'scoopl_tip_amount' );
    if ( $propina > 0 ) WC()->cart->add_fee( 'Propina', $propina );
});

add_action( 'wp_ajax_set_scoopl_tip', 'scoopl_set_tip_ajax' );
add_action( 'wp_ajax_nopriv_set_scoopl_tip', 'scoopl_set_tip_ajax' );
function scoopl_set_tip_ajax() {
    WC()->session->set( 'scoopl_tip_amount', floatval($_POST['amount']) );
    WC()->cart->calculate_totals();
    wp_send_json_success();
    die();
}

add_shortcode('resumen_pago_scoopl', function() {
    if ( !WC()->cart ) return;
    $propina_actual = WC()->session->get( 'scoopl_tip_amount' ) ?: 0;
    ob_start(); ?>
    <div class="scoopl-resumen-sidebar" style="font-family:'Montserrat', sans-serif;">
        <h3 style="font-size: 25px; font-weight: 700;">Detalles del pedido</h3>
        <div style="display:flex; justify-content:space-between; margin: 15px 0; font-size: 16px;">
            <span style="color: #666;">Subtotal</span>
            <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
        </div>
        <div style="margin: 25px 0;">
            <div style="display:flex; justify-content:space-between; margin-bottom: 12px; font-size: 14px; color: #666;">
                <span>Propina</span>
                <strong><?php echo ($propina_actual > 0) ? '$' . number_format($propina_actual, 2) : '$0,00'; ?></strong>
            </div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                <?php foreach([2, 3, 5] as $m): ?>
                    <button type="button" class="tip-btn <?php echo ($propina_actual == $m) ? 'active' : ''; ?>" onclick="setTip(<?php echo $m; ?>)">$<?php echo $m; ?></button>
                <?php endforeach; ?>
                <button type="button" class="tip-btn" onclick="setTip(0)">Otros</button>
            </div>
        </div>
        <div style="display:flex; justify-content:space-between; font-size: 20px; font-weight: 700; border-top: 1px solid #eee; padding-top: 20px;">
            <span>Total</span>
            <span><?php echo WC()->cart->get_total(); ?></span>
        </div>
    </div>
    <script>
    function setTip(amt) {
        jQuery.post('/wp-admin/admin-ajax.php', {action: 'set_scoopl_tip', amount: amt}, function() { location.reload(); });
    }
    </script>
    <style>.tip-btn{padding:12px 2px; border-radius:10px; border:1px solid #f0f0f0; background:#fff; cursor:pointer; font-weight:500; font-size:12px; color:#000;}.tip-btn.active{background:#FFC2D1; border-color:#FFC2D1;}</style>
    <?php return ob_get_clean();
});

/* ---------------------------------------------------------------- SESIÓN 9-11: PASARELA MANUAL */
add_filter( 'woocommerce_payment_gateways', function($g){ $g[] = 'WC_Gateway_Scoopl_Manual'; return $g; });
add_action( 'plugins_loaded', function(){
    class WC_Gateway_Scoopl_Manual extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'scoopl_manual';
            $this->method_title = 'Pagos Scoopl';
            $this->has_fields = true;
            $this->init_form_fields();
            $this->init_settings();
            $this->title = 'Pagos Scoopl';
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        }
        public function init_form_fields() { $this->form_fields = array('enabled' => array('title' => 'Habilitar', 'type' => 'checkbox', 'default' => 'yes')); }
        public function payment_fields() { scoopl_render_metodos_pago_html(); }
        public function process_payment( $order_id ) {
            $order = wc_get_order( $order_id );
            $order->update_status( 'on-hold', 'Esperando verificación.' );
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url( $order ));
        }
    }
});

function scoopl_render_metodos_pago_html() { ?>
    <div id="scoopl-custom-checkout" style="font-family:'Montserrat',sans-serif; background: #fff; padding: 20px; border-radius: 15px;">
        <h3 style="font-size: 20px; font-weight: 700;">Método de pago 💳</h3>
        <div style="display: flex; gap: 10px; margin: 15px 0;">
            <div onclick="selPay('pm')" class="pay-tab active" id="t-pm">Pago Móvil</div>
            <div onclick="selPay('bc')" class="pay-tab" id="t-bc">Bancolombia</div>
            <div onclick="selPay('bn')" class="pay-tab" id="t-bn">Binance</div>
        </div>
        <div id="d-pm" class="pay-details" style="display:block;"><strong>Banco:</strong> Venezuela (0102)<br><strong>Tel:</strong> 0414-7000000<br><strong>CI:</strong> V-12.345.678</div>
        <div id="d-bc" class="pay-details" style="display:none;"><strong>Ahorros:</strong> 123-456789-00<br><strong>Titular:</strong> Scoopl</div>
        <div id="d-bn" class="pay-details" style="display:none;"><strong>ID:</strong> 556677889<br><strong>Email:</strong> pagos@scoopl.com</div>
        <div style="margin-top:20px;">
            <label style="display:block; font-weight:700; font-size:12px; margin-bottom:5px;">REFERENCIA DE PAGO</label>
            <input type="text" name="scoopl_payment_ref" placeholder="Ej: 123456" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;">
        </div>
    </div>
    <script>
    function selPay(m){ jQuery('.pay-details').hide(); jQuery('#d-'+m).show(); jQuery('.pay-tab').removeClass('active'); jQuery('#t-'+m).addClass('active'); }
    </script>
    <style>
        .pay-tab { padding: 10px 11px; border: 1px solid #000; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; background: transparent; color: #000; transition: all 0.3s ease; text-align: center; flex: 1; }
        #t-pm.active { background: #000080 !important; color: #fff !important; border-color: #000080 !important; }
        #t-bc.active { background: #F4CF63 !important; color: #000 !important; border-color: #F4CF63 !important; }
        #t-bn.active { background: #000 !important; color: #F4CF63 !important; border-color: #000 !important; }
        .pay-details { background: #f9f9f9; padding: 15px; border-radius: 12px; font-size: 13px; }
    </style>
<?php }

add_shortcode('metodos_pago_scoopl', function() { ob_start(); scoopl_render_metodos_pago_html(); return ob_get_clean(); });

/* ---------------------------------------------------------------- SESIÓN 12-15: REDIRECCIONES */
add_action( 'template_redirect', function() { if ( is_cart() && !is_admin() ) { wp_redirect( wc_get_checkout_url() ); exit; } });
add_filter( 'woocommerce_return_to_shop_redirect', function() { return home_url(); });
add_filter( 'woocommerce_checkout_fields' , function($f) {
    $campos = ['billing_first_name', 'billing_last_name', 'billing_address_1', 'billing_city', 'billing_postcode', 'billing_country', 'billing_state', 'billing_phone', 'billing_email'];
    foreach($campos as $c) { if(isset($f['billing'][$c])) $f['billing'][$c]['required'] = false; }
    return $f;
});
add_filter( 'woocommerce_after_checkout_validation', function($data, $errors){
    $basicos = ['billing_first_name', 'billing_last_name', 'billing_address_1', 'billing_city', 'billing_postcode'];
    foreach($basicos as $b) { $errors->remove($b.'_required'); }
}, 10, 2);

/* ---------------------------------------------------------------- SESIÓN 16: VALIDACIÓN */
add_action('woocommerce_checkout_process', 'scoopl_validar_campos_final');
function scoopl_validar_campos_final() {
    if ( empty($_POST['scoopl_full_name']) ) wc_add_notice( 'Indica <strong>quién recogerá</strong> el pedido.', 'error' );
    if ( empty($_POST['scoopl_cedula']) ) wc_add_notice( 'La <strong>Cédula</strong> es obligatoria.', 'error' );
    if ( empty($_POST['scoopl_phone']) ) wc_add_notice( 'El <strong>Teléfono</strong> es obligatorio.', 'error' );
    if ( empty($_POST['scoopl_payment_ref']) ) wc_add_notice( 'Ingresa la <strong>Referencia</strong> de pago.', 'error' );
}

/* ---------------------------------------------------------------- SESIÓN 17-18: ADMIN COLUMNS */
add_filter( 'manage_edit-shop_order_columns', function($columns) {
    $new = [];
    foreach($columns as $key => $val) {
        $new[$key] = $val;
        if($key === 'order_number') {
            $new['scoopl_info'] = 'Cliente (Scoopl)';
            $new['scoopl_ref'] = 'Ref. Pago';
        }
    }
    return $new;
});

add_action( 'manage_shop_order_posts_custom_column', function($col, $post_id) {
    if ( $col === 'scoopl_info' ) {
        echo '<div style="font-size:11px;"><strong>CI:</strong> '.get_post_meta($post_id, '_scoopl_cedula', true).'<br><strong>Tel:</strong> '.get_post_meta($post_id, '_scoopl_phone', true).'</div>';
    }
    if ( $col === 'scoopl_ref' ) {
        echo '<mark style="background:#e5e5e5; padding:5px; border-radius:5px; font-weight:bold;">'.get_post_meta($post_id, '_scoopl_payment_ref', true).'</mark>';
    }
}, 10, 2 );

add_filter( 'woocommerce_shop_order_search_fields', function($sf) {
    return array_merge($sf, ['_scoopl_cedula', '_scoopl_payment_ref', '_scoopl_phone']);
});

/* ---------------------------------------------------------------- SESIÓN 19: JS BRIDGE */
add_action('wp_footer', 'scoopl_js_bridge_checkout');
function scoopl_js_bridge_checkout() {
    if ( ! is_checkout() ) return;
    ?>
    <script>
    jQuery(document).ready(function($){
        $('form.checkout').on('checkout_place_order', function() {
            var form = $(this);
            var campos = ['scoopl_full_name', 'scoopl_cedula', 'scoopl_phone', 'scoopl_payment_ref', 'scoopl_order_note', 'scoopl_metodo_entrega'];
            campos.forEach(function(nombre) {
                var valor = $('[name="' + nombre + '"]').val();
                if (valor !== undefined) {
                    form.find('input[name="' + nombre + '"]').remove();
                    $('<input>').attr({ type: 'hidden', name: nombre, value: valor }).appendTo(form);
                }
            });
            return true;
        });
    });
    </script>
    <?php
}
/* ---------------------------------------------------------------- SESIÓN 20: NOMBRE DINÁMICO EN EL PEDIDO */
// 1. Pasa el nombre personalizado del carrito a los datos del elemento del pedido
add_action( 'woocommerce_checkout_create_order_line_item', 'cantidad', 10, 4 );
function cantidad( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['scoopl_nombre'] ) ) {
        // Guardamos el nombre personalizado como un meta oculto del item para usarlo después
        $item->add_meta_data( 'Cantidad', $values['scoopl_nombre'], true );
    }
    if ( ! empty( $values['scoopl_detalle'] ) ) {
        // Añadimos los sabores elegidos como un detalle visible debajo del producto
        $item->add_meta_data( 'Sabores', $values['scoopl_detalle'], true );
    }
}

// 2. Reemplaza el nombre del producto en la vista del Admin y correos usando el meta guardado
add_filter( 'woocommerce_order_item_name', 'Sabor_elegido', 10, 2 );
function Sabor_elegido( $item_name, $item ) {
    // Si el item tiene nuestro meta guardado, cambiamos el nombre visible
    $nombre_personalizado = $item->get_meta( 'Sabor elegido' );
    if ( ! empty( $nombre_personalizado ) ) {
        return esc_html( $nombre_personalizado );
    }
    return $item_name;
}
/* ---------------------------------------------------------------- SESIÓN 21: MÉTODO DE PAGO DINÁMICO */
// Reemplaza el nombre genérico de la pasarela por la opción específica elegida por el cliente
add_filter( 'woocommerce_order_get_payment_method_title', 'scoopl_personalizar_titulo_pago_admin', 10, 2 );

function scoopl_personalizar_titulo_pago_admin( $title, $order ) {
    // Evitamos errores si se ejecuta fuera del panel de administración o flujos de orden
    if ( ! $order ) {
        return $title;
    }

    // Verificamos si es nuestra pasarela manual de Scoopl
    if ( $order->get_payment_method() === 'scoopl_manual' || $order->get_payment_method() === 'pasarela_scoopl' ) {
        
        // Obtenemos el metadato del método específico que guardamos en la base de datos (JS Bridge)
        // Nota: Asegúrate de usar la misma clave 'metodo_pago' o '_scoopl_metodo' que definiste al guardar el meta
        $metodo_elegido = $order->get_meta( 'scoopl_metodo_pago' ); // Cambia por tu meta key exacta si es diferente

        if ( ! empty( $metodo_elegido ) ) {
            // Capitalizamos o mapeamos estéticamente el nombre para que se vea impecable
            switch ( strtolower( trim( $metodo_elegido ) ) ) {
                case 'pago_movil':
                case 'pagomovil':
                    return 'Pago Móvil';
                case 'bancolombia':
                    return 'Bancolombia';
                case 'binance':
                    return 'Binance';
                default:
                    return esc_html( ucfirst( $metodo_elegido ) );
            }
        }
    }

    return $title;
}
/* ---------------------------------------------------------------- SESIÓN 22: RANGO DE FECHA SEMANAL AUTOMÁTICO */
add_shortcode( 'fecha_sabores_semanales', 'scoopl_obtener_rango_semana_actual' );

function scoopl_obtener_rango_semana_actual() {
    // Definimos los nombres de los meses en español
    $meses = array(
        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
    );

    // Buscamos el lunes de la semana actual (si hoy es lunes, mantiene hoy)
    $lunes = strtotime( 'monday this week' );
    // Buscamos el domingo de esta misma semana
    $domingo = strtotime( 'sunday this week' );

    // Extraemos los días en formato numérico (ej: "04")
    $dia_inicio = date( 'd', $lunes );
    $dia_fin    = date( 'd', $domingo );

    // Extraemos el mes numérico y el año de finalización
    $mes_fin_num = date( 'm', $domingo );
    $nombre_mes  = isset( $meses[$mes_fin_num] ) ? $meses[$mes_fin_num] : '';

    // Retornamos la cadena formateada estéticamente: "04 - 11 de Mayo"
    return esc_html( "$dia_inicio - $dia_fin de $nombre_mes" );
}
