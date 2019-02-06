<?php

if ( ! class_exists( 'Higgs_Service_Section' ) ) :
/**
 * Shows service section, populated by 'Services widget area'.
 *
 * @since Higgs 1.0.0.
 *
 * @package Higgs
 */
class Higgs_Service_Section extends Stag_Widget {
	public function __construct() {
		$this->widget_id          = 'higgs_service_section';
		$this->widget_cssclass    = 'service-section';
		$this->widget_description = __( 'Output the service section (based on the &ldquo;Services widget area&rdquo;).', 'higgs-assistant' );
		$this->widget_name        = __( 'Section: Services', 'higgs-assistant' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __( 'Our Services', 'higgs-assistant' ),
				'label' => __( 'Title:', 'higgs-assistant' ),
			),
			'link_text' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Custom Link Text (Optional):', 'higgs-assistant' ),
			),
			'link' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Custom Link URL (Optional):', 'higgs-assistant' ),
			),
		);

		parent::__construct();
	}

	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$link      = esc_url( $instance['link'] );
		$link_text = $instance['link_text'];

		echo $before_widget;

		if ( $title ) :
			echo $before_title;
			echo $title;
			if ( '' != $link ) : ?>
			<span class="custom-link">
				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $link_text ); ?> <span class="genericon genericon-next"></span></a>
			</span>
			<?php endif;
			echo $after_title;
		endif;

		if ( is_active_sidebar( 'sidebar-services' ) ) :
			dynamic_sidebar( 'sidebar-services' );
		endif;

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	public static function register() {
		register_widget( __CLASS__ );
	}
}
endif;

add_action( 'widgets_init', array( 'Higgs_Service_Section', 'register' ) );
