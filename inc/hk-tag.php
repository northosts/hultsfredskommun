
	<div id="breadcrumb" class="breadcrumb"><?php hk_breadcrumb(); ?></div>

	<div id="primary" class="primary">

	<div id="content" class="tag-listing" role="main">

	<?php
		/**
		 * Default order in orderby no set
		 */
		$shownPosts = array();
		if ($tag != "") : 
			if ($cat != "") {
				$children =  get_categories(array('child_of' => $cat, 'hide_empty' => true));
			}
			else {
				$children =  get_categories(array('hide_empty' => true));
			}
			
			
			$args = array( 'posts_per_page' => -1,
			'ignore_sticky_posts' => 1);
			if ($cat != "")
				$args["category__and"] = array($cat);
			if ($tag != "")
				$args["tag_slug__in"] = split(",",$tag);
				
			echo "<h1>Inneh&aring;ll fr&aring;n " . $tag . " i kategorin " . single_tag_title("",false) . "</h1>";
			
			query_posts( $args );
			
			if ( have_posts() ) : ?>
				<ul>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'single-line' ); ?>
					<?php $shownPosts[] = get_the_ID(); ?>
				<?php endwhile; ?>
				</ul>
			<?php endif; ?>
			<span class='hidden debug'>all from this category<br><?php print_r($args); ?></span>

			<?php wp_reset_query(); // Reset Query ?>
			

			<?php // get category child posts
			foreach ($children as $childcat) {
				//echo $tag . "<br>";
				$args = array( 'posts_per_page' => -1,
				'ignore_sticky_posts' => 1);
				if ($childcat->cat_ID != "")
					$args["category__and"] = array($childcat->cat_ID);
				if ($tag != "")
					$args["tag_slug__in"] = split(",",$tag);
				
				query_posts( $args );
				//print_r($args);
				if ( have_posts() ) : ?>
				<h2><?php echo $childcat->name; ?></h2><ul>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'single-line' ); ?>
					<?php $shownPosts[] = get_the_ID(); ?>
				<?php endwhile; ?>
				</ul>
				<?php endif; ?>
				<span class='hidden debug'>all from this category<br><?php print_r($args); ?></span>

				<?php wp_reset_query(); // Reset Query

			}
			
			
		endif;
		
		
		
		/* helper array to know which posts is shown if loading more dynamically */
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

		/* help text if nothing is found */
		if (empty($shownPosts)) {
			hk_empty_navigation();
		} ?>

	</div><!-- #content -->

	
	
</div><!-- #primary -->