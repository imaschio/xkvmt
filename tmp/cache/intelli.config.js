intelli.config = {"htaccessfile_0":"<IfModule mod_expires.c>\r\n\tExpiresActive On\r\n\tExpiresDefault \"access plus 1 year\"\r\n<\/IfModule>\r\n\r\n<IfModule mod_deflate.c>\r\n\tAddOutputFilterByType DEFLATE \\\r\n\t\ttext\/html text\/plain text\/xml text\/css text\/javascript \\\r\n\t\tapplication\/xml application\/xhtml+xml application\/rss+xml \\\r\n\t\tapplication\/javascript application\/x-javascript \\\r\n\t\timage\/svg+xml font\/opentype application\/x-font-ttf\r\n<\/IfModule>\r\n\r\n# Uncomment next line if you get 404 error on accounts page\r\n# Options -MultiViews\r\n\r\n<IfModule mod_rewrite.c>\r\n# enable mod_rewrite\r\nRewriteEngine on\r\n\r\n","htaccessfile_1":"# SECTION 1\r\n# correct urls for yahoo bot\r\nRewriteCond %{REQUEST_URI} !\\..+$\r\nRewriteCond %{REQUEST_URI} !\/$\r\nRewriteRule (.*) %{REQUEST_URI}\/ [R=301,L]\r\n\r\n# mod_rewrite rules for plugins\r\nRewriteCond %{REQUEST_FILENAME} -f\r\nRewriteRule ^mod\/(.*)(\\.xml|\\.php([0-9]*)|\\.tpl|\\.phtml|\\.ini|\\.inc|\/)$ controller.php?_p=$1 [QSA,L]\r\n\r\nRewriteCond %{REQUEST_FILENAME} !-f\r\nRewriteRule ^mod\/(.*)$ controller.php?_p=$1 [QSA,L]\r\n\r\n# mod_rewrite rules for view account page\r\nRewriteRule ^accounts\/$ accounts.php [QSA,L]\r\nRewriteRule ^accounts\/(.*)\/$ accounts.php?alpha=$1 [QSA,L]\r\nRewriteRule ^accounts\/index([0-9]+).html$ accounts.php?page=$1 [QSA,L]\r\nRewriteRule ^accounts\/(.*)\/index([0-9]+).html$ accounts.php?alpha=$1&page=$2 [QSA,L]\r\nRewriteRule ^accounts\/(.*).html$ view-account.php?account=$1 [QSA,L]\r\n\r\nRewriteRule ^sitemap.xml$ tmp\/sitemap\/google\/sitemap.xml [QSA,L]\r\nRewriteRule ^sitemap([0-9]+).xml$ tmp\/sitemap\/google\/sitemap$1.xml [QSA,L]\r\nRewriteRule ^sitemap_index.xml$ tmp\/sitemap\/google\/sitemap_index.xml [QSA,L]\r\nRewriteRule ^urllist.txt$ tmp\/sitemap\/yahoo\/urllist.txt [QSA,L]\r\n\r\n# mod_rewrite rules for alphabetic search\r\nRewriteRule ^alpha\/(.*)\/$ search.php?alpha=$1 [QSA,L]\r\nRewriteRule ^alpha\/(.*)\/index([0-9]+).html$ search.php?alpha=$1&page=$2 [QSA,L]\r\n\r\n","htaccessfile_2":"# SECTION 2\r\n# mod_rewrite rules for ROOT category\r\nRewriteRule ^index([0-9]+).html$ index.php?category=0&page=$1 [QSA,L]\r\n\r\n","htaccessfile_3":"# SECTION 3\r\n# mod_rewrite rules for categories pages with HTML path option disable\r\nRewriteRule ^(.*)\/$ index.php?category=$1 [QSA,L]\r\nRewriteRule ^(.*)\/index([0-9]+).html$ index.php?category=$1&page=$2 [QSA,L]\r\n\r\n","htaccessfile_4":"# SECTION 4\r\nRewriteRule ^([a-z]+)-listings.html$ listings.php?view=$1 [QSA,L]\r\nRewriteRule ^([a-z]+)-listings([0-9]+).html$ listings.php?view=$1&page=$2 [QSA,L]\r\n\r\n","htaccessfile_5":"# SECTION 5\r\n# mod_rewrite rules for view listing page\r\nRewriteRule ^([^\/]+)-l([0-9]+).html$ view-listing.php?cat=&title=$1&id=$2 [QSA,L]\r\nRewriteRule ^(.*)\/([^\/]+)-l([0-9]+).html$ view-listing.php?cat=$1&title=$2&id=$3 [QSA,L]\r\n\r\n","htaccessfile_6":"# SECTION 6\r\n# mod_rewrite rules for error pages\r\nRewriteRule ^([0-9]+).htm$ error.php?error=$1 [QSA,L]\r\n\r\n","htaccessfile_7":"# SECTION 7\r\n# mod_rewrite rules for add category page\r\nRewriteRule ^suggest-category-([0-9]+).html$ suggest-category.php?id=$1 [QSA,L]\r\n\r\n","htaccessfile_8":"# SECTION 8\r\nRewriteRule ^LICENSE.htm$ LICENSE.htm [QSA,L]\r\n\r\n","htaccessfile_9":"# SECTION 9\r\n# mod_rewrite rules for categories pages\r\nRewriteRule ^(.*)_([0-9]+).html$ index.php?category=$1&page=$2 [QSA,L]\r\nRewriteRule ^(.*).html?(.*)$ index.php?category=$1&$2 [QSA,L]\r\nRewriteRule ^(.*).html$ index.php?category=$1 [QSA,L]\r\n<\/IfModule>\r\n\r\n","htaccessfile_10":"# SECTION 10\r\nErrorDocument 500 500.htm\r\nErrorDocument 404 404.htm\r\nErrorDocument 403 403.htm\r\nErrorDocument 401 401.htm","display_twitter":"1","display_feedbacks":"1","display_changelog":"1","accounts":"1","accounts_autoapproval":"1","allow_listings_submit_guest":"1","allow_categories_submit_guest":"1","allow_delete_accounts":"0","num_get_accounts":"10","account_listing_limit":"100","suggest_category":"1","use_html_path":"0","related":"1","neighbour":"1","show_children_listings":"1","hide_empty_categories":"0","categories_order":"title","num_categories_cols":"2","num_listings_display":"0","subcats_display":"3","num_cats_for_search":"10","categories_tree_type":"tree","categories_icon_display":"1","categories_icon_width":"32","categories_icon_height":"32","cron_recip":"0","cron_broken":"1","cron_pagerank":"1","cron_num_listings":"10","cron_report_job":"0","cron_report_job_extra":"0","cron_report_cycle":"0","cron_report_cycle_extra":"0","cron_backup":"1","cron_backup_archive":"1","cron_backup_interval":"60","cron_backup_last_time":"1426025127","cron_duplicate":"0","cron":"1","sponsored_listings":"1","sponsored_accounts":"1","currency_symbol":"\u20ac","sponsored_only":"0","num_sponsored_display":"3","version":"3.3.0","site":"PortaleSEO: Indicizzazione - SEO","suffix":"","site_email":"support@seoportale.com","site_logo":"img_5f0eea26c7.png","site_watermark":"","site_watermark_position":"bottom_right","site_main_content":"<p><strong><a href=\"http:\/\/seoportale.com\/suggest-listing.php?cid=0\">Segnala il tuo sito qui<\/a>! <\/strong>PortaleSEO &egrave; un servizio utile ai webmaster e non solo: promozione del tuo sito internet gratis, indicizzazione, visibilit&agrave; SEO e strumenti webmaster<\/p>\r\n","site_description":"Segnala il tuo sito qui! PortaleSEO \u00e8 un servizio utile ai webmaster e non solo: promozione del tuo sito internet gratis, indicizzazione e visibilit\u00e0 SEO","site_keywords":"PortaleSEO, seo, indicizzazione, directory, popolarit\u00e0, webmaster","backup":"backup\/","tmpl":"gelio","admin_tmpl":"default","lang":"it","charset":"UTF-8","date_format":"%b %e, %Y","title_breadcrumb":"1","language_switch":"1","lowercase_urls":"1","alias_urlencode":"0","external_blank_page":"1","external_no_follow":"0","display_frontend":"1","underconstruction":"We are sorry. Our site is under construction.","old_pages_urls":"0","config_keys":"recaptcha_publickey,recaptcha_privatekey,sendmail_path,smtp_secure_connection,smtp_server,smtp_user,smtp_password","default_categories_icon":"","date_format_js":"M, d, Y H:i:s","logo_height":"20","allow_listings_submission":"1","allow_listings_deletion":"0","autometafetch":"1","auto_approval":"0","new_window":"1","forward_to_listing_details":"1","pagerank":"1","viewlisting_fields":"0,1,3,4","deep_links_validate":"0","count_crossed_listings":"0","listing_marked_as_new":"15","num_index_listings":"12","num_get_listings":"12","num_featured_display":"5","num_partner_display":"3","visitor_sorting":"1","listings_sorting":"date","listings_sorting_type":"ascending","expire_notif":"","expire_period":"","expire_action":"","broken_listings_report":"1","sort_listings_in_boxes":"site-wide","mcross_functionality":"0","mcross_number_links":"3","mcross_only_sponsored":"1","listing_check":"0","broken_visitors":"0","http_headers":"200,403,405","reciprocal_check":"0","reciprocal_link":"http:\/\/www.eannunci.com\/","reciprocal_required_only_for_free":"0","reciprocal_domain":"1","recip_featured":"1","reciprocal_visitors":"1","reciprocal_label":"To validate the reciprocal link please include the following HTML code in the page at the URL specified above, before submitting this form:","reciprocal_text":"<a href=\"http:\/\/seoportale.com\/\">eSyndiCat Directory<\/a>","duplicate_checking":"0","duplicate_type":"domain","duplicate_visitors":"1","email_groups":"account,listing,other,custom","mail_function":"php mail","mimetype":"html","smtp_port":"","account_admin_register":"1","account_approved":"1","account_banned":"0","account_change_email":"1","account_change_password":"1","account_confirm_change_email":"1","account_confirm_restore_password":"1","account_deleted":"0","account_disapproved":"0","account_register":"1","listing_admin_submit":"1","listing_approve":"1","listing_banned":"0","listing_delete":"0","listing_disapprove":"0","listing_modify":"1","listing_move":"1","listing_payment":"1","listing_submit":"1","listing_subscr_eot":"0","listing_subscr_payment":"1","listing_upgraded":"1","notif_account_confirmed":"0","notif_account_payment":"0","notif_account_register":"0","notif_category_submit":"1","notif_listing_broken_report":"1","notif_listing_payment":"1","notif_listing_submit":"1","notif_listing_subscr_eot":"0","notif_listing_subscr_payment":"1","notif_listing_upgraded":"1","payment_expiration":"1","ckeditor_code_highlighting":"0","ckeditor_default_language":"auto","ckeditor_ui_color":"#bbd2e0","captcha":"1","captcha_name":"kcaptcha","captcha_preview":"","thumbshot":"1","thumbshots_name":"pagepeeker","lightbox_name":"fancybox","stw_size":"lg","captcha_case_sensitive":"1","fancybox_slide_transition":"elastic","fancybox_open_transition":"fade","fancybox_slide_speed":"fast","fancybox_open_speed":"fast","stw_cache_days":"30","prettyphoto_show_title":"1","alphabetic_search":"1","gelio_social_f":"https:\/\/www.facebook.com\/portaleseo","gelio_social_g":"https:\/\/plus.google.com\/115108970085473240598","gelio_social_t":"https:\/\/twitter.com\/PortaleSEO","max_top_menu_items":"7","ckeditor_color":"#AAB","esyndicat_block_positions":"inventory,mainmenu,header,verytop,left,top,center,user1,user2,bottom,right,verybottom,footer1,footer2,footer3,footer4,copyright","off_line_payment":"0","off_line_payment_send_email":"1","off_line_payment_msg":"Your Bank Account details {url_listing} {payment_for_submit}","banners_payment":"0","num_verytop_banners":"1","num_verybottom_banners":"1","num_belowcategories_banners":"1","num_inventory_banners":"1","num_left_banners":"1","num_right_banners":"1","num_top_banners":"1","num_mainmenu_banners":"1","num_copyright_banners":"1","num_bottom_banners":"1","num_user1_banners":"1","num_user2_banners":"1","num_footer1_banners":"1","num_footer2_banners":"1","num_footer3_banners":"1","num_footer4_banners":"1","banner_guests_submit":"1","banner_prefix":"banner_","banners_order":"random","stw_full_length":"0","googlemap_longtitude":"12.482269","googlemap_latitude":"41.895623","googlemap_show_without_data":"0","googlemap_map_width":"100%","googlemap_map_height":"400px","googlemap_default_zoom":"16","googlemap_append_field":"address","googlemap_bgclassname":"gmap-marker","googlemap_bgcolor":"#dbe8f7","googlemap_minwidth":"150","googlemap_maxwidth":"250","googlemap_bordercolor":"#aa7","googlemap_borderradius":"4","googlemap_borderwidth":"1","notif_comment_added":"1","tags_convert_utf_ascii":"0","use_title_for_tags":"0","use_description_for_tags":"0","tags_count":"30","num_tags_display":"6","tags_count_all":"500","tags_size":"80-240","stw_custom_quality":"95","stw_custom_delay":"5","stw_refresh_ondemand":"1","stw_wide_screen":"","stw_max_height":"","stw_custom_resolution":"","num_top_cat":"8","num_popular_cat":"8","comments_news_rating":"1","comments_news_rating_block_max":"10","comments_news_max_chars":"300","comments_news_min_chars":"10","comments_news_approval":"0","comments_news_html":"0","comments_news_accounts":"1","comments_allow_news_submission":"1","comments_articles_rate_period":"1","comments_articles_rating_block_max":"10","comments_articles_rating":"1","comments_articles_max_chars":"300","comments_articles_min_chars":"10","comments_articles_approval":"0","comments_articles_html":"0","comments_articles_accounts":"1","comments_allow_articles_submission":"1","comments_listings_rate_period":"1","comments_listings_rating_block_max":"10","comments_listings_rating":"1","comments_listings_max_chars":"300","comments_listings_min_chars":"10","comments_listings_approval":"0","comments_listings_html":"0","comments_listings_accounts":"1","comments_allow_listings_submission":"1","bad_words":"fuck, shit, cunt, viagra, cialis","bad_words_checking":"1","pagepeeker_format":"gif","tell_friend":"1","notif_tell_friend":"1","tell_friend_include_admin":"1","paypal_currency_code":"EUR","paypal_email":"admin@seoportale.com","paypal_demo":"0","paypal_secret_word":"0d47b425dd366f39cc075512b862b6fb","thumbshotsorg_api_key":"\/65MQ6nHRCo=","spider_google_api":"","captcha_num_chars":"5","display_slider":"1","num_slides":"5","slideshow_speed":"7000","animation_speed":"600","slider_animation":"slide","slider_direction":"horizontal","comments_news_rate_period":"1","comments_num_latest_comments":"5","comments_rating":"1","comments_rating_max":"10","fb_like":"1","fb_like_faces":"1","fb_comments":"0","stw_secret_key":"8484d","stw_access_key":"b1424414a24523d","prettyphoto_socialtools":"","prettyphoto_anim_speed":"normal","prettyphoto_style":"dark_rounded","num_related_listings":"5","num_top_listings":"5","num_random_listings":"5","num_new_listings":"7","ip_block_text":"We are sorry. Your IP address is blocked.","ip_block_list":"These\r\nare\r\nyour\r\nIP\r\nadress\r\nparted\r\nby\r\nnew line","enable_block_list":"1","google_verification":"<meta name=\"google-site-verification\" content=\"_AvwYkWgJS3VZdeOD7Q8pXQIIMkolU7-9U5rGikHGb8\" \/>","google_analytics":"<script>\r\n  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\r\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n  })(window,document,'script','\/\/www.google-analytics.com\/analytics.js','ga');\r\n\r\n  ga('create', 'UA-60600240-1', 'auto');\r\n  ga('send', 'pageview');\r\n\r\n<\/script>","fb_admin_id":"","fb_num_comment":"15","fb_comments_width":"400","fb_app_id":"355505111310792","fb_app_secret":"d5afa1a590125599bf49bd2db51ab9aa","webthumbnail_height":"200","webthumbnail_width":"200","webthumbnail_format":"gif","webthumbnail_browser":"chrome","stw_branded":"0","websnapr_api_key":"E899gGrcr2wL","esyn_url":"http:\/\/seoportale.com\/"};