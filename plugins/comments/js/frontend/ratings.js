$(function()
{
	var item_name = $("input[name='item_name']").val();

	var rating = new exstars(
	{
		id: 'comments_ratings',
		idText: 'ratings_text',
		numStars: intelli.config['comments_' + item_name + '_rating_block_max'],
		clsNoFill: 'exstar-no-fill',
		clsFill: 'exstar-fill',
		clsHalfFill: 'exstar-voted-fill',
		url: intelli.config.esyn_url + 'controller.php?plugin=comments',
		numDefault: $("#current_comment_rating").val(),
		voted: ('1' == $("#comment_voted").val()) ? true : false
	});

	rating.init();
});