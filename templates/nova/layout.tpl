<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{$title} {$config.suffix}</title>
		<meta charset="{$config.charset}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="{if isset($description) && !empty($description)}{$description|escape:'html'}{/if}" />
		<meta name="keywords" content="{if isset($keywords) && !empty($keywords)}{$keywords|escape:'html'}{/if}" />
		<meta name="generator" content="eSyndiCat Business Directory Script {$smarty.const.IA_VERSION}" />
		<base href="{$smarty.const.IA_URL}" />

		{ia_print_css files=$css_files}

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="js/bootstrap/js/shiv.js" type="text/javascript"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$img}ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$img}ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$img}ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="{$img}ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="{$smarty.const.IA_URL}favicon.ico">
		<link rel="canonical" href="{$smarty.const.IA_CANONICAL}"/>

		<!-- RSS links inside category and view page -->
		{if isset($category) && $category.id >= 0}
			<link rel="alternate" type="application/rss+xml" title="{$category.title|escape:'html'}" href="{$smarty.const.IA_URL}feed.php?from=category&amp;id={$category.id}" />
		{/if}
		
		{if isset($view) && $view != 'random' && isset($listings)}
			<link rel="alternate" type="application/rss+xml" title="{$category.title|escape:'html'}" href="{$smarty.const.IA_URL}feed.php?from={$view}" />
		{/if}

		{ia_add_media files='jquery, bootstrap-awesome, intelli, js:js/jquery/plugins/flexslider/flexslider.min'}

		{ia_hooker name='headSection'}

		{if $config.logo_height}
			<style type="text/css">
				/*.nav-main { padding: {$config.logo_height}px 0 0; }*/
			</style>
		{/if}

		<!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="{$templates}css/ie.css"/>
		<![endif]-->

		<!-- CUSTOM STYLES -->
		<link rel="stylesheet" type="text/css" href="{$templates}css/user-style.css">

	</head>

	<body class="page-{$smarty.const.IA_REALM}">

		<div class="section section--inventory">
			<div class="container">
				{if $config.language_switch && count($languages) > 1}
					<ul class="nav nav-pills nav-langs pull-right">
						{foreach from=$languages key='code' item='language'}
							<li{if $smarty.const.IA_LANGUAGE == $code} class="active"{/if}><a href="{$smarty.const.IA_CANONICAL}?language={$code}" title="{$language}">{$language}</a></li>
						{/foreach}
					</ul>
				{/if}

				{if $config.nova_social}
					<ul class="nav nav-pills nav-social pull-left">
						{if !empty($config.nova_social_t)}<li class="twitter"><a href="{$config.nova_social_t}" title="Twitter"{if $config.nova_social_new_window} target="_blank"{/if}><i class="icon-twitter-sign"></i></a></li>{/if}
						{if !empty($config.nova_social_f)}<li class="facebook"><a href="{$config.nova_social_f}" title="Facebook"{if $config.nova_social_new_window} target="_blank"{/if}><i class="icon-facebook-sign"></i></a></li>{/if}
						{if !empty($config.nova_social_g)}<li class="gplus"><a href="{$config.nova_social_g}" title="Google+"{if $config.nova_social_new_window} target="_blank"{/if}><i class="icon-google-plus-sign"></i></a></li>{/if}

						{if isset($listings)}
							<li class="xml">
								{if isset($category.id)}
									<a href="{$smarty.const.IA_URL}feed.php?from=category&amp;id={$category.id}"><i class="icon-rss-sign"></i></a>
								{elseif in_array($view, array('popular', 'new', 'top'))}
									<a href="{$smarty.const.IA_URL}feed.php?from={$view}"><i class="icon-rss-sign"></i></a>
								{/if}
							</li>
						{/if}

						{ia_hooker name='tplFrontHeaderAfterRSS'}
					</ul>
				{/if}

				{include file='parse-blocks.tpl' pos=$blocks.inventory|default:null}

				{ia_hooker name='tplFrontAfterInventory'}
			</div>
		</div>

		<header class="section section--header">
			<div class="container">
				<div class="brand pull-left">
					<a class="logo" href="{$smarty.const.IA_URL}">
						{if $config.site_logo}
							{print_img ups=true fl=$config.site_logo full=true title=$config.site}
						{else}
							{print_img fl='logo.png' full=true alt='eSyndiCat Web Directory Software'}
						{/if}
					</a>
					<p class="slogan">{lang key='slogan'}</p>
				</div>

				{include file='parse-blocks.tpl' pos=$blocks.mainmenu|default:null}
			</div>
		</header>

		{include file='parse-blocks.tpl' pos=$blocks.slider|default:null}

		<div class="section section--light section--search">
			<div class="container">
				<form action="{$smarty.const.IA_URL}search.php" method="get" class="form-inline" id="searchFormBar">
					<label>{lang key='search_for'}
						<input type="text" class="span3" name="what" value="{if isset($smarty.get.what)}{$smarty.get.what|escape:'html'}{/if}" id="search_input" autocomplete="off">
					</label>
					
					<label>{lang key='search_in'}:
						<select name="search_category">
							<option value="0">{lang key='_select_'}</option>
							{foreach $top_level_categories as $top_level_category}
								<option value="{$top_level_category.id}"{if $smarty.get.search_category == $top_level_category.id}selected="selected"{/if}>{$top_level_category.title}</option>
							{/foreach}
						</select>
					</label>

					{ia_hooker name='tplFrontAfterSearchInputs'}

					<button type="submit" class="js-top-search-button btn btn-primary" title="{lang key='search'}"><i class="icon-search"></i> {lang key='search'}</button>
					<a href="{$smarty.const.IA_URL}search.php?adv=true" class="btn btn-success" title="{lang key='advanced_search'}"><i class="icon-cog"></i></a>
				</form>
			</div>
		</div>
		
		{if $config.alphabetic_search && !empty($search_alphas)}
			<div class="section section--narrow section--light section--alphas">
				<div class="container">
					<div class="alphabetic-search text-center">
						{foreach $search_alphas as $value}
							{if !isset($smarty.get.alpha) || $smarty.get.alpha != $value}
								<a href="{$smarty.const.IA_URL}alpha/{$value}/" class="btn btn-small">{$value}</a>
							{else}
								<span>{$value}</span>
							{/if}
						{/foreach}
					</div>
				</div>
			</div>
		{/if}

		{if isset($breadcrumb) && 'index' != $smarty.const.IA_REALM}
			<div class="section section--narrow section--breadcrumbs">
				<div class="container">{include file='breadcrumb.tpl'}</div>

				{ia_hooker name='afterBreadcrumb'}

			</div>
		{/if}

		<div class="container">
			<div class="js-groupWrapper" data-position="verytop">
				{include file='parse-blocks.tpl' pos=$blocks.verytop|default:null}
			</div>
		</div>

		<div id="content" class="section section--content">
			<div class="container">
				<div class="row">
					{if isset($blocks.left) && !empty($blocks.left)}
						<div class="js-groupWrapper span3" data-position="left">
							{include file='parse-blocks.tpl' pos=$blocks.left|default:null}
						</div>
					{/if}

					{if (!isset($blocks.left) && empty($blocks.left)) || (!isset($blocks.right) && empty($blocks.right))}
						<div class="span9">	
					{else}
						<div class="span6">	
					{/if}

						<div class="js-groupWrapper top-blocks" data-position="top">
							{include file='parse-blocks.tpl' pos=$blocks.top|default:null}
						</div>

						{ia_hooker name='beforeMainContent'}

						<div class="content-wrap">
							<h1>{$header|default:$title}</h1>

							{include file='notification.tpl'}
							
							{$_content_}

						</div>

						{ia_hooker name='afterMainContent'}

						<div class="content-bottom-blocks">
							<div class="row">
								<div class="js-groupWrapper span3" data-position="user1">
									{include file='parse-blocks.tpl' pos=$blocks.user1|default:null}
								</div>
								<div class="js-groupWrapper span3" data-position="user2">
									{include file='parse-blocks.tpl' pos=$blocks.user2|default:null}
								</div>
							</div>
						</div>

						<div class="js-groupWrapper bottom-blocks" data-position="bottom">
							{include file='parse-blocks.tpl' pos=$blocks.bottom|default:null}
						</div>
						
					</div><!-- /.span6 -->
					{if isset($blocks.right) && !empty($blocks.right)}
						<div class="js-groupWrapper span3" data-position="right">
							{include file="parse-blocks.tpl" pos=$blocks.right|default:null}
						</div>
					{/if}
				</div><!-- /.row -->
			</div><!-- /.container -->
		</div>

		{if isset($blocks.verybottom)}
			<div class="section section--light">
				<div class="container">
					<div class="js-groupWrapper verybottom-blocks" data-position="verybottom">
						{include file='parse-blocks.tpl' pos=$blocks.verybottom|default:null}
					</div>
				</div>
			</div>
		{/if}

		{if isset($blocks.footer1) || isset($blocks.footer2) || isset($blocks.footer3) || isset($blocks.footer4)}
			<div id="very-bottom" class="section section--dark">
				<div class="container">
					<div class="row">
						<div class="js-groupWrapper span3" data-position="footer1">
							{include file='parse-blocks.tpl' pos=$blocks.footer1|default:null}
						</div>
						<div class="js-groupWrapper span3" data-position="footer2">
							{include file='parse-blocks.tpl' pos=$blocks.footer2|default:null}
						</div>
						<div class="js-groupWrapper span3" data-position="footer3">
							{include file='parse-blocks.tpl' pos=$blocks.footer3|default:null}
						</div>
						<div class="js-groupWrapper span3" data-position="footer4">
							{include file='parse-blocks.tpl' pos=$blocks.footer4|default:null}
						</div>
					</div>
				</div>
			</div>
		{/if}

		<footer class="section section--narrow section--dark section--footer">
			<div class="container">
				{ia_hooker name='beforeFooterLinks'}

				<div class="row">
					<div class="span4">
						<p class="copyright">&copy; {$smarty.server.REQUEST_TIME|date_format:'%Y'} {lang key='copyright'}</p>
					</div>
					<div class="span8">
						{include file='parse-blocks.tpl' pos=$blocks.copyright|default:null}
					</div>
				</div>

				{ia_hooker name='afterFooterLinks'}
			</div>
		</footer>

		<div id="backToTop"><a href="#"><i class="icon-chevron-up"></i><br>{lang key='top'}</a></div>

		<noscript>
			<div class="js_notification">{lang key='error_javascript'}</div>
		</noscript>

		<!-- thumbs preview start -->
		<div class="thumb">
			<div class="loading" style="display: none;">{print_img fl='ajax-loader.gif' full=true class='spinner'}</div>
		</div>
		<!-- thumbs preview end -->

		{ia_hooker name='footerBeforeIncludeJs'}

		<!-- include mandatory javascript files -->
		{ia_add_media files='footer'}

		<script type="text/javascript">
			{if isset($phpVariables)}{$phpVariables}{/if}
			intelli.lang = intelli.lang['{$config.lang}'];
		</script>

		{if isset($messages.system)}
			{if isset($manageMode)}
				{ia_add_media files='managemode'}
			{/if}

			<div id="mod_box" class="managemode">
				{foreach $messages.system as $message}
					<p>{lang key=$message}</p>
				{/foreach}
			</div>
		{/if}

		{if $config.cron}<img src="cron.php" width="1" height="1" alt="" style="display:none">{/if}

		{ia_hooker name='beforeCloseTag'}

		{ia_print_js files=$js_files printcode=true}
	</body>
</html>