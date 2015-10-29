$("[id^='bnr']").click(function()
{
	countBannerClick(this);
});

function countBannerClick(el)
{
	if(el.id.length < 4)
	{
		return true;
	}

	itemid = el.id.substring(3);
	if(parseInt(itemid.charAt(0)) < 1 || itemid.match(/\D/))
	{
		return true;
	}
	i = new Image();

	i.src= 'controller.php?plugin="banners"&file="banner-count"&id=' + itemid;

	return true;
}