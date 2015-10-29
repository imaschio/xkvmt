{assign var='place' value=$block.position}

{if in_array($place, array('inventory', 'copyright'))}
	{include file="menu-ul.tpl" menus=$block.contents class="nav nav-pills pull-right"}
{elseif 'mainmenu' == $place}
	{include file="menu-ul.tpl" menus=$block.contents class="nav nav-pills"}
{elseif in_array($place, array('left', 'right', 'user1', 'user2', 'top', 'verytop', 'bottom', 'verybottom', 'center'))}
	{if 'account' == $block.name && !empty($esynAccountInfo)}
	<div class="clearfix account-block">
		<div class="media ia-item pull-left">
			{if $esynAccountInfo.avatar}
				{print_img ups=true fl=$esynAccountInfo.avatar full=true title=$esynAccountInfo.username class='avatar'}
			{else}
				{print_img fl='no-avatar.png' full=true class='avatar'}
			{/if}
		</div>
		<div class="pull-left">
			{lang key='welcome'},<br />
			<strong>{$esynAccountInfo.username}</strong>!
			<p>{lang key='listings'}: {$esynAccountInfo.num_listings}</p>
		</div>
	</div>
	{/if}
	
	{include file="menu-ul.tpl" menus=$block.contents class="nav nav-pills nav-stacked"}
{else}
	<!--__ms_{$block.id}-->
	{if $block.show_header || isset($manageMode)}
		<div class="menu_header">{$block.title}</div>
	{else}
		<div class="menu">
	{/if}

	<!--__ms_c_{$block.id}-->
	{include file="menu-ul.tpl" menus=$block.contents class="nav nav-links nav-stacked nav-actions"}
	<!--__me_c_{$block.id}-->

	</div>
	<!--__me_{$block.id}-->
{/if}