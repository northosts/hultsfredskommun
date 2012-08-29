<?php
/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function hk_widgets_init() {

	register_sidebar( array(
		'name' => 'Bildspelsyta',
		'id' => 'slideshow-content',
		'description' => 'Bildspelsyta p&aring; alla sidor',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Startsidans toppinneh&aring;ll',
		'id' => 'firstpage-top-content',
		'description' => 'Inneh&aring;ll h&ouml;gst upp p&aring; startsidan',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => 'Startsidans inneh&aring;ll',
		'id' => 'firstpage-content',
		'description' => 'Inneh&aring;ll under sticky-posts p&aring; startsidan',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => 'Startsidans sidof&auml;lt',
		'id' => 'firstpage-sidebar',
		'description' => 'Startsidans sidof&auml;lt',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Startsidans v&auml;nstra sidof&auml;lt',
		'id' => 'firstpage-secondary-sidebar',
		'description' => 'Startsidans v&auml;nstra sidof&auml;lt',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Sidonavigering',
		'id' => 'nav-sidebar',
		'description' => 'Inneh&aring;llsidans navigeringsyta',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => 'Sidof&auml;lt',
		'id' => 'sidebar',
		'description' => 'Inneh&aring;llsidans sidof&auml;lt',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => "Sidfot",
		'id' => 'footer-sidebar',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'hk_widgets_init' );
?>