/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config )
{
	if ('auto' != intelli.config.ckeditor_default_language)
	{
		config.language = intelli.config.ckeditor_default_language;
	}

	config.resize_enabled = true;
	config.filebrowserImageUploadUrl = 'ck_upload.php?Type=Image';
	config.allowedContent = true;
	config.extraPlugins = 'mediaembed';

	if (1 == intelli.config.ckeditor_code_highlighting)
	{
		config.extraPlugins += ',syntaxhighlight';
	}

	if (typeof intelli.admin == 'undefined')
	{
		config.toolbar = 'Basic';
		config.uiColor = intelli.config.ckeditor_ui_color;
	}
	else
	{
		config.toolbar = 'User';
		config.uiColor = '#bbd2e0';

		config.extraPlugins += ',codemirror';
	}

	config.toolbar_User = [
		['Source', '-', 'Maximize'],
		['Cut', 'Copy', 'Paste','PasteText','PasteFromWord','-','Undo','Redo'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Image','MediaEmbed','Code','Table','HorizontalRule','SpecialChar']
	];

	config.toolbar_Basic = [
		['Bold','Italic','Underline','TextColor','BGColor'],
		['Cut', 'Copy', 'Paste','PasteText','PasteFromWord'],
		['Link','Unlink','Image','MediaEmbed','Code','RemoveFormat']
	];

	config.toolbar_Simple = [
		['Source'],
		['Bold','Italic','Underline','TextColor','-','Link','Unlink'],
		['Cut', 'Copy', 'Paste','PasteText','PasteFromWord','-','RemoveFormat']
	];	
};