{function name=menu level=0}
{if $category}
	{assign var="page_name" value="index_browse|"|cat:$category.id}
{/if}
{if isset($menus)}
	<ul class="level_{$level} {$class}">
		{foreach $data as $menu}
			{if $menu@iteration > $config.max_top_menu_items|default:5 && $level < 1 && 'mainmenu' == $place}{capture append='dropdown'}{/if}
			<li class="m_{$menu.name} 
				{if isset($menu.children) && $menu@iteration <= $config.max_top_menu_items|default:5} dropdown{/if}
				{if isset($menu.children) && $menu@iteration > $config.max_top_menu_items|default:5} dropdown-submenu{/if}
				{if $level >= 1 && isset($menu.children)} dropdown-submenu{/if}
				{if $smarty.const.IA_REALM == $menu.name or $page_name == $menu.name} active{/if}
				">
				<a href="{if $menu.url}{$menu.url}{else}{$smarty.const.IA_SELF}#{/if}" 
					{if isset($menu.children)}class="dropdown-toggle" data-toggle="dropdown" data-target="#"{/if} 
					{if $menu.nofollow == '1'}rel="nofollow"{/if} 
					{if $menu.new_window == '1'}target="_blank"{/if}
					>
					{$menu.title}{if isset($menu.children) && $level < 1 && $menu@iteration <= $config.max_top_menu_items|default:5} <b class="caret"></b>{/if}
				</a>
				{if isset($menu.children)}
					{menu data=$menu.children level=$level+1 class="dropdown-menu"}
				{/if}
			</li>
			{if $menu@iteration > $config.max_top_menu_items|default:5 && $level < 1 && 'mainmenu' == $place}{/capture}{/if}
		{/foreach}
		{if $dropdown && $level < 1}
			<li class="m_more dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					{lang key='main_menu_more'}
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu pull-right" role="menu">
					{foreach $dropdown as $menu}
						{$menu}
					{/foreach}
				</ul>
			</li>
		{/if}
		{if $config.accounts && 'inventory' == $place && $level == 0}
			<li class="nav-login dropdown{if 'account_login' == $smarty.const.IA_REALM} active{/if}">
				{if $esynAccountInfo}
					<a href="{$smarty.const.IA_URL}logout.php?action=logout">{lang key='logout'}&nbsp;[{$esynAccountInfo.username}]</a>
				{else}
					{if 'account_login' == $smarty.const.IA_REALM}
						<a href="{$smarty.const.IA_URL}login.php">{lang key='login'}</a>
					{else}
						<a href="{$smarty.const.IA_URL}login.php" data-toggle="dropdown" class="dropdown-toggle" data-target="#">{lang key='login'}</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a class="close" href="#">&times;</a>
								<form action="{$smarty.const.IA_URL}login.php" method="post" class="form">
									<label>{lang key='username'}:
										<input type="text" tabindex="1" class="input-block-level" name="username" size="18" value="" />
									</label>
									<label>{lang key='password'}:
										<input type="password" tabindex="2" class="input-block-level" name="password" size="18" value="" />
									</label>
									<button type="submit" class="btn btn-block btn-warning" tabindex="6" name="login" value="login">{lang key='login'}</button>

									<div class="actions">
										<a href="{$smarty.const.IA_URL}register.php" rel="nofollow">{lang key='register'}</a>
										<a href="{$smarty.const.IA_URL}forgot.php">{lang key='forgot'}</a>
									</div>
								</form>
							</li>
						</ul>
					{/if}
				{/if}
			</li>
		{/if}

		{if $config.accounts && 'account' == $block.name && $esynAccountInfo}
			<li class="m_logout"><a href="{$smarty.const.IA_URL}logout.php?action=logout">{lang key='logout'}&nbsp;[{$esynAccountInfo.username}]</a></li>
		{/if}
	</ul>
{/if}
{/function}

{menu data=$menus class=$class}