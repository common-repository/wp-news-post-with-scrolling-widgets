<?php class WPOH_News_Widget extends WP_Widget {

    function __construct() {

        $widget_ops = array('classname' => 'WPOH_News_Widget', 'description' => __('Displayed Latest News post from the News in a sidebar', 'wp-news-post-with-scrolling-widget') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'wpoh_news_widget' );
        parent::__construct( 'wpoh_news_widget', __('Latest News Post', 'wp-news-post-with-scrolling-widget'), $widget_ops, $control_ops );
    }

    function form($instance) {
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        "date"              => false, 
        'show_category'     => false,
        'category'          => 0,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"> <?php _e( 'Title:', 'wp-news-post-with-scrolling-widget' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>"><?php _e( 'Number of Items: ', 'wp-news-post-with-scrolling-widget' ); ?>  <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo esc_attr($num_items); ?>" /></label></p>
      <p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'wpoh-news-cat', 'class' => 'widefat', 'show_option_all' => __( 'All', 'wp-news-post-with-scrolling-widget' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>	
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] );   
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
        $postcount = 0;
        echo $before_widget;
?>
             <h4 class="widget-title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php
			$no_p = '';
			if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items <?php echo $no_p?>">
                <ul>
            <?php // setup the query
            $news_args = array(
                           'posts_per_page' => $num_items,
                           'post_type'      => 'wpoh-news',
                           'post_status'    => array( 'publish' ),
                           'order'          => 'DESC'
                        );

            if( $category != 0 ) {
            	$news_args['tax_query'] = array(
                                            array(
                                                'taxonomy'  => 'wpoh-news-cat',
                                                'field'     => 'term_id',
                                                'terms'     => $category
                                            ));
            }

            $cust_loop = new WP_Query($news_args);
			global $post;
                $post_count = $cust_loop->post_count;
                $count = 0;           
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                $count++;
               $terms = get_the_terms( $post->ID, 'wpoh-news-cat' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
                       <a class="news-post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
					   <?php if($date == "true" ||  $show_category == "true"){ ?>
					   <div class="widget-date-post">
						<?php echo ($date == "true")? get_the_date() : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name : "" ?>
						</div>
					   <?php } ?>
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>
            </div>
<?php
        echo $after_widget;
    }
}
/* Register the widget */
function wpnpwsw_widget_load_widgets() {
    register_widget( 'WPOH_News_Widget' );
}
/* Load the widget */
add_action( 'widgets_init', 'wpnpwsw_widget_load_widgets' );

/* scrolling news */
class WPOH_News_scrolling_Widget extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'WPOH_News_scrolling_Widget', 'description' => __('Scroll Latest News Post from the News in a sidebar', 'wp-news-post-with-scrolling-widget') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'wpoh_news_s_widget' );
        parent::__construct( 'wpoh_news_s_widget', __('Latest News Scrolling Post', 'wp-news-post-with-scrolling-widget'), $widget_ops, $control_ops );
    }
    function form($instance) {
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        'date'              => false, 
        'show_category'     => false,
        'category'          => 0,
		'height'          	=> 400,      
        'pause'  			=> 2000,                
        'speed'             => 500,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;              
    ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"> <?php _e( 'Title:', 'wp-news-post-with-scrolling-widget' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>"><?php _e( 'Number of Items: ', 'wp-news-post-with-scrolling-widget' ); ?>  <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo esc_attr($num_items); ?>" /></label></p>
      <p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'wpoh-news-cat', 'class' => 'widefat', 'show_option_all' => __( 'All', 'wp-news-post-with-scrolling-widget' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>
		 <p>
            <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'height' ); ?>"  value="<?php echo $instance['height']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" />
        </p>
		 <p>
            <label for="<?php echo $this->get_field_id( 'pause' ); ?>"><?php _e( 'Pause:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'pause' ); ?>"  value="<?php echo $instance['pause']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'pause' ); ?>" />
        </p>
		 <p>
            <label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e( 'Speed:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <input type="text" name="<?php echo $this->get_field_name( 'speed' ); ?>"  value="<?php echo $instance['speed']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'speed' ); ?>" />
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] ); 
		$instance['height']   = intval( $new_instance['height'] );
        $instance['pause']              = intval( $new_instance['pause'] ); 
		$instance['speed']              = intval( $new_instance['speed'] ); 	
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);
        $current_post_name = get_query_var('name');
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);  
		$num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);  
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
		if ( isset( $instance['height'] ) && is_numeric( $instance['height'] ) ) { $args['height'] = intval( $instance['height'] ); }
		if ( isset( $instance['pause'] ) && is_numeric ( $instance['pause'] ) ) { $args['pause'] = intval( $instance['pause'] ); }
		if ( isset( $instance['speed'] ) && is_numeric ( $instance['speed'] ) ) { $args['speed'] = intval( $instance['speed'] ); }
        $postcount = 0;

        echo $before_widget;
        $unique_id = wpnpwsw_get_unique();
?>
             <h4 class="widget-title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php
				$no_p = '';
			if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items-scroll <?php echo $no_p;?>">
               <div class="newsticker-jcarousellite-<?php echo $unique_id;?>">
			   <ul>
            <?php // setup the query
            $news_args = array(
                            'posts_per_page'    => $num_items,
                            'post_type'         => 'wpoh-news',
                            'post_status'       => array( 'publish' ),
                            'order'             => 'DESC'
                         );

            if( $category != 0 ) {
            	$news_args['tax_query'] = array(
                                            array(
                                                    'taxonomy'  => 'wpoh-news-cat',
                                                    'field'     => 'term_id',
                                                    'terms'     => $category
                                            ));
            }
            $cust_loop = new WP_Query($news_args);
			global $post;
                $post_count = $cust_loop->post_count;
                $count = 0;
           
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    $count++;
               $terms = get_the_terms( $post->ID, 'wpoh-news-cat' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
                        <a class="newspost-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    	  <?php if($date == "true" ||  $show_category == "true"){ ?>
					   <div class="widget-date-post">
						<?php echo ($date == "true")? get_the_date() : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name : "" ?>
						</div>
					   <?php } ?>
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>
                </ul>
	            </div>
            </div>
			<script>
   jQuery(function() {
  jQuery('.newsticker-jcarousellite-<?php echo $unique_id;?>').vTicker(
  {
	  speed:<?php echo $instance['speed']?>,
	  height:<?php echo $instance['height']?>,
	  padding:10,
	  pause:<?php echo $instance['pause']?>
	  
  });
});
</script>
<?php
        echo $after_widget;
    }
}

/* Register the widget */
function wpnpwsw_scroll_widget_load_widgets() {
    register_widget( 'WPOH_News_scrolling_Widget' );
}
/* Load the widget */
add_action( 'widgets_init', 'wpnpwsw_scroll_widget_load_widgets' );

/* news with thumb */
class WPOH_News_thmb_Widget extends WP_Widget {

    function __construct() {

        $widget_ops = array('classname' => 'WPOH_News_thmb_Widget', 'description' => __('Displayed Latest News Post in a sidebar with thumbnails', 'wp-news-post-with-scrolling-widget') );
        $control_ops = array( 'width' => 350, 'height' => 450, 'id_base' => 'wpoh_news_sthumb_widget' );
        parent::__construct( 'wpoh_news_sthumb_widget', __('Latest News Post with Thumbnail', 'wp-news-post-with-scrolling-widget'), $widget_ops, $control_ops );
    }

    function form($instance) {	
        $defaults = array(
        'limit'             => 5,
        'title'             => '',
        "date"              => false, 
        'show_category'     => false,
        'category'          => 0,
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
     <p><label for="<?php echo $this->get_field_id('title'); ?>"> <?php _e( 'Title:', 'wp-news-post-with-scrolling-widget' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('num_items'); ?>"><?php _e( 'Number of Items: ', 'wp-news-post-with-scrolling-widget' ); ?>  <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo esc_attr($num_items); ?>" /></label></p>
    	<p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox"<?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>"><?php _e( 'Display Date', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox"<?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>"><?php _e( 'Display Category', 'wp-news-post-with-scrolling-widget' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category:', 'wp-news-post-with-scrolling-widget' ); ?></label>
            <?php
                $dropdown_args = array( 'taxonomy' => 'wpoh-news-cat', 'class' => 'widefat', 'show_option_all' => __( 'All', 'wp-news-post-with-scrolling-widget' ), 'id' => $this->get_field_id( 'category' ), 'name' => $this->get_field_name( 'category' ), 'selected' => $instance['category'] );
                wp_dropdown_categories( $dropdown_args );
            ?>
        </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['num_items'] = $new_instance['num_items'];
        $instance['date'] = (bool) esc_attr( $new_instance['date'] );
        $instance['show_category'] = (bool) esc_attr( $new_instance['show_category'] );
        $instance['category']      = intval( $new_instance['category'] );
        return $instance;
    }
    function widget($news_args, $instance) {
        extract($news_args, EXTR_SKIP);

        $current_post_name = get_query_var('name');

        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);
        if ( isset( $instance['date'] ) && ( 1 == $instance['date'] ) ) { $date = "true"; } else { $date = "false"; }
        if ( isset( $instance['show_category'] ) && ( 1 == $instance['show_category'] ) ) { $show_category = "true"; } else { $show_category = "false"; }
        if ( isset( $instance['category'] ) && is_numeric( $instance['category'] ) ) $category = intval( $instance['category'] );
        $postcount = 0;
        echo $before_widget;
?>
             <h4 class="widget-title"><?php echo $title ?></h4>
            <!--visual-columns-->
            <?php
				$no_p = '';
				if($date == "false" && $show_category == "false"){ 
                $no_p = "no_p";
                }?>
            <div class="recent-news-items <?php echo $no_p;?>">
			 
                <ul>
            <?php // setup the query
            $news_args = array(
                                'posts_per_page'    => $num_items,
                                'post_type'         => 'wpoh-news',
                                'post_status'       => array( 'publish' ),
                                'order'             => 'DESC'
                            );

            if($category != 0) {
            	$news_args['tax_query'] = array(
                                            array(
                                                'taxonomy'  => 'wpoh-news-cat',
                                                'field'     => 'term_id',
                                                'terms'     => $category
                                        ));
            }

            $cust_loop = new WP_Query($news_args);
			global $post;
            $post_count = $cust_loop->post_count;
            $count = 0;
            if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); $postcount++;
                    $count++;
               $terms = get_the_terms( $post->ID, 'wpoh-news-cat' );
                    $news_links = array();
                    if($terms){

                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term );
                        $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                    }
                }
                    $cate_name = join( ", ", $news_links );
                    ?>
                    <li class="news_li">
						<div class="news_thumb_left">
					   <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
                  		
                  	<?php
                  if ( function_exists('has_post_thumbnail') && has_post_thumbnail() ) {
                   the_post_thumbnail( array(80,80) );
                  }
                  ?> </a></div>
				  <div class="news_thumb_right">
                        <a class="newspost-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						  <?php if($date == "true" ||  $show_category == "true"){ ?>
					   <div class="widget-date-post">
						<?php echo ($date == "true")? get_the_date() : "" ;?>
                  		<?php echo ($date == "true" && $show_category == "true" && $cate_name != '') ? " , " : "";?>
                  		<?php echo ($show_category == 'true' && $cate_name != '') ? $cate_name : "" ?>
						</div>
					   <?php } ?>
						</div>
						
                    </li>
            <?php endwhile;
            endif;
             wp_reset_query(); ?>

                </ul>
            </div>
<?php
        echo $after_widget;
    }
}
/* Register the widget */
function wpnpwsw_thumb_widget_load_widgets() {
    register_widget( 'WPOH_News_thmb_Widget' );
}

/* Load the widget */
add_action( 'widgets_init', 'wpnpwsw_thumb_widget_load_widgets' );