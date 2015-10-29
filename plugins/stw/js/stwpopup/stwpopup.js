// configuration
var baseURI = intelli.config.esyn_url + 'plugins/stw/js/stwpopup';
var stwsize = intelli.config.stw_size;
var branded = intelli.config.stw_branded;
var requestURI = intelli.config.esyn_url + 'controller.php?plugin=stw&url='; // ajax url. all API params are defined right in php file.
// to use deafult API uncomment the following strings
/*
var stwurl = "http://images.shrinktheweb.com/";
var stwauth = '';
var stwinside = '';
var stwdelay = '';
var stwfull = '';
var stwsize = 'sm';
var requestURI = stwurl + 'xino.php?stwembed=1&stwaccesskeyid='+stwauth+'&stwinside='+stwinside+'&stwdelay='+stwdelay+'&stwfull='+stwfull+'&stwsize='+stwsize+'&stwurl=';
*/

// configuration end

// main script ! DO NOT MODIFY !
if (typeof jQuery === "undefined")
{
	loadjQuery("http://code.jquery.com/jquery-1.10.1.min.js", main);
}
else
{
	main();
}

function loadjQuery(url, callback)
{
	var script_tag = document.createElement('script');
	script_tag.setAttribute("src", url)
	script_tag.onload = callback; // Run callback once jQuery has loaded
	script_tag.onreadystatechange = function () 
	{
		// Same thing but for IE
		if (this.readyState == 'complete' || this.readyState == 'loaded') callback();
	}
	document.getElementsByTagName("head")[0].appendChild(script_tag);
}

function loadCSS(url)
{
	var script_tag = document.createElement('link');
	script_tag.rel = "stylesheet";
	script_tag.type = "text/css";
	script_tag.href = baseURI + url;
	document.getElementsByTagName("head")[0].appendChild(script_tag);
}

function main()
{

	function checkPosition(link, params)
	{
		var bounds = {};
		var pos = {};
		var win = $(window);
		var viewport = {
			top : win.scrollTop(),
			left : win.scrollLeft()
		};
		viewport.right = viewport.left + win.width();
		viewport.bottom = viewport.top + win.height();

		var link_bounds = link.offset(); // get link position
		bounds.top = link_bounds.top + params.tooltip_height;
		bounds.left = link_bounds.left + link.width()/2 + params.tooltip_width;
		bounds.right = link_bounds.left + link.width()/2 + link.outerWidth() + params.tooltip_width;
		bounds.bottom = link_bounds.top + link.outerHeight() + params.tooltip_height;

		pos.x = 'r'; //right
		pos.y = 'b'; //bottom
		params.tooltip_top = +link.height();
		params.tooltip_left = +link.width()/2;

		if (bounds.right >= viewport.right)
		{
			pos.x = 'l'; //left
			params.tooltip_left = -params.tooltip_width + link.width()/2;
		}
		if (bounds.bottom >= viewport.bottom)
		{
			params.tooltip_top = -params.tooltip_height;
			pos.y = 't'; //top
		}

		params.pos = pos.x + pos.y;

		return params;
	}

	$(document).ready(function()
	{
		var params = stwsize + '_size'; // object with all tooltip params
		var url = 'url('+ baseURI +'/'; // specify url for non-branded images
		var add_class = '';

		loadCSS("/stwpop.css");
	
		if (1 == branded)
		{
			url = 'url('+ baseURI +'/bg'; // specify url for branded images
			add_class = 'bg';
			switch (stwsize)
			{
				case 'sm' :
				params = {
					tooltip_width: 163,
					tooltip_height: 151
				}
				break

				case 'lg' :
				params = {
					tooltip_width: 250,
					tooltip_height: 231
				}
				break

				case 'xlg' :
				params = {
					tooltip_width: 400,
					tooltip_height: 371
				}
				break
			}

		} else {

			switch (stwsize)
			{
				case 'sm' :
				params = {
					tooltip_width: 158,
					tooltip_height: 127
				}
				break

				case 'lg' :
				params = {
					tooltip_width: 243,
					tooltip_height: 195
				}
				break

				case 'xlg' :
				params = {
					tooltip_width: 380,
					tooltip_height: 305
				}
				break
			}
		}

		var bg_img = []; // define images
		bg_img['rb'] = url +'rb-'+ stwsize +'.png)';
		bg_img['rt'] = url +'rt-'+ stwsize +'.png)';
		bg_img['lb'] = url +'lb-'+ stwsize +'.png)';
		bg_img['lt'] = url +'lt-'+ stwsize +'.png)';

		// control tooltip
		
		$('a.stwpopup').mouseover(function()
		{
			params = checkPosition($(this), params);

			if ($(this).children('.stw-tooltip').length <= 0)
			{
				var img;
				if (typeof stwurl !== "undefined")
				{
					img = requestURI + $(this).attr('href');
				}
				else
				{
					$.ajax({
						url: requestURI + $(this).attr('href'),
						async: false,
						success: function(data)
						{
							img = data;
						}
					});
				}
				
				var tooltip = $('<div class="stw-tooltip" />').css({
					width: params.tooltip_width,
					height: params.tooltip_height,
					top: params.tooltip_top,
					left: params.tooltip_left,
					backgroundImage: bg_img[params.pos]
				});
				tooltip.addClass(add_class + params.pos + '-' + stwsize);

				$(this).append(tooltip.append(img));
			}
			else
			{
				$(this).children('.stw-tooltip').css({
					top: params.tooltip_top,
					left: params.tooltip_left,
					backgroundImage: bg_img[params.pos]
				}).show();
			}

		}).mouseleave(function()
		{
			$(this).children('.stw-tooltip').hide('fast');
		});
	});
}