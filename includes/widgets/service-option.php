<?php

if ( ! class_exists( 'Higgs_Service_Option' ) ) :
	/**
	 * Adds a services option, to be ultimately added to services section widget.
	 *
	 * @since Higgs 1.0.0.
	 *
	 * @package Higgs
	 */
	class Higgs_Service_Option extends Stag_Widget {
		public function __construct() {
			$this->widget_id          = 'higgs_service_option';
			$this->widget_cssclass    = 'service-option';
			$this->widget_description = __( 'Create a service option for services section.', 'higgs-assistant' );
			$this->widget_name        = __( 'Service Box', 'higgs-assistant' );
			$this->settings           = array(
				'title'            => array(
					'type'  => 'text',
					'std'   => '',
					'label' => __( 'Title:', 'higgs-assistant' ),
				),
				'icon'             => array(
					'type'  => 'text',
					'std'   => '',
					'label' => __( 'Custom Icon URL:', 'higgs-assistant' ),
				),
				'description'      => array(
					'type'  => 'textarea',
					'std'   => '',
					'rows'  => '4',
					'label' => __( 'Description:', 'higgs-assistant' ),
				),
				'description_help' => array(
					'type' => 'description',
					'std'  => __( 'Accepts shortcodes/HTML.', 'higgs-assistant' ),
				),
			);

			parent::__construct();
		}

		function widget( $args, $instance ) {
			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			ob_start();

			extract( $args );

			$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			$icon        = esc_url( $instance['icon'] );
			$description = stripslashes( $instance['description'] );

			echo $before_widget; ?>

		<div class="service-option__container">
				<?php if ( '' !== $icon ) : ?>
				<figure class="service-option__thumb">
					<img src="<?php echo esc_url( $icon ); ?>" alt="<?php echo esc_attr( $title ); ?>">
				</figure>
			<?php endif; ?>

				<?php
				if ( $title ) {
					echo $before_title . $title . $after_title;}
				?>

				<?php if ( '' !== $description ) : ?>
			<div class="service-option__content">
					<?php echo do_shortcode( $description ); ?>
			</div>
			<?php endif; ?>
		</div>

			<?php
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

add_action( 'widgets_init', array( 'Higgs_Service_Option', 'register' ) );
