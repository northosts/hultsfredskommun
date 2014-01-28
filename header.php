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


/* hide if single and not visible */
if (in_category($hk_options["hidden_cat"])) {
	header("HTTP/1.0 404 Not Found");
	//TODO print 404 error - include("404.php");?
	die("Inte synlig.");
}
 




//print_r($wp_query);

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
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />

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
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
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
	
	
	/* google analytics */
	if ($hk_options["google_analytics"] != "" && $default_settings['allow_google_analytics']) : ?>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';
	_gaq.push(['_require', 'inpage_linkid', pluginUrl]);
	_gaq.push(['_setAccount', '<?php echo $hk_options["google_analytics"]; ?>']); 
	_gaq.push(['_setDomainName', '<?php echo $hk_options['google_analytics_domain'];  ?>']);
	_gaq.push(['_setAllowLinker', true]);
	_gaq.push(['_trackPageview']);


	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	<?php endif;

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
<div id="version-2" style="display:none; visibility:hidden"></div>
<div id="responsive-info"></div>
<div id="page" class="hfeed">
	<header id="branding" class="branding" role="banner">
		<?php /* IMPORTANT DYNAMIC TOP WIDGET CONTENT */ ?>	
		<?php dynamic_sidebar('important-top-content'); ?>
		<?php if(!($_REQUEST["cookies"] == "true" || $default_settings['allow_cookies'])) : ?>
		<?php //if(!$default_settings['allow_cookies'] && $hk_options["cookie_accept_enable"] == "1") : ?>
			<div class="cookieinformation"><div class="textwidget"><?php echo $hk_options["cookie_text"]; ?>
			<?php $cookie_button_text = "Forts&auml;tt"; 
			if ($hk_options["cookie_button_text"] != "") { $cookie_button_text = $hk_options["cookie_button_text"];  } ?>
			<a href="?cookies=true"><?php echo $cookie_button_text; ?></a>
			<?php if ($hk_options["cookie_link"] != "") : ?>
			 <a href="<?php echo $hk_options["cookie_link"]; ?>">Mer information</a>
			<?php endif; ?>
			</div></div>
		<?php endif; ?>

		<?php /* top right navigation */ ?>
		<?php 
		
			if ((($locations = get_nav_menu_locations()) && isset( $locations['topmenu'] ) && $locations['topmenu'] > 0) || 
				(!empty($hk_options["pre_topmenu_html"]) && $hk_options["pre_topmenu_html"] != "") || 
				(!empty($hk_options["post_topmenu_html"]) && $hk_options["post_topmenu_html"] != "") ) : ?>
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
			<span id="logo" class="logo"><a href="<?php echo site_url('/'); ?>"><img src="<?php echo $hk_options["logo_image"]; ?>" alt="<?php bloginfo( 'name' ); ?>" /></a></span>
			<hgroup class="site-title">
				<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
				

			<div class="responsive-menu">
				<a class="js-show-main-menu" href="#"><span class="menu-icon"></span></a>
				<a class="js-show-search" href="#"><span class="search-icon"></span></a>
			</div>

			<?php /* search form*/ ?>
			<div id="searchnavigation" class="searchnavigation" role="search">			
				<?php get_search_form(); ?>
			</div>
			<?php if ($hk_options["gcse_id"] != "") : ?>
			<div class="hk-gcse-wrapper">
				<div class="hk-gcse-border">
					<div class="hk-gcse-results">
						<gcse:searchresults></gcse:searchresults>
					</div>
					<div class="hk-gcse-hooks"></div>
				</div>
			</div>
			<?php endif; ?>



			

			<?php if (($hk_options["logo2_image"] != "") || ($hk_options["logo3_image"] != "") || (!empty($hk_options["right_logo_html"]) && $hk_options["right_logo_html"] != "")) : ?>
			<div id="logo2" class="logo2">
				<?php /* right logo html if any in options */ ?>
				<?php if (!empty($hk_options["right_logo_html"]) && $hk_options["right_logo_html"] != "") : ?>
					<?php echo $hk_options["right_logo_html"]; ?>
				<?php endif; ?>
				<?php if ($hk_options["logo2_image"] != "") : ?>
				<a target="_blank" href="<?php echo $hk_options["logo2_link"]; ?>" title="<?php echo $hk_options["logo2_descr"]; ?>"><img src="<?php echo $hk_options["logo2_image"]; ?>" alt="<?php echo $hk_options["logo2_descr"]; ?>" /></a>
				<?php endif; ?>
				<?php if ($hk_options["logo3_image"] != "") : ?>
				<a target="_blank" href="<?php echo $hk_options["logo3_link"]; ?>" title="<?php echo $hk_options["logo3_descr"]; ?>"><img src="<?php echo $hk_options["logo3_image"]; ?>" alt="<?php echo $hk_options["logo3_descr"]; ?>" /></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>

		</div></div>			
		<nav id="menu" class="menu-wrapper" role="navigation">
			<?php 
				$menu_name = 'primary';
				// get nav_menu_parent id
				if (is_single()) {
					$category_hierarchy = hk_get_parent_categories_from_id(get_the_ID(), $menu_name);
				} else if ($cat != "") {
					$category_hierarchy = hk_get_parent_categories_from_cat($cat);	
				}

				$nav_menu_top_parent = hk_getNavMenuId($category_hierarchy[0], $menu_name);
				$nav_menu_sub_parent = hk_getNavMenuId($category_hierarchy[1], $menu_name);
				$top_parent = $category_hierarchy[0];
				$top_parent = $category_hierarchy[0];
				$sub_parent = $category_hierarchy[1];
				$category = $category_hierarchy[2];
			
				if (!(($locations = get_nav_menu_locations()) && isset( $locations[$menu_name] ) && $locations[$menu_name] > 0 )) {
					echo "<div class='important-widget'>&nbsp;Du m&aring;ste s&auml;tta huvudmeny under <i>Utseende -> Menyer</i>.</div>";
				}

				
				$topwalker = new hk_topmenu_walker_nav_menu();
				$args = array(
					'theme_location'	=> $menu_name, 
					'container' 		=> '',
					'items_wrap' 		=> '%3$s',
					'before' 			=> '',
					'after'				=> '',
					'depth' 			=> 1, //$default_settings['num_levels_in_menu'],
					'echo' 				=> true,
					'walker'			=> $topwalker
				);
				if ($top_parent > 0) {
					$args["current_category"] = $top_parent;
				} ?>
				<?php

				echo "<ul class='main-menu'>";
				echo "<li class='small-words'>".hk_getSmallWords($hk_options["smallwords"])."</li>";
				wp_nav_menu( $args ); 
				if ( is_active_sidebar( 'right-main-menu-item-sidebar' ) ) { 
					dynamic_sidebar( 'right-main-menu-item-sidebar' ); 
				}

				echo "</ul>";
				
				echo "<div class='responsive-sub-menu'>";
				$parent = hk_getParent($cat);
				
				$top_name = get_cat_name($top_parent);
				$sub_name = get_cat_name($sub_parent);
				$cat_name = get_cat_name($category);
				if ($sub_name == $cat_name)
					$cat_name = "";
				if ($top_name == $sub_name)
					$sub_name = "";
					
				if ($cat_name != "")
					$cat_title = $cat_name;
				if ($sub_name != "")
					$sub_title = $sub_name;
				else if ($top_name != "")
					$sub_title = $top_name;
				else if (get_query_var("s") != "")
					$sub_title = "Du s&ouml;kte p&aring; " . get_query_var("s");
				else 
					$sub_title = "";
					
				if ($sub_parent > 0 && $parent > 0) { 
					echo "<a class='menu-up' href='" . get_category_link($parent) . "'><span class='menu-icon up'></span></a>";
				}

				echo "<a class='js-show-main-sub-menu menu'><span class='menu-icon'></span>";
				echo "<span class='title'>Meny</span>";//<span class='dropdown-icon'></span>";

				echo "</a>";
				echo "</div>";
				
				if ($nav_menu_sub_parent > 0) {
					
					if ($default_settings['num_levels_in_menu'] > 1) {
						echo "<ul class='main-sub-menu'>";
						$submenu = new hk_submenu_walker_nav_menu();
						$args = array(
							'theme_location'	=> $menu_name, 
							'container' 		=> '',							
							'items_wrap' 		=> '%3$s',
							'before' 			=> '',
							'after'				=> '',
							'depth' 			=> $default_settings['num_levels_in_menu'],
							'echo' 				=> true,
							'walker'			=> $submenu,
							'nav_menu_parent'	=> $nav_menu_top_parent
						);
						if ($sub_parent > 0) {
							$args["current_category"] = $sub_parent;
						}
						wp_nav_menu( $args ); 
						if ( is_active_sidebar( 'right-main-sub-menu-item-sidebar' ) ) { 
							dynamic_sidebar( 'right-main-sub-menu-item-sidebar' ); 
						}
						echo "</ul>";
					}
				}
				else {
					echo "<ul class='main-sub-menu'><li class='menu-item one-whole'><a>&nbsp;</a></li></ul>";
				}

			?>
		</nav>
	</header><!-- #branding -->

	<div id="main" class="main">
	<div class="main-wrapper">
