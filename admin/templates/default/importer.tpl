{include file="header.tpl"}

{if isset($report) && !empty($report)}
	{include file="box-header.tpl" title=$esynI18N.import_report}

	<table cellspacing="0" width="100%" class="striped">
	
	{foreach from=$report item=rep}
		<tr>
			<td width="250">{$rep.msg}</td>
			<td>
				<strong>
					{if $rep.success}
						<span style="color: green;">OK</span>
					{else}
						<span style="color: red;">FAIL</span>
					{/if}
				</strong>
			</td>
		</tr>
	{/foreach}

	</table>

	{include file='box-footer.tpl'}
{/if}

{if isset($importers) && !empty($importers) && !isset($success)}
	{include file="box-header.tpl" title=$gTitle id='database_panel'}
	
	<form action="controller.php?file=importer" method="post" id="import_form">
	{preventCsrf}

	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td width="150"><strong>{$esynI18N.choose_importer}:</strong></td>
		<td>
			<select name="importer" id="importer" class="common" style="float: left;">
			{foreach from=$importers item="importer"}
				<option value="{$importer}" {if isset($smarty.post.importer) && $smarty.post.importer == $importer}selected="selected"{/if}>{$importer}</option>
			{/foreach}
			</select>

			<div class="option_tip" style="float: left; margin: 2px 0 0 10px;"><i>Please use the details of your source database that you want to migrate the data from.</i></div>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.database_host}:</strong></td>
		<td>
			<input type="text" name="host" class="common" value="{if isset($smarty.post.host) && !empty($smarty.post.host)}{$smarty.post.host|escape:'html'}{/if}" id="host" placeholder="{$smarty.const.IA_DBHOST}"/>
		</td>
	</tr>

	<tr>
		<td style="vertical-align:top;"><strong>{$esynI18N.database_name}:</strong></td>
		<td>
			<input type="text" name="database" class="common" value="{if isset($smarty.post.database) && !empty($smarty.post.database)}{$smarty.post.database|escape:'html'}{/if}" id="database" placeholder="{$smarty.const.IA_DBNAME}">
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.database_username}:</strong></td>
		<td>
			<input type="text" name="username" class="common" value="{if isset($smarty.post.username) && !empty($smarty.post.username)}{$smarty.post.username|escape:'html'}{/if}" id="username" placeholder="{$smarty.const.IA_DBUSER}" autocomplete="off"/>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.database_password}:</strong></td>
		<td>
			<input type="password" name="password" id="password" class="common" placeholder="{$smarty.const.IA_DBPASS}" autocomplete="off"/>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.database_prefix}:</strong></td>
		<td>
			<input type="text" name="prefix" id="prefix" class="common" value="{if isset($smarty.post.prefix) && !empty($smarty.post.prefix)}{$smarty.post.prefix|escape:'html'}{/if}" placeholder="{$smarty.const.IA_DBPREFIX}">
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<input type="button" name="connect" id="connect" value="Connect" class="common" style="float: left; margin-right: 10px;"/>
			<input type="button" id="placehold" value="Use same DB" class="common" style="float: left; margin-right: 10px;">
			<input type="button" name="resetdb" id="resetdb" value="Reset" class="common">
		</td>
	</tr>
	</table>

	</form>

	{include file='box-footer.tpl'}
{/if}

<div id="tables_panel" style="display: none;">
	{include file="box-header.tpl" title=$gTitle id='tables_panel'}

	<div id="migrate_items" style="text-transform: capitalize; margin: 10px 0 10px 10px;"></div>

	<p>
		<input type="submit" name="start" id="start" value="Import" class="common">
	</p>

	<div class="progress" style="margin: 20px 0 10px 0;">
		<div id="percents">0%</div>
		<div id="progress_bars" style="background: url(templates/{$config.admin_tmpl}/img/bgs/progress_bar.gif) left repeat-x;"></div>
	</div>

	<div class="import_result" id="import_result"></div>

	{include file='box-footer.tpl'}
</div>

{include_file js="js/admin/importer"}

{include file='footer.tpl'}
