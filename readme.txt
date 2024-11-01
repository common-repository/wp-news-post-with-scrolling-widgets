=== WP News Post with scrolling Widget  ===
Contributors: wponlinehelp, bhargavDholariya
Tags:  wordpress news plugin, news website, main news page scrolling, news, news plugin, wordpress vertical news plugin widget, wordpress horizontal news plugin widget, Free scrolling wordpress plugin, Free scrolling news widget wordpress plugin, WordPress set post or page as news, WordPress dynamic news, news, latest news, custom post type, cpt, widget, vertical news scrolling widget, news widget
Requires at least: 3.1
Tested up to: 4.9.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an News Post with custom post type and it has three widget for Wordpress.

== Description ==

 WordPress site needs a news section. News Post allows you add, manage and display news, date archives, widget, vertical news, scrolling news, with thumbnails widget on your website.



* Now you can Display news post with the help of short code : 

<code> [wpoh_news] </code>

* Display News with Grid:

<code>[wpoh_news grid="2"] </code>

* Also you can Display the news post with Multiple categories wise you can pass multiple ids. 

<code> Sports news : 
[wpoh_news category="category_id"]
Arts news 
[wpoh_news category="category_id"]
</code>

* **Complete shortcode example:**

<code>[wpoh_news limit="10" category="category_id" grid="2"  show_content="true" show_full_content="true" show_category_name="true" show_date="false" content_words_limit="30" ]</code>

* Comments for the news
* Added Widget Options like Show News date, Show News Categories, Select News Categories.

* Template code : <code><?php echo do_shortcode('[wpoh_news]'); ?></code>

= Following are News Parameters: =

* **limit :** [wpoh_news limit="10"] (Display latest 10 news and then pagination).
* **category :**  [wpoh_news category="category_id"] (Display News categories wise).
* **pagination_type:** [wpohp_news pagination_type="numeric"] (Select the pagination type for News ie "numeric" OR "next-prev" ).
* **grid :** [wpoh_news grid="2"] OR [wpoh_news grid="list"] (Display News in Grid formats. To display News in list view, Use grid="list").
* **show_date :** [wpoh_news show_date="false"] (Display News date OR not. By default value is "True". Options are "ture OR false")
* **show_content :** [wpoh_news show_content="true" ] (Display News Short content OR not. By default value is "True". Options are "ture OR false").
* **show_full_content :** [wpoh_news show_full_content="true"] (Display Full news content on main page if you do not want word limit. By default value is "false")
* **show_category_name :** [wpoh_news show_category_name="true" ] (Display News category name OR not. By default value is "True". Options are "ture OR false").
* **content_words_limit :** [wpoh_news content_words_limit="30" ] (Control News short content Words limt. By default limit is 20 words).

The plugin adds a News tab to your admin menu, which allows you to enter news items just as you would regular posts.

If you are getting any kind of problum with news page means your are not able to see all news items then please remodify your permalinks Structure like, first select "Default" and save then again select "Custom Structure"  and save. 

= Added New Features : =
* Added List view <code>[wpoh_news grid="list"] </code>
* Category wise News <code> Sports news [wpoh_news category="category_id"] </code>
* Display News with Grid <code>[wpoh_news grid="2"]</code> and List <code>[wpoh_news grid="list"]</code>
* Added pagination [wpoh_news limit="10"]
* Added new shortcode parameters ie **show_content, show_category_name and content_words_limit**
* Added Widget Options like Show News date, Show News Categories, Select News Categories.
* Added new shortcode parameters **show_date**
* Added shortcode parameter **show_full_content**
* Added translation in German, French (France), Polish languages (Beta)
== Installation ==

1. Upload the 'wp-news-post-with-scrolling-widgets' folder to the '/wp-content/plugins/' directory.
1. Activate the wp-news-post-with-scrolling-widgets plugin through the 'Plugins' menu in WordPress.
1. Add and manage news items on your site by clicking on the  'News Post' tab that appears in your admin menu.
1. Create a page with the any name and paste this short code  <code> [wpoh_news] </code>.

== Frequently Asked Questions ==

= Can I filter the list of news items by date? =

Yes. Just as you can display a list of your regular posts by year, month, or day, you can display news items for a particular year (/news/2017/), month (/news/2017/08/), or day (/news/2017/08/22/).

= Do I need to update my permalinks after I activate this plugin? =

No, not usually. But if you are geting "/news" page OR 404 error on single news then please  update your permalinks to Custom Structure.   

= Are there shortcodes for news items? =

Yes  <code> [wpoh_news] </code>

== Screenshots ==

1. Add new News
2. List of all News
3. Display News with grid view
4. Widgets and Widgets Options
5. Display News in List with image view, only List view, List with scrolling view,


== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial release.