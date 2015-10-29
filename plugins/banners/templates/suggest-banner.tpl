<form action="mod/banners/index.html?banner_pos={$banner_pos}" method="post" enctype="multipart/form-data" class="ia-form bordered">
{if $plans}
	<div class="fieldset collapsible" id="fieldPlans">
		<h4 class="title">{lang key='plans'}</h4>
		<div class="payment content collapsible-content" id="plans">
			{foreach from=$plans item=plan name="plans"}
			<p class="ia-reset">
				<input type="hidden" value="{$plan.cost}" id="planCost_{$plan.id}">
				<label for="p{$plan.id}" class="radio">
					<input type="radio" id="p{$plan.id}" value="{$plan.id}" name="plan" {if isset($smarty.post.plan) && $smarty.post.plan == $plan.id}checked="checked"{elseif !isset($smarty.post.plan) && $smarty.foreach.plans.first}checked="checked"{/if} />
					<b>{$plan.title} - {$config.currency_symbol}{$plan.cost}</b>
				</label>
				
			</p>
			<div class="plan-description">{$plan.description}</div>
			{/foreach}
		</div>
	</div>
{/if}

<div class="fieldset collapsible">
	<h4 class="title">{lang key='suggest_banner'}</h4>

	<div class="collapsible-content content">

		{if !$esynAccountInfo}
		<div class="control-group">
			<label for="bannerEmail">{lang key='banner_email'}</label>
			<input type="text" name="email" id="bannerEmail" size="32" value="{if isset($one_banner.email)}{$one_banner.email}{elseif isset($smarty.post.email)}{$smarty.post.email}{/if}" />
		</div>
		{/if}

		<div class="control-group">
			<label for="typeSelecter">{lang key='banner_type'}</label>
			<select id="typeSelecter" name="type">
				{foreach from=$types key=code item=type}
					{if (!$upl_writable && $code neq "local") || $upl_writable}
						<option value="{$code}" {if (isset($one_banner.type) && $code eq $one_banner.type) || ($smarty.post && $smarty.post.type eq $code)} selected="selected"{/if}>{$type}</option>
					{/if}
				{/foreach}
			</select>
		</div>

		<div id="imageUrl" style="display:none;">
			<div class="control-group">
				<label for="bannerImageUrl">{lang key='banner_img_url'}</label>

				<input type="text" class="common" name="image" id="bannerImageUrl" size="32" value="{if isset($one_banner.image)}{if $one_banner.type eq 'remote'}http://{/if}{$one_banner.image}{elseif isset($smarty.post.image)}{$smarty.post.image}{/if}" />
			</div>
		</div>

		<div id="uploadcontainer" style="display:none">
			<div class="control-group">
				<label for="bannerImageUrl">{lang key='banner_img_url'}</label>
				<div class="clearfix">
					<div class="upload-wrap pull-left">
						<div class="input-append">
							<span class="span2 uneditable-input">{lang key='click_to_upload'}</span>
							<span class="add-on">{lang key='browse'}</span>
						</div>
						<input type="file" class="upload-hidden" id="bannerImageUrl" name="uploadfile" />
					</div>
				</div>
			</div>
		</div>

		<div class="control-group">
			<label for="title">{lang key='banner_title'}</label>
			<input type="text" class="common" id="title" name="title" size="32" value="{if isset($one_banner.title)}{$one_banner.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" />
		</div>

		<div id="imageTitle" style="display:none">
			<div class="control-group">
				<label for="alt">{lang key='banner_alt'}</label>
				<input type="text" class="common" id="alt_title" name="alt" size="32" value="{if isset($one_banner.alt)}{$one_banner.alt}{elseif isset($smarty.post.alt)}{$smarty.post.alt}{/if}" />
			</div>
		</div>

		<div id="textcontainer" style="display:none;">
			<div class="control-group">
				<label for="bn_content">{lang key='content'}</label>
				<textarea id="bn_content" name="content" class="ckeditor_textarea input-block-level">
					{if isset($one_banner.content)}
						{$one_banner.content}
					{elseif isset($smarty.post.content)}
						{$smarty.post.content}
					{/if}
				</textarea>
			</div>
		</div>

		<div id="planetextcontainer" style="display:none">
			<div class="control-group">
				<label for="planetext_content">{lang key='content'}</label>
				<textarea name="planetext_content" class="common" cols="45" rows="6" class="input-block-level">
					{if isset($one_banner.planetext_content)}
						{$one_banner.planetext_content}
					{elseif isset($smarty.post.planetext_content)}
						{$smarty.post.planetext_content}
					{/if}
				</textarea>
			</div>
		</div>

		<div class="control-group">
			<label>No Follow</label>
			<label for="nofollow1" class="radio">
				<input type="radio" value="1" name="no_follow" id="nofollow1" {if isset($one_banner.no_follow) and $one_banner.no_follow eq '1'}checked="checked"{elseif isset($smarty.post.no_follow) && $smarty.post.no_follow == '1'}checked="checked"{elseif !isset($one_banner.no_follow) && !isset($smarty.post.no_follow)}checked="checked"{/if} />
				{lang key='yes'}
			</label>
			<label for="nofollow2" class="radio">
				<input type="radio" value="0" name="no_follow" id="nofollow2" {if isset($one_banner.no_follow) and $one_banner.no_follow eq '0'}checked="checked"{elseif isset($smarty.post.no_follow) and $smarty.post.no_follow eq '0'}checked="checked"{/if} />
				{lang key='no'}
			</label>
		</div>

		<div id="mediasize" style="display:none">
			<div class="control-group">
				<label for="orig">{lang key='use_orig_params'}</label>
				<label for="orig">
					<input type="radio" name="params" id="orig" value="1" onclick="$('#imageParams').hide('slow')" {if isset($one_banner.width) and empty($one_banner.width)}checked="checked"{elseif isset($smarty.post) and empty($smarty.post.width)}checked="checked"{/if} />
					{lang key='yes'}
				</label>
				<label for="setown">
					<input type="radio" name="params" id="setown" value="0" onclick="$('#imageParams').show('slow')" {if isset($one_banner.width) and !empty($one_banner.width)}checked="checked"{elseif isset($smarty.post) and !empty($smarty.post.width)}checked="checked"{/if} />
					{lang key='no'}
				</label>
			</div>
		</div>

		<div id="imageParams" style="display:none">
			<div class="control-group">
				<label for="imageWidth">{lang key='image_width'}</label>
				<input type="text" class="common" size="5" maxlength="3" name="width" id="imageWidth" value="{if isset($one_banner.width)}{$one_banner.width}{elseif isset($smarty.post.width)}{$smarty.post.width}{/if}" />
			</div>

			<div class="control-group">
				<label for="imageHeight">{lang key='image_height'}</label>
				<input type="text" class="common" size="5" maxlength="3" name="height" id="imageHeight" value="{if isset($one_banner.height)}{$one_banner.height}{elseif isset($smarty.post.height)}{$smarty.post.height}{/if}" />
			</div>

			<div class="control-group">
				<label for="imageKeepRatio">{lang key='image_keepratio'}</label>
				<input type="checkbox" name="keep_ratio" id="imageKeepRatio" />
			</div>
		</div>

		<div id="imageFit" style="display:none">
			<div class="control-group">
				<label for="fit">{lang key='image_fit'}</label>
				<input type="checkbox" name="fit" id="fit"/>
			</div>
		</div>

		<div id="bannerurl" style="display:none;">
			<div class="control-group">
				<label for="bn_url">{lang key='banner_url'}</label>
				<input type="text" class="common" id="bn_url" name="url" size="32" value="{if isset($one_banner.url)}{$one_banner.url}{elseif isset($smarty.post.url)}{$smarty.post.url}{/if}" />
			</div>
			
			<div class="control-group">
				<label for="banner_target">{lang key='target'}</label>
				<select name="target" id="banner_target">
					{foreach from=$targets key=code item=target}
						<option value="{$code}" {if isset($one_banner.target) && $code == $one_banner.target} selected="selected"{elseif isset($smarty.post.target) && $smarty.post.target == $code}selected="selected"{/if}>{$target}</option>
					{/foreach}
						<option value="other" {if isset($one_banner.target) && 'other' == $one_banner.target} selected="selected"{elseif isset($smarty.post.target) && $smarty.post.target == 'other'}selected="selected"{/if}>{lang key='other'}...</option>
				</select>
				<span id="settarget" style="display:none;">
					<input type="text" name="targetframe" value="{if isset($one_banner.targetframe)}{$one_banner.targetframe}{elseif isset($smarty.post.targetframe)}{$smarty.post.targetframe}{else}_blank{/if}" />
				</span>
			</div>
		</div>

		<div class="control-group">
			<label>{lang key='category'}</label>
			<div id="tree"></div>
			<label>
				<input type="checkbox" name="recursive" value="1" {if isset($one_banner.recursive) && $one_banner.recursive == '1'}checked="checked"{elseif isset($smarty.post.recursive) && $smarty.post.recursive == '1'}checked="checked"{/if} />&nbsp;{lang key='include_subcats'}
			</label>
		</div>
	</div>
</div>

<div id="gateways">
	<div class="fieldset">
		<h4 class="title">{lang key='payment_gateway'}</h4>
		<div class="content">
			{ia_hooker name='paymentButtons'}
		</div>
	</div>
</div>

<div class="actions">
	<input type="hidden" name="categories" id="categories" value="{if isset($one_banner.categories)}{$one_banner.categories}{elseif isset($smarty.post.categories)}{$smarty.post.categories}{/if}" />
	<input type="submit" name="save" class="btn btn-primary btn-large" value="{if $id}{lang key='save_changes'}{else}{lang key='add'}{/if}" class="common" />
</div>
</form>

{ia_print_js files='js/ckeditor/ckeditor, js/intelli/intelli.tree, plugins/banners/js/frontend/banners'}