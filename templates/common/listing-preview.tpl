<div class="media ia-item bordered<% if (options['add_border']) { %> ia-visual-option--border<% } %>">
	<% if (options['add_badge']) { %>
		<div class="pull-right ia-visual-option--badge">
			<img src="<%= options['add_badge']['value'] %>" alt="Badge">
		</div>
	<% } %>

	<div class="media-body">
		<h4 class="media-heading">
			<% if (options['add_star']) { %>
				<img src="<%= options['add_star']['value'] %>" alt="Star" class="ia-visual-option--star">
			<% } %>

			<a href="#" style="
				<% if (options['highlight']) { %>
					background: <%= options['highlight']['value'] %>;
				<% } %>
				<% if (options['color_link']) { %>
					color: <%= options['color_link']['value'] %>;
				<% } %>
				<% if (options['link_big']) { %>
					font-size: <%= options['link_big']['value'] %>px;
				<% } %>
			"<% if (options['itali_link']) { %> class="ia-visual-option--italic-link"<% } %>>{lang key='preview_listing_title'}</a>
			<span class="label label-important">{lang key='new'}</span>
		</h4>

		<div class="description<% if (options['desc_bold']) { %> ia-visual-option--bold-desc<% } %><% if (options['desc_ital']) { %> ia-visual-option--italic-desc<% } %>">
			<p>
				{lang key='preview_listing_description'}
			</p>
		</div>
	</div>

	<div class="panel clearfix">
		<span class="account" title="{lang key='account'}"><i class="icon-user icon-blue"></i> <a href="#">{lang key='username'}</a></span>

		<span class="date" title="{lang key='listing_added'}"><i class="icon-calendar icon-blue"></i> {$smarty.now|date_format:$config.date_format}</span>

		<span class="category" title="{lang key='category'}"><i class="icon-folder-open icon-blue"></i> <a href="#">{lang key='category'}</a></span>

		<span class="clicks"><i class="icon-hand-right icon-blue"></i> {lang key='clicks'}: 0</span>

		{if $config.pagerank}
			<span class="rank"><i class="icon-signal icon-blue"></i> {lang key='pagerank'}: {lang key='no_pagerank'}</span>
		{/if}
	</div>
</div>