<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<title>{if isset($gTitle)}{$gTitle}{/if}&nbsp;{$config.suffix}</title>
	<meta http-equiv="Content-Type" content="text/html;charset={$config.charset}">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7;">
	<base href="{$smarty.const.IA_ADMIN_URL}">
	<link rel="shortcut icon" href="{$smarty.const.IA_URL}favicon.ico">

	{include_file css="js/ext/resources/css/ext-all, {$smarty.const.IA_ADMIN_DIR}/templates/{$config.admin_tmpl}/css/style, js/jquery/plugins/jreject/css/jquery.reject"}

	{if isset($css)}
		{include_file css=$css}
	{/if}

	{include_file js='js/ext/ext-base, js/ext/ext-all, js/jquery/jquery, js/jquery/plugins/jquery.interface, js/jquery/plugins/jreject/js/jquery.reject'}
	{include_file js="js/intelli/intelli, js/intelli/intelli.admin, {$smarty.const.IA_TMP_NAME}/cache/intelli.config, {$smarty.const.IA_TMP_NAME}/cache/intelli.admin.lang.{$config.lang}"}

	{if isset($js)}
		{include_file js=$js}
	{/if}

	{ia_hooker name='adminHeadSection'}

	<script type="text/javascript">
		intelli.admin.lang = intelli.admin.lang['{$config.lang}'];
		$(function($)
		{
			$.reject({
				reject: {  
					firefox1: true, 
					firefox2: true,
					konqueror: true,
					chrome1: true, 
					chrome2: true,
					opera7: true,
					opera8: true,
					opera9: true,
					opera10: true,
					msie5: true, 
					msie6: true, 
					msie7: true,
					msie8: true
				},
				imagePath: '{$smarty.const.IA_URL}js/jquery/plugins/jreject/images/',
				display: ['firefox','chrome','opera','safari']
			});
		});
	</script>
</head>

<body>

<noscript>
	<div class="js_notification">{$esynI18N.error_javascript}</div>
</noscript>

<!-- header start -->
<div class="header">

	<div class="logo"><a href="http://www.esyndicat.com/"><img src="templates/{$config.admin_tmpl}/img/logos/logo.png" alt="eSyndiCat Directory Software" title="eSyndiCat Directory Script"/></a></div>

	<div class="header-buttons">
		<ul>
			<li><a class="inner" href="{$smarty.const.IA_URL}" target="_blank">{$esynI18N.directory_home}</a></li>
		</ul>
	</div>

	<div class="login-info">
		<ul>
			<li>{$esynI18N.howdy}, <a href="controller.php?file=admins&amp;do=edit&amp;id={$currentAdmin.id}">{$currentAdmin.username}</a></li>
			<li><a class="logout" href="logout.php" id="admin_logout">{$esynI18N.logout}</a></li>
		</ul>
	</div>

	<!-- header menu start -->
	<div class="header-menus">
		<div class="h-menu">
			<div class="h-menu-inner">
				<div class="jump-to">
					<span style="float:left;"><a>{$esynI18N.quick_jump_to}</a></span>
					<span class="h-arrow">&nbsp;</span>
					<div style="clear:both;"></div>
				</div>
				<div class="h-submenu">
				{foreach $adminHeaderMenu as $item}
					{if 'divider' == $item.block_name}
						<div class="h-divider"></div>
					{else}
						<a href="{$item.href}" {if isset($item.attr) && !empty($item.attr)}{$item.attr}{/if}>{$item.text}</a>
					{/if}
				{/foreach}
				</div>
			</div>
		</div>
	</div>
	<!-- header menu end -->
</div>
<!-- header end -->

<div class="top-menu" id="top_menu">{if isset($update_msg) && !empty($update_msg)}{$update_msg}{/if}</div>

<!-- content start -->
<div class="content" id="mainCon">

	<!-- left column start -->
	<div class="left-column" id="left-column">
		{foreach from=$adminMenu key=key item="menu"}
			<!-- menu start -->
			<div class="menu dragGroup" id="menu_box_{$menu.name}" {if !isset($menu.items)}style="display:none;"{/if}>
				<div class="inner">
					<div class="menu-caption">{$menu.text}</div>
					<div class="minmax white-{if isset($menu.open)}{$menu.open}{else}open{/if}" id="amenu_{$menu.name}"></div>
					<div class="box-content" style="padding: 0; {if isset($menu.open) && $menu.open == 'close'}display:none;{/if}" >
						<ul class="menu" id="menu_{$menu.name}">
						{if isset($menu.items) && !empty($menu.items)}
							{foreach $menu.items as $item}
								<li{if $smarty.const.IA_REALM == $item.aco} class="active"{/if}><a {print_icon_url realm=$item.aco path='menu'} href="{$item.href}" {if isset($item.attr) && !empty($item.attr)}{$item.attr}{/if} class="submenu">{$item.text}</a></li>
							{/foreach}
						{/if}
						</ul>
					</div>
				</div>
			</div>
			<!-- menu end -->
		{/foreach}
	</div>
	<!-- left column end -->

	<!-- right column start -->
	<div class="right-column">

		{if isset($esyn_tabs) && !empty($esyn_tabs)}
			<div class="empty-div"></div>
			{foreach $esyn_tabs as $esyn_tab}
				<div class="tab-content {$esyn_tab.name}" id="esyntab-content-{$esyn_tab.name}">
					<div class="tab-content-inner">
						{assign var="esyn_tab_content_key" value="tab_content_{$esyn_tab.name}"}
						{$esynI18N.$esyn_tab_content_key}
					</div>
				</div>
			{/foreach}

			{foreach $esyn_tabs as $esyn_tab}
				<div class="tab-shortcut" id="esyntab-shortcut-{$esyn_tab.name}">
					<div class="tab-shortcut-inner">
						{assign var="esyn_tab_title_key" value="tab_title_{$esyn_tab.name}"}
						<div class="tab-icon tab-icon-{$esyn_tab.name}">{$esynI18N.$esyn_tab_title_key}</div>
					</div>
				</div>
			{/foreach}
		{/if}

		{$breadcrumb}

		{if isset($gTitle)}<h1 class="common"{print_icon_url realm=$smarty.const.IA_REALM header=true}>{$gTitle}</h1>{/if}

		{include file='buttons.tpl'}

		{if isset($notifications) && !empty($notifications)}
			{include file='notification.tpl' msg=$notifications id='notif'}
		{/if}

		{include file='notification.tpl' messages=$messages}