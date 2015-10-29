<div id="recount_yandex" {if !isset($smarty.get.type) || ('recountyandex' neq $smarty.get.type)}style="display: none;"{/if}>

{include file="box-header.tpl" title=$esynI18N.recount_ya_rank}

<input type="text" size="6" value="0" id="start_num_yandex" class="common" />
<input type="button" value="{$esynI18N.recount}" id="start_yandex" class="common" />

<div style="margin-top: 30px; position:relative; border:1px solid #76A9DC; height:33px; width:100%; background-color:#ffffff;color:#335B92;text-align:center;">
	<div id="percent_yandex" style="position:absolute;left:50%;top:10px;z-index:2;font-size:13px;font-weight:bold;">0%</div>
	<div id="progress_bar_yandex" style="height:33px; width:0%; background: url({$smarty.const.IA_URL}plugins/yandexrank/admin/templates/img/progress_bar.gif) left repeat-x;color:#335B92;"></div>
</div>

{include file="box-footer.tpl"}

</div>