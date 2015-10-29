<div id="{$id|default:'notification'}">
	{foreach $messages as $type => $messages_list}
		<div class="message {$type}">
			<div class="icon"></div>
			<ul>
				{foreach $messages_list as $message}
					<li>{$message}</li>
				{/foreach}
			</ul>
		</div>
	{/foreach}
</div>