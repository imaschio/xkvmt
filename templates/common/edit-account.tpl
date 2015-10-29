<ul class="nav nav-tabs">
	<li class="tab_edit active"><a href="#tab-pane_edit" data-toggle="tab">{lang key='edit_account'}</a></li>
	<li class="tab_password"><a href="#tab-pane_password" data-toggle="tab">{lang key='change_password'}</a></li>
	{if $config.allow_delete_accounts}
		<li class="tab_delete"><a href="#tab-pane_delete" data-toggle="tab">{lang key='delete_account'}</a></li>
	{/if}
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="tab-pane_edit">
		<div class="ia-wrap">
			<form action="{$smarty.const.IA_URL}edit-account.php" method="post" enctype="multipart/form-data" class="ia-form">
				<label for="email">{lang key='email'}</label>
				<input type="text" class="span3" name="email" id="email" value="{$smarty.post.email|default:$esynAccountInfo.email}">

				<label>{lang key='avatar'}</label>
				{assign var='file_path' value="{$smarty.const.IA_UPLOADS}{$esynAccountInfo.avatar}"}

				{if $file_path|is_file && $file_path|file_exists}
					<div id="file_manage" class="clearfix">
						<p>
							{print_img ups='true' fl=$esynAccountInfo.avatar full='true' title=$esynAccountInfo.username class='avatar'}
							<a class="btn btn-danger btn-small" href="{$smarty.const.IA_URL}edit-account.php?delete=photo" title="{lang key='delete'}"><i class="icon-remove-sign icon-white"></i></a>
						</p>
					</div>
				{/if}
				<div class="clearfix">
					<div class="upload-wrap pull-left">
						<div class="input-append">
							<span class="span2 uneditable-input">{lang key='click_to_upload'}</span>
							<span class="add-on">{lang key='browse'}</span>
						</div>
						<input type="file" class="upload-hidden" name="avatar" id="avatar">
					</div>
				</div>

				{if $config.sponsored_accounts && $plans}
					<div style="margin-top: 20px;">
						<div class="fieldset">
							<h4 class="title">{lang key='plans'}</h4>
							<div class="content">
								{foreach $plans as $plan}
									<div class="b-plan">
										<label for="p{$plan.id}" class="radio b-plan__title">
											<input type="radio" name="plan" value="{$plan.id}" id="p{$plan.id}" {if $esynAccountInfo.plan_id == $plan.id}checked="checked"{/if}>
											<b>{$plan.title} &mdash; {$config.currency_symbol}{$plan.cost}</b>
										</label>

										<div class="b-plan__description">{$plan.description}</div>
									</div>
								{/foreach}
							</div>
						</div>

						<div id="gateways" class="fieldset" style="display: none;">
							<h4 class="title">{lang key='payment_gateway'}</h4>
							<div class="content collapsible-content">
								{ia_hooker name='paymentButtons'}
							</div>
						</div>
					</div>
				{/if}

				<div class="actions">
					<input type="hidden" name="old_email" value="{$esynAccountInfo.email}">
					<input type="submit" name="change_email" value="{lang key='save_changes'}" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>

	<div class="tab-pane" id="tab-pane_password">
		<div class="ia-wrap">
			<form action="{$smarty.const.IA_URL}edit-account.php" method="post" class="ia-form">

				<label for="current">{lang key='current_password'}</label>
				<input type="password" class="span3" name="current" id="current-password">

				<label for="new">{lang key='new_password'}</label>
				<input type="password" class="span3" name="new" id="new-password">

				<label for="confirm">{lang key='new_password2'}</label>
				<input type="password" class="span3" name="confirm" id="new-password-confirm">

				<div class="actions">
					<input type="submit" name="change_pass" value="{lang key='change_password'}" class="btn btn-primary" />
				</div>
			</form>
		</div>
	</div>

	{if $config.allow_delete_accounts}
		<div class="tab-pane" id="tab-pane_delete">
			<div class="ia-wrap">
				<form action="{$smarty.const.IA_URL}edit-account.php" method="post" class="ia-form">
					<label for="delete_accept" class="checkbox"><input type="checkbox" name="delete_accept" id="delete_accept" /> {lang key='delete_account_label'}</label>

					<div class="actions">
						<input name="delete_account" class="btn btn-danger" type="submit" value="{lang key='delete_account'}">
					</div>
				</form>
			</div>
		</div>
	{/if}
</div>