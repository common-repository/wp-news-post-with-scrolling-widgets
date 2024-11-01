<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Action to add menu
add_action('admin_menu', 'wpnpwsw_register_design_page');

/**
 * Register plugin design page in admin menu
 * 
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */
function wpnpwsw_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WPNPWSW_POST_TYPE, __('How it works - WP News Post with scrolling Widgets', 'wp-news-post-with-scrolling-widget'), __('How It Works', 'wp-news-post-with-scrolling-widget'), 'edit_posts', 'wpnpwsw-designs', 'wpnpwsw_designs_page' );
}
/**
 * Function to display plugin design HTML
 * 
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */
function wpnpwsw_designs_page() {

	$wpoh_feed_tabs = wpnpwsw_help_tabs();
	$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
?>
		
	<div class="wrap wpnpwsw-wrap">
		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpoh_feed_tabs as $tab_key => $tab_val) {
				$tab_name	= $tab_val['name'];
				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array( 'post_type' => WPNPWSW_POST_TYPE, 'page' => 'wpnpwsw-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>
			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

			<?php } ?>
		</h2>
		
		<div class="wpnpwsw-tab-cnt-wrp">
		<?php
			if( isset($active_tab) && $active_tab == 'how-it-work' ) {
				wpnpwsw_howitwork_page();
			}
			else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
				echo wpnpwsw_get_plugin_design( 'plugins-feed' );
			} else {
				echo wpnpwsw_get_plugin_design( 'offers-feed' );
			}
		?>
		</div><!-- end .wpnpwsw-tab-cnt-wrp -->

	</div><!-- end .wpnpwsw-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */
function wpnpwsw_get_plugin_design( $feed_type = '' ) {
	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
	
	// If tab is not set then return
	if( empty($active_tab) ) {
		return false;
	}

	// Taking some variables
	$wpoh_feed_tabs = wpnpwsw_help_tabs();
	$transient_key 	= isset($wpoh_feed_tabs[$active_tab]['transient_key']) 	? $wpoh_feed_tabs[$active_tab]['transient_key'] 	: 'wpnpwsw_' . $active_tab;
	$url 			= isset($wpos_feed_tabs[$active_tab]['url']) 			? $wpoh_feed_tabs[$active_tab]['url'] 				: '';
	$transient_time = isset($wpos_feed_tabs[$active_tab]['transient_time']) ? $wpoh_feed_tabs[$active_tab]['transient_time'] 	: 172800;
	$cache 			= get_transient( $transient_key );
	
	if ( false === $cache ) {
		
		$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
		$response_code 	= wp_remote_retrieve_response_code( $feed );
		
		if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( $transient_key, $cache, $transient_time );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'wp-news-post-with-scrolling-widget' ) . '</div>';
		}
	}
	return $cache;	
}

/**
 * Function to get plugin feed tabs
 *
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */
function wpnpwsw_help_tabs() {
	$wpoh_feed_tabs = array(
						'how-it-work' 	=> array(
													'name' => __('How It Works', 'wp-news-post-with-scrolling-widget'),
												),
					);
	return $wpoh_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @package WP News Post with scrolling Widgets
 * @since 1.0.0
 */
function wpnpwsw_howitwork_page() { ?>

	<style type="text/css">
		.wpoh-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpoh-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpoh-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpnpwsw-wrap .wpoh-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpnpwsw-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">								
								<h3 class="hndle">
									<span><?php _e( 'How It Works - Display and Shortcode', 'wp-news-post-with-scrolling-widget' ); ?></span>
								</h3>								
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php _e('Geeting Started', 'wp-news-post-with-scrolling-widget'); ?></label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1: This plugin create a News Post menu tab in WordPress menu with custom post type.".', 'wp-news-post-with-scrolling-widget'); ?></li>														
														<li><?php _e('Step-2: Go to "News Post > Add news item tab".', 'wp-news-post-with-scrolling-widget'); ?></li>
														<li><?php _e('Step-3: Add news title, description, category, and image as featured image.', 'wp-news-post-with-scrolling-widget'); ?></li>
														<li><?php _e('Step-4: Repeat this process and add multiple news item.', 'wp-news-post-with-scrolling-widget'); ?></li>	
														<li><?php _e('Step-4: To display news category wise you can use category shortcode under "News > News category"', 'wp-news-post-with-scrolling-widget'); ?></li>															
													</ul>
												</td>
											</tr>
											<tr>
												<th>
													<label><?php _e('How Shortcode Works', 'wp-news-post-with-scrolling-widget'); ?></label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Create a page like Our News OR Latest News.', 'wp-news-post-with-scrolling-widget'); ?></li>
														<li><?php _e('<b>Please make sure that Permalink link should not be "/news" Otherwise all your news will go to archive page. You can give it other name like "/ournews, /latestnews etc"</b>', 'wp-news-post-with-scrolling-widget'); ?></li>
														<li><?php _e('Step-2. Put below shortcode as per your need.', 'wp-news-post-with-scrolling-widget'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('All Shortcodes', 'wp-news-post-with-scrolling-widget'); ?></label>
												</th>
												<td>
													<span class="wpnpwsw-shortcode-preview">[wpoh_news grid="list"]</span> – <?php _e('News in List View', 'wp-news-post-with-scrolling-widget'); ?> <br />
													<span class="wpnpwsw-shortcode-preview">[wpoh_news grid="1"]</span> – <?php _e('Display News in grid 1', 'wp-news-post-with-scrolling-widget'); ?> <br />
													<span class="wpnpwsw-shortcode-preview">[wpoh_news grid="2"]</span> – <?php _e('Display News in grid 2', 'wp-news-post-with-scrolling-widget'); ?> <br />
													<span class="wpnpwsw-shortcode-preview">[wpoh_news grid="3"]</span> – <?php _e('Display News in grid 3', 'wp-news-post-with-scrolling-widget'); ?>
												</td>
											</tr>						
												
											<tr>
												<th>
													<label><?php _e('Need Support?', 'wp-news-post-with-scrolling-widget'); ?></label>
												</th>
												<td>
													<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'wp-news-post-with-scrolling-widget'); ?></p> <br/>
													<a class="button button-primary" href="#" target="_blank"><?php _e('Plugin Documentation', 'wp-news-post-with-scrolling-widget'); ?></a>									
													<a class="button button-primary" href="#" target="_blank"><?php _e('News Plugin Demo', 'wp-news-post-with-scrolling-widget'); ?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-body-content -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
<?php }