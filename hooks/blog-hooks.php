<?php
/**
 * FIXME: Post Hooks
 *
 * FIXME: Displays Drag and Drop Elements
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 * FIXME: POINT USERS TO DOWNLOAD OUR STARTER CHILD THEME AND DOCUMENTATION
 *
 * @category Cyber Chimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

function blog_section_order_action() {
	global $post;
	
	$blog_section_order = cyberchimps_get_option( 'blog_section_order' );
	
	if ( is_array($blog_section_order) ) {
		foreach ( $blog_section_order as $func) {
			do_action($func);
		}
	}
}
add_action('cyberchimps_blog_content', 'blog_section_order_action');

function cyberchimps_post(){ ?>
<div id="container" <?php cyberchimps_filter_container_class(); ?>>

	<?php do_action( 'cyberchimps_before_content_container'); ?>
  
	<div id="content" <?php cyberchimps_filter_content_class(); ?>>
		
		<?php do_action( 'cyberchimps_before_content'); ?>
		
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>
				
			<?php endwhile; ?>
			
		<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>
		
		<?php do_action( 'cyberchimps_after_content'); ?>
		
	</div><!-- #content -->
<?php do_action( 'cyberchimps_after_content_container'); ?>
	
</div><!-- #container -->
<?php }
add_action( 'blog_post_page', 'cyberchimps_post' );