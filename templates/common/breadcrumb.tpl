{if isset($breadcrumb) && $breadcrumb|count}
<div class="breadcrumb-wrapper noprint clearfix">
	<div xmlns:v="http://rdf.data-vocabulary.org/#">
		<ul class="ia-breadcrumb pull-left">
			<li><a href="{$smarty.const.IA_URL}" rel="v:url" property="v:title">{lang key='home'}</a> <span class="divider">/</span></li>
			{foreach $breadcrumb as $item}
				{if $item.url && !$item@last}
					<li typeof="v:Breadcrumb">
						<a href="{$item.url}"{if isset($item.no_follow) && $item.no_follow} rel="nofollow"{/if} rel="v:url" property="v:title">{$item.caption}</a>
						<span class="divider">/</span>
					</li>
				{else}
					<li class="active">{$item.caption}</li>
				{/if}
			{/foreach}
		</ul>

		<ul class="unstyled special-icons pull-right">
			{ia_hooker name='smartyFrontBreadcrumbIcons'}
		</ul>
	</div>
</div>
{/if}