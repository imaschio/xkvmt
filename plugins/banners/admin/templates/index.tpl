{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	{include file="box-header.tpl" title=$gTitle}

		<div id="bannerThumbnail" style="display:none;">
			{if $one_banner.type eq 'local'}
				{print_img fl=$one_banner.image full="true" ups="true"}
			{elseif $one_banner.type eq 'flash' and $one_banner.image|substr:-3 eq 'swf'}
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="{$one_banner.width}" height="{$one_banner.height}">
					<param name="movie" value="{print_img fl=$one_banner.image ups='true'}">
					<param name="quality" value="high">
					<embed src="{print_img fl=$one_banner.image ups='true'}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="{$one_banner.width}" height="{$one_banner.height}"></embed>
				</object>
			{/if}
		</div>

	<form action="controller.php?plugin=banners&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">
	{preventCsrf}
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<!--<tr>
		<td width="100"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select name="lang" {if $langs|count eq 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}" {if $block.lang eq $code || $smarty.post.lang eq $code}selected="selected"{/if}>{$lang}</option>
				{/foreach}
			</select>
		</td>
	</tr>-->


	<tr>
		<td width="150"><strong>{$esynI18N.position}: </strong></td>
		<td>
			<select name="position">
				{foreach from=$positions item=position}
					<option value="{$position}"{if $position eq $one_banner.position || ($smarty.post && $smarty.post.position eq $position)} selected="selected"{/if}>{$position}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.banner_type}: </strong></td>
		<td>
			<select id="typeSelecter" name="type" onchange="changeType()">
				{foreach from=$types key=code item=type}
					<option value="{$code}"{if $code eq $one_banner.type || ($smarty.post && $smarty.post.type eq $code)} selected="selected"{/if}>{$type}</option>
				{/foreach}
			</select>
		</td>
	</tr>
</table>

	<div id="imageUrl" style="display:none;">
		<table class="striped" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="150">
					<label for="bannerImageUrl"><strong>{$esynI18N.banner_img_url}: </strong></label>
				</td>
				<td>
					<input type="text" class="common" name="image" id="bannerImageUrl" size="32" value="{if isset($one_banner.image)}{if $one_banner.type eq 'remote'}http://{/if}{$one_banner.image}{elseif isset($smarty.post.image)}{$smarty.post.image}{/if}" />
				</td>
			</tr>
		</table>
	</div>

	<div id="uploadcontainer" style="display:none">
		<table class="striped" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="150"><strong>{$esynI18N.choose_file_upload}: </strong></td>
				<td><input type="file" class="common" name="uploadfile" id="file"/></td>
			</tr>
		</table>
	</div>

<table class="striped" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="150"><strong>{$esynI18N.banner_title}: </strong></td>
		<td><input type="text" class="common" name="title" size="32" value="{if isset($one_banner.title)}{$one_banner.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>
</table>

<div id="imageTitle" style="display:none">
<table class="striped" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="150"><strong>{$esynI18N.banner_alt}: </strong></td>
		<td><input type="text" class="common" name="alt" size="32" value="{if isset($one_banner.alt)}{$one_banner.alt}{elseif isset($smarty.post.alt)}{$smarty.post.alt}{/if}" /></td>
	</tr>
</table>
</div>

<div id="textcontainer" style="display:none">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150"><strong>{$esynI18N.content}:</strong></td>
	<td>
		<textarea id="content" name="content" class="content" style="width:50%;height:150px;" cols="1" rows="1">{if isset($one_banner.content)}{$one_banner.content}{elseif isset($smarty.post.content)}{$smarty.post.content}{/if}</textarea>

	</td>
</tr>
</table>
</div>
<div id="planetextcontainer" style="display:none">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150"><strong>{$esynI18N.content}:</strong></td>
	<td>
		<textarea name="planetext_content" class="common" style="width:50%;height:150px;" cols="1" rows="1">{if isset($one_banner.planetext_content)}{$one_banner.planetext_content}{elseif isset($smarty.post.planetext_content)}{$smarty.post.planetext_content}{/if}</textarea>

	</td>
</tr>
</table>
</div>

<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150">
		<strong>No follow: </strong>
	</td>
	<td>
		<label for="nofollow1">{$esynI18N.yes}</label> <input type="radio" name="no_follow" id="nofollow1" value="1" {if $one_banner.no_follow eq '1'}checked="checked" {/if} />
		<label for="nofollow2">{$esynI18N.no}</label> <input type="radio" name="no_follow" id="nofollow2" value="0" {if $one_banner.no_follow eq '0'}checked="checked" {/if} />
	</td>
</tr>
</table>

<div id="mediasize" style="display:none">
<table class="striped" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="150">
			<strong>{$esynI18N.use_orig_params}: </strong>
		</td>
		<td>
			<label for="orig">{$esynI18N.yes}</label> <input type="radio" name="params" id="orig" value="1" onclick="$('#imageParams').hide()" {if empty($one_banner.width)}checked="checked"{/if} />
			<label for="setown">{$esynI18N.no}</label> <input type="radio" name="params" id="setown" value="0" onclick="$('#imageParams').show()" {if !empty($one_banner.width)}checked="checked"{/if} />
		</td>
	</tr>
</table>
</div>

<div id="imageParams" style="display:none">
	<table class="striped" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageWidth">{$esynI18N.image_width}:</label></td>
			<td><input type="text" class="common" size="5" maxlength="3" name="width" id="imageWidth" value="{if isset($one_banner.width)}{$one_banner.width}{elseif isset($smarty.post.width)}{$smarty.post.width}{/if}" /></td>
		</tr>
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageHeight">{$esynI18N.image_height}:</label></td>
			<td><input type="text" class="common" size="5" maxlength="3" name="height" id="imageHeight" value="{if isset($one_banner.height)}{$one_banner.height}{elseif isset($smarty.post.height)}{$smarty.post.height}{/if}" /></td>
		</tr>
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageKeepRatio">{$esynI18N.image_keepratio}:</label></td>
			<td><input type="checkbox" name="keep_ratio" id="imageKeepRatio" onclick="imageFit()"/></td>
		</tr>
	</table>
</div>
<div id="imageFit" style="display:none">
    <table class="striped" width="100%" cellpadding="0" cellspacing="0">
   		<tr>
    		<td width="150" style="padding-left:40px;"><label for="fit">{$esynI18N.image_fit}:</label></td>
    		<td><input type="checkbox" name="fit" id="fit"/></td>
    	</tr>
    </table>
</div>

<div id="bannerurl" style="display:none;">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<td width="150"><strong>{$esynI18N.banner_url}: </strong></td>

	<td><input type="text" class="common" name="url" size="32" value="{if isset($one_banner.url)}{$one_banner.url}{elseif isset($smarty.post.url)}{$smarty.post.url}{/if}" /></td>
	</tr>

	<tr>
	<td width="150"><strong>{$esynI18N.target}: </strong></td>
	<td>

	<select name="target" onchange="getTarget(this)">
		{foreach from=$targets key=code item=target}
				<option value="{$code}"{if $code eq $one_banner.target} selected="selected"{/if}>{$target}</option>
		{/foreach}
		<option value="other">{$esynI18N.other}...</option>
	</select>
		<span id="settarget" style="display:none;">
			<input type="text" name="targetframe" value="{if $one_banner.target}{$one_banner.target}{else}_blank{/if}" />
		</span>
	</td>
	</tr>
</table>
</div>

<table class="striped" width="100%" cellpadding="0" cellspacing="0">

	<tr>
		<td width="150"><strong>{$esynI18N.category}:</strong></td>
		<td>
			<div id="tree"></div>
			<label><input type="checkbox" name="recursive" value="1" {if isset($one_banner.recursive) && $one_banner.recursive eq '1'}checked="checked"{elseif isset($smarty.post.recursive) && $smarty.post.recursive eq '1'}checked="checked"{/if} />&nbsp;{$esynI18N.include_subcats}</label>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				{foreach from=$statuses key=code item=status}
					<option value="{$code}"{if $code eq $one_banner.status || ($smarty.post && $smarty.post.status eq $code)} selected="selected"{/if}>{$status}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	{if isset($smarty.get.do) && $smarty.get.do eq 'add'}
		<tr>
			<td colspan="2">
				<span>Add banner <strong>and then</strong></span>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto eq 'list'}selected="selected"{/if}>Go to list</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto eq 'add'}selected="selected"{/if}>Add another one</option>
				</select>
			</td>
		</tr>
{/if}



	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" />
			<input type="hidden" name="id" value="{if isset($one_banner.id)}{$one_banner.id}{/if}" />
			<input type="hidden" name="categories" id="categories" value="{$selected_categories}" />
			<input type="submit" name="save" value="{if $smarty.get.do eq 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" class="common" />
		</td>
	</tr>
	</table>
	</form>
	{include file="box-footer.tpl" class="box"}
{else}
	<div id="box_banners" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/ckeditor/ckeditor, js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/banners/js/admin/banners"}

{include file='footer.tpl'}