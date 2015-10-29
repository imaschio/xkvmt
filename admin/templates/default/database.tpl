{include file="header.tpl"}

{if $smarty.get.page == 'export'}
	{if isset($backup_is_not_writeable)}
		<div class="message alert" id="backup_message">
			<div class="inner">
				<div class="icon"></div>
				<ul>
					<li>{$backup_is_not_writeable}</li>
				</ul>
			</div>
		</div>
	{/if}

	{if isset($out_sql) && !empty($out_sql)}
		{include file="box-header.tpl" title=$esynI18N.export}
			<textarea class="common" style="margin-top: 10px;" rows="24" cols="15" readonly="readonly">
				{$out_sql}
			</textarea>
		{include file='box-footer.tpl'}
	{/if}

	{include file="box-header.tpl" title=$esynI18N.export}

	<form action="controller.php?file=database&amp;page=export" method="post" name="dump" id="dump">
	{preventCsrf}
	<table width="100%" cellspacing="0" cellpadding="0" class="striped">
	<tr class="tr">
		<td><strong>{$esynI18N.export}:</strong></td>
		<td><strong>{$esynI18N.mysql_options}:</strong></td>
	</tr>
	<tr>
		<td valign="top">
			<select name="tbl[]" id="tbl" size="7" multiple="multiple" style="font-size: 12px; font-family: Verdana;">

			{foreach from=$tables item=table}
				<option value="{$table}">{$table}</option>
			{/foreach}

			</select>

			<div style="margin-top: 5px; text-align: center;" class="selecting">
				<a href="#" class="select">{$esynI18N.select_all}</a>&nbsp;/&nbsp;
				<a href="#" class="deselect">{$esynI18N.select_none}</a>
			</div>
		</td>
		<td align="left" width="100%">
			<table cellspacing="1" width="100%" class="striped">
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="sql_structure" value="structure" id="sql_structure" {if isset($smarty.post.sql_structure) || !$smarty.post}checked="checked"{/if} style="vertical-align: middle">
					<label for="sql_structure"><b>{$esynI18N.structure}:</b></label><br />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="drop" value="1" {if isset($smarty.post.drop) && $smarty.post.drop == '1'}checked="checked"{/if} id="dump_drop" style="vertical-align: middle">
					<label for="dump_drop">{$esynI18N.add_drop_table}</label>
				</td>
			</tr>
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="sql_data" value="data" id="sql_data" {if isset($smarty.post.sql_data) || !$smarty.post}checked="checked"{/if} style="vertical-align: middle">
					<label for="sql_data"><b>Data:</b></label><br />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="showcolumns" value="1" {if isset($smarty.post.showcolumns) && $smarty.post.showcolumns == '1'}checked="checked"{/if} id="dump_showcolumns" style="vertical-align: middle">
					<label for="dump_showcolumns">{$esynI18N.complete_inserts}</label>
				</td>
			</tr>
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="real_prefix" id="real_prefix" {if isset($smarty.post.real_prefix) || !$smarty.post}checked="checked"{/if} style="vertical-align: middle">
					<label for="real_prefix"><b>{$esynI18N.use_real_prefix}</b></label><br />
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="tr">
			<input type="checkbox" name="save_file" id="save_file" style="vertical-align: middle">
			<label for="save_file"><b>{$esynI18N.save_as_file}</b></label><br />
		</td>
	</tr>
	</table>

	<div id="save_to" style="display: none;">
		<table width="100%" cellpadding="0" cellspacing="0" class="striped">
		<tr class="tr">
			<td width="50%" style="padding-left: 10px;" class="fields-pages">
				<label for="server"><input type="radio" name="savetype" value="server" id="server">&nbsp;{$esynI18N.save_to_server}</label>
				<label for="client"><input type="radio" name="savetype" value="client" id="client" {if isset($smarty.post.savetype) && $smarty.post.savetype == 'client' || !$smarty.post}checked="checked"{/if} />&nbsp;{$esynI18N.save_to_pc}</label>
			</td>
			<td style="padding-right: 20px; text-align: right;">
				<input type="checkbox" name="gzip_compress" id="gzip_compress" {if isset($smarty.post.gzip_compress) || !$smarty.post}checked="checked"{/if} style="vertical-align: middle">
				<label for="gzip_compress">{$esynI18N.gzip_compress}</label>
			</td>
		</tr>
		</table>
	</div>

	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
		<tr class="all">
			<td colspan="2" align="right">
				<input type="button" id="exportAction" value="{$esynI18N.go}" class="common">
				<input type="hidden" name="export" id="export">
			</td>
		</tr>
	</table>
	</form>
	{include file='box-footer.tpl'}
{elseif $smarty.get.page == 'import'}
	{include file="box-header.tpl" title=$esynI18N.import}

	<form action="controller.php?file=database&amp;page=import" method="post">
	{preventCsrf}
	<table width="100%" cellspacing="0" class="striped">

	{if $upgrades}
		<tr class="tr">
			<td><strong>{$esynI18N.choose_import_file}:</strong></td>
		</tr>
		<tr>
			<td width="50%">
				<select name="sqlfile">
					{foreach from=$upgrades item=value}
						<option value="{$value}" {if isset($smarty.post.sqlfile) && $smarty.post.sqlfile == $value}selected="selected"{/if}>{$value}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr class="all tr">
			<td align="right"><input type="submit" name="run_update" value="{$esynI18N.go}" class="common"></td>
		</tr>
	{else}
		<tr class="tr">
			<td><strong>{$esynI18N.no_upgrades}</strong></td>
		</tr>
	{/if}

	</table>
	</form>

	<form enctype="multipart/form-data" action="controller.php?file=database&amp;page=import" method="post" name="update" id="update">
	{preventCsrf}
	<table cellpadding="0" cellspacing="0" width="100%" class="striped">
	<tr class="tr">
		<td class="caption"><strong>{$esynI18N.choose_import_file}</strong></td>
	</tr>
	<tr class="tr">
		<td><strong>{$esynI18N.location_sql_file}:</strong></td>
	</tr>
	<tr>
		<td>
			<input type="file" name="sql_file" id="sql_file" class="textfield">&nbsp;(Max: 2,048KB)<br />
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
		</td>
	</tr>
	<tr class="all tr">
		<td align="right">
			<input type="button" id="importAction" value="{$esynI18N.go}" class="common">
			<input type="hidden" name="run_update" id="run_update">
		</td>
	</tr>
	</table>
	</form>
	{include file='box-footer.tpl'}
{elseif $smarty.get.page == 'sql'}
	{include_file js="js/ext/plugins/tablegrid/TableGrid"}

	{include file='box-header.tpl' title=$gTitle}

	<form action="controller.php?file=database&amp;page=sql" method="post">
	{preventCsrf}
	<table width="100%" cellspacing="0" cellpadding="0" class="striped">
	<tr style="font-weight: bold;" class="tr">
		<td>{$esynI18N.run_sql_queries}:</td>
		<td>{$esynI18N.tables_fields}:</td>
	</tr>
	<tr>
		<td width="99%" valign="top">
			<textarea class="noresize" rows="4" cols="4" name="query" id="query" style="height: 300px; width: 100%; font-size: 12px; font-family: Verdana;">{if isset($smarty.post.show_query) && $smarty.post.show_query == '1' && isset($sql_query) && $sql_query != ''}{$sql_query}{else}SELECT * FROM {/if}</textarea>
		</td>
		<td width="30" valign="top">
			<select name="table" id="table" size="10" style="font-size: 12px; font-family: Verdana; height: 158px; width: 200px;">
			{foreach from=$tables item=table}
				<option value="{$table}">{$table}</option>
			{/foreach}
			</select>
			<p style="text-align: left; margin: 10px 0;">
				<input type="button" value="&#171;" id="addTableButton" class="common small">
			</p>

			<select name="field" id="field" size="5" style="font-size: 12px; font-family: Verdana; display: none; width: 200px;">
				<option>&nbsp;</option>
			</select>
			<p style="text-align: left; margin: 10px 0 0 0;">
				<input type="button" value="&#171;" id="addFieldButton" style="display: none;" class="common small"/>
			</p>
		</td>
	</tr>
	<tr class="all tr">
	<td>
		<input type="checkbox" name="show_query" value="1" id="sh1" style="vertical-align: middle" {if isset($smarty.post.show_query) && $smarty.post.show_query == '1' || !$smarty.post}checked="checked"{/if} />
		<label for="sh1">{$esynI18N.show_query_again}</label>
	</td>
	<td colspan="2" align="right">
		<input type="submit" value="{$esynI18N.go}" name="exec_query" class="common small">
		<input type="button" value="{$esynI18N.clear}" id="clearButton" class="common small">
	</td>
	</tr>
	</table>
	</form>
	{include file='box-footer.tpl'}

	{if isset($queryOut) && $queryOut != ''}
		{include file="box-header.tpl" title=$esynI18N.display style="height: 450px; overflow: auto;"}
		{$queryOut}
		{include file='box-footer.tpl'}
	{/if}
{elseif $smarty.get.page == 'consistency'}

	{include file='box-header.tpl' title=$gTitle}

	<ul class="consistency-list">
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.categories_level}:</h5>
				<a class="ajax" data-type="categories_level" data-num="{$stats_items.categories}" href="#">{$esynI18N.repair}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_categories_level'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.categories_paths_repair}:</h5>
				<a class="ajax" data-type="categories_paths" data-num="{$stats_items.categories}" data-prelaunch="1" href="#">{$esynI18N.repair}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_categories_paths'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.categories_relation}:</h5>
				<a class="ajax" data-type="categories_relation" data-num="{$stats_items.categories}" data-prelaunch="1" href="#">{$esynI18N.repair}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_categories_relation'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.listing_aliases}:</h5>
				<a class="ajax" data-type="listing_alias" data-num="{$stats_items.listings}" href="#">{$esynI18N.repair}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_listing_aliases'}
				<div style="margin-top:10px;">
					<input type="checkbox" name="all_listings" id="all_listings" style="width:auto;">
					<label for="all_listings">{$esynI18N.remove_all_aliases}</label>
				</div>
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.active_listings_count}:</h5>
				<a class="ajax" data-type="num_all_listings" data-num="{$stats_items.categories}" href="#">{$esynI18N.recount}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_num_all_listings'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.listings_and_categories}:</h5>
				<a class="ajax" data-type="listing_categories" data-num="{$stats_items.categories}" href="#">{$esynI18N.find_and_delete}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_listing_categories'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.repair_tables}:</h5>
				<a href="controller.php?file=database&amp;page=consistency&amp;type=repair_tables">{$esynI18N.repair}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_repair_tables'}
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5>{$esynI18N.optimize_tables}:</h5>
				<a href="controller.php?file=database&amp;page=consistency&amp;type=optimize_tables">{$esynI18N.optimize_tables}</a>
			</div>
			<div class="consistency-item-annotation">
				{lang key='consistency_optimize_tables'}
			</div>
		</li>

		{ia_hooker name="adminDatabaseConsistency"}

	</ul>

	<div class="consistency-progress">
		<div class="progressbar">
			<div class="progressbar-info">
				<span class="current-info">0</span> / 
				<span class="total-info">100%</span>
			</div>
			<div class="bar"></div>
		</div>
		<div class="consistency-progress-actions">
			{lang key="limit"}: <input class="limit" type="text" value="100"> 
			<a href="#" class="run">{lang key="run"}</a>
			<a href="#" class="stop hide">{lang key="stop"}</a>
		</div>
	</div>

	{include file='box-footer.tpl'}

{elseif $smarty.get.page == 'reset' && !empty($reset_options)}
	{include file='box-header.tpl' title=$gTitle}
		<form action="controller.php?file=database&amp;page=reset" method="post">
		{preventCsrf}
		<table width="100%" cellspacing="0" cellpadding="0" class="striped">
		<tr>
			<td width="150"><label for="all_options"><strong>{lang key='reset_all'}</strong></label></td>
			<td><input type="checkbox" value="all" name="all_options" id="all_options"></td>
		</tr>

		{foreach from=$reset_options key=key item=option}
			<tr>
				<td><label for="option_{$key}">{$option}<label></td>
				<td><input type="checkbox" id="option_{$key}" name="options[]" value="{$key}"></td>
			</tr>
		{/foreach}

		<tr>
			<td rowspan="2">
				<input type="submit" name="reset" class="common" value="{lang key='reset'}">
			</td>
		</tr>
		</table>
		</form>
	{include file='box-footer.tpl'}
{elseif $smarty.get.page == 'hook_editor'}
	{include file="box-header.tpl" title=$esynI18N.hook_editor}

	<table class="striped" width="98%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="200">
			<select id="hook">
				{foreach from=$hooks item=hook}
					<optgroup label="{$hook.title}">
						{foreach from=$hook.code item=code}
							<option value="{$code.id}">{$code.name}</option>
						{/foreach}
					</optgroup>
				{/foreach}
			</select>
		</td>

		<td>
			<input type="button" class="common" id="show" value="Show Code">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<textarea name="code" id="codeContainer" class="common codepress php" cols="10" rows="20"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" class="common" name="save" id="save" value="Save">
			<input type="submit" class="common" id="close_all" value="Close All">
		</td>
	</tr>
	</table>

	{include file='box-footer.tpl'}

	{include_file js="js/utils/edit_area/edit_area_full, js/admin/hook_editor"}
{/if}

{include_file js="js/admin/database"}

{ia_hooker name="tplAdminDatabaseBeforeFooter"}

{include file='footer.tpl'}