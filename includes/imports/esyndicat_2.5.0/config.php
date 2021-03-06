<?php

// set of items to be migrated
$items_map = array(
	'accounts' => array(
		'table' => array(
			// source table => destination table
			'accounts' => 'accounts'
		),
		'fields' => array(
			// source table field name => destination table field name
			'id' => 'id',
			'username' => 'username',
			'password' => 'password',
			'email' => 'email',
			'nemail' => 'nemail',
			'sec_key' => 'sec_key',
			'status' => 'status',
			'date_reg' => 'date_reg',
		),
		'limit' => 100,
	),

	'admins' => array(
		'table' => array(
			// source table => destination table
			'admins' => 'admins'
		),
		'fields' => array(
			// source table field name => destination table field name
			'id' => 'id',
			'username' => 'username',
			'password' => 'password',
			'fullname' => 'fullname',
			'email' => 'email',
			'submit_notif' => 'submit_notif',
			'payment_notif' => 'payment_notif',
			'super' => 'super',
			'status' => 'status',
			'confirmation' => 'confirmation',
			'date_reg' => 'date_reg',
			'last_visited' => 'last_visited',
			'state' => 'state',
		),
		'limit' => 100,
	),
	'blocks' => array(
		'table' => array(
			'blocks' => 'blocks'
		),
		'fields' => array(
			'name' => 'name',
			'title' => 'title',
			'contents' => 'contents',
			'order' => 'order',
			'lang' => 'lang',
			'position' => 'position',
			'type' => 'type',
			'plugin' => 'plugin',
			'status' => 'status',
			'show_header' => 'show_header',
			'collapsible' => 'collapsible',
			'collapsed' => 'collapsed',
			'sticky' => 'sticky',
			'multi_language' => 'multi_language',
			'rss' => 'rss',
		),
		'limit' => 100,
	),

	'categories' => array(
		'table' => array(
			'categories' => 'categories'
		),
		'fields' => array(
			'id' => 'id',
			'account_id' => 'account_id',
			'parent_id' => 'parent_id',
			'title' => 'title',
			'page_title' => 'page_title',
			'icon' => 'icon',
			'description' => 'description',
			'status' => 'status',
			'meta_description' => 'meta_description',
			'meta_keywords' => 'meta_keywords',
			'path' => 'path',
			'order' => 'order',
			'locked' => 'locked',
			'hidden' => 'hidden',
			'unique_tpl' => 'unique_tpl',
			'level' => 'level',
			'num_cols' => 'num_cols',
			'num_neighbours' => 'num_neighbours',
			'num_listings' => 'num_listings',
			'num_all_listings' => 'num_all_listings',
			'clicks' => 'clicks',
			'no_follow' => 'no_follow',
			'confirmation' => 'confirmation',
			'confirmation_text' => 'confirmation_text',
		),
		'extra_tables' => array(
			'category_clicks' => array(
				'name' => 'category_clicks',
				'fields' => array(
					'id' => 'id',
					'category_id' => 'category_id',
					'ip' => 'ip',
					'date' => 'date',
				)
			),
			'crossed' => array(
				'name' => 'crossed',
				'fields' => array(
					'id' => 'id',
					'category_id' => 'category_id',
					'category_title' => 'category_title',
					'crossed_id' => 'crossed_id',
				),
			),
			'related' => array(
				'name' => 'related',
				'fields' => array(
					'id' => 'id',
					'category_id' => 'category_id',
					'related_id' => 'related_id',
				),
			)
		),
		'limit' => 100,
	),

	'listings' => array(
		'table' => array(
			'listings' => 'listings'
		),
		'fields' => array(
			'id' => 'id',
			'account_id' => 'account_id',
			'category_id' => 'category_id',
			'plan_id' => 'plan_id',
			'transaction_id' => 'transaction_id',
			'moved_from' => 'moved_from',
			'domain' => 'domain',
			'url' => 'url',
			'description' => 'description',
			'email' => 'email',
			'reciprocal' => 'reciprocal',
			'recip_valid' => 'recip_valid',
			'ip_address' => 'ip_address',
			'listing_header' => 'listing_header',
			'status' => 'status',
			'pagerank' => 'pagerank',
			'rank' => 'rank',
			'date' => 'date',
			'last_modified' => 'last_modified',
			'clicks' => 'clicks',
			'partner' => 'partner',
			'partner_start' => 'partner_start',
			'featured' => 'featured',
			'featured_start' => 'featured_start',
			'sponsored' => 'sponsored',
			'sponsored_start' => 'sponsored_start',
			'title' => 'title',
			'last_check_date' => 'last_check_date',
			'cron_cycle' => 'cron_cycle',
			'fav_accounts_set' => 'fav_accounts_set',
			'meta_description' => 'meta_description',
			'meta_keywords' => 'meta_keywords',
			'duplicate' => 'duplicate',
			'date_del' => 'date_del',
			'expire_date' => 'expire_date',
			'expire_notif' => 'expire_notif',
			'expire_action' => 'expire_action',
			'expire_salt' => 'expire_salt',
		),
		'extra_tables' => array(
			'listing_clicks' => array(
				'name' => 'listing_clicks',
				'fields' => array(
					'id' => 'id',
					'listing_id' => 'listing_id',
					'ip' => 'ip',
					'date' => 'date',
				)
			),
			'deep_links' => array(
				'name' => 'deep_links',
				'fields' => array(
					'id' => 'id',
					'listing_id' => 'listing_id',
					'title' => 'title',
					'url' => 'url',
				)
			),
			'listing_categories' => array(
				'name' => 'listing_categories',
				'fields' => array(
					'listing_id' => 'listing_id',
					'category_id' => 'category_id',
				),
			)
		),
		'limit' => 100,
	),

	'plans' => array(
		'table' => array(
			'plans' => 'plans'
		),
		'fields' => array(
			'id' => 'id',
			'title' => 'title',
			'description' => 'description',
			'cost' => 'cost',
			'period' => 'period',
			'status' => 'status',
			'order' => 'order',
			'lang' => 'lang',
			'deep_links' => 'deep_links',
			'multicross' => 'multicross',
			'mark_as' => 'mark_as',
			'pages' => 'pages',
			'item' => 'item',
			'expire_notif' => 'expire_notif',
			'expire_action' => 'expire_action',
			'recursive' => 'recursive',
		),
		'extra_tables' => array(
			'plan_categories' => array(
				'name' => 'plan_categories',
				'fields' => array(
					'id' => 'id',
					'plan_id' => 'plan_id',
					'category_id' => 'category_id',
				),
			)
		),
		'limit' => 100,
	),
	'transactions' => array(
		'table' => array(
			'transactions' => 'transactions'
		),
		'fields' => array(
			'id' => 'id',
			'item_id' => 'item_id',
			'account_id' => 'account_id',
			'plan_id' => 'plan_id',
			'plan_title' => 'plan_title',
			'payment_gateway' => 'payment_gateway',
			'email' => 'email',
			'order_number' => 'order_number',
			'total' => 'total',
			'date' => 'date',
			'status' => 'status',
			'item' => 'item',
			'plugin' => 'plugin',
			'method' => 'method',
			'sec_key' => 'sec_key',
			'subscr_id' => 'subscr_id',
			'pending_reason' => 'pending_reason',
		),
		'limit' => 100,
	),
);
