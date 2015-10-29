<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	{ia_print_js files='js/utils/sessvars'}
</head>
<body>

{if isset($form_custom) && !empty($form_custom)}
	{include file=$form_custom}
{else}
	<form action="{$form_action}" method="post" id="payment_form">
		{foreach from=$form_values key='key' item='val'}
		<input type="hidden" name="{$key}" value="{$val}" />
		{/foreach}
	</form>
{/if}

<script type="text/javascript">
	sessvars.$.clearMem();
	if (document.getElementById("payment_form"))
	{
		document.getElementById("payment_form").submit();
	}
</script>

</body>
</html>