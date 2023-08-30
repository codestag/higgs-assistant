<?php
/**
 * Plugin Name: Higgs Assistant
 * Plugin URI: https://github.com/Codestag/higgs-assistant
 * Description: A plugin to assist Higgs theme in adding widgets.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0.1
 * Text Domain: higgs-assistant
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Higgs
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Higgs_Assistant' ) ) :
	/**
	 * Higgs Assistant Class
	 *
	 * @since 1.0
	 */
	class Higgs_Assistant {

		/**
		 * Instance var.
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Register instance for plugin class.
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Higgs_Assistant ) ) {
				self::$instance = new Higgs_Assistant();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 * Defined constants.
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'HA_VERSION', '1.0.1' );
			$this->define( 'HA_DEBUG', true );
			$this->define( 'HA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'HA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Defines a constant.
		 *
		 * @param string $name
		 * @param string $value
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Includes plugin files.
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once HA_PLUGIN_PATH . 'includes/widgets/stag-widget.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/portfolio.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/recent-posts.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/service-option.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/service-section.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/static-content.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/testimonials.php';
		}
	}
endif;


/**
 * Plugin class instance.
 *
 * @since 1.0
 */
function higgs_assistant() {
	return Higgs_Assistant::register();
}

/**
 * Plugin activation alert.
 *
 * @since 1.0
 */
function higgs_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Higgs Assistant requires Higgs WordPress Theme to be installed and activated.', 'higgs-assistant' );
	echo '</p></div>';
}

/**
 * Plugin activation check.
 *
 * @since 1.0
 */
function higgs_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme.
	if ( 'Higgs' === $theme->name || 'Higgs' === $theme->parent_theme ) {
		add_action( 'after_setup_theme', 'higgs_assistant' );
	} else {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'higgs_assistant_activation_notice' );
	}
}

// Plugin loads.
higgs_assistant_activation_check();
