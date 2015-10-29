{include file='header.tpl' css="{$smarty.const.IA_URL}js/jquery/plugins/lightbox/css/jquery.lightbox"}

{include file='box-header.tpl' title=$gTitle}
	{foreach $templates as $template}
		<div class="tpls-plate{if $template.name == $tmpl} tpls-plate--active{/if}">
			<div class="tpls-plate__image">
				{if isset($template.local)}
					<a href="#" class="screenshots"><img src="{$smarty.const.IA_URL}templates/{$template.name}/info/preview.jpg" title="{$template.title}" alt="{$template.title}"></a>
					{if isset($template.screenshots) && !empty($template.screenshots)}
						{foreach from=$template.screenshots item=screenshot}
							<a class="lb" href="{$smarty.const.IA_URL}templates/{$template.name}/info/screenshots/{$screenshot}" style="display: none;"><img src="{$smarty.const.IA_URL}templates/{$template.name}/info/screenshots/{$screenshot}"></a>
						{/foreach}
					{/if}
				{else}
					<img src="{$template.logo}" title="{$template.title}" alt="{$template.title}">
				{/if}
			</div>
			<div class="tpls-plate__info">
				<p>
					{$esynI18N.name}:&nbsp;<strong>{$template.title}</strong><br />
					{$esynI18N.author}:&nbsp;<strong>{$template.author}</strong><br />
					{$esynI18N.contributor}:&nbsp;<strong>{$template.contributor}</strong><br />
					{$esynI18N.release_date}:&nbsp;<strong>{$template.date}</strong><br />
					{$esynI18N.esyndicat_version}:&nbsp;<strong>{$template.compatibility}</strong>
				</p>
			</div>
			<div class="tpls-plate__actions">
				<form method="post">
					{preventCsrf}
					<input type="hidden" name="template" value="{$template.name}">
					{if isset($template.local)}
						{if $template.name != $tmpl}
							<input type="submit" name="set_template" value="{$esynI18N.set_default}" class="common">
						{/if}
						<a href="{$smarty.const.IA_URL}{if $template.name != $tmpl}?preview={$template.name}{/if}" target="_blank">{$esynI18N.preview}</a>
					{else}
						<input type="submit" name="download_template" value="{$esynI18N.download}" class="common">
						<a href="{$template.url}" target="_blank">{$esynI18N.details}</a>
					{/if}
				</form>
			</div>
		</div>
	{/foreach}
{include file='box-footer.tpl'}

{include_file js='js/jquery/plugins/lightbox/jquery.lightbox, js/admin/templates'}

{include file='footer.tpl'}