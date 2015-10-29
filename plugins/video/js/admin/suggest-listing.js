$(function()
{
	if(intelli.config.youtube_number_video > 1)
	{
		var field = $("input[name='youtubevideo']");
		var field_parent = field.parent();
		var field_value = field.val();
		
		var html = '';
		var tmpl_html = '';
		
		tmpl_html += '<div class="youtubevideo_box">';
		tmpl_html += '<input type="text" class="common" value="{value}" name="youtubevideo[]" size="45" />';
		tmpl_html += '<input type="button" value="+" class="add_video" />';
		tmpl_html += '<input type="button" value="-" class="remove_video" />';
		tmpl_html += '<input type="button" value="' + intelli.admin.lang.view + '" class="view_video" />';
		tmpl_html += '</div>';
		
		if(field_value != '')
		{
			var new_html = '';
			
			$.each(field_value.split(','), function(i, v)
			{
				new_html += tmpl_html.replace("{value}", v);
			});
			
			html = new_html;
		}
		else
		{
			html = tmpl_html.replace("{value}", '');
		}
		
		field.remove();
		field_parent.append(html);
		
		attach_events(field_parent, tmpl_html);
	}
});

function attach_events(field_parent, tmpl_html)
{
	$("input.add_video").click(function()
	{
		var count = field_parent.children("div.youtubevideo_box").length;

		if(count < intelli.config.youtube_number_video)
		{
			var clone = field_parent.children("div.youtubevideo_box:first").clone(true);
			
			clone.find("input[name='youtubevideo[]']").val('');

			field_parent.append(clone);
		}
		else
		{
			alert(intelli.admin.lang.youtube_no_more_video);
		}
	});
	
	$("input.remove_video").click(function()
	{
		var video = $(this).siblings("input[name='youtubevideo[]']").val();
		var count = field_parent.children("div.youtubevideo_box").length;
		var self = this;
		
		if(video != '')
		{
			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: intelli.admin.lang.youtube_are_you_sure_remove_video,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						$(self).parent("div.youtubevideo_box").remove();
						
						if(1 == count)
						{
							field_parent.append(tmpl_html.replace("{value}", ""));
							
							attach_events(field_parent, tmpl_html);
						}
					}
				}
			});
		}
		else
		{
			if(count > 1)
			{
				$(this).parent("div.youtubevideo_box").remove();
			}
		}
	});

	$("input.view_video").click(function()
	{
		var html = '';
		var video = $(this).siblings("input[name='youtubevideo[]']").val();
		
		if('' != video)
		{
			var video_url = parse_url(video);
			var v = video_url.query.replace("v=", "");
				
			html += '<object width="425" height="355">';
			html += '<param name="movie" value="http://www.youtube.com/v/' + v + '&rel=1"></param>';
			html += '<param name="wmode" value="transparent"></param>';
			html += '<embed src="http://www.youtube.com/v/' + v + '&rel=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed>';
			html += '</object>';
			
			var p = new Ext.Panel({
				height : 500,
				html : html,
				border: false
			});
			
			var w = new Ext.Window(
			{
				width : 439,
				height : 425,
				modal: true,
				resizable: false,
				items: p,
				buttons:[
				{
					text: intelli.admin.lang.close,
					handler: function()
					{
						w.destroy();
					}
				}]
			}).show();
		}
	});
}

function parse_url (str, component)
{
    // http://kevin.vanzonneveld.net
    // +      original by: Steven Levithan (http://blog.stevenlevithan.com)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // %          note: Based on http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
    // %          note: blog post at http://blog.stevenlevithan.com/archives/parseuri
    // %          note: demo at http://stevenlevithan.com/demo/parseuri/js/assets/parseuri.js
    // %          note: Does not replace invaild characters with '_' as in PHP, nor does it return false with
    // %          note: a seriously malformed URL.
    // %          note: Besides function name, is the same as parseUri besides the commented out portion
    // %          note: and the additional section following, as well as our allowing an extra slash after
    // %          note: the scheme/protocol (to allow file:/// as in PHP)
    // *     example 1: parse_url('http://username:password@hostname/path?arg=value#anchor');
    // *     returns 1: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}

    var  o   = {
        strictMode: false,
        key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
        q:   {
            name:   "queryKey",
            parser: /(?:^|&)([^&=]*)=?([^&]*)/g
        },
        parser: {
            strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ // Added one optional slash to post-protocol to catch file:/// (should restrict this)
        }
    };
    
    var m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
    uri = {},
    i   = 14;
    while (i--) {uri[o.key[i]] = m[i] || "";}
    // Uncomment the following to use the original more detailed (non-PHP) script
    /*
        uri[o.q.name] = {};
        uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
        if ($1) uri[o.q.name][$1] = $2;
        });
        return uri;
    */

    switch (component) {
        case 'PHP_URL_SCHEME':
            return uri.protocol;
        case 'PHP_URL_HOST':
            return uri.host;
        case 'PHP_URL_PORT':
            return uri.port;
        case 'PHP_URL_USER':
            return uri.user;
        case 'PHP_URL_PASS':
            return uri.password;
        case 'PHP_URL_PATH':
            return uri.path;
        case 'PHP_URL_QUERY':
            return uri.query;
        case 'PHP_URL_FRAGMENT':
            return uri.anchor;
        default:
            var retArr = {};
            if (uri.protocol !== '') {retArr.scheme=uri.protocol;}
            if (uri.host !== '') {retArr.host=uri.host;}
            if (uri.port !== '') {retArr.port=uri.port;}
            if (uri.user !== '') {retArr.user=uri.user;}
            if (uri.password !== '') {retArr.pass=uri.password;}
            if (uri.path !== '') {retArr.path=uri.path;}
            if (uri.query !== '') {retArr.query=uri.query;}
            if (uri.anchor !== '') {retArr.fragment=uri.anchor;}
            return retArr;
    }
}