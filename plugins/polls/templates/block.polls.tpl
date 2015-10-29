{if $polls}
	{foreach $polls as $poll}
		<h5>{$poll.title}</h5>
		{if $poll.alreadyVoted}
			{$poll.results}
		{else}
			<div id="poll_{$poll.id}">
				<form class="poll_options_list ia-form">
					{foreach $poll.options as $option}
						<label for="poll_option_{$poll.id}_{$option.id}" class="radio">
							<input class="poll_option" type="radio" id="poll_option_{$poll.id}_{$option.id}" value="0" name="poll_option_{$poll.id}" /> {$option.title}
						</label>
					{/foreach}
				</form>
			</div>
		{/if}

		{if !$poll@last}<hr class="poll_separator">{/if}
	{/foreach}

	{ia_print_js files='plugins/polls/js/frontend/polls'}
	{ia_print_css files='plugins/polls/templates/css/style'}
{/if}