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

global $default_settings;
if (is_single() && in_category($default_settings["hidden_cat"])) {
	header("HTTP/1.0 404 Not Found");
	//TODO print 404 error - include("404.php");?
	die("Inte synlig.");
}

// redirect to first menu item in 'primary'-menu 
// if on startpage, there are a 'primary' menu set and more than one top menu level
$menu_name = 'primary';
if ( is_home() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) && $default_settings["num_levels_in_menu"] > 1 ) {
	if ($default_settings["startpage_cat"] != "" && $default_settings["startpage_cat"] != "0") {
		header("Location: " . get_category_link($default_settings["startpage_cat"]));
	}
	else {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items( $menu );
		header("Location: " . $menu_items[0]->url);
	}
}

// set current menuselection in session to next time



?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/* get hk_options */
	$hk_options = get_option('hk_theme');


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
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
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
	wp_head();
?>
</head>

<body <?php body_class((is_sub_category_firstpage()) ? "home":""); ?>>
<div id="responsive-info"></div>
<div id="page" class="hfeed">

	<header id="branding" role="banner">
		<?php /* IMPORTANT DYNAMIC TOP WIDGET CONTENT */ ?>	
		<?php dynamic_sidebar('important-top-content'); ?>
		
		<?php if(!$default_settings['allow_cookies'] && $hk_options["cookie_accept_enable"] == "1") : ?>
			<div class="important-widget"><div class="textwidget"><?php echo $hk_options["cookie_text"]; ?>
			<?php if ($hk_options["cookie_link"] != "") : ?>
			<a href="?cookies=true">Forts&auml;tt</a> <a href="<?php echo $hk_options["cookie_link"]; ?>">Mer information</a>
			<?php endif; ?>
			</div></div>
		<?php endif; ?>

		<div id="topwrapper">
			<span id="logo"><a href="<?php echo site_url('/'); ?>"><img src="<?php echo $hk_options["logo_image"]; ?>" alt="<?php bloginfo( 'name' ); ?>" /></a></span>
			<hgroup>
				<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
				
			<?php 
				if (($locations = get_nav_menu_locations()) && isset( $locations['topmenu'] ) && $locations['topmenu'] > 0 ) {
					echo "<div id='topmenu'><aside><nav>";
					wp_nav_menu( array(
						'theme_location' => 'topmenu', 
						'container' 	=> '',							
						'items_wrap'	=> '<ul>%3$s</ul>',
						'depth' 		=> 1,
						'echo' 			=> true
					)); 
					echo "</nav></aside></div>";
				}
			?>
			<div id="searchnavigation" role="search">			
				<?php get_search_form(); ?>
			</div>
			<div class="clear"></div>
		</div>		
		<nav id="menu" role="navigation">
			<a class="dropdown-menu">Meny</a>
			<?php 
				wp_nav_menu( array(
					'theme_location'	=> 'primary', 
					'container' 		=> '',							
					'items_wrap' 		=> '<ul class="main-menu">%3$s</ul>',
					'before' 			=> '',
					'after'				=> '',
					'depth' 			=> $default_settings['num_levels_in_menu'],
					'echo' 				=> true
				)); 
			?>
			<div class="clear"></div>
		</nav><!-- #access -->
	</header><!-- #branding -->

	<div id="main">
		<div id="breadcrumb"><?php hk_breadcrumb(); ?></div>
