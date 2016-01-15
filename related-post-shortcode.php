<?php

/*
  Plugin Name: Related Post Shortcode
  Plugin URI: http://www.enguerranweiss.fr
  Description: Insert a related article section in your Wordpress content with a new button on TinyMCE editor
  Version: 1.0
  Author: Enguerran Weiss
  Author URI: http://www.enguerranweiss.fr
 */


/* -----------------------------------------------------------------------------
 *  Init functions
  ---------------------------------------------------------------------------- */

add_action('admin_init', 'related_post_shortcode_init');
add_action( 'admin_head', 'related_post_shortcode_button' );
add_shortcode('related-post', 'related_post_shortcode');
add_action( 'wp_ajax_related_post_shortcode_getPostsIds', 'related_post_shortcode_getPostsIds' );
add_action( 'wp_ajax_nopriv_related_post_shortcode_getPostsIds', 'related_post_shortcode_getPostsIds' );
add_action( 'wp_ajax_related_post_shortcode_getPluginUrl', 'related_post_shortcode_getPluginUrl' );
add_action( 'wp_ajax_nopriv_related_post_shortcode_getPluginUrl', 'related_post_shortcode_getPluginUrl' );
add_action( 'wp_ajax_related_post_shortcode_getTransFields', 'related_post_shortcode_getTransFields' );
add_action( 'wp_ajax_nopriv_related_post_shortcode_getTransFields', 'related_post_shortcode_getTransFields' );

add_action( 'wp_enqueue_scripts', 'related_post_shortcode_style' );

// related_post_shortcode register TinyMCE button


function related_post_shortcode_init() {

  load_plugin_textdomain('related-post-shortcode', false, basename( dirname( __FILE__ ) ) . '/i18n' );
}

function related_post_shortcode_button() {
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
	    add_filter( 'mce_buttons', 'related_post_shortcode_register_button' );
	    add_filter( "mce_external_plugins", "related_post_shortcode_add_button" );
	}
}

function related_post_shortcode_add_button( $plugin_array ) {
    $plugin_array['related_post_shortcode'] = plugins_url('/related-post-shortcode.js', __FILE__);
    return $plugin_array;
}

function related_post_shortcode_register_button( $buttons ) {
    array_push( $buttons, 'related_post_shortcode_button' );
    return $buttons;
}


// related_post_shortcode

function related_post_shortcode_style() {
	wp_enqueue_style( 'related_post_shortcode_style', plugins_url('/related-post-shortcode.css', __FILE__) );
}


function related_post_shortcode( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'id' => 0,
	), $atts );
	if($a['id'] === 0){
		$categories = get_the_category();
		$category_id = $categories[0]->cat_ID;
		$posts = query_posts(array(
			'showposts' => 1,
			'orderby' => 'rand',
			'cat' => $category_id,
			'date_query'    => array(
		        'column'  => 'post_date',
		        'after'   => '- 90 days'
		    )
		));
		$postID = $posts[0]->ID;
	}
	else {
		$postID = $a['id'];
	}
	$post = get_post(intval($postID));
  $post_trim = preg_replace('/((\w+\W*){16}(\w+))(.*)/', '${1}', $post->post_content);

	$excerpt = strip_tags($post_trim).'...';


	return '
		<div class="rps-container" >

			<a class="rps-thumb" href="'.get_permalink($postID).'" >'.get_the_post_thumbnail($postID,'thumbnail').'</a>
			<div class="rps-desc">
				<span class="rps-container-title">'.__('You may also like','related-post-shortcode').'</span>
				<a href="'.get_permalink($postID).'" class="rps-title">'.get_the_title( $postID ).'</a>
        <div class="rps-excerpt">'.$excerpt.'</div>

			</div>
		</div>
	';
}
function related_post_shortcode_getPluginUrl() {
   $url = plugins_url('', __FILE__);
	echo $url;
	die();
}
function related_post_shortcode_getTransFields() {
   $trans = new stdClass();
	 $trans->btnTitle = __('Insert related article','related-post-shortcode');
   $trans->popupTitle = __('Which post do you want to insert?','related-post-shortcode');
   $trans->popupNote = __('Click on a post to select it or<br>click the button below the select it randomly.','related-post-shortcode');
	 $trans->popupFilter = __('Post filter: ','related-post-shortcode');
	 $trans->popupFilterPlaceholder = __('Start typing to filter the results','related-post-shortcode');
	 $trans->popupList = __('List of all posts: ','related-post-shortcode');
	 $trans->popupOr = __('Or','related-post-shortcode');
	 $trans->popupBtn = __('Choose a post randomly in the same category','related-post-shortcode');
	echo json_encode($trans);
	die();
}
function related_post_shortcode_getPostsIds() {
   $posts = query_posts(array(
			'showposts' => -1
		));
   $filtered = array();
   foreach ($posts as &$post) {
   		$newPost = new stdClass();
	    $newPost->text = $post->post_title;
	    $newPost->value = $post->ID;
		 $filtered[] = $newPost;
	}

   $filtered = json_encode($filtered);
	echo $filtered;
	die();
}
