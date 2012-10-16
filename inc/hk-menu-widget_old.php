<?php

/** 
 * Description: Add Menu navigation widget
 *  */

/* WIDGET */
class HK_Menu_Widget extends WP_Widget {

        public function __construct() {
		parent::__construct(
	 		'hk_menu_widget', // Base ID
			'Hk_Menu_Widget', // Name
			array( 'description' => __( 'Menu Widget to display menus from relevant categories', 'text_domain' ), ) // Args
		);
	}

 	public function form( $instance ) {
	}

	public function update( $new_instance, $old_instance ) {
		return $instance;
	}

	public function widget( $args, $instance ) {
	    global $post;
	    extract( $args );
		$search = get_query_var("s");
		$cat = get_query_var("cat");
		$tags = get_query_var("tag");

		echo "<aside id='nav'><nav>";

		if ($cat != "") {
				
			if ($this->hk_countParents($cat) >= 2) {
				$args = array(
					'orderby'            => 'name',
					'order'              => 'ASC',
					'style'              => 'list',
					'hide_empty'         => 0,
					'use_desc_for_title' => 1,
					'child_of'           => $cat,
					'hierarchical'       => true,
					'title_li'           => '',
					'show_option_none'   => '',
					'echo'               => 1,
					'depth'              => 3,
					'taxonomy'           => 'category'
				);
			
				wp_list_categories( $args );
		
			}
		}
		/*
		if (is_single())
		{
			echo '<div id="selected">';
			$this->hk_single_page_menu();
			echo "</div>";
		}
		else {
			echo '<div id="selected">';
				$this->hk_selected();
			echo '</div>';
			echo '<div id="filters">';
				$this->hk_tag_filters();
			echo '</div>';
		}*/
		//echo "<div style='clear:both;'>&nbsp;</div>";
		echo "</nav></aside>";
	}


	function hk_single_page_menu() { ?>
		<div class='widget-title'>Sidans etiketter:</div>
		<ul id="selected_filter">
		<?php 
			$categories_list = get_the_category();
			if (!empty($categories_list)) : foreach ( $categories_list as $list):
				echo "<li class='link cat'><div class='icon'></div><a href='".get_category_link($list->term_id)."'>" . $list->name . "</a></li>";
			endforeach; endif; // End if categories

			$tags_list = get_the_terms(get_the_ID(),"post_tag");
			if (!empty($tags_list)) : foreach ( $tags_list as $list):
				echo "<li class='link tag'><div class='icon'></div><a href='".get_tag_link($list->term_id)."'>" . $list->name . "</a></li>";
			endforeach; endif; // End if tags
		 
			$tags_list = get_the_terms(get_the_ID(),"vem");
			if (!empty($tags_list)) : foreach ( $tags_list as $list):
				echo "<li class='link vem'><div class='icon'></div><a href='".get_term_link($list->slug,"vem")."'>" . $list->name . "</a></li>";
			endforeach; endif; // End if vem

			$tags_list = get_the_terms(get_the_ID(),"ort");
			if (!empty($tags_list)) : foreach ( $tags_list as $list):
				echo "<li class='link ort'><div class='icon'></div><a href='".get_term_link($list->slug,"ort")."'>" . $list->name . "</a></li>";
			endforeach; endif; // End if vem

		?>
		</ul>
	<?php 
	}



	/* echo the tags and categories that currently are selected */
	function hk_selected() {

		$search = get_query_var("s");
		$cat = get_query_var("cat");
		$tags = get_query_var("tag");
		$vem_tags = get_query_var("vem");
		$ort_tags = get_query_var("ort");
		
		
		// check if any tag or cat is set
		if ($cat != "" || $tags != "" || $ort_tags != "" || $vem_tags != "" || $search != "") :

			// javascript to be used for dynamic effects
			?>
			<script type="text/javascript">
				(function($) {
					$(document).ready(function(){
				
					});
				})(jQuery);
			</script>
			<div class='widget-title'>Du har valt:</div>
					
			<ul id="selected_filter">
			<?php 

			// initialize caturl
			if ($cat != "")
				$caturl = get_category_link( $value->parent );
			else
				$caturl = get_bloginfo("wpurl");

			$selected_array = array();

	    	// show selected search
	    	if ($search != "") {
				$taglink= "?tag=" . $tags;
				$vemlink= "&vem=" . $vem_tags;
				$ortlink= "&ort=" . $ort_tags;
				$selected_array[] = array("name" => "S&ouml;k: $search", "url" => $caturl . $taglink . $vemlink . $ortlink, "noselect" => "", "tag" => "search" );
			}

			// show selected categories
			if ($cat != "") {
				
				// get category parents
				$parents = $this->hk_getParents($cat);

				// init tag links
				$taglink= "?tag=" . $tags;
				$vemlink= "&vem=" . $vem_tags;
				$ortlink= "&ort=" . $ort_tags;
				
				// add category parents to selected_array set noselect if there are a selected child
				if (!empty($parents)) {
					$prev_parent = $parents[0]->parent;
					$parent_count = 1;

					foreach ($parents as $value) {
						if (count($parents) == $parent_count) {
							if ($value->parent != 0)
								$parent_link = get_category_link( $value->parent );
							else
								$parent_link = get_bloginfo("wpurl");

							$selected_array[] = array("name" => $value->name, "url" => $parent_link . $taglink . $vemlink . $ortlink, "noselect" => "", "tag" => "cat" );
							
						}
						else {
							$selected_array[] = array("name" => $value->name, "url" => "", "noselect" => "noselect", "tag" => "cat" );
						}
						$parent_count++;
					}
				}
	        }

	        // get selected tags and add to selected_array
	    	$tag_array = array("tag","vem","ort");
	    	$tags = array();

	    	// show selected tags
	    	foreach ($tag_array as $tag) {
	    		// reset tags array
				$tags["tag"] = get_query_var("tag");
				$tags["vem"] = get_query_var("vem");
				$tags["ort"] = get_query_var("ort");

				// check if some tags selected
				if ($tags[$tag] != "") {
					$selected_tags = explode(",", $tags[$tag]);
					if (!empty($selected_tags)) : foreach ($selected_tags as $value) {
						// add name and url to remove the $value tag
						$tags[$tag] = get_query_var($tag);
						$tags[$tag] = str_replace($value, "", $tags[$tag]);
						$tags[$tag] = str_replace(",,",",",trim($tags[$tag],","));
						$taglink= "?tag=" . $tags["tag"];
						$vemlink= "&vem=" . $tags["vem"];
						$ortlink= "&ort=" . $tags["ort"];
						$searchlink= "&s=" . $search;
						$selected_array[] = array("name" => $value, "url" => $caturl . $taglink . $vemlink . $ortlink . $searchlink, "noselect" => "", "tag" => $tag );
					} endif;
				}
			}


			// print selected_array
			$selected_ids = array();
			if (count($selected_array) == 1)
			{
				echo "<li class='noselect " . $selected_array[0]["tag"] . "'><div class='icon'></div><a>" . $selected_array[0]["name"] . "</a></li>";
			}
			else if (!empty($selected_array)) {
				foreach ($selected_array as $value) {
					echo "<li class='" . $value["noselect"] . " " . $value["tag"] . "'><div class='icon'></div><a href='" . $value["url"] . "'>" . $value["name"] . "</a></li>";
				}
			}
			?>
			</ul>
				
		<?php endif; 
	}


	/* echo the tags and categories that are available to filter result */
	function hk_tag_filters() {
		$search = get_query_var("s");
		$cat = get_query_var("cat");
		// initialize caturl
		if ($cat != "")
			$caturl = get_category_link( $cat );
		else
			$caturl = get_bloginfo("wpurl");

		// get category parents slug array to check if cat is selected
		$cat_slug_array = array();
		if ($cat != "") {
			foreach ($this->hk_getParents($cat) as $value) {
				$cat_slug_array[] = $value->slug;
			}
		}
		$tags = get_query_var("tag");
		$vem_tags = get_query_var("vem");
		$ort_tags = get_query_var("ort");

		$var = array(	"category" => $cat,
						"post_tag" => $tags,
						"vem" => $vem_tags,
						"ort" => $ort_tags);
		$link = $var;

		// set tag headings
		//$tag_heading = array('category' => "Kategorier", 'post_tag' => "Vad vill du", "vem" => "Vem är du", "ort" => "Ort" );
		
		// get an array with the current tags used as filter
		$tag_clouds = $this->hk_helper_get_tag_filters(array("category","post_tag","vem","ort"));

		// get one taxonomy at the time tag_key contain the slug, tag_cloud contain the cloud-array 
		$hasFilters = false; ?>
		<span class='heading'>Visa bara</span>
		
		<?php
		foreach ($tag_clouds as $tag_key => $tag_cloud) {
			// set selected tags array
			if ($tag_key != "category")
				$selected_tags = explode(",", $var[$tag_key]);
			else
				$selected_tags = $cat_slug_array;

			// trim selected from tag cloud
			$set_tags = array();
			foreach ($tag_cloud as $key => $value) {
				if (!in_array($key, $selected_tags))
				{
					$set_tags[$key] = $value; 
				}
			}
			$tag_cloud = $set_tags;
			
			// echo the tags that are left in the array
			if (!empty($tag_cloud)) : ?>
				<!--span class='heading'><?php echo $tag_heading[$tag_key]; ?> &raquo;</span-->
				<?php if ($hasFilters) : ?> 
				<hr>
				<?php endif; ?>
				<ul class="<?php echo $tag_key; ?>">
				<?php if (!empty($tag_cloud)) : foreach ($tag_cloud as $key => $value) {
					$hasFilters = true;
					if ($var[$tag_key] == "") {
						$link[$tag_key] = $key;
					}
					else {
						$link[$tag_key] = $var[$tag_key].",".$key;
					}
					$curr_caturl = $caturl;
					
					if ($tag_key == "category")
						$curr_caturl = get_category_link(get_cat_ID($value));
					
					echo "<li><span class='arrow'>&gt;</span><a class='$selected' href='" . $curr_caturl . 
					"?tag=" . $link["post_tag"] .
					"&vem=" . $link["vem"] . 
					"&ort=" . $link["ort"] .
					"&s=" . $search . 
					"'>" . $value . "</a></li> ";

					$link[$tag_key] = $var[$tag_key];
				} endif; ?>
		        
		        </ul>
			<?php 
			endif; 
		}
		if (!$hasFilters) {
			echo "<ul><li><span class='leftspace'>Hittade inga fler filter</span></li></ul>";
		}
	}


	/* related tags to selected taxonomy in selected category */
	function hk_helper_get_tag_filters($taxonomies) { 
		/* get request variables */
		$search = get_query_var("s");
		$cat = get_query_var("cat");
		$tags = get_query_var("tag");
		$tag_array = "";
		if ($tags != "")
			$tag_array = split(",", $tags);
		$vem_tags = get_query_var("vem");
		$vem_array = array();
		if ($vem_tags != "")
			$vem_array = split(",", $vem_tags);
		$ort_tags = get_query_var("ort");
		$ort_array = array();
		if ($ort_tags != "")
			$ort_array = split(",", $ort_tags);

		// init arrays
		$tags_arr = array();
		$all_tags_arr = array();

		/* get the id of the selected categories children */
		$cat_children = get_categories('child_of='.$cat);
		$cat_array = array($cat);
		foreach ($cat_children as $child) {
			$cat_array[] = $child->term_id;
		}

		// check for all taxonomies in this query
		$query = array( 'category__in' => $cat_array,
					 	'tag_slug__in' => $tag_array,
						
					 	'posts_per_page' => '-1');

		if ($search != "")
			$query["s"] = $search;

		if (!empty($vem_array) && !empty($ort_array)) {
			$query['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'vem',
					'field' => 'slug',
					'terms' => $vem_array )
				,
				array(
					'taxonomy' => 'ort',
					'field' => 'slug',
					'terms' => $ort_array
				)
			);
		}						 	
		else if (!empty($vem_array)) {
			$query['tax_query'] = array(
				array(
					'taxonomy' => 'vem',
					'field' => 'slug',
					'terms' => $vem_array )
			);
		}						 	
		else if (!empty($ort_array)) {
			$query['tax_query'] = array(
				array(
					'taxonomy' => 'ort',
					'field' => 'slug',
					'terms' => $ort_array )
			);
		}


		// loop all posts to get all taxonomies used 
		$wpq = new WP_Query($query);
		if ($wpq->have_posts()) : while ($wpq->have_posts()) : $wpq->the_post();
			if (!empty($taxonomies)) : foreach ($taxonomies as $taxonomy) {
			    $posttags = wp_get_post_terms(get_the_ID(),$taxonomy);
				if (!empty($posttags)) : foreach($posttags as $tag) {
					// only show categories that are children to selected category
					if ($taxonomy != "category" || $this->hk_childHasParent($tag->term_id, $cat)) :
						//USING JUST $tag MAKING $all_tags_arr A MULTI-DIMENSIONAL ARRAY, WHICH DOES WORK WITH array_unique
						$all_tags_arr[$taxonomy][$tag -> slug] = $tag -> name; 
					endif;
				} endif;
			} endif;
		endwhile; endif;

		if (!empty($taxonomies)) : foreach ($taxonomies as $taxonomy) {
			//REMOVES DUPLICATES
			if (!empty($all_tags_arr[$taxonomy])) {
				$tags_arr[$taxonomy] = array_unique($all_tags_arr[$taxonomy]);
			} 
		} endif;
		
		return $tags_arr;

	}


	// check if category $child is a child of category $parent
	function hk_childHasParent($child, $parent) {
		$i = 0;
		do {
			$i++;
			$args = array(
				'type'                     => 'post',
				'include'                  => $child,
				'taxonomy'                 => 'category' );
			$getparent = get_categories( $args );

			// return true if parent is found
			if ($parent == $getparent[0]->parent)
				return true;
			// else get next parent
			$child = $getparent[0]->parent;
			
		} while($child != "0" && $i < 10);
		
		return false;
	}

	function hk_getParents($child) {
		$parent_array = array();
		$i = 0;
		do {
			$i++;
			$args = array(
				'type'                     => 'post',
				'include'                  => $child,
				'taxonomy'                 => 'category' );
			$getparent = get_categories( $args );
			$parent_array[] = $getparent[0];
			$child = $getparent[0]->parent;
		} while($child != "0" && $i < 10);
		//invert parent order
		krsort($parent_array);
		return $parent_array;
	}
	
	function hk_countParents($cat) {
		$cats_str = get_category_parents($cat, false, '%#%');
		$cats_array = explode('%#%', $cats_str);
		$cat_depth = sizeof($cats_array)-1;
		return $cat_depth;
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "Hk_Menu_Widget" );' ) );

?>