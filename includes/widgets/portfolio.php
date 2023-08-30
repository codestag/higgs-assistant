<?php
if ( ! class_exists( 'Higgs_Portfolio' ) ) :
	/**
	 * Displays recent portfolio posts.
	 *
	 * @since 1.0.0.
	 * @package Higgs
	 */
	class Higgs_Portfolio extends Stag_Widget {
		public function __construct() {
			$this->widget_id          = 'higgs_portfolio';
			$this->widget_cssclass    = 'section-portfolio';
			$this->widget_description = __( 'Display recent portfolio post in grid style.', 'higgs-assistant' );
			$this->widget_name        = __( 'Section: Portfolio', 'higgs-assistant' );
			$this->settings           = array(
				'title'     => array(
					'type'  => 'text',
					'std'   => '',
					'label' => __( 'Title:', 'higgs-assistant' ),
				),
				'orderby'   => array(
					'type'    => 'select',
					'std'     => 'post_date',
					'label'   => __( 'Sort by:', 'higgs-assistant' ),
					'options' => array(
						'post_date' => __( 'Date', 'higgs-assistant' ),
						'title'     => __( 'Title', 'higgs-assistant' ),
						'rand'      => __( 'Random', 'higgs-assistant' ),
					),
				),
				'number'    => array(
					'type'  => 'number',
					'std'   => '6',
					'label' => __( 'Count:', 'higgs-assistant' ),
					'step'  => '1',
					'min'   => '1',
					'max'   => '20',
				),
				'more_text' => array(
					'type'  => 'text',
					'std'   => __( 'View all Posts', 'higgs-assistant' ),
					'label' => __( 'More Posts text:', 'higgs-assistant' ),
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

			$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			$orderby   = $instance['orderby'];
			$number    = absint( $instance['number'] );
			$more_text = wp_strip_all_tags( $instance['more_text'] );

			echo $before_widget;

			$link = get_post_type_archive_link( 'jetpack-portfolio' );

			if ( $title ) :
				echo $before_title;
				echo $title;
				if ( '' !== $link ) : ?>
			<span class="custom-link">
				<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $more_text ); ?> <span class="genericon genericon-next"></span></a>
			</span>
					<?php
			endif;
				echo $after_title;
			endif;

			add_filter( 'subtitle_view_supported', '__return_false' );

			$p = new WP_Query(
				apply_filters(
					'higgs_portfolio_args',
					array(
						'post_type'           => 'jetpack-portfolio',
						'post_status'         => 'publish',
						'orderby'             => $orderby,
						'posts_per_page'      => $number,
						'no_found_rows'       => true,
						'ignore_sticky_posts' => true,
						'meta_query'          => array( array( 'key' => '_thumbnail_id' ) ),
					)
				)
			);

			if ( $p->have_posts() ) :
				?>

			<div id="portfolio-container" class="portfolio-container">
				<div class="portfolio-loader">
					<div class="line-scale-pulse-out">
						<div></div>
						<div></div>
						<div></div>
						<div></div>
						<div></div>
					</div>
				</div>

				<?php
				while ( $p->have_posts() ) :
					$p->the_post();
					get_template_part( 'partials/content', 'portfolio-archive' );
				endwhile;
				?>

			<div id="gutter-sizer"></div>
			</div>
				<?php
		endif;

			remove_all_filters( 'subtitle_view_supported' );

			wp_reset_postdata();

			?>

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

add_action( 'widgets_init', array( 'Higgs_Portfolio', 'register' ) );
