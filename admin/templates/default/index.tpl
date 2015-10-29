{include file="header.tpl"}

<div id="box_panels_content" style="margin-top: 15px;"></div>

<div id="box_statistics" style="display: none;">
	<table width="99%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="49%" valign="top">
			<table cellspacing="0" class="striped common">
			<tr>
				<th width="90%" class="first">{$esynI18N.listings}</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=suspended">{$esynI18N.suspended}</a>:
				</td>
				<td>
					<strong>{$listings[2].total}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=banned">{$esynI18N.banned}</a>:
				</td>
				<td>
					<strong>{$listings[1].total}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=approval">{$esynI18N.approval}</a>:
				</td>
				<td>
					<strong>{$listings[0].total}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=active">{$esynI18N.active}</a>:
				</td>
				<td>
					<strong>{$listings[3].total}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=deleted">{$esynI18N.deleted}</a>:
				</td>
				<td>
					<strong>{$listings[4].total}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=destbroken">{$esynI18N.broken}</a>:
				</td>
				<td>
					<strong>{$broken_listings}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=recipbroken">{$esynI18N.nonrecip}</a>:
				</td>
				<td>
					<strong>{$no_reciprocal_listings}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=recipvalid">{$esynI18N.reciprocal}</a>:
				</td>
				<td>
					<strong>{$reciprocal_listings}</strong>
				</td>
			</tr>
		
			{if $config.sponsored_listings}
				<tr>
					<td class="first">
						<a href="controller.php?file=listings&amp;type=sponsored">{$esynI18N.sponsored}</a>:
					</td>
					<td>
						<strong>{$sponsored_listings}</strong>
					</td>
				</tr>
			{/if}
		
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;type=featured">{$esynI18N.featured}</a>:
				</td>
				<td>
					<strong>{$featured_listings}</strong>
				</td>
			</tr>
		
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;type=partner">{$esynI18N.partner}</a>:
				</td>
				<td>
					<strong>{$partner_listings}</strong>
				</td>
			</tr>
		
			<tr class="last">
				<td class="first">{$esynI18N.total}:</td>
				<td><strong>{$all_listings}</strong></td>
			</tr>
			</table>
			
			{ia_hooker name="adminIndexStats1"}
			
		</td>
		<td style="padding-left: 15px; vertical-align: top;">
			<table cellspacing="0" class="common striped" width="99%">
			<tr>
				<th width="90%" class="first">{$esynI18N.categories}</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=categories&amp;status=approval">{$esynI18N.approval}</a>:
				</td>
				<td>
					<strong>{$approval}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=categories&amp;status=active">{$esynI18N.active}</a>:
				</td>
				<td>
					<strong>{$active}</strong>
				</td>
			</tr>
			<tr class="last">
				<td class="first">{$esynI18N.total}:</td>
				<td><strong>{$summary}</strong></td>
			</tr>
			</table>
		
			{if $config.accounts && $currentAdmin.super}
			<table cellspacing="0" class="common striped" width="99%">
			<tr>
				<th width="90%" class="first">{$esynI18N.accounts}</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=approval">{$esynI18N.approval}</a>:
				</td>
		
				<td>
					<strong>{$approval_accounts}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=active">{$esynI18N.active}</a>:
				</td>
				<td>
					<strong>{$active_accounts}</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=unconfirmed">{$esynI18N.unconfirmed}</a>:
				</td>
				<td>
					<strong>{$unconfirmed_accounts}</strong>
				</td>
			</tr>
			<tr class="last">
				<td class="first">{$esynI18N.total}:</td>
				<td><strong>{$all_accounts}</strong></td>
			</tr>
			</table>
			{/if}
			
			{ia_hooker name="adminIndexStats2"}
			
		</td>
	</tr>
	</table>
</div>

{ia_hooker name='adminIndexPage'}

{if $config.display_new_plugins && isset($esyndicat_new_plugins.items) && !empty($esyndicat_new_plugins.items)}
	<div id="box_new_plugins" style="display: none;">
		<table cellspacing="0" class="striped">
		{foreach from=$esyndicat_new_plugins.items item=plugin}
			<tr>
				<td>
					<a href="{$plugin.link}" target="_blank">{$plugin.title}</a><br />
					&nbsp;{$plugin.description}
				</td>
			</tr>
		{/foreach}
		</table>
	</div>
{/if}

{if $config.display_twitter && !empty($timeline)}
	<div id="box_twitter" class="twitter" style="display: none;">
		<ul>
		{foreach $timeline as $tweet}
			<li>
				<div class="date">
					{$tweet.created_at|date_format:$config.date_format}
				</div>
				<div class="avatar">
					<a href="{$tweet.url}"><img src="http://static.intelliants.com/media/logotypes/intelliants-logo-symbol-80-inverse.png" alt=""></a>
				</div>
				<div class="tweet">
					<h5><a href="{$tweet.url}">{$tweet.name}</a> <span><a href="{$tweet.url}">@{$tweet.screen_name}</a></span></h5>
					{$tweet.text}
				</div>
			</li>
		{/foreach}
		</ul>
	</div>
{/if}

{if $config.display_feedbacks}
	<div id="box_fdb" style="display: none;">
		<div id="feedbackbox" style="margin: 0 10px 10px 10px; display: none;"></div>
		<div class="feedback" id="feedback">
			<form action="" method="post" id="feedbackform">
				<div>{$esynI18N.feedback_terms}</div>

				<div>{$esynI18N.subject}: <select name="subject" id="subject" style="width: 50%">
					<option>{$esynI18N._select_}</option>
					<option>{$esynI18N.bug_report}</option>
					<option>{$esynI18N.feature_request}</option>
					<option>{$esynI18N.custom_quote}</option>
				</select></div>
				<div>{$esynI18N.message_body}:<br />
					<textarea cols="40" rows="5" name="body" id="body" class="common" style="width: 99%;"></textarea>
				</div>
				<div><input type="button" id="submitButton" value="{$esynI18N.submit}" class="common"/> <input type="button" id="resetButton" value="{$esynI18N.clear}" class="common"/></div>
			</form>
		</div>
	</div>
{/if}

{if isset($changelog)}
	<div id="box_changelog" style="display: none;">
		<select id="changelog_item" class="common">
			{foreach $changelog_titles as $item => $index}
				<option value="{$index}">{$item}</option>
			{/foreach}
		</select>
		{foreach from=$changelog item='items' key='index'}
			<div class="changelog_item" id="changelog_{$index}" style="display: none;">
				{foreach from=$items item='list' key='class'}
					{if !empty($list) && $class != 'title'}
						<div class="info change_{$class}">{lang key='changelog_'|cat:$class}</div>
						<ul>{$list}</ul>
					{/if}
				{/foreach}
			</div>
		{/foreach}
		<div class="changelog_footer">{$esynI18N.changelog_roadmap}</div>
	</div>
{/if}

<script type="text/javascript" src="?action=get-state"></script>

{include_file js="js/ext/plugins/portal/Portal, js/ext/plugins/portal/PortalColumn, js/ext/plugins/portal/Portlet, js/ext/plugins/portal/overrides, js/ext/plugins/httpprovider/httpprovider, js/admin/index"}

{ia_hooker name="adminIndexAfterIncludeJs"}

{include file='footer.tpl'}
