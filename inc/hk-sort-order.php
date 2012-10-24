<?php
	/**
	 * Sort-order function:
	 * adds function for displaying sort-order buttons
	 * adds filter for sort-order
	 * orderby == popular depends on the plugin WP-PostViews by Lester 'GaMerZ' Chan 
	 */

	function displaySortOrderButtons() { ?>
		<div id="sort-order"><ul>
			<?php
				//init
				$tags = get_query_var("tag");
				$search = get_query_var("s");
				
				if($tags != ''){ $tags = "&tag=".$tags; }
				if($search != ''){ $search = "&s=".$search; }
			?>
			<?php 
				$orderby = $_REQUEST["orderby"];
				if ($orderby == "") {
					if (function_exists( 'views_orderby' ))
						$orderby = "popular";
					else
						$orderby = "latest";
				}
			?>
			
			<?php if ($_REQUEST["orderby"] == "alpha") { ?>
				<li class='current-menu-item'><a href="?orderby=alpha<?php echo $tags.$search; ?>">A - &Ouml;</a></li>
			<?php } else { ?>
				<li><a href="?orderby=<?php echo $tags.$search; ?>">A - &Ouml;</a></li>
			<?php } ?>
			<li <?php echo ($_REQUEST["orderby"] == "alpha")?"class='current-menu-item'":""; ?>><a href="?orderby=alpha<?php echo $tags.$search; ?>">A - &Ouml;</a></li>
			<li <?php echo ($_REQUEST["orderby"] == "alpha_desc")?"class='current-menu-item'":""; ?>><a href="?orderby=alpha_desc<?php echo $tags.$search; ?>">&Ouml; - A</a></li>
			<li <?php echo ($_REQUEST["orderby"] == "latest")?"class='current-menu-item'":""; ?>><a href="?orderby=latest<?php echo $tags.$search; ?>">Senast</a></li>
			<li <?php echo ($_REQUEST["orderby"] == "oldest")?"class='current-menu-item'":""; ?>><a href="?orderby=oldest<?php echo $tags.$search; ?>">&Auml;ldst</a></li>
			<?php if( function_exists('views_orderby') ) : ?>
				<li <?php echo ($_REQUEST["orderby"] == "popular")?"class='current-menu-item'":""; ?>><a href="?orderby=popular<?php echo $tags.$search; ?>">Popul&auml;rast</a></li>
			<?php endif; ?>
			<?php if ($_REQUEST["orderby"] != "") : ?>
				<li><a href="?orderby=<?php echo $tags.$search; ?>">Standard</a></li>
			<?php endif; ?>
		</ul></div>

	<?php }
	
	// code from WP-PostViews process_postviews to count views when viewing posts dynamically
	function hk_process_postviews() {
		global $user_ID, $post;
		if(is_int($post)) {
			$post = get_post($post);
		}
		if(!wp_is_post_revision($post)) {
			//REMOVED FROM ORIGINAL if(is_single() || is_page()) {
				$id = intval($post->ID);
				$views_options = get_option('views_options');
				$post_views = get_post_custom($id);
				$post_views = intval($post_views['views'][0]);
				$should_count = false;
				switch(intval($views_options['count'])) {
					case 0:
						$should_count = true;
						break;
					case 1:
						if(empty($_COOKIE[USER_COOKIE]) && intval($user_ID) == 0) {
							$should_count = true;
						}
						break;
					case 2:
						if(intval($user_ID) > 0) {
							$should_count = true;
						}
						break;
				}
				if(intval($views_options['exclude_bots']) == 1) {
					$bots = array('Google Bot' => 'googlebot', 'Google Bot' => 'google', 'MSN' => 'msnbot', 'Alex' => 'ia_archiver', 'Lycos' => 'lycos', 'Ask Jeeves' => 'jeeves', 'Altavista' => 'scooter', 'AllTheWeb' => 'fast-webcrawler', 'Inktomi' => 'slurp@inktomi', 'Turnitin.com' => 'turnitinbot', 'Technorati' => 'technorati', 'Yahoo' => 'yahoo', 'Findexa' => 'findexa', 'NextLinks' => 'findlinks', 'Gais' => 'gaisbo', 'WiseNut' => 'zyborg', 'WhoisSource' => 'surveybot', 'Bloglines' => 'bloglines', 'BlogSearch' => 'blogsearch', 'PubSub' => 'pubsub', 'Syndic8' => 'syndic8', 'RadioUserland' => 'userland', 'Gigabot' => 'gigabot', 'Become.com' => 'become.com');
					$useragent = $_SERVER['HTTP_USER_AGENT'];
					foreach ($bots as $name => $lookfor) { 
						if (stristr($useragent, $lookfor) !== false) { 
							$should_count = false;
							break;
						} 
					}
				}
				if($should_count) {			
					if(defined('WP_CACHE') && WP_CACHE) {
						echo "\n".'<!-- Start Of Script Generated By WP-PostViews -->'."\n";
						wp_print_scripts('jquery');					
						echo '<script type="text/javascript">'."\n";
						echo '/* <![CDATA[ */'."\n";
						echo "jQuery.ajax({type:'GET',url:'".admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))."',data:'postviews_id=".$id."&action=postviews',cache:false});";
						echo '/* ]]> */'."\n";
						echo '</script>'."\n";						
						echo '<!-- End Of Script Generated By WP-PostViews -->'."\n";
					} else {
						if(!update_post_meta($id, 'views', ($post_views+1))) {
							add_post_meta($id, 'views', 1, true);
						}
					}
				}
			//REMOVED FROM ORIGINAL }
		}
	}



	function hk_FilterOrder ($order = '') {
		global $wpdb;

		if (isset($_REQUEST["orderby"]))
			$orderby = $_REQUEST["orderby"];
		else if( !function_exists( 'views_orderby' ))
			$orderby = "latest"; // alphabetical to be standard if no set

		if ($orderby == "latest") {
			// wordress blog standard
			$order = ' (' . $wpdb->posts . '.post_date ) DESC';
		}
		else if ($orderby == "oldest") {
			$order = ' (' . $wpdb->posts . '.post_date ) ASC';
		}
		else if ($orderby == "alpha") {
			$order = ' (' . $wpdb->posts . '.post_title ) ASC';
		}
		else if ($orderby == "alpha_desc") {
			$order = ' (' . $wpdb->posts . '.post_title ) DESC';
		}
		return $order;
	}
	if (!is_admin()) {
		add_filter ('posts_orderby', 'hk_FilterOrder');
	}
	// Sets sort-order most viewed as default or if orderby == popular and plugin WP-PostViews is loaded
	function hk_views_sorting($local_wp_query) {
		if(function_exists( 'views_orderby' )) {
			if ( !isset($_REQUEST["orderby"]) or (isset($_REQUEST["orderby"]) and $_REQUEST["orderby"] == 'popular') ) {
				add_filter('posts_fields', 'views_fields');
				add_filter('posts_join', 'views_join');
				add_filter('posts_where', 'views_where');
				add_filter('posts_orderby', 'views_orderby');
			} else {
				remove_filter('posts_fields', 'views_fields');
				remove_filter('posts_join', 'views_join');
				remove_filter('posts_where', 'views_where');
				remove_filter('posts_orderby', 'views_orderby');
			}
		}
	}
	if (!is_admin()) {
		add_action('pre_get_posts', 'hk_views_sorting');
	}
?>