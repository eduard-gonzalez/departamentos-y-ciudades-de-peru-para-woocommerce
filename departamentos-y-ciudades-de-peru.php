<?php
/**
 * @link              https://eduardogonzalez.me/
 * @since             1.0.2
 * @package           Departamentos y Ciudades de Perú
 * @wordpress-plugin
 * Plugin Name:       Departamentos y Ciudades de Perú
 * Plugin URI:        https://eduardogonzalez.me/
 * Description:       Complemento de departamentos y ciudades para Peru woocommerce
 * Version:           1.0.2
 * Author:            Efrain Gonzalez
 * Author URI:        https://eduardogonzalez.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       departamentos-y-ciudades-de-peru
 * WC tested up to: 3.5
 * WC requires at least: 2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
add_action('plugins_loaded','dpc_peru_states_places_peru_init',1);

function states_places_peru_smp_notices($classes, $notice){
    ?>
    <div class="<?php echo $classes; ?>">
        <p><?php echo $notice; ?></p>
    </div>
    <?php
}
function dpc_peru_states_places_peru_init(){
    load_plugin_textdomain('departamentos-y-ciudades-de-peru-para-woocommerce',
        FALSE, dirname(plugin_basename(__FILE__)) . '/languages');

    /**
     * Verify woocoommerce active
     */
    if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        require_once ('includes/states-places.php');
        /**
         * Instantiate class
         */
        $GLOBALS['wc_states_places'] = new WC_States_Places_peru(__FILE__);
        require_once ('includes/filter-by-cities.php');
        add_filter( 'woocommerce_shipping_methods', 'add_filters_by_cities_method' );
        function add_filters_by_cities_method( $methods ) {
            $methods['filters_by_cities_shipping_method'] = 'Filters_By_Cities_Method';
            return $methods;
        }
        add_action( 'woocommerce_shipping_init', 'filters_by_cities_method' );
    }
}
add_filter( 'woocommerce_default_address_fields', 'dpc_peru_woocommerce_default_address_fields' );
function dpc_peru_woocommerce_default_address_fields( $fields ) {
    $fields['city']['priority'] = 60;
    $fields['state']['priority'] = 50;
    if ($fields['city']['priority'] < $fields['state']['priority']){
        $state_priority = $fields['state']['priority'];
        $fields['state']['priority'] = $fields['city']['priority'];
        $fields['city']['priority'] = $state_priority;

    }
    return $fields;
}