$(function()
{
	editAreaLoader.init(
	{
		id : "codeContainer"
		,syntax: "php"
		,start_highlight: true
		,allow_resize: 'no'
		,replace_tab_by_spaces: true
		,toolbar: 'save, search, go_to_line, |, undo, redo, |, help'
		,save_callback: "saveHook"
	});
	
	$("#show").click(function()
	{
		var hook = $("#hook").val();
		var hookText = $("#hook option:selected").text();

		$.post("controller.php?file=database", {action: "getCode", hook: hook}, function(code)
		{
			editAreaLoader.openFile("codeContainer", {id: hook, text: code, syntax: 'php', title: hookText});
		});
	});

	$("#save").click(function()
	{
		var code = editAreaLoader.getValue("codeContainer");
		var save_hook = editAreaLoader.getCurrentFile("codeContainer").id;

		saveHook(save_hook, code);
	});

	$("#close_all").click(function()
	{
		var hooks = editAreaLoader.getAllFiles("codeContainer");

		if(hooks)
		{
			for(hook in hooks)
			{
				editAreaLoader.closeFile("codeContainer", hook);
			}
		}
	});
});

function saveHook(id, code)
{
	var save_hook = editAreaLoader.getCurrentFile("codeContainer").id;
	
	$.post("controller.php?file=database", {action: "setCode", hook: save_hook, code: code}, function()
	{
		intelli.admin.notifFloatBox({msg: 'HOOK is saved', type: 'notif', autohide: true});
	});
}
