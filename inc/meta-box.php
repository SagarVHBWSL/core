<?php
/**
 * Title: Metabox
 *
 * Description: Defines metabox fields for page.
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category Cyber Chimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

add_action('admin_head', 'cyberchimps_load_meta_boxes_scripts');
function cyberchimps_load_meta_boxes_scripts() {
	global $post_type;

	// Set library path.
	$lib_path = get_template_directory_uri() . "/cyberchimps/lib/";
	
	if ( $post_type == 'page' ) :
		wp_enqueue_style( 'meta-boxes-css', $lib_path . 'css/metabox-tabs.css' );
	
		// Enqueue only if it is not done before
		if( !wp_script_is('jf-metabox-tabs') ) :
			wp_enqueue_script('meta-boxes-js', $lib_path . 'js/metabox-tabs.min.js', array('jquery'));
		endif;	
	endif;
}

add_action('init', 'cyberchimps_init_meta_boxes');
function cyberchimps_init_meta_boxes() {
	
	// Set image path
	$directory_uri	 = get_template_directory_uri();
	$image_path		 = $directory_uri . "/cyberchimps/lib/images/";
	$portfolio_image = $image_path . "portfolio.jpg";
	
	// get default image for slider lite.
	$slider_image1 = $directory_uri .  apply_filters( 'cyberchimps_slider_lite_img1' ,'/elements/lib/images/slider/slide1.jpg' );
	$slider_image2 = $directory_uri .  apply_filters( 'cyberchimps_slider_lite_img2' ,'/elements/lib/images/slider/slide1.jpg' );
	$slider_image3 = $directory_uri .  apply_filters( 'cyberchimps_slider_lite_img3' ,'/elements/lib/images/slider/slide1.jpg' );
	
	// get default image for Boxes lite.
	$boxes_lite_image1 = $directory_uri . apply_filters( 'cyberchimps_boxes_lite_img1', '/elements/lib/images/boxes/slidericon.png' );
	$boxes_lite_image2 = $directory_uri . apply_filters( 'cyberchimps_boxes_lite_img2', '/elements/lib/images/boxes/blueprint.png' );
	$boxes_lite_image3 = $directory_uri . apply_filters( 'cyberchimps_boxes_lite_img3', '/elements/lib/images/boxes/docs.png' );
	
	// add gravatar image as default
	$email = get_option( 'admin_email' );
	if( $email ) {
		$hash = md5( strtolower( trim ( $email ) ) );
		$default_profile =  'http://gravatar.com/avatar/' . $hash . '.jpg?s=250&r=g';
	}
	else {
		$default_profile = $directory_uri . '/elements/lib/images/profile/profile.jpg';
	}
	
	// Declare variables
	$portfolio_options = array(); 
	$carousel_options = array();
	$slider_options = array();
	$boxes_options = array();
	$blog_options = array();
	
	// Call taxonomies for select options
	$portfolio_terms = get_terms('portfolio_cats', 'hide_empty=0');
	if( ! is_wp_error( $portfolio_terms ) ):
		foreach($portfolio_terms as $term) {
			$portfolio_options[$term->slug] = $term->name;
		}
	endif;
	
	$carousel_terms = get_terms('carousel_categories', 'hide_empty=0');
	if( ! is_wp_error( $carousel_terms ) ): 
		foreach($carousel_terms as $term) {
			$carousel_options[$term->slug] = $term->name;
		}
	endif;
	
	$slide_terms = get_terms('slide_categories', 'hide_empty=0');
	if( ! is_wp_error( $slide_terms ) ):
		foreach($slide_terms as $term) {
			$slider_options[$term->slug] = $term->name;
		}
	endif;
	
	// Get custom categories of boxes element
	$boxes_terms = get_terms('boxes_categories', 'hide_empty=0');
	if( ! is_wp_error( $boxes_terms ) ):
		foreach($boxes_terms as $term) {
			$boxes_options[$term->slug] = $term->name;
		}
	endif;
	
	$category_terms = get_terms('category', 'hide_empty=0');
	if( ! is_wp_error( $category_terms ) ):
		$blog_options['all'] = "All";
		foreach($category_terms as $term) {
			$blog_options[$term->slug] = $term->name;
		}
	endif;
	
	// get cat ids for portfolio
	$cat_terms = get_terms('category', 'hide_empty=0');
	if( ! is_wp_error( $cat_terms ) ):
		$blog_id_options['all'] = "All";
		foreach($cat_terms as $term) {
			$blog_id_options[$term->term_id] = $term->name;
		}
	endif;
	
	// Get all post categories
	$all_cats = array();
	$all_categories = get_terms( 'category');
	if( ! is_wp_error( $all_categories ) ) {
		$all_cats['all'] = "All";
		foreach( $all_categories as $all_cat ) {
			$all_cats[$all_cat->term_id] = $all_cat->name;
		}
	}
	// End taxonomy call
	
	$meta_boxes = array();
	
	if( cyberchimps_theme_check() == "pro" ) {
		$mb = new Chimps_Metabox('post_slide_options', __('Slider Options', 'cyberchimps_core' ), array('pages' => array('post')));
		$mb
			->tab("Slider Options")
				->single_image('cyberchimps_slider_image', __('Slider Image', 'cyberchimps_core' ), '')
				->text('cyberchimps_slider_caption', __('Slider Caption', 'cyberchimps_core' ), '')			
				->text('cyberchimps_slider_url', __( 'Custom Slide Link', 'cyberchimps_core' ), '')
				->checkbox('cyberchimps_slider_hidetitle', __('Title', 'cyberchimps_core' ), '', array('std' => '1'))
				->checkbox('cyberchimps_slider_hidecaption', __('Caption', 'cyberchimps_core' ), '', array('std' => '1'))
				->sliderhelp('', __('Need Help?', 'cyberchimps_core' ), '')
			->end();
	}
	
	$mb = new Chimps_Metabox('pages', 'Page Options', array('pages' => array('page')));
	$mb
		->tab("Page Options")
			->image_select('cyberchimps_page_sidebar', __( 'Select Page Layout', 'cyberchimps_core' ), '',
				array('options' => apply_filters( 'sidebar_layout_options', array(
					'full_width'		 => $image_path . '1col.png',
					'right_sidebar'		 => $image_path . '2cr.png'
					) ), 'std' => 'right_sidebar') )
			->checkbox('cyberchimps_page_title_toggle', __('Page Title', 'cyberchimps_core' ), '', array('std' => '1'))
			->section_order('cyberchimps_page_section_order', __( 'Page Elements', 'cyberchimps_core' ), '', array(					
				'options' => apply_filters( 'cyberchimps_elements_draganddrop_page_options', array(
					'boxes'				 => __( 'Boxes', 'cyberchimps_core' ),					
					'page_section'		 => __( 'Page', 'cyberchimps_core' ),
					'portfolio_lite'	 => __( 'Portfolio Lite', 'cyberchimps_core' ),
					'slider_lite'		 => __( 'Slider Lite', 'cyberchimps_core' ),
					'twitterbar_section' => __( 'Twitter Bar', 'cyberchimps_core' )
				) ),
					'std' => array( 'page_section' )
				))
			->pagehelp('', __( 'Need Help?', 'cyberchimps_core' ), '')
		->tab("Magazine Layout Options")
			->checkbox('cyberchimps_magazine_meta_data_toggle', __( 'Meta Data', 'cyberchimps_core' ), '', array('std' => '1'))
			->checkbox('cyberchimps_magazine_featured_image', __( 'Featured Image', 'cyberchimps_core' ), '', array( 'std' => 1 ) )
			->select('cyberchimps_magazine_category', __( 'Category', 'cyberchimps_core' ), '', array('options' => ( $all_cats ? $all_cats : array( 'cc_no_options' => __( 'You need to create a Category', 'cyberchimps_core' ) ) ) ) )
			->select('cyberchimps_magazine_no_of_columns', __( 'Number of Columns', 'cyberchimps_core' ), '', array('options' => array( 2 => '2', 3 => '3')) )
			->select('cyberchimps_magazine_no_of_rows', __( 'Number of Posts', 'cyberchimps_core' ), '', array('options' => array( 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20')) )
			->checkbox('cyberchimps_magazine_wide_post_toggle', __( 'Wide Posts Below Magazine', 'cyberchimps_core' ), '', array('std' => '1'))
			->select('cyberchimps_magazine_no_of_wide_posts', __( 'Number of Wide Posts ', 'cyberchimps_core' ), '',
						array('options' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10', 11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15', 16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20')))
		/*->tab("Featured Posts Options")
			->select('cyberchimps_featured_post_category_toggle', __( 'Select post source', 'cyberchimps_core' ), '', array('options' => array( __( 'Latest posts', 'cyberchimps_core' ), __( 'From category', 'cyberchimps_core' ))) )
			->text('cyberchimps_featured_post_category', __( 'Enter category', 'cyberchimps_core' ), '', array('std' => __( 'featured', 'cyberchimps_core' )))*/
		->tab("Slider Lite Options")
			->single_image('cyberchimps_slider_lite_slide_one_image', __( 'Slide One Image', 'cyberchimps_core' ), '', array('std' => $slider_image1 ))
			->text('cyberchimps_slider_lite_slide_one_url', __( 'Slide One Link', 'cyberchimps_core' ), '', array('std' => 'http://wordpress.org'))
			->single_image('cyberchimps_slider_lite_slide_two_image', __( 'Slide Two Image', 'cyberchimps_core' ), '', array('std' => $slider_image2 ))
			->text('cyberchimps_slider_lite_slide_two_url', __( 'Slide Two Link', 'cyberchimps_core' ), '', array('std' => 'http://wordpress.org'))
			->single_image('cyberchimps_slider_lite_slide_three_image', __( 'Slide Three Image', 'cyberchimps_core' ), '', array('std' => $slider_image3 ))
			->text('cyberchimps_slider_lite_slide_three_url', __( 'Slide Three Link', 'cyberchimps_core' ), '', array('std' => 'http://wordpress.org'))
		->tab("iFeature Slider Options")
			->select('cyberchimps_slider_size', __( 'Slider Size', 'cyberchimps_core' ), '', array( 'options' => array('full' => __( 'Full', 'cyberchimps_core' ), 'half' => __( 'Half', 'cyberchimps_core' )) ) )
			->select('cyberchimps_slider_type', __( 'Slider Type', 'cyberchimps_core' ), '', array( 'options' => array( 'custom_slides' => __( 'Custom', 'cyberchimps_core' ), 'post' => __( 'Posts', 'cyberchimps_core' ) ) ) )
			->select('cyberchimps_slider_post_categories', __( 'Post Categories', 'cyberchimps_core' ), '', array( 'options' => $blog_id_options, __( 'All', 'cyberchimps_core' ) ) )
			->select('cyberchimps_slider_custom_categories', __( 'Custom Categories', 'cyberchimps_core' ), '', array( 'options' => ( $slider_options ? $slider_options : array( 'cc_no_options' => __( 'You need to create a Category', 'cyberchimps_core' ) ) ) ) )
			->text('cyberchimps_number_featured_posts', __( 'Number of Featured Posts', 'cyberchimps_core' ), '', array('std' => 5) )
			->text('cyberchimps_slider_height', __( 'Slider Height', 'cyberchimps_core' ), '' )
			->text('cyberchimps_slider_speed', __( 'Slider Speed', 'cyberchimps_core' ), '', array( 'std' => 3000 ) )
			->checkbox('cyberchimps_slider_arrows', __( 'Slider Arrows', 'cyberchimps_core' ), '', array('std' => "1") )
			->sliderhelp('', __( 'Need Help?', 'cyberchimps_core' ), '')
		->tab("Product Options")
			->select('cyberchimps_product_text_align', __( 'Product Layout', 'cyberchimps_core' ), '', array( 'options' => array('left' => __( 'Text Left', 'cyberchimps_core' ),
                                                                                                                                 'right' => __( 'Text Right',
                                                                                                                                                                                          'cyberchimps_core' ) ) ) )
			->text('cyberchimps_product_title', __( 'Product Title', 'cyberchimps_core' ), '', array('std' => __( 'Product', 'cyberchimps_core' ) ) )
			->textarea('cyberchimps_product_text', __( 'Product Text', 'cyberchimps_core' ), '', array('std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. '))
			->select('cyberchimps_product_type', __( 'Media Type', 'cyberchimps_core' ), '', array( 'options' => array('image' => __( 'Image', 'cyberchimps_core' ), 'video' => __( 'Video',
                                                                                                                                                                              'cyberchimps_core' ) )
                                                                                             ) )
			->single_image('cyberchimps_product_image', __( 'Product Image URL', 'cyberchimps_core' ), '', array('std' =>  $directory_uri . '/elements/lib/images/product/product.jpg'))
			->textarea('cyberchimps_product_video', __( 'Product Video Embed', 'cyberchimps_core' ), '')
			->checkbox('cyberchimps_product_link_toggle', __( 'Product Link', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('cyberchimps_product_link_url', __( 'Product URL', 'cyberchimps_core' ), '', array('std' => home_url()))
			->text('cyberchimps_product_link_text', __( 'Product Link Text', 'cyberchimps_core' ), '', array('std' => __( 'Buy Now', 'cyberchimps_core' ) ) )
		->tab("Callout Options")
			->text('callout_title', __( 'Callout Title', 'cyberchimps_core' ), '',
				array('std' => sprintf( __( '%1$s\'s Call Out Element', 'cyberchimps_core' ),
					apply_filters( 'cyberchimps_current_theme_name', 'Cyberchimps' ) ) ) )
			->textarea('callout_text', __( 'Callout Text', 'cyberchimps_core' ), '',
				array('std' => sprintf( __( 'Use %1$s\'s Call Out section on any page where you want to deliver an important message to your customer or client.', 'cyberchimps_core' ),
					apply_filters( 'cyberchimps_current_theme_name', 'Cyberchimps' ) ) ) )
			->checkbox('disable_callout_button', __( 'Callout Button', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('callout_button_text', __( 'Callout Button Text', 'cyberchimps_core' ), '', array('std' => __( 'Click Here', 'cyberchimps_pro' ) ) )
			->text('callout_url', __( 'Callout Button URL', 'cyberchimps_core' ), '')
			->checkbox('extra_callout_options', __( 'Custom Callout Options', 'cyberchimps_core' ), '', array('std' => '0'))
			->single_image('callout_image', __( 'Custom Button Image', 'cyberchimps_core' ), '')
			->color('custom_callout_color', __( 'Custom Background Color', 'cyberchimps_core' ), '')
			->color('custom_callout_title_color', __( 'Custom Title Color', 'cyberchimps_core' ), '')
			->color('custom_callout_text_color', __( 'Custom Text Color', 'cyberchimps_core' ), '')
			->color('custom_callout_button_color', __( 'Custom Button Color', 'cyberchimps_core' ), '')
			->color('custom_callout_button_text_color', __( 'Custom Button Text Color', 'cyberchimps_core' ), '')
			->pagehelp('', __( 'Need help?', 'cyberchimps_core' ), '')
		->tab("HTML Box Options")
			->textarea('html_box', __( 'Custom HTML', 'cyberchimps_core' ), __( 'Enter your custom html here', 'cyberchimps_core' ) )
		->tab("Portfolio Options")
			->select('portfolio_row_number', __( 'Images per row', 'cyberchimps_core' ), '', array('options' => array( 2 => __( 'Two', 'cyberchimps_core' ), 3 => __( 'Three', 'cyberchimps_core' ), 4 => __( 'Four', 'cyberchimps_core' ) ), 'std' => 3) )
			->select('portfolio_category', __( 'Portfolio Category', 'cyberchimps_core' ), '', array('options' => ( $portfolio_options ? $portfolio_options : array( 'cc_no_options' => __( 'You need to create a Category', 'cyberchimps_core' ) ) ) ) )
			->checkbox('portfolio_title_toggle', __( 'Portfolio Title', 'cyberchimps_core' ), '')
			->text('portfolio_title', __( 'Title', 'cyberchimps_core' ), '', array('std' => __( 'Portfolio', 'cyberchimps_core' ) ) )
		->tab("Portfolio Lite Options")
			->single_image('cyberchimps_portfolio_lite_image_one', __( 'First Portfolio Image', 'cyberchimps_core' ), '', array('std' => $portfolio_image ) )
			->text('cyberchimps_portfolio_lite_image_one_caption', __( 'First Portfolio Image Caption', 'cyberchimps_core' ), '', array('std' => __( 'Image 1', 'cyberchimps_core' ) ) )
			->checkbox('cyberchimps_portfolio_link_toggle_one', __( 'First Porfolio Link', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_one', __( 'Link URL', 'cyberchimps_core' ), '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_two', __( 'Second Portfolio Image', 'cyberchimps_core' ), '', array('std' => $portfolio_image ) )
			->text('cyberchimps_portfolio_lite_image_two_caption', __( 'Second Portfolio Image Caption', 'cyberchimps_core' ), '', array('std' => __( 'Image 2', 'cyberchimps_core' ) ) )
			->checkbox('cyberchimps_portfolio_link_toggle_two', __( 'Second Porfolio Link', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_two', __( 'Link URL', 'cyberchimps_core' ), '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_three', __( 'Third Portfolio Image', 'cyberchimps_core' ), '', array('std' => $portfolio_image ) )
			->text('cyberchimps_portfolio_lite_image_three_caption', __( 'Third Portfolio Image Caption', 'cyberchimps_core' ), '', array('std' => __( 'Image 3', 'cyberchimps_core' ) ) )
			->checkbox('cyberchimps_portfolio_link_toggle_three', __( 'Third Porfolio Link', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_three', __( 'Link URL', 'cyberchimps_core' ), '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_four', __( 'Fourth Portfolio Image', 'cyberchimps_core' ), '', array('std' => $portfolio_image ) )
			->text('cyberchimps_portfolio_lite_image_four_caption', __( 'Fourth Portfolio Image Caption', 'cyberchimps_core' ), '', array('std' => __( 'Image 4', 'cyberchimps_core' ) ) )
			->checkbox('cyberchimps_portfolio_link_toggle_four', __( 'Fourth Porfolio Link', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_four', __( 'Link URL', 'cyberchimps_core' ), '', array('std' => home_url()))
			->checkbox('cyberchimps_portfolio_title_toggle', __( 'Portfolio Title', 'cyberchimps_core' ), '')
			->text('cyberchimps_portfolio_title', __( 'Title', 'cyberchimps_core' ), '', array('std' => __( 'Portfolio', 'cyberchimps_core' )))
		->tab("Recent Posts Options")
			->checkbox('cyberchimps_recent_posts_title_toggle', __( 'Title', 'cyberchimps_core' ), '')
			->text('cyberchimps_recent_posts_title', '', '')
			->select('cyberchimps_recent_posts_category', __( 'Post Category', 'cyberchimps_core' ), '', array('options' => $blog_options, __( 'All', 'cyberchimps_core' ) ) )
			->checkbox('cyberchimps_recent_posts_images_toggle', __( 'Images', 'cyberchimps_core' ), '')
		->tab("Carousel Options")
			->select('carousel_category', __( 'Carousel Category', 'cyberchimps_core' ), '', array('options' => ( $carousel_options ? $carousel_options : array( 'cc_no_options' => __( 'You need to create a Category', 'cyberchimps_core' ) ) ) ) )
		->tab("Boxes Options")
			->select('boxes_category', __( 'Boxes Category', 'cyberchimps_core' ), '', array('options' => ( $boxes_options ? $boxes_options : array( 'cc_no_options' => __( 'You need to create a Category', 'cyberchimps_core' ) ) ) ) )
			->select('boxes_per_row', __( 'Number of boxes per row', 'cyberchimps_core' ), '',
				array( 'options' => array( 2 => '2', 3 => '3', 4 => '4' ), 'std' => '3' ) )
		->tab("Twitter Options")
			->checkbox('cyberchimps_twitter_page_options_toggle', __( 'Use default setting', 'cyberchimps_core' ), '', array( 'std' => '1' ) )
			->text( 'cyberchimps_twitter_handle', __( 'Twitter Handle', 'cyberchimps_core' ), __( 'Enter your Twitter handle if using the Twitter bar', 'cyberchimps_core' ), array('std' => apply_filters( 'cyberchimps_twitter_handle_filter', 'CyberChimps' ) ) )
		/*->tab("Profile Options")
			->text('profile_name', __( 'Profile Name', 'cyberchimps_core' ), "" )
			->single_image('profile_picture', __( 'Profile Picture', 'cyberchimps_core' ), '', array('std' => $default_profile))
			->text('profile_profession', __( 'Profession', 'cyberchimps_core' ), "" )
			->textarea('profile_about', __( 'About', 'cyberchimps_core' ), '')
			->text('profile_location', __( 'Location', 'cyberchimps_core' ), "" )
			->text('profile_phone', __( 'Phone Number', 'cyberchimps_core' ), "" )
			->text('profile_email', __( 'Email Address', 'cyberchimps_core' ), "" )
			->text('profile_website', __( 'Website Address', 'cyberchimps_core' ), "" )
			->checkbox('profile_twitter', __( 'Twitter', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('profile_twitter_url', __( 'Twitter URL', 'cyberchimps_core' ), "", array('std' => 'http://www.twitter.com/' ) )
			->checkbox('profile_facebook', __( 'Facebook', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('profile_facebook_url', __( 'Facebook URL', 'cyberchimps_core' ), "", array('std' => 'http://www.facebook.com/' ) )
			->checkbox('profile_google', __( 'Google+', 'cyberchimps_core' ), '', array('std' => '1'))
			->text('profile_google_url', __( 'Google+ URL', 'cyberchimps_core' ), "", array('std' => 'http://www.google.com/' ) )
			->checkbox('profile_flickr', __( 'Flicker', 'cyberchimps_core' ), '')
			->text('profile_flickr_url', __( 'Flicker URL', 'cyberchimps_core' ), "", array('std' => 'http://www.flickr.com/' ) )
			->checkbox('profile_pinterest', __( 'Pinterest', 'cyberchimps_core' ), '')
			->text('profile_pinterest_url', __( 'Pinterest URL', 'cyberchimps_core' ), "", array('std' => 'http://www.pinterest.com/' ) )
			->checkbox('profile_linkedin', __( 'Linked In', 'cyberchimps_core' ), '')
			->text('profile_linkedin_url', __( 'Linked In URL', 'cyberchimps_core' ), "", array('std' => 'http://www.linkedin.com/' ) )
			->checkbox('profile_youtube', __( 'Youtube', 'cyberchimps_core' ), '')
			->text('profile_youtube_url', __( 'Youtube URL', 'cyberchimps_core' ), "" , array('std' => 'http://www.youtube.com/' ))
			->checkbox('profile_rss', __( 'RSS', 'cyberchimps_core' ), '')
			->text('profile_rss_url', __( 'RSS URL', 'cyberchimps_core' ), "", array('std' => get_bloginfo_rss( 'rss_url' ) ) )
			->checkbox('profile_email_id', __( 'Email', 'cyberchimps_core' ), '')
			->text('profile_email_id_url', __( 'Email URL', 'cyberchimps_core' ), "" )
			->checkbox('profile_googlemaps', __( 'Google Map', 'cyberchimps_core' ), '' )
			->text('profile_googlemaps_url', __( 'Google Map URL', 'cyberchimps_core' ), "", array('std' => 'http://www.maps.google.com/' ) ) */
		->end();

	foreach ($meta_boxes as $meta_box) {
		$my_box = new RW_Meta_Box_Taxonomy($meta_box);
	}
}