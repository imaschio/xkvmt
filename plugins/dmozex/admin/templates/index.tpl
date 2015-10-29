{include file="header.tpl"}

<input type="hidden" id="dmoz_session" value="{$dmoz_session}">

{include file="box-header.tpl" title=$esynI18N.browser id="bBrowser" hidden="true"}
{include file="box-footer.tpl" class="box"}

{include file="box-header.tpl" title=$esynI18N.categories id="bCategories" hidden="true"}
{include file="box-footer.tpl" class="box"}

{include file="box-header.tpl" title=$esynI18N.listings id="bListings" hidden="true"}
{include file="box-footer.tpl" class="box"}

{include_file js="plugins/dmozex/js/admin/dmozex"}

{include file='footer.tpl'}