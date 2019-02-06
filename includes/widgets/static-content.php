<?php
if ( ! class_exists( 'Higgs_Widget_Static_Content' ) ) :
/**
 * Display static content from an specific page.
 *
 * @since Higgs 1.0.0.
 *
 * @package Higgs
 */
class Higgs_Widget_Static_Content extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'higgs_static_content';
		$this->widget_cssclass    = 'static-content';
		$this->widget_description = __( 'Displays content from a specific page.', 'higgs-assistant' );
		$this->widget_name        = __( 'Section: Static Content', 'higgs-assistant' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'higgs-assistant' ),
			),
			'page' => array(
				'type'  => 'page',
				'std'   => '',
				'label' => __( 'Select Page:', 'higgs-assistant' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$page  = $instance[ 'page' ];
		$post  = new WP_Query( array( 'page_id' => $page ) );

		echo $before_widget;

		// Allow site-wide customization of the 'Read more' link text
		$read_more = apply_filters( 'higgs_read_more_text', __( 'Read more', 'higgs-assistant' ) );
		?>
		<section class="inner-section">

			<?php if ( $post->have_posts() ) : ?>
				<?php while ( $post->have_posts() ) : $post->the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ( $title ) echo $before_title . $title . $after_title; ?>

						<div class="entry-content">
							<?php the_content( $read_more ); ?>
						</div>
					</article>
				<?php endwhile; ?>
			<?php endif; ?>

		</section>

		<?php
		echo $after_widget;

		wp_reset_postdata();

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @return void.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}
endif;

add_action( 'widgets_init', array( 'Higgs_Widget_Static_Content', 'register' ) );
