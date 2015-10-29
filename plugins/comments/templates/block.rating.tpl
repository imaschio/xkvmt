{if isset($comment_rating) && !empty($comment_rating)}

	<div id="comments_ratings"></div><br />

	{assign var=comments_rating_block_max value="comments_"|cat:$item.item|cat:"_rating_block_max"}
	{$lang.comment_rating}:&nbsp;<span id="ratings_text">{$comment_rating.rating|string_format:"%.2f"}&nbsp;/&nbsp;{$config.$comments_rating_block_max}&nbsp;({$comment_rating.num_votes}&nbsp;{if $comment_rating.num_votes > 1}{$lang.votes_cast}{else}{$lang.vote_cast}{/if})</span>

	<input type="hidden" id="item_id" value="{$item.id}" />
	<input type="hidden" id="item_name" value="{$item.item}" />
	<input type="hidden" id="current_comment_rating" value="{$comment_rating.rating}" />
	<input type="hidden" id="comment_voted" value="{if $comment_rating.voted}1{else}0{/if}" />

	{ia_print_js files='plugins/comments/js/frontend/exstars, plugins/comments/js/frontend/ratings'}
{/if}