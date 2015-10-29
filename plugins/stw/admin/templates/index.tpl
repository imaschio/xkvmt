{include file="header.tpl"}
{include file="box-header.tpl" title=$gTitle}

<form action="controller.php?plugin=stw" method="post">
	{preventCsrf}
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
		<tr>
			<td style="width:200px;"><strong>{$esynI18N.clear_system_thumbs}:</strong></td>
			<td>
				<input type="submit" name="clear_system_thumbs" class="common" value="{$esynI18N.clear}">
			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.clear_all_thumbs}:</strong></td>
			<td>
				<input type="submit" name="clear_all_thumbs" class="common" value="{$esynI18N.clear}">
			</td>
		</tr>
	</table>
</form>

{include file="box-footer.tpl"}
{include file="footer.tpl"}