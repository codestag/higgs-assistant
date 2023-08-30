<?php
if ( ! class_exists( 'Higgs_Recent_Posts' ) ) :
	/**
	 * Displays latest blog posts.
	 *
	 * @since 1.0.0.
	 * @package Higgs
	 */
	class Higgs_Recent_Posts extends Stag_Widget {
		public function __construct() {
			$this->widget_id          = 'higgs_recent_posts';
			$this->widget_cssclass    = 'section-recent-posts';
			$this->widget_description = __( 'Displays recent posts from Blog.', 'higgs-assistant' );
			$this->widget_name        = __( 'Section: Recent Posts', 'higgs-assistant' );
			$this->settings           = array(
				'title'     => array(
					'type'  => 'text',
					'std'   => 'Latest Posts',
					'label' => __( 'Title:', 'higgs-assistant' ),
				),
				'count'     => array(
					'type'  => 'number',
					'std'   => '3',
					'label' => __( 'Number of posts to show:', 'higgs-assistant' ),
				),
				'post_date' => array(
					'type'  => 'checkbox',
					'std'   => 'on',
					'label' => __( 'Display Post Date?', 'higgs-assistant' ),
				),
				'category'  => array(
					'type'  => 'category',
					'std'   => '0',
					'label' => __( 'Post Category:', 'higgs-assistant' ),
				),
				'more_text' => array(
					'type'  => 'text',
					'std'   => __( 'View all Posts', 'higgs-assistant' ),
					'label' => __( 'More Posts text:', 'higgs-assistant' ),
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
			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			ob_start();

			extract( $args );

			$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			$count     = $instance['count'];
			$show_date = $instance['post_date'];
			$category  = $instance['category'];
			$more_text = wp_strip_all_tags( $instance['more_text'] );

			$posts      = wp_get_recent_posts(
				array(
					'post_type'   => 'post',
					'numberposts' => $count,
					'post_status' => 'publish',
					'category'    => $category,
				),
				OBJECT
			);
			$posts_page = get_option( 'page_for_posts' );

			if ( 0 === $posts_page ) {
				$posts_page = home_url();
			} else {
				$posts_page = get_permalink( $posts_page );
			}

			global $post;

			echo $before_widget;
			?>

		<ul>
			<?php
			if ( $title ) :
				echo $before_title;
				echo $title;
				if ( '' !== $posts_page ) :
					?>
				<span class="custom-link">
					<a href="<?php echo esc_url( $posts_page ); ?>"><?php echo esc_html( $more_text ); ?> <span class="genericon genericon-next"></span></a>
				</span>
					<?php
				endif;
				echo $after_title;
				endif;

			foreach ( $posts as $post ) :
				setup_postdata( $post );
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
				<?php if ( $show_date ) : ?>
					<span class="post-date"><?php echo get_the_date(); ?></span>
				<?php endif; ?>
				</li>
				<?php
			endforeach;

			remove_all_filters( 'subtitle_view_supported' );

			wp_reset_postdata();

			?>
		</ul>

			<?php
			echo $after_widget;

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

add_action( 'widgets_init', array( 'Higgs_Recent_Posts', 'register' ) );
