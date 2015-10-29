function esynTree(tree, params)
{
	var tree = $(tree);

	this.params = params;
	delete params;

	str = '';
	// Opera refuses to work with radios without form tag
	if($.browser.opera)
	{
		str += "<form>";
	}
	if(this.params.widget)
	{
		widget = this.params.widget;
	}
	else
	{
		widget = 'radio'
	}

	var state = new Array();

	if(this.params.state)
	{
		state = this.params.state.split(",");
	}

	rootChecked = '';
	for(var i = 0; i < state.length; i++)
	{
		if(state[i] == '0')
		{
			rootChecked = ' checked="checked"';
			break;
		}
	}
	rootNotDisabled = this.params.rootNotDisabled ? '' : 'disabled="yes" ';
	
	str += '<ul class="'+this.params.treeClass+'">';
	str += '<li>';
	str += '<a href="javascript:void(0)" onclick="'+this.params.obj+'.getCategoryChildren(\'0\')" class="no"><img style="margin-bottom:3px;height:9px;width:9px" src="img/tree/plus.png" id="im_0'+this.params.obj+'" alt="" /> <img style="margin-bottom:3px;height:12px;width:16px" src="img/tree/folder.gif" id="imf_0'+this.params.obj+'" alt="" /></a>';
	str += '<input type="'+widget+'" class="'+this.params.inputClass+'" path="" title="ROOT" name="categories[]" '+rootNotDisabled;
	if(this.params.callback)
	{
		str += ' onclick="'+this.params.obj+'.'+this.params.callback+'(this)"';
	}
	str += ' class="treeItem_1" value="0" id="labelcat0'+this.params.obj+'"'+rootChecked+' />';
	str += '<label for="labelcat0'+this.params.obj+'">ROOT</label>';
	str += '<div id="category_0'+this.params.obj+'" style="display:none;"></div>'
	str += '</li>';
	str += '</ul>';
	if($.browser.opera)
	{
		str += "</form>";
	}
	tree.html(str);
}

// dummy
esynTree.prototype.onGetCategory = function()
{
	return true;
}

esynTree.prototype.reset = function()
{
	$("#category_0"+this.params.obj).removeClass("_tree_loaded").hide();
	$("#im_0"+this.params.obj).attr("src", "img/tree/plus.png");
	$("#imf_0"+this.params.obj).attr("src", "img/tree/folder.gif");
}

esynTree.prototype.getCategoryChildren = function(catid, state, sync)
{
	if(!this.onGetCategory())
	{
		return false;
	}
	var synxro = sync ? false : true;

	var c = "#category_"+catid+this.params.obj;

	imsrc = $('#im_' + catid + this.params.obj).attr("src");

	// if the node is expanded then collapse
	if(-1!=imsrc.indexOf("minus.png"))
	{
		$('#im_' + catid + this.params.obj).attr("src", 'img/tree/plus.png');
		$('#imf_' + catid + this.params.obj).attr("src", 'img/tree/folder.gif');
		$(c).hide();

		return;
	}
	else
	{
		var x = $(c).attr("class");
		$('#im_' + catid + this.params.obj).attr("src", 'img/tree/minus.png');	
		$('#imf_' + catid + this.params.obj).attr("src", 'img/tree/open_folder.gif');

		// if class contains _tree_loaded but not marked as reloadTree then show already loaded
		// otherwise tree must be reloaded (e.g if type of the tree is changed)
		if(x && x.match(/_tree_loaded/))
		{
			$(c).show();
		
			return;
		}
	}

	var pars = 'id='+catid;
	pars += '&widget='+this.params.widget;
	if(this.params.callback)
	{
		pars += "&callback="+this.params.obj+"."+this.params.callback;
	}
	pars += "&obj="+this.params.obj;

	if(this.params.state)
	{
		pars+="&state="+this.params.state;
	}
	if(this.params.inputClass)
	{
		pars += "&inputClass="+this.params.inputClass;
	}

	$(c).before($("#spinner"));
	$("#spinner").show();

	$.ajax({
		type: "GET",
		url: "get-categories.php?"+pars,
		async: synxro,
		success: function(data)
		{
			$(c).html(data).show();
			// mark the node as loaded see above code
			$(c).addClass("_tree_loaded");
			$("#spinner").hide();
		}
	});

}