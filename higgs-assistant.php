<?php
/**
 * Plugin Name: Higgs Assistant
 * Plugin URI: https://github.com/Codestag/higgs-assistant
 * Description: A plugin to assist Higgs theme in adding widgets.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0
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
	 *
	 * @since 1.0
	 */
	class Higgs_Assistant {

		/**
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Higgs_Assistant ) ) {
				self::$instance = new Higgs_Assistant();
				self::$instance->init();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function init() {
			add_action( 'enqueue_assets', 'plugin_assets' );
		}

		/**
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'HA_VERSION', '1.0' );
			$this->define( 'HA_DEBUG', true );
			$this->define( 'HA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'HA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
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
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once HA_PLUGIN_PATH . 'includes/widgets/portfolio.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/recent-posts.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/service-option.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/service-section.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/static-content.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/testimonials.php';

			require_once HA_PLUGIN_PATH . 'includes/updater/updater.php';
		}
	}
endif;


/**
 *
 * @since 1.0
 */
function higgs_assistant() {
	return Higgs_Assistant::register();
}

/**
 *
 * @since 1.0
 */
function higgs_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Higgs Assistant requires Higgs WordPress Theme to be installed and activated.', 'higgs-assistant' );
	echo '</p></div>';
}

/**
 *
 *
 * @since 1.0
 */
function higgs_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Higgs' == $theme->name || 'Higgs' == $theme->parent_theme ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'after_setup_theme', 'higgs_assistant' );
		} else {
			ink_assistant();
		}
	} else {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'higgs_assistant_activation_notice' );
	}
}

// Theme loads.
higgs_assistant_activation_check();
