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
$subfirstpageClass = (is_sub_category_firstpage()) ? "subhome":"";
?>
<body <?php body_class($firstpageClass . " " . $printpageClass . " " . $printpageClass ); ?>>
<?php echo $hk_options['in_topbody_section']; ?>
<div id="version-2" style="display:none; visibility:hidden"></div>
<div id="responsive-info"></div>
<div id="page" class="hfeed">
	<header id="branding" class="branding" role="banner">
		<?php /* IMPORTANT DYNAMIC TOP WIDGET CONTENT */ ?>	
		<?php dynamic_sidebar('important-top-content'); ?>

		<?php /* top right navigation */ ?>
		<?php 
			if ( ((($locations = get_nav_menu_locations()) && isset( $locations['topmenu'] ) && $locations['topmenu'] > 0) || 
				(!empty($hk_options["pre_topmenu_html"]) && $hk_options["pre_topmenu_html"] != "") || 
				(!empty($hk_options["post_topmenu_html"]) && $hk_options["post_topmenu_html"] != "") ) ) : ?>
				<aside id='topmenu' class='top-menu-wrapper'><div class='content--center'>
					
					<?php if ( (($locations = get_nav_menu_locations()) && isset( $locations['topmenu'] ) && $locations['topmenu'] > 0 ) || 
							 (!empty($hk_options["translate_url"]) && $hk_options["translate_url"] != "") || 
							 (!empty($hk_options["readspeaker_id"]) && $hk_options["readspeaker_id"] != "") ) : ?>
						<nav>
						
						<ul class='top-menu'>
						<?php /* pre html if any in options */ ?>
						<?php if (!empty($hk_options["pre_topmenu_html"]) && $hk_options["pre_topmenu_html"] != "") : ?>
							<li class="pre-top-menu"><?php echo $hk_options["pre_topmenu_html"]; ?></li>
						<?php endif; ?>
						<?php
						if (($locations = get_nav_menu_locations()) && isset( $locations['topmenu'] ) && $locations['topmenu'] > 0 ) :
						wp_nav_menu( array(
							'theme_location' => 'topmenu', 
							'container' 	=> '',
							'items_wrap'	=> '%3$s',
							'depth' 		=> 2,
							'echo' 			=> true
						)); 
						endif;
						 ?>
						<?php /* post html if any in options */ ?>
						<?php if (!empty($hk_options["post_topmenu_html"]) && $hk_options["post_topmenu_html"] != "") : ?>
							<li class="post-top-menu"><?php echo $hk_options["post_topmenu_html"]; ?></li>
						<?php endif; ?>
						</ul></nav>
					<?php endif; ?>
						
				</div></aside>
			<?php endif; ?>
		<div id="topwrapper" class="content--center"><div class="top-wrapper">
			<span id="logo" class="logo"><a href="<?php echo site_url('/'); ?>"><img src="<?php echo $hk_options["logo_image"]; ?>" alt="<?php bloginfo( 'name' ); ?>" title="<?php bloginfo( 'name' ); ?>" /></a></span>
			<div class="site-title">
				<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>
				
			<?php if (!is_search() && get_query_var("tag") == "") : ?>
			<div class="responsive-menu">
				<a class="js-show-main-menu" href="#"><span class="menu-icon"></span></a>
				<?php /* <a class="js-show-search" href="#"><span class="search-icon"></span></a> */ ?>
			</div>
			<?php endif; ?>

			<?php /* search form*/ ?>
			<div id="searchnavigation" class="searchnavigation" role="search">			
				<?php get_search_form(); ?>
			</div>

			<?php if (($hk_options["logo2_image"] != "") || ($hk_options["logo3_image"] != "") || (!empty($hk_options["right_logo_html"]) && $hk_options["right_logo_html"] != "")) : ?>
			<div id="logo2" class="logo2">
				<?php /* right logo html if any in options */ ?>
				<?php if (!empty($hk_options["right_logo_html"]) && $hk_options["right_logo_html"] != "") : ?>
					<?php echo $hk_options["right_logo_html"]; ?>
				<?php endif; ?>
				<?php if ($hk_options["logo2_image"] != "") : ?>
				<a target="_blank" href="<?php echo $hk_options["logo2_link"]; ?>" title="<?php echo $hk_options["logo2_descr"]; ?>"><img src="<?php echo $hk_options["logo2_image"]; ?>" alt="<?php echo $hk_options["logo2_descr"]; ?>" title="<?php echo $hk_options["logo2_descr"]; ?>" /></a>
				<?php endif; ?>
				<?php if ($hk_options["logo3_image"] != "") : ?>
				<a target="_blank" href="<?php echo $hk_options["logo3_link"]; ?>" title="<?php echo $hk_options["logo3_descr"]; ?>"><img src="<?php echo $hk_options["logo3_image"]; ?>" alt="<?php echo $hk_options["logo3_descr"]; ?>" title="<?php echo $hk_options["logo3_descr"]; ?>"/></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

		</div>
		<?php if ($hk_options["gcse_id"] != "" && $hk_options["gcse_ajax"] != "") { ?>
		<?php $column_class = " no-hook"; if($hk_options["gcse_enable_kontakter_search"] != "" || has_action('hk_pre_ajax_search') || has_action('hk_post_ajax_search')) { $column_class = " has-hook"; } ?>
		<div class="hk-gcse-ajax-searchresults-wrapper">
			<div class="hk-gcse-ajax-searchresults<?php echo $column_class; ?>">
				<div class="hk-gcse-hook-results">
					<div class="islet">Väntar på sökresultat...<span style="display:inline-block" class="spinner"></span></div>
				</div>
				<div class="hk-gcse-googleresults">
					<div class="gcse-searchresults"><div class="islet">Väntar på sökresultat...<span style="display:inline-block" class="spinner"></span></div></div>
				</div>
			</div>
		</div>
		<?php }  ?>
		</div>
		<!--googleoff: all-->
		<?php if (!is_search()) : ?>
		
		<nav id="menu" class="menu-wrapper<?php echo "$responsive_class"; ?>" role="navigation">
			<?php
				if (!(($locations = get_nav_menu_locations()) && isset( $locations['primary'] ) && $locations['primary'] > 0 )) {
					echo "<div class='important-widget'>&nbsp;Du m&aring;ste s&auml;tta meny under <i>Utseende -> Menyer</i>.</div>";
				}
				hk_navmenu_navigation("primary", $cat, "primarymenu");
			?>
		</nav>
		<?php endif; // not is search ?>
	</header><!-- #branding -->

	<div id="main" class="main">
	<div class="main-wrapper">
		<?php echo hk_view_quick_links(); ?>
