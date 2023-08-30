<?php
if ( ! class_exists( 'Higgs_Testimonials' ) ) :
/**
 * Adds a simple Testimonials cycle slideshow.
 *
 * @since 1.0.0.
 * @package Higgs
 */
class Higgs_Testimonials extends Stag_Widget {
	public function __construct() {
		$this->widget_id          = 'higgs_testimonials';
		$this->widget_cssclass    = 'section-testimonials';
		$this->widget_description = __( 'Displays the testimonails in a simple slideshow.', 'higgs-assistant' );
		$this->widget_name        = __( 'Section: Testimonials', 'higgs-assistant' );
		$this->settings           = array(
			'orderby' => array(
				'type'    => 'select',
				'std'     => 'post_date',
				'label'   => __( 'Sort by:', 'higgs-assistant' ),
				'options' => array(
					'post_date' => __( 'Date', 'higgs-assistant' ),
					'title'     => __( 'Title', 'higgs-assistant' ),
					'rand'      => __( 'Random', 'higgs-assistant' ),
				),
			),
			'number' => array(
				'type'  => 'number',
				'std'   => '5',
				'label' => __( 'Count:', 'higgs-assistant' ),
				'step'  => '1',
				'min'   => '1',
				'max'   => '20',
			),
		);

		parent::__construct();
	}

	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$orderby = $instance['orderby'];
		$number  = absint( $instance['number'] );

		echo $before_widget;

		$t = new WP_Query( apply_filters( 'higgs_testimonials_args', array(
			'post_type'           => 'jetpack-testimonial',
			'post_status'         => 'publish',
			'orderby'             => $orderby,
			'no_found_rows'       => true,
			'posts_per_page'      => $number,
			'ignore_sticky_posts' => true,
		) ) );

		$classes = 'higgs-testimonial-slider cycle-slideshow';

			// Data attributes
			$data_attributes  = ' data-cycle-log=false';
			$data_attributes .= ' data-cycle-slides=.cycle-slide';
			$data_attributes .= ' data-cycle-auto-height=calc';
			$data_attributes .= ' data-cycle-center-horz=true';
			$data_attributes .= ' data-cycle-center-vert=true';
			$data_attributes .= ' data-cycle-swipe=true';
			$data_attributes .= ' data-cycle-paused=true';
			$data_attributes .= ' data-cycle-timeout=6000';
			$data_attributes .= ' data-cycle-fx=fade';

		if ( $t->have_posts() ) : ?>
			<div class="<?php echo esc_attr( $classes ); ?>"<?php echo esc_attr( $data_attributes ); ?>>
			<?php
			while ( $t->have_posts() ) : $t->the_post(); ?>
				<div id="<?php the_ID(); ?>" <?php post_class( 'testimonial cycle-slide' ); ?>>
					<div class="testimonial__content">
						<?php the_content(); ?>
					</div>
					<h3 class="testimonial__author"><?php the_title(); ?></h3>
				</div>
			<?php endwhile; ?>

			<?php if ( 1 < $t->post_count ) : ?>
			<div class="cycle-prev"><span class="genericon genericon-previous"></span></div>
			&#47;
			<div class="cycle-next"><span class="genericon genericon-next"></span></div>
			<?php endif; ?>

			</div>
		<?php endif;

		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

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

add_action( 'widgets_init', array( 'Higgs_Testimonials', 'register' ) );
