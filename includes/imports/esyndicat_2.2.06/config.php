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
			'unique_tpl' => 'unique_tpl',
			'level' => 'level',
			'num_cols' => 'num_cols',
			'num_neighbours' => 'num_neighbours',
			'num_listings' => 'num_listings',
			'num_all_listings' => 'num_all_listings',
			'clicks' => 'clicks',
			'no_follow' => 'no_follow',
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
			'sponsored_plan_id' => 'plan_id',
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
			'clicks' => 'clicks',
			'partner' => 'partner',
			'partners_start' => 'partner_start',
			'featured' => 'featured',
			'feature_start' => 'featured_start',
			'sponsored' => 'sponsored',
			'sponsored_start' => 'sponsored_start',
			'title' => 'title',
			'last_check_date' => 'last_check_date',
			'cron_cycle' => 'cron_cycle',
			'fav_accounts_set' => 'fav_accounts_set',
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
			'order' => 'order',
			'lang' => 'lang',
			'deep_links' => 'deep_links',
			'multicross' => 'multicross',
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
			'listing_id' => 'item_id',
			'account_id' => 'account_id',
			'plan_id' => 'plan_id',
			'payment_type' => 'payment_gateway',
			'email' => 'email',
			'order_number' => 'order_number',
			'total' => 'total',
			'date' => 'date',
		),
		'limit' => 100,
	),
);
