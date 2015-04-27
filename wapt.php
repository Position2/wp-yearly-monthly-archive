<?php
/**
 * Plugin Name:  Wordpress Archives Lists
 * Description: A  Wordpress Archives Lists
 * Version: 0.1
 * Author: Position2 WAAS Team
 * Author URI: www.position2.com
 * License: A "Slug" license name e.g. GPL2
 */
class wapt_Blog_Archives extends WP_Widget {


    function wapt_Blog_Archives() {

			$control_ops = array();
			$widget_ops = array(  'description' => __('A Archive Lists', 'blog_Archives_dsc') );
			$this->WP_Widget( 'wapt_blog_archives', __('List Post Archives', 'wapt_blog_archives'), $widget_ops, $control_ops );
			// wp_enqueue_style( 'wapt_PluginStylesheet' );
	        
	       
			 // posts group by specfic users 
			//add_filter( 'posts_groupby',array($this,'wapt_posts_groupby'));
			//add_action( 'profile_personal_options', 'wapt_show_extra_profile_fields' );
	} // end of wapt_Blog_Archives
		
	function widget( $args, $instance ) {
		extract( $args );
	    global $wpdb;
		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		// add style sheet 
		wp_enqueue_script('archive-scripts',plugins_url( 'wapt.js' , __FILE__ ), true);
		wp_enqueue_style('archive-styles',plugins_url( 'css/style.css' , __FILE__ ), true);
		// end before widget 
		 echo $before_widget;
		// Display the widget title 
            echo ' <!-- BLOG ARCHIVES BEGIN -->' ;         
            echo' <div id="BlogArchivesWrapper">
        	<div id="BlogArchivesList">';
           if ($title):
			echo $before_title . $title . $after_title;
		   endif;  
 
      echo '<div class="blog-list-archive">';

		$years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date)
										FROM $wpdb->posts
										 WHERE post_status = 'publish'
										AND post_type = 'post' 
										ORDER BY post_date DESC");
		 echo ' <ul class="archive-menu">';
		foreach($years as $year) :
		      echo '<li class="year-archive"><a href="JavaScript:void(0)">'.$year.'</a>';
		       
				     $months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date)
											          FROM $wpdb->posts 
											          WHERE post_status = 'publish' 
											          AND post_type = 'post'
											          AND YEAR(post_date) = '".$year."' 
											          ORDER BY post_date DESC");
 				echo ' <ul style="display:none" class="archive-sub-menu">';

		        foreach($months as $month) :
		              // '. get_month_link($year, $month).'
				         echo '<li class="month-archive"><a href="JavaScript:void(0)">'.date( 'F', mktime(0, 0, 0, $month) ).'</a>';

		                   $sposts = $wpdb->get_col( " SELECT ID
											                FROM $wpdb->posts
											                WHERE MONTH(post_date) = '$month'
										                    AND YEAR(post_date) =  '$year'
										                    AND `post_status` = 'publish'
										                    AND `post_type` = 'post'
		            										ORDER BY post_date DESC " );

		                    echo '<ul style="display:none" class="archive-post-title">';
		                  
								foreach( $sposts as $spost ) :
                                 echo '<li><a href="'.get_permalink( $spost ).'">' . get_the_title( $spost ) . '</a></li>';
							    endforeach; 
				            
				            echo '</ul>';
				         echo '</li>'; 
				   endforeach; 
			    echo '</ul>

			    </li>';
	 endforeach; 
   echo '</ul>';
 wp_reset_query();
   echo '</div>';
          
				echo  $after_widget;
	}  // end of widgets
	 


	function wapt_posts_groupby($groupby) {
			 global $wpdb;
			 $groupby = "{$wpdb->posts}.post_author";
			 return $groupby;
	} // end of wapt_posts_groupby function
	//Update the widget function begain

	function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				//Strip tags from title and name to remove HTML 
				$instance['title'] = strip_tags( $new_instance['title'] );
 
				return $instance;
	}  // end of function update

	function form( $instance ) {
				//Set up some default widget settings.
				//
				$instance['profile_style'] = array();
				$defaults = array( 'title' => __('Blog Archives', 'default'));
				$instance = wp_parse_args( (array) $instance, $defaults ); 
				// Widget Title: Text Input.
				?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'default'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
			</p>
			 
	<?php
	}  // end on form function 
	
	 
} // end of class wapt_Blog_Archives

add_action( 'widgets_init', 'wapt_Blog_Archives_init');

	function wapt_Blog_Archives_init() {
		register_widget( 'wapt_Blog_Archives' );
	 }
	 
 add_shortcode( 'BLOG-ARCHIVES', array( 'wapt_Blog_Archives', 'widget' ) );