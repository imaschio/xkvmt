{if isset($esyndicat_actions) && !empty($esyndicat_actions)}
	<ul class="nav nav-actions">
		{foreach $esyndicat_actions as $name => $action}
			{if 'favorites' == $name}
				{if $esynAccountInfo && $esynAccountInfo.id != $listing.account_id}
					<li>
					{assign var='fav_array' value=','|explode:$listing.fav_accounts_set}
					{if !empty($fav_array) && in_array($esynAccountInfo.id, $fav_array)}
						<a href="#" class="js-favorites" data-id="{$listing.id}" data-account="{$esynAccountInfo.id}" data-action="remove">{lang key='remove_from_favorites'}</a>
					{else}
						<a href="#" class="js-favorites" data-id="{$listing.id}" data-account="{$esynAccountInfo.id}" data-action="add">{lang key='add_to_favorites'}</a>
					{/if}
					</li>
				{elseif $esynAccountInfo && $esynAccountInfo.id == $listing.account_id}
					<li><a href="{$smarty.const.IA_URL}suggest-listing.php?edit={$listing.id}" class="js-edit-listing" data-id="{$listing.id}" data-account="{$esynAccountInfo.id}" data-action="edit">{lang key='edit_listing'}</a></li>
				{/if}
			{else}
				<li><a href="{$action.url|default:'#'}" class="js-{$name}" data-id="{$listing.id}" data-listing-account="{$listing.account_id}" data-account="{$esynAccountInfo.id}" data-url="{$listing.url}">{lang key="esyndicat_action_{$name}"}</a></li>
			{/if}
		{/foreach}
	</ul>
{/if}