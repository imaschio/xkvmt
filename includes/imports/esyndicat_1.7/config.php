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
			'status' => 'status',
			'date_reg' => 'date_reg',
		),
		'limit' => 500,
	),
	'categories' => array(
		'table' => array(
			'categories' => 'categories'
		),
		'fields' => array(
			'id' => 'id',
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
			'num_cols' => 'num_cols',
			'clicks' => 'clicks',
		),
		'extra_tables' => array(
			'category_clicks' => array(
				'name' => 'category_clicks',
				'fields' => array(
					'id' => 'id',
					'category_id' => 'category_id',
					'ip' => 'ip',
					'date' => 'date',
				),
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
			'title' => 'title',
			'meta_description' => 'meta_description',
			'meta_keywords' => 'meta_keywords',
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

	'comments' => array(
		'table' => array(
			'comments' => 'comments'
		),
		'fields' => array(
			'id' => 'id',
			'account_id' => 'account_id',
			'listing_id' => 'item_id',
			'author' => 'author',
			'url' => 'url',
			'body' => 'body',
			'email' => 'email',
			'ip_address' => 'ip_address',
			'rating' => 'rating',
			'date' => 'date',
			'status' => 'status',
			'sess_id' => 'sess_id',
		),
		'extra_tables' => array(
			'votes' => array(
				'name' => 'votes',
				'fields' => array(
					'listing_id' => 'item_id',
					'vote_value' => 'vote_value',
					'ip_address' => 'ip_address',
					'date' => 'date',
				),
			)
		),
	),
);
