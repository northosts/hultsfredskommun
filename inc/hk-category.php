<div id="primary">

	<div id="content" role="main">


		<header class="page-header">
			<?php 
				if( function_exists('displaySortOrderButtons') ){
					displaySortOrderButtons();
				} 
			?>
			
			

			<?php 
				if( function_exists('displayTagFilter') ){
					displayTagFilter();
				}
			?>
			
			<div id="viewmode">
				<a id="viewmode_summary" title="Listvisning" href="#">Sammanfattning</a>
				<a id="viewmode_titles" title="Rubrikvisning" href="#">Rubriker</a>
			</div>
			<div class="clear"></div>
		</header>
		
	<?php
		/**
		 * Default order in orderby no set
		 */
		$shownPosts = array();
		if ($_REQUEST["orderby"] == "") :
			$posts_per_page = get_option('posts_per_page');
			
			/* Get category id */
			$cat = get_query_var("cat");
			$tag = get_query_var("tag");
			$tag_array = array();
			if ($tag != "")
				$tag_array = split(",", $tag);

			if ( $cat != "" || $tag != "") :
				/* Get all sticky posts from this category */
				$sticky = get_option( 'sticky_posts' );
					
				if ( !empty($sticky) ) {
					/* Query sticky posts */
					$args = array( 'post__in' => $sticky, 'posts_per_page' => -1);
					if ($tag == "") {
						$args["post_type"] = array('post');
					}
					else {
						$args["post_type"] = array('post','attachment');
					}
					$args['post_status'] = 'publish';		
					
					if ( !empty($cat) ) {
						$args["category__and"] = $cat;
					}
					if ( !empty($tag_array) ) {
						$args["tag_slug__in"] = $tag_array;
					}
					query_posts( $args );
					if ( have_posts() ) : while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
						$shownPosts[] = get_the_ID();
					endwhile; endif;
				}
				wp_reset_query(); // Reset Query
				

				/* Get all NOT sticky posts from this category */

				$args = array( 'posts_per_page' => -1 );

					if ($tag == "") {
						$args["post_type"] = array('post');
					}
					else {
						$args["post_type"] = array('post','attachment');
					}
				$args['post_status'] = 'publish';		

				if ( !empty($sticky) || !empty($shownPosts)) {
					$args['post__not_in'] = array_merge($sticky,$shownPosts);
				}
				if ( !empty($cat) ) {
					$args["category__and"] = $cat;
				}
				if ( !empty($tag_array) ) {
					$args["tag_slug__in"] = $tag_array;
				}
				
				query_posts( $args );
				if ( have_posts() ) : while ( have_posts() ) : the_post();
					get_template_part( 'content', get_post_format() );
					$shownPosts[] = get_the_ID();
				endwhile; endif;
				wp_reset_query(); // Reset Query
				
				
				/* Get posts from children of this category */
				if ($cat != "") {
					$children =  hk_getChildrenIdArray($cat);
					if ( !empty($children) ) {
						/* Get all sticky posts children of this category */
						
						$no_top_space = (count($shownPosts) == 0)?" no-top-space":"";
						echo "<div class='more-from-heading" . $no_top_space ."'><span>Mer från underkategorier</span></div>";
						$args = array( 'category__in' => $children, 'posts_per_page' => -1 );
						if (!empty($sticky)) {
							if ($tag == "") {
								$args["post_type"] = array('post');
							}
							else {
								$args["post_type"] = array('post','attachment');
							}
							$args['post_status'] = 'publish';		
							$args['post__in'] = $sticky;
							if ( !empty($tag_array) ) {
								$args["tag_slug__in"] = $tag_array;
							}
							if (!empty($shownPosts)) {
								$args['post__not_in'] = $shownPosts;
							}
							query_posts( $args );
							if ( have_posts() ) : while ( have_posts() ) : the_post();
								get_template_part( 'content', get_post_format());
								$shownPosts[] = get_the_ID();
							endwhile; endif;
							wp_reset_query(); // Reset Query
						}
						
						/* Get all NOT sticky posts children of this category */
						$args = array( 'category__in' => $children);
						if ($tag == "") {
							$args['posts_per_page'] = $posts_per_page;
							$args["post_type"] = array('post');
						}
						else {
							$args["post_type"] = array('post','attachment');
							$args['posts_per_page'] = -1;
						}
						$args['post_status'] = 'publish';
						if ( !empty($sticky) || !empty($shownPosts)) {
							$args['post__not_in'] = array_merge($sticky,$shownPosts);
						}
						if ( !empty($tag_array) ) {
							$args["tag_slug__in"] = $tag_array;
						}
						query_posts( $args );
						if ( have_posts() ) : while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
							$shownPosts[] = get_the_ID();
						endwhile; endif;
						wp_reset_query(); // Reset Query
					}
				}
			endif;
			/****Default order END***/

		else :

			/* otherwise start standard Loop if orderby is set */ 
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
				$shownPosts[] = get_the_ID();
			endwhile;
		endif;

		if (empty($sticky)) {
			$allposts = $shownPosts;
		}
		else if (empty($shownPosts)) {
			$allposts = $sticky;
		}
		else if (empty($shownPosts) && empty($sticky)) {
			$allposts = array();
		}
		else {
			$allposts = array_merge($sticky,$shownPosts);
		}
		if (!empty($allposts))
			$allposts = implode(",",$allposts);
		else
			$allposts = "";
			
		echo "<span id='shownposts' class='hidden'>" . $allposts . "</span>";

		//hk_content_nav( 'nav-below' );
		

		/* if nothing found */
		if (empty($shownPosts)) {
			hk_nothing_found_navigation();
		} ?>

	</div><!-- #content -->

	<?php
	if (!empty($shownPosts)) {
		hk_category_help_navigation(); 
	}
	?>
	
</div><!-- #primary -->