$(function()
{
	if ('undefined' != typeof pWhat && pWhat != '')
	{
		var justOneWord = true;

		if ($('#any').attr("checked") || $('#all').attr("checked"))
		{
			var allWords = new Array;

			allWords = pWhat.split(" ");
			justOneWord = !!(allWords.length);

			if (!justOneWord)
			{
				for (var i = 0; i < allWords.length; i++)
				{
					if (allWords[i].length > 2 )
					{
						var pat = "(" + allWords[i] + ")";

						$('h4.media-heading a, div.media-body div.description, div.cat-wrap a').each(function()
						{
							var th = $(this).html();
							th = doHighlight(th, allWords[i]);
							$(this).html(th);
						});
					}
				}
			}
		}

		if ($('#exact').attr("checked") || justOneWord)
		{
			pWhat = "(" + pWhat + ")";

			$("h4.media-heading a, div.cat-wrap a").each(function()
			{
				var th = $(this).html();
				var re = new RegExp(pWhat,"gi");

				if (re.test(th))
				{
					th = th.replace(re, '<span class="highlight">$1</span>');
					$(this).html(th);
				}
			});

			$('div.media-body div.description').each(function()
			{
				var th = $(this).html();
				var re = /<\/?\w+[^>]*>/gi;
				var tmp = th.split(re);

				var re2 = new RegExp(pWhat,"mgi");

				for(var i=0; i<tmp.length;i++)
				{
					var n =	tmp[i];
					var s = n.replace(re2, '<span class="highlight">$1</span>');
					th = th.replace(n,s);
				}

				$(this).html(th);
			});
		}
	}
	
	$("#adv_cat_search_submit").click(function() 
	{
		$("#adv_cat_search_form").submit();

		return false;
	});
});

function doHighlight(bodyText, searchTerm, highlightStartTag, highlightEndTag) 
{
	if ((!highlightStartTag) || (!highlightEndTag))
	{
		highlightStartTag = '<span class="highlight">';
		highlightEndTag = '</span>';
	}
  
	var newText = "";
	var i = -1;
	var lcSearchTerm = searchTerm.toLowerCase();
	var lcBodyText = bodyText.toLowerCase();
    
	while (bodyText.length > 0)
	{
		i = lcBodyText.indexOf(lcSearchTerm, i+1);
		
		if (i < 0)
		{
			newText += bodyText;
			bodyText = "";
		}
		else
		{
			// skip anything inside an HTML tag
			if (bodyText.lastIndexOf(">", i) >= bodyText.lastIndexOf("<", i))
			{
				// skip anything inside a <script> block
				if (lcBodyText.lastIndexOf("/script>", i) >= lcBodyText.lastIndexOf("<script", i))
				{
					newText += bodyText.substring(0, i) + highlightStartTag + bodyText.substr(i, searchTerm.length) + highlightEndTag;
					bodyText = bodyText.substr(i + searchTerm.length);
					lcBodyText = bodyText.toLowerCase();
					i = -1;
				}
			}
		}
	}
  
	return newText;
}