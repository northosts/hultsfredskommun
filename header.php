<?php

/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 

global $default_settings, $wp_query;
/* get hk_options */
$hk_options = get_option('hk_theme');

//force the page to use http if not logged in
if ($_SERVER["SERVER_PORT"] == 443 && !is_user_logged_in()) {
    $redir = "Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header($redir);
    exit();
}
/* hide if single and not visible */
if (in_category($hk_options["hidden_cat"])) {
	header("HTTP/1.0 404 Not Found");
	//TODO print 404 error - include("404.php");?
	die("Inte synlig.");
}
 
/* get category_as_filter recursive setting */
$catvalue = "category_" . get_query_var("cat");
$default_settings['category_as_filter'] = ((get_field("category_as_filter", $catvalue)=="")?false:true);
$default_settings['category_show_children'] = ((get_field("category_show_children", $catvalue)=="")?false:true);

if ($hk_options["lang"] == "") {
	$lang = 'lang="sv-SE"';
}
else {
	$lang = 'lang="' . $hk_options["lang"] . '"';
}


//print_r($wp_query);

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php echo $lang; //language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php echo $lang; //language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php echo $lang; //language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php echo $lang; //language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if (!is_single() && $hk_options["meta_description"] != "") {
	$meta_description = $hk_options["meta_description"];
} else if (is_single() && get_the_ID() > 0) {
	$meta_description = substr( strip_tags(get_post_field('post_content', get_the_ID())), 0, 200);
} 
if ($meta_description != "") :?>
<meta name="description" content="<?php echo $meta_description; ?>" />
<?php endif; ?>
<?php /* SET VIEWPORT */ ?>
<meta name="viewport" content="width=device-width" />

<title><?php

	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if ( $hk_options["favicon_image32"] != "" ) : ?>
	<link rel="icon" href="<?php echo $hk_options["favicon_image32"]; ?>" sizes="32x32" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image64"] != "" ) : ?>
	<link rel="icon" href="<?php echo $hk_options["favicon_image64"]; ?>" sizes="64x64" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image128"] != "" ) : ?>
	<link rel="icon" href="<?php echo $hk_options["favicon_image128"]; ?>" sizes="128x128" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image256"] != "" ) : ?>
	<link rel="icon" href="<?php echo $hk_options["favicon_image256"]; ?>" sizes="256x256" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image152"] != "" ) : ?>
	<link rel="apple-touch-icon" href="<?php echo $hk_options["favicon_image152"]; ?>" sizes="152x152" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image144"] != "" ) : ?>
	<link rel="apple-touch-icon" href="<?php echo $hk_options["favicon_image144"]; ?>" sizes="144x144" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image120"] != "" ) : ?>
	<link rel="apple-touch-icon" href="<?php echo $hk_options["favicon_image120"]; ?>" sizes="120x120" type="image/png">
<?php endif; ?>
<?php if ( $hk_options["favicon_image114"] != "" ) : ?>
	<link rel="apple-touch-icon" href="<?php echo $hk_options["favicon_image114"]; ?>" sizes="114x114" type="image/png">
<?php endif; ?>

<?php if ( $hk_options["favicon_image64"] != "" ) : ?>
	<!--[if IE]><link rel="shortcut icon" href="<?php echo $hk_options["favicon_image64"]; ?>"><![endif]-->
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ($_REQUEST["localstyle"] != "") : ?>
<link href="http://localhost/<?php echo $_REQUEST["localstyle"]; ?>" rel="stylesheet">
<?php endif; ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() . "/style-lt-ie9.css"; ?>" />
<![endif]-->
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );


	/* Some style generated by settings in hk-options-page */
	if ($hk_options["top_image"]) :?>
	<style type="text/css">
		#page {
			background-image: url('<?php echo $hk_options["top_image"]; ?>');
			background-repeat: no-repeat;
			background-position: top center;
		}
		#branding {
			background-color: transparent;
		}
		#main {
			background-color: white;
		}
	</style><?php
	endif;

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	 
	/* option to be able to add scipts or other from setting */ 
	echo $hk_options['in_head_section'];

	/* wp_head last in <head> */
	wp_head();
?>
</head>
<?php
$firstpageClass =(is_sub_category_firstpage() && get_query_var("tag") == "") ? "home":"";
$printpageClass = ($_REQUEST["print"] == 1) ? "print":"";
$menuversion = (empty($default_settings["new_mobile_menu"]))?"old":"new";//"new"; //new or old
$hide_leftmenu_class = ($hk_options['hide_leftmenu']) ? "hide-left-menu":"";
$dynamic_post_load_class = ($hk_options['use_dynamic_posts_load_in_category'] == 1) ? "hk-js-dynamic-posts-load  dynamic-posts-load":"no-dynamic-posts-load";
$category_as_filter_class = (!empty($default_settings["category_as_filter"]) && $default_settings["category_as_filter"] == 1) ? "hk-js-category-filter  category-filter":"no-category-filter";
$category_show_children_class = (!empty($default_settings["category_show_children"]) && $default_settings["category_show_children"] == 1) ? "hk-js-category-show-children  category-show-children":"no-category-show-children";

?>
<body <?php body_class($category_show_children_class . " " . $category_as_filter_class . " " . $dynamic_post_load_class . " " . $firstpageClass . " " . $printpageClass . " " . $printpageClass . " " . $menuversion . "-menu " . $hide_leftmenu_class ); ?>>
<?php echo $hk_options['in_topbody_section']; ?>
<div id="version-2" style="display:none; visibility:hidden"></div>
<div id="responsive-info"></div>
<div id="page" class="hfeed">
	<?php
		if ($menuversion == "new") {
			require( get_template_directory() . '/inc/hk-header.php'); 
		}
		else {
			require( get_template_directory() . '/inc/hk-old-header.php'); 		
		}?>

	<div class="main hk-quick"><div class="main-wrapper">
		<?php if (!is_sub_category_firstpage()) { echo hk_view_quick_links(); } ?>
	</div></div>
	<div id="main" class="main">
	<div class="main-wrapper">
