	<?php 
		/*
		 * if not is sub start page 
		 */ 

		global $default_settings;
		
		if (!is_sub_category_firstpage()) : ?>
			<?php 
			if ($cat != "") : 
			
			
			
				/* check if there are posts to be hidden */
				$args = array(	'posts_per_page' => -1,
								'category__and' => array($cat),
								'meta_query' => array(
													array(
														'key' => 'hk_hide_from_category',
														'compare' => '=',
														'value' => '1'
													)
												)
						);

				$dyn_query = new WP_Query();
				$dyn_query->query($args);

				/* Start the hidden posts Loop */
				$hiddenarr = array();
				while ( $dyn_query->have_posts() ) : $dyn_query->the_post();
					$hiddenarr[] = get_the_ID();
				endwhile;

				// do category list query
				$args = array(	'paged' => $paged,
								'category__and' => array($cat),
								'post__not_in' => $hiddenarr,
								
								);
				$options = get_option("hk_theme");
				if ($_REQUEST["orderby"] == "" && get_query_var("cat") != "" && in_array(get_query_var("cat"), split(",",$options["order_by_date"])) ) {
					//$args['suppress_filters'] = 'true';
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';
				}
				else if ($_REQUEST["orderby"] == "latest") {
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';				
				}
				else if ($_REQUEST["orderby"] == "oldest") {
					$args['orderby'] = 'date';
					$args['order'] = 'ASC';				
				}
				else if (function_exists( 'views_orderby' )) {
					$args['meta_key'] = 'views'; 
					$args['orderby'] = 'meta_value_num';
					$args['order'] = 'DESC';	
				}
				else {
					$args['orderby'] = 'date';
					$args['order'] = 'DESC';				
				}

				query_posts( $args );

			endif; // end if cat is set
			
	?>
	<div id="breadcrumb" class="<?php echo ($wp_query->post_count <= 1 && $wp_query->max_num_pages == 1)?"one_article ":""; ?>breadcrumb"><?php hk_breadcrumb(); ?><?php hk_postcount() ?></div>

	
	<?php 
	if ($default_settings["show_quick_links_in_subsubcat"] > 0 && is_sub_sub_category_firstpage()) :
		echo hk_view_quick_links();
	endif; //endif show most viewed ?>

	<?php if ($default_settings["show_most_viewed_in_subsubcat"] > 0 && is_sub_sub_category_firstpage()) :
		echo hk_view_most_viewed_posts();
	endif; //endif show most viewed ?>

	<?php endif; // end if !is_sub_category_firstpage ?>

	<div id="primary" class="primary">
	<?php 
		/* display slideshow */
		$vars = array();
		$vars['thumbnail-size'] = 'wide-image';
		$vars['posts_per_page'] = '-1';

		echo hk_slideshow_generate_output($vars);
	?>
	<div id="content" role="main">

	<?php if (false): //REMOVED 20141223 ($wp_query->post_count > 1 || $wp_query->max_num_pages > 1) : ?>
		<header class="page-header">
			<ul class="num-posts">
				<?php
					if ($wp_query->max_num_pages > $paged && $wp_query->max_num_pages > 1) {
						$url = next_posts(0,false);
						$class = "";
					}
					else {
						$url = "";
						$class = "nolink";
					}
					echo "<li><a class='$class' href='$url'>Visar " . $wp_query->post_count;
					if ($wp_query->max_num_pages > 1) {
						echo " av " . $wp_query->found_posts;
					}
					if ($wp_query->post_count <= 1 && $wp_query->max_num_pages == 1) 
						echo " artikel";
					else
						echo " artiklar";
					if ($wp_query->max_num_pages > 1) {
						if ($paged == 0) { $p = 1; } else { $p = $paged; }
						echo " | Sida " . $p . " av " . $wp_query->max_num_pages . "";
					}
					echo "</a></li>";
				?>
			</ul>
				<?php //echo "<pre>"; print_r($wp_query); echo "</pre>"; ?>
			
			<ul class="category-tools">
				<?php $related_output = hk_related_output(true); 
				$taglist = displayTagFilter(false, "sub-menu", false); ?>
				<?php if ($default_settings["show_tags"] != 0 && $taglist != "") : ?>
				<li class="tag-menu cat-item<?php echo ($related_output == "")?" rounded":""; ?>">
					<a href="#">Visa bara<span class="dropdown-icon"></span></a>
					<?php echo $taglist; ?>
				</li>
				<?php endif; ?>
				<?php echo $related_output;	?>
			</ul>
			<ul class="view-tools">
				<li class="menu-item view-mode"> 
				<a class="viewmode_titles js-view-titles active" title="Listvisning" href="#"></a>
				<a class="viewmode_summary js-view-summary" title="Kompakt visning" href="#"></a>
				</li>
			</ul>
			<?php 
				if( function_exists('displaySortOrderButtons') ){
					displaySortOrderButtons();
				} 
				
			?>

		</header>
	<?php endif; // end hidden ?>
		
	<?php
		/**
		 * Default order in orderby no set
		 */
		if ($default_settings["hide_articles_in_subsubcat"] != 1 || !is_sub_sub_category_firstpage()) :

			$shownPosts = array();
			if ($cat != "") : 
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					//echo get_field('hk_hide_from_category'); 
					//echo "-";
					/*echo get_post_meta(get_the_ID(),'views',true); 
					if (is_sticky())
						echo "sticky";*/
					if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1)
						get_template_part( 'content', 'single' );
					else
						get_template_part( 'content', get_post_type() );
					$shownPosts[] = get_the_ID();
				endwhile; endif;
			endif;
			
			
			hk_content_nav( 'nav-below' );

			/* help text if nothing is found */
			if (empty($shownPosts)) {
				hk_empty_navigation();
			} 
		endif;
		?>

	</div><!-- #content -->

	
	
</div><!-- #primary -->