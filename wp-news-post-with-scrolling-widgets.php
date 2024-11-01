<?php
/*
Plugin Name: WP News Post with scrolling Widgets
Plugin URL: https://www.wponlinehelp.com
Text Domain: wp-news-post-with-scrolling-widget
Domain Path: /languages/
Description: A simple News post and scrolling widgets(static, scrolling and with thumbnail) plugin
Version: 1.0
Author: WP Online Help
Author URI: https://www.wponlinehelp.com
Contributors: WP Online Help
*/

if( !defined( 'WPNPWSW_VERSION' ) ) {
    define( 'WPNPWSW_VERSION', '1.0' ); // Version of plugin
}
if( !defined( 'WPNPWSW_DIR' ) ) {
    define( 'WPNPWSW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPNPWSW_URL' ) ) {
    define( 'WPNPWSW_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPNPWSW_POST_TYPE' ) ) {
    define( 'WPNPWSW_POST_TYPE', 'wpoh-news' ); // Plugin post type
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package News Post and Scrolling Widget
 * @since 1.0
 */
add_action('plugins_loaded', 'wpnpwsw_textdomain');
function wpnpwsw_textdomain() {
	load_plugin_textdomain( 'wp-news-post-with-scrolling-widget', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

// Initialization function
add_action('init', 'wpnpwsw_news_init');
function wpnpwsw_news_init() {
  // Create new News custom post type
    $news_post_labels = array(
    'name'                 => _x('News Post', 'wp-news-post-with-scrolling-widget'),
    'singular_name'        => _x('news post', 'wp-news-post-with-scrolling-widget'),
    'add_new'              => _x('Add News Item', 'wp-news-post-with-scrolling-widget'),
    'add_new_item'         => __('Add New News Item', 'wp-news-post-with-scrolling-widget'),
    'edit_item'            => __('Edit News Item', 'wp-news-post-with-scrolling-widget'),
    'new_item'             => __('New News Item', 'wp-news-post-with-scrolling-widget'),
    'view_item'            => __('View News Item', 'wp-news-post-with-scrolling-widget'),
    'search_items'         => __('Search  News Items','wp-news-post-with-scrolling-widget'),
    'not_found'            => __('No News Items found', 'wp-news-post-with-scrolling-widget'),
    'not_found_in_trash'   => __('No News Items found in Trash', 'wp-news-post-with-scrolling-widget'), 
    '_builtin'             =>  false, 
    'parent_item_colon'    => '',
    'menu_name'          => _x( 'News Post', 'admin menu', 'wp-news-post-with-scrolling-widget' )
  );
  $news_post_args = array(
    'labels'              => $news_post_labels,
    'public'              => true,
    'publicly_queryable'  => true,
    'exclude_from_search' => false,
    'show_ui'             => true,
    'show_in_menu'        => true, 
    'query_var'           => true,
    'rewrite'             => array( 
							'slug' => 'wpoh-news',
							'with_front' => false
							),
    'capability_type'     => 'post',
    'has_archive'         => true,
    'hierarchical'        => false,
    'menu_position'       => 5,
	'menu_icon'   		  => 'dashicons-list-view',
    'supports'            => array('title','editor','thumbnail','excerpt','comments'),
    'taxonomies'          => array('post_tag')
  );
  register_post_type('wpoh-news',$news_post_args);
}
/* Register Taxonomy */
add_action( 'init', 'wpnpwsw_news_scrolling');
function wpnpwsw_news_scrolling() {
    $labels = array(
        'name'              => _x( 'Post Category', 'wp-news-post-with-scrolling-widget' ),
        'singular_name'     => _x( 'Post Category', 'wp-news-post-with-scrolling-widget' ),
        'search_items'      => __( 'Search Category', 'wp-news-post-with-scrolling-widget' ),
        'all_items'         => __( 'All Category', 'wp-news-post-with-scrolling-widget' ),
        'parent_item'       => __( 'Parent Category', 'wp-news-post-with-scrolling-widget' ),
        'parent_item_colon' => __( 'Parent Category:', 'wp-news-post-with-scrolling-widget' ),
        'edit_item'         => __( 'Edit Category', 'wp-news-post-with-scrolling-widget' ),
        'update_item'       => __( 'Update Category', 'wp-news-post-with-scrolling-widget' ),
        'add_new_item'      => __( 'Add New Category', 'wp-news-post-with-scrolling-widget' ),
        'new_item_name'     => __( 'New Category Name', 'wp-news-post-with-scrolling-widget' ),
        'menu_name'         => __( 'Post Category', 'wp-news-post-with-scrolling-widget' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'wpoh-news-cat' ),
    );

    register_taxonomy( 'wpoh-news-cat', array( 'wpoh-news' ), $args );
}

function wpnpwsw_rewrite_flush() {  
	wpnpwsw_news_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wpnpwsw_rewrite_flush');

add_action( 'wp_enqueue_scripts','wpnpwsw_css_script' );
function wpnpwsw_css_script() {
    wp_enqueue_style( 'wpohcss',  WPNPWSW_URL.'css/wpoh-news-publilc.css', array(), WPNPWSW_VERSION );
    wp_enqueue_script( 'wpohnewsjs', WPNPWSW_URL.'js/wpoh-news-public.js', array( 'jquery' ), WPNPWSW_VERSION);
}
require_once( 'wpnpwsw-function.php' );	
function wpnpwsw_get_news( $atts, $content = null ){
    // setup the query
    extract(shortcode_atts(array(
		"limit"                 => '',	
		"category"              => '',
		"grid"                  => '',
        "show_date"             => '',          
        "show_category_name"    => '',          
        "show_content"          => '',      
		"show_full_content"     => '',      
        "content_words_limit"   => '',       
        "pagination_type"       => 'numeric',   //OR next-prev
	), $atts));
	
    // Define limit
	
    if( $limit ) { $posts_per_page = $limit; } else { $posts_per_page = '-1'; }
	if( $category ) { $cat = $category; } else { $cat = '';	}
	if( $grid ) { $gridcol = $grid; } else { $gridcol = '1'; }
    if( $show_date ) { $showDate = $show_date; } else { $showDate = 'true'; }
	if( $show_category_name ) { $showCategory = $show_category_name; } else { $showCategory = 'true'; }
    if( $show_content ) { $showContent = $show_content; } else { $showContent = 'true'; }
	if( $show_full_content ) { $showFullContent = $show_full_content; } else {$showFullContent = 'false';}
	if( $content_words_limit ) { $words_limit = $content_words_limit; } else {$words_limit = '20'; }
    if($pagination_type == 'numeric'){ $pagination_type = 'numeric'; }else{$pagination_type = 'next-prev';}

	ob_start();
	global $paged;
    if(is_home() || is_front_page()) {
		  $paged = get_query_var('page');
	} else {
		 $paged = get_query_var('paged');
	}

	$post_type 		= 'wpoh-news';
	$orderby 		= 'date';
	$order 			= 'DESC';

    $args = array ( 
        'post_type'      => $post_type,
        'post_status'    => array( 'publish' ),
        'orderby'        => $orderby,
        'order'          => $order,
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );

    if($cat != "") {
        $args['tax_query'] = array(
            array(
                'taxonomy'  => 'wpoh-news-cat',
                'field'     => 'term_id',
                'terms'     => $cat
            ));
    }

    $query = new WP_Query($args);

    global $post;
    $post_count = $query->post_count;
    $count = 0;
	?>
	<div class="wpnpwsw-plugin wpnpwsw-clearfix">
	<?php
    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        
        $count++;
        $terms = get_the_terms( $post->ID, 'wpoh-news-cat' );
        $news_links = array();
        if($terms) {
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
            }
        }        
        $cate_name = join( ", ", $news_links );
        $css_class="wpnpwsw-news-post";
        if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == ($count - 1) % $grid ) ) || 1 == $count ) { $css_class .= ' wpnpwsw-first'; }
        if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == $count % $grid ) ) || $post_count == $count ) { $css_class .= ' wpnpwsw-last'; }
        if($showDate == 'true'){ $date_class = "has-date"; } else { $date_class = "has-no-date";} ?>	
    	<div id="post-<?php the_ID(); ?>" class="news type-news wpnpwsw-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>">
			<div class="news-inner-wrap wpnpwsw-clearfix">	
				<div class="news-post-thumb">    			
					<?php if ( has_post_thumbnail()) {    				
						if($gridcol == '1'){ ?>    					
							<div class="grid-news-post-thumb">    				    
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('url'); ?></a>
							</div>
						<?php } else if($gridcol > '2') { ?>    					
							<div class="grid-news-post-thumb">	    				    
								<a href="<?php the_permalink(); ?>">	<?php the_post_thumbnail('large'); ?></a>
							</div>
						<?php  } else { ?>        			    
							<div class="grid-news-post-thumb">        				
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
							</div>
						<?php } 
					} ?>
				</div>			
				<div class="news-post-content">    			
					<?php if($gridcol == '1') {                    
						if($showDate == 'true'){ ?>        				
							<div class="date-post">            			
								<h2><span><?php echo get_the_date('j'); ?></span></h2>            			
								<p><?php echo get_the_date('M y'); ?></p>
							</div>
						<?php }?>
					<?php } else {  ?>    				
						<div class="news-post-date">        			
							<?php echo ($showDate == "true")? get_the_date() : "" ;?>                    
							<?php echo ($showDate == "true" && $showCategory == "true" && $cate_name != '') ? " / " : "";?>                    
							<?php echo ($showCategory == 'true' && $cate_name != '') ? $cate_name : ""?>
						</div>
					<?php  } ?>    			
					<div class="news-post-content-box">    				
						<?php the_title( sprintf( '<h3 class="news-post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );	?>    			    
						<?php if($showCategory == 'true' && $gridcol == '1'){ ?>    				
							<div class="news-cat">                        
								<?php echo $cate_name; ?>
							</div>
						<?php }?>
						<?php if($showContent == 'true'){?>        			 
							<div class="news-content-excerpt">            			
								<?php  if($showFullContent == "false" ) {
									$excerpt = get_the_content(); ?>                				
									<div class="news-post-content">                                    
										<?php echo string_limit_news_word( $post->ID, $excerpt, $words_limit, '...'); ?>
									</div>                				
									<a href="<?php the_permalink(); ?>" class="news-read-more"><?php _e( 'Read More', 'wp-news-post-with-scrolling-widget' ); ?></a>	
								<?php } else {             				
									the_content();
								} ?>
							</div><!-- .entry-content -->
						<?php }?>
					</div>
				</div>
			</div><!-- #post-## -->
        </div><!-- #post-## -->
    <?php  endwhile; endif; ?>
	</div>		
    <div class="post_pagination">        
        <?php if($pagination_type == 'numeric'){ 
            echo wpnpwsw_pagination( array( 'paged' => $paged , 'total' => $query->max_num_pages ) );
        }else{ ?>    		
            <div class="button-news-p"><?php next_posts_link( ' Next >>', $query->max_num_pages ); ?></div>    		
            <div class="button-news-n"><?php previous_posts_link( '<< Previous' ); ?> </div>
        <?php } ?>
	</div><?php
    
    wp_reset_query(); 
				
	return ob_get_clean();
	}
add_shortcode('wpoh_news','wpnpwsw_get_news');

function string_limit_news_word( $post_id = null, $content = '', $word_length = '55', $more = '...' ) {

    $has_excerpt  = false;
    $word_length    = !empty($word_length) ? $word_length : '55';

    // If post id is passed
    if( !empty($post_id) ) {
        if (has_excerpt($post_id)) {
            $has_excerpt    = true;
            $content        = get_the_excerpt();
        } else {
            $content = !empty($content) ? $content : get_the_content();
        }
    }

    if( !empty($content) && (!$has_excerpt) ) {
        $content = strip_shortcodes( $content ); // Strip shortcodes
        $content = wp_trim_words( $content, $word_length, $more );
    }

    return $content;
}

function wpnpwsw_display_tags( $query ) {
    if( is_tag() && $query->is_main_query() ) {       
       $post_types = array( 'post', 'wpoh-news' );
        $query->set( 'post_type', $post_types );
    }
}
add_filter( 'pre_get_posts', 'wpnpwsw_display_tags' );


// Manage Category Shortcode Columns

add_filter("manage_wpoh-news-cat_custom_column", 'wpoh_news_cat_columns', 10, 3);
add_filter("manage_edit-wpoh-news-cat_columns", 'wpoh_news_cat_manage_columns'); 
function wpoh_news_cat_manage_columns($theme_columns) {
    $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'post_shortcode' => __( 'News Category Shortcode', 'wp-news-post-with-scrolling-widget' ),
            'slug' => __('Slug'),
            'posts' => __('Posts')
			);
    return $new_columns;
}

function wpoh_news_cat_columns($out, $column_name, $theme_id) {
    $theme = get_term($theme_id, 'wpoh-news-cat');
    switch ($column_name) {
        case 'title':
            echo get_the_title();
        break;
        case 'post_shortcode': 
             echo '[wpoh_news category="' . $theme_id. '"]';
        break;
        default:
            break;
    }
    return $out; 
}
function wpnpwsw_pagination($args = array()){    
    $big = 999999999; // need an unlikely integer
    $paging = apply_filters('news_blog_paging_args', array(
                    'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format'    => '?paged=%#%',
                    'current'   => max( 1, $args['paged'] ),
                    'total'     => $args['total'],
                    'prev_next' => true,
                    'prev_text' => __('« Previous', 'wp-news-post-with-scrolling-widget'),
                    'next_text' => __('Next »', 'wp-news-post-with-scrolling-widget'),
                ));
    
    echo paginate_links($paging);
}
function wpnpwsw_get_unique() {
  static $unique = 0;
  $unique++;
  return $unique;
}

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( WPNPWSW_DIR . '/admin/wpnpwsw-how-it-work.php' );
}