<form action="{if $smarty.const.IA_MODREWRITE}mod/tell_friend/{else}controller.php?plugin=tell_friend{/if}" method="post" class="ia-form">
	<label>{$lang.fullname}</label>
	<input type="text" name="fullname" value="{if isset($temp.fullname)}{$temp.fullname|escape:"html"}{elseif isset($smarty.post.fullname)}{$smarty.post.fullname}{/if}">

	<label>{$lang.your_email}</label>
	<input type="text" name="email" value="{if isset($temp.email)}{$temp.email|escape:"html"}{elseif isset($smarty.post.email)}{$smarty.post.email}{/if}">

	<label>{$lang.friend_fullname}</label>
	<input type="text" name="fullname2" value="{if isset($temp.fullname2)}{$temp.fullname2|escape:"html"}{elseif isset($smarty.post.fullname2)}{$smarty.post.fullname2}{/if}">

	<label>{$lang.email}</label>
	<input type="text" name="email2" value="{if isset($temp.email2)}{$temp.email2|escape:"html"}{elseif isset($smarty.post.email2)}{$smarty.post.email2}{/if}">

	<label>{$lang.your_message}</label>
	<textarea name="body" rows="8" class="input-block-level">{if isset($temp.body)}{$temp.body|escape:"html"}{elseif isset($smarty.post.body)}{$smarty.post.body}{/if}</textarea>

	{include file='captcha.tpl'}

	<div class="actions">
		<input type="submit" class="btn btn-primary btn-plain" name="tell" value="{lang key='submit'}" />
	</div>
</form>