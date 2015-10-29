/**
 * Class for creating listing fields section.
 * @class This is the Listing fields class.  
 *
 * @param {Array} conf
 *
 * @param {String} conf.id The id div of fields element
 * @param {Array} conf.session The array of last state
 * @param {Boolean} conf.restore Restoring data after updating box
 * @param {String} conf.listingId The listing id
 *
 */
intelli.fields = function(conf)
{
	var obj = (-1 != conf.id.indexOf('#')) ? $(conf.id) : $('#' + conf.id);
	var restore = conf.restore ? conf.restore : false;
	var lastState = (typeof conf.session != 'undefined') ? conf.session : new Array();
	var listingId = conf.listingId || 0;
	var part = conf.part || 'suggest';

	var listingFields = new Array();

	/**
	 * Return id current category 
	 *
	 * @return {Integer}
	 */
	var getIdCategory = function()
	{
		return $('#category_id').val();
	};

	/**
	 * Return id current plan
	 *
	 * @return {Integer}
	 */
	var getIdPlan = function()
	{
		var id = '';
		
		if($('#plans').length > 0)
		{
			id = $("#plans input[type='radio']:checked").val();
		}

		return id;
	};

	var addImgItem = function(btn)
	{
		var thisParent = btn.closest('.upload-gallery-wrap-outer');
		var clone = thisParent.clone(true);
		var name = $('input[type="file"]', thisParent).attr("name").replace('[]', '');
		var num = $("#" + name + "_num_img").val();

		if(num > 0)
		{
			// empty clone fields
			$('input', clone).val('');
			$('.uneditable-input', clone).text(intelli.lang.click_to_upload);
			thisParent.after(clone);
			$("#" + name + "_num_img").val(num - 1);
		}
		else
		{
			alert(intelli.lang.no_more_files);
		}

		detectFilename();
	};

	var removeImgItem = function(btn)
	{
		var thisParent = btn.closest('.upload-gallery-wrap-outer');
		var name = $('input[type="file"]', thisParent).attr("name").replace('[]', '');
		var num = $("#" + name + "_num_img").val();

		if (thisParent.prev().hasClass('upload-gallery-wrap-outer') || thisParent.next().hasClass('upload-gallery-wrap-outer'))
		{
			thisParent.remove();
			$("#" + name + "_num_img").val(num * 1 + 1);
		}
	};
	
	// Create listing field
	var createField = function(conf, group_id)
	{
		var length = conf.length.split(',');

		var min = parseInt(length[0]);
		var max = parseInt(length[1]);
		var tooltip = intelli.trim(conf.tooltip);

		var html = '<div class="control-group" id="field_wrapper_' + conf.name + '">';

		html += '<label for="f_' + conf.name + '"id="form_field_' + conf.name + '">';
		html += conf.title;
		html += 1 == conf.required ? ' <sup class="text-error">*</sup> ' : ' ';
		if ('' != tooltip)
		{
			html += '<i class="js-tooltip icon-info-sign icon-blue" data-original-title="' + tooltip + '"></i> ';
		}
		html += '</label>';

		switch(conf.type)
		{
			case 'text':
				conf['default'] = intelli.htmlspecialchars(conf['default']);
				html += '<input type="text" size="35" id="f_' + conf.name + '" value="'+ conf['default'] +'" name="' + conf.name + '" />';

				if ('url' == conf.name && intelli.config.autometafetch == 1)
				{
					html += ' <a href="#" class="js-tooltip" onclick="javascript: metaFetch(); return false;" title="' + intelli.lang.fetch_meta_description + '"><i class="icon-refresh"></i></a>';
				}

				break;
			case 'textarea':
				conf['default'] = intelli.htmlspecialchars(conf['default']);
				html += '<textarea cols="45" rows="6" id="f_' + conf.name + '" name="' + conf.name + '" class="input-block-level">'+ conf['default'] +'</textarea>';

				if(0 == conf.editor && (!isNaN(min) || !isNaN(max)))
				{
					html += '<p class="help-block text-right">' + intelli.lang.characters_count + ': <input type="text" class="char-counter" id="counter_'+ conf.name +'" size="4" readonly /></p>';
				}

				break;
			case 'pictures':
				var length = parseInt(conf.length) - 1;
				
				if('edit' == part)
				{
					if('' != conf['default'])
					{
						var images = conf['default'].split(',');
						
						$.each(images, function(i, v)
						{
							var img = new Image();
							var img_html = '';
							
							img.src = intelli.config.esyn_url + 'uploads/small_' + v;
							
							img.onload = function()
							{
								img_html += '<div class="img_div" style="padding: 3px 0 0 0;"><a href="' + intelli.config.esyn_url + 'uploads/' + v + '" target="_blank" rel="ia_lightbox"><img src="' + intelli.config.esyn_url + 'uploads/small_' + v + '" /></a>&nbsp;';
								img_html += '<a href="' + intelli.config.esyn_url + '" class="remove_img">Remove</a></div>';
							
								$("#form_field_" + conf.name).append(img_html);
								
								$("#" + conf.name + "_num_img").val($("#" + conf.name + "_num_img").val() - 1);
								
								// $("a.lightbox").lightBox();
								
								$("a.remove_img").each(function()
								{
									if(!$(this).hasClass('loaded'))
									{
										$(this).click(function()
										{
											var self = $(this);
											
											$.post('suggest-listing.php?action=removeimg', {id: intelli.urlVal('edit'), field: conf.name, image: v}, function()
											{
												self.parent("div.img_div").remove();
												$("#" + conf.name + "_num_img").val(parseInt($("#" + conf.name + "_num_img").val()) + 1);
											});
											
											return false;
										});
										
										$(this).addClass('loaded');
									}
								});
							}
							
							img.onerror = function()
							{
								
								img_html += '<div class="img_div"><a href="' + intelli.config.esyn_url + 'uploads/' + v + '" target="_blank" rel="ia_lightbox"><img src="' + intelli.config.esyn_url + 'uploads/' + v + '" /></a>';
								img_html += '<a href="' + intelli.config.esyn_url + '" class="remove_img">Remove</a></div>';
							
								$("#form_field_" + conf.name).append(img_html);
								$("#" + conf.name + "_num_img").val($("#" + conf.name + "_num_img").val() - 1);
								
								// $("a.lightbox").lightBox();
								
								$("a.remove_img").each(function()
								{
									if(!$(this).hasClass('loaded'))
									{
										$(this).click(function()
										{
											var self = $(this);
											
											$.post('suggest-listing.php?action=removeimg', {id: intelli.urlVal('edit'), field: conf.name, image: v}, function()
											{
												self.parent("div.img_div").remove();													
												$("#" + conf.name + "_num_img").val(parseInt($("#" + conf.name + "_num_img").val()) + 1);
											});
											
											return false;
										});
										
										$(this).addClass('loaded');
									}
								});
							}
						});
					}
				}

				html += '<div class="upload-gallery-wrap-outer">';
				html += '' + intelli.lang.image + '';
				html += '<div class="upload-gallery-wrap clearfix">';
				html += '<div class="upload-wrap pull-left">';
				html += '<div class="input-append"><span class="span2 uneditable-input">' + intelli.lang.click_to_upload + '</span><span class="add-on">' + intelli.lang.browse + '</span></div>';
				html += '<input class="upload-hidden" type="file" name="'+ conf.name +'[]" />';
				html += '</div>';
				html += '<input type="text" class="upload-title" placeholder="' + intelli.lang.image_title + '" value="" name="' + conf.name + '_titles[]" />';
				html += '<button class="add_img btn btn-info"><i class="icon-plus-sign icon-white"></i></button>';
				html += '<button class="remove_img btn btn-info"><i class="icon-minus-sign icon-white"></i>';
				html += '<input type="hidden" value="' + length + '" name="num_images" id="'+ conf.name +'_num_img" />';
				html += '</div>';
				html += '</div>';

				break;
			case 'combo':
				var values = conf.values.split(',');
				var selected = '';
				
				html += '<select name="'+ conf.name +'" id="f_' + conf.name + '">';

				for(var i = 0; i < values.length; i++)
				{
					selected = '';
					selected = (conf['default'] == values[i]) ? 'selected="selected"' : "";

					html += '<option value="'+ values[i] +'" '+ selected +'>';
					html += conf.labels[values[i]];
					html += '</option>';
				}

				html += '</select>';
				break;
			case 'radio':
				var values = conf.values.split(',');
				var checked = '';

				for (var i = 0; i < values.length; i++)
				{
					checked = '';
					checked = (conf['default'] == values[i]) ? 'checked="checked"' : "";

					html += '<label for="f_'+ conf.name + '_' + i +'" class="radio" >' + '<input type="radio" name="'+ conf.name +'" id="f_'+ conf.name + '_' + i +'" value="'+ values[i] +'"'+ checked +' />';
					html += conf.labels[values[i]] +'</label>'
				}

				break;
			case 'checkbox':
				var values = conf.values.split(',');
				var defaults = conf['default'].split(',');
				var checked = '';
				
				if (conf.check_all == '1')
				{
					html += '<label for="check_all_'+ conf.name +'" class="checkbox">' + '<input type="checkbox" name="check_all_'+ conf.name + '" id="check_all_'+ conf.name + '"/>';
					html += intelli.lang['check_all'] +'</label>';
				}

				for(var i = 0; i < values.length; i++)
				{
					checked = '';
					checked = (intelli.inArray(values[i], defaults)) ? 'checked="checked"' : "";

					html += '<label for="f_'+ conf.name + '_' + i +'" class="checkbox">'+ '<input type="checkbox" name="'+ conf.name +'[]" id="f_'+ conf.name + '_' + i +'" value="'+ values[i] +'"'+ checked +'/>';
					html += conf.labels[values[i]] +'</label>'
				}

				break;
			case 'image':

				html += '<div class="clearfix">';
				html += '<div class="upload-wrap pull-left">';
				html += '<div class="input-append"><span class="span2 uneditable-input">' + intelli.lang.click_to_upload + '</span><span class="add-on">' + intelli.lang.browse + '</span></div>';
				html += '<input type="file" class="upload-hidden" id="f_' + conf.name + '" name="'+ conf.name +'" />';
				html += '</div>';
				html += ' <input type="text" placeholder="' + intelli.lang['title'] +'" id="f_' + conf.name + '_title" name="' + conf.name + '_title" length="' + conf.image_title_length + '" />';

				if ('' != conf['default'])
				{
					$.ajax({
						url: intelli.config.esyn_url + 'uploads/' + conf['default'],
						type: 'HEAD',
						success: function()
						{
							html += '<a class="btn btn-info" href="' + intelli.config.esyn_url + 'uploads/' + conf['default'] + '" title="' + intelli.lang.view + '" rel="ia_lightbox"><i class="icon-eye-open"></i></a> ';
							html += '<a class="btn btn-danger" title="' + intelli.lang['delete'] + '" href="#" id="remove_'+ conf.name +'"><i class="icon-remove-sign"></i></a> ';
						}
					});
				}

				html += '</div>';
				
			break;
			case 'storage':
				html += '<div class="clearfix">';
				html += '<div class="upload-wrap pull-left">';
				html += '<div class="input-append"><span class="span2 uneditable-input">' + intelli.lang.click_to_upload + '</span><span class="add-on">' + intelli.lang.browse + '</span></div>';
				html += '<input type="file" class="upload-hidden" id="f_' + conf.name + '" name="'+ conf.name +'" />';
				html += '</div>';

				$.ajax({
					url: intelli.config.esyn_url + 'uploads/' + conf['default'],
					type: 'HEAD',
					success: function()
					{
						html += '<a class="btn btn-info" id="view_'+ conf.name +'" href="'+ intelli.config.esyn_url +'uploads/'+ conf['default'] +'" title="'+ intelli.lang.view +'" target="_blank"><i class="icon-eye-open"></i></a> ';
						html += '<a href="#" id="remove_'+ conf.name +'" class="btn btn-danger" title="'+ intelli.lang['delete'] +'"><i class="icon-remove-sign"></i></a>';
					}
				});

				html += '</div>';

				break;
			case 'number':
				html += '<input type="text" class="text" size="35" id="f_' + conf.name + '" value="'+ conf['default'] +'" name="' + conf.name + '" />';
				break;
			default:
				break;
		}
		html += '</div>';

		('' != group_id) ? $('#' + group_id).append(html) : obj.append(html);
		
		$("#check_all_" + conf.name).click(function()
		{
			if($(this).prop('checked'))
			{
				$("input[id^='f_" + conf.name + "']").prop('checked','checked');
			}
			else
			{
				$("input[id^='f_" + conf.name + "']").prop('checked','');
			}

			return true;
		});

		$("#remove_"+ conf.name).click(function()
		{
			var selffile = $(this);
			$.post('suggest-listing.php?action=removefile', {id: intelli.urlVal('edit'), field: conf.name, file: conf['default']}, function()
			{
				selffile.parent().remove();
			});
			return false;
		});
		
		// textarea WSYWYG editor
		if ('textarea' == conf.type && 1 == conf.editor)
		{
			if (CKEDITOR.instances["f_" + conf.name])
			{
				delete CKEDITOR.instances["f_" + conf.name];
			}

			if (!isNaN(min) || !isNaN(max))
			{
				var opt = {toolbar: 'Basic', counter: 'counter_' + conf.name, min_length: min, charcount_limit: max};
			}
			else
			{
				var opt = {toolbar: 'Basic'};
			}

			intelli.ckeditor('f_' + conf.name, opt);
		}

		// textcounter for textarea fields initialization
		if ('textarea' == conf.type && 0 == conf.editor && (!isNaN(min) || !isNaN(max)))
		{
			var textcounter = new intelli.textcounter(
			{
				textarea_el: 'f_' + conf.name,
				counter_el: 'counter_' + conf.name,
				min: min,
				max: max
			});

			textcounter.init();
		}

		if ('number' == conf.type)
		{
			$('#f_' + conf.name).keydown(function(e)
			{
				var code = e.which || e.keyCode;

				if(code > 31 && (code < 48 || code > 57))
				{
					return false;
				}

				return true;
			});
		}

		// image gallery
		if ('pictures' == conf.type)
		{
			$('.upload-gallery-wrap button').each(function()
			{
				$(this).click(function(e)
				{
					e.preventDefault();
					var _this = $(this);

					if(_this.hasClass('add_img'))
					{
						addImgItem(_this);
					}
					else
					{
						removeImgItem(_this);
					}
				});
			});
		}

		$('.js-tooltip').tooltip();
	};

	// fill up the fields section
	this.fillFields = function()
	{
		// save current values
		if (restore)
		{
			saveState();
		}

		// clearing field box
		obj.empty();

		intelli.display(obj, 'hide');
		$('.fields-loader').show();

		// get default ids category and plan
		var idCategory = getIdCategory();
		var idPlan = getIdPlan();
		var params = {action: 'getfields', part: part, idcategory: idCategory};

		if (idPlan)
		{
			params['idplan'] = idPlan;
		}

		if (listingId)
		{
			params['idlisting'] = listingId;
		}

		$.ajaxSetup({async: false});

		// getting listings fields by AJAX
		$.getJSON('get-fields.php', params, function(fieldGroups)
		{
			// reciprocal fieldset hide
			$("#reciprocal").hide();

			$.each(fieldGroups, function(groupName, fieldGroup)
			{
				var id = '';
				var html = '';

				if ('non_group' != groupName)
				{
					id = 'group_' + groupName;

					// add necessary classes for collapsible
					var collapsedStatus = '';
					if (1 == fieldGroup['collapsible'])
					{
						collapsedStatus += ' collapsible';
						if (1 == fieldGroup['collapsed'])
						{
							collapsedStatus += ' collapsed';
						}
					}

					html += '<div class="fieldset' + collapsedStatus + '">';
					html += '<h4 class="title">' + intelli.lang['field_group_title_' + groupName] + '</h4>';
					html += '<div id="' + id + '" class="content collapsible-content"></div>';
					html += '</div>';

					obj.append(html);
				}
				else
				{
					id = 'group_non_group';

					html += '<div id="' + id + '" class="fieldset non-group"></div>';

					obj.append(html);
				}

				for (var i = 0; i < fieldGroup['fields'].length; i++)
				{
					if (restore && '' != lastState)
					{
						for(var j = 0; j < lastState.length; j++)
						{
							if (fieldGroup['fields'][i].name == lastState[j].name)
							{
								if (lastState[j].value && '' != lastState[j].value)
								{
									fieldGroup['fields'][i]['default'] = lastState[j].value;
								}
							}
						}
					}
					// reciprocal fieldset show if reciprocal field is exists
					if ('reciprocal' == fieldGroup['fields'][i].name)
					{
						$("#reciprocal").show();
					}

					createField(fieldGroup['fields'][i], id);

					listingFields.push(fieldGroup['fields'][i]);
				}
			});
		});

		$.ajaxSetup({async: true});

		minMax();

		intelli.display(obj, 'show');
		$('.fields-loader').hide();

	};

	// conversion the form
	this.conversion = function()
	{
		for(var i = 0; i < listingFields.length; i++)
		{
			var html = '';

			switch(listingFields[i].type)
			{
				case 'text':
					html += '<br />';
					html += '<i>';
					html += $('#f_' + listingFields[i].name).val();
					html += '</i>';
					break;
				case 'textarea':
					html += '<br />';
					html += '<i>';
					html += $('#f_' + listingFields[i].name).val();
					html += '</i>';
					break;
				case 'combo':
					html += '<br />';
					html += '<i>';
					html += listingFields[i].labels[$('#f_' + listingFields[i].name).val()];
					html += '</i>';
					break;
				case 'radio':
					// TODO: use selectors for getting selected radio element
					var values = listingFields[i].values.split(',');

					for(var j = 0; j < values.length; j++)
					{
						if($('#f_' + listingFields[i].name + '_' + values[j]).attr('checked'))
						{
							html += '<br />';
							html += '<i>';
							html += listingFields[i].labels[$('#f_' + listingFields[i].name + '_' + values[j]).val()];
							html += '</i>';
						}
					}
					break;
				case 'checkbox':
					// TODO: use selectors for getting selected radio element
					var values = listingFields[i].values.split(',');
					var firstElement = true;

					html += '<br />';
					html += '<i>';

					for(var j = 0; j < values.length; j++)
					{
						if($('#f_' + listingFields[i].name + '_' + values[j]).attr('checked'))
						{
							if(values.length > 1)
							{
								if(!firstElement)
								{
									html += ',&nbsp;';
								}
								else
								{
									firstElement = false;
								}
							}
							html += listingFields[i].labels[$('#f_' + listingFields[i].name + '_' + values[j]).val()];
						}
					}

					html += '</i>';
					break;
				case 'storage':
					html += '<br />';
					html += '<i>';
					html += $('#f_' + listingFields[i].name).val();
					html += '</i>';
					break;
				case 'image':
					html += '<br />';
					html += '<i>';
					html += $('#f_' + listingFields[i].name).val();
					html += '</i>';
					break;
				case 'number':
					html += '<br />';
					html += '<i>';
					html += $('#f_' + listingFields[i].name).val();
					html += '</i>';
					break;
				default:
					break;
			}

			$('#val_field_' + listingFields[i].name + ' > span').empty();
			$('#val_field_' + listingFields[i].name + ' > span').append(html);

			intelli.display('form_field_' + listingFields[i].name, 'hide');
			intelli.display('val_field_' + listingFields[i].name, 'show');
		}
	};

	/**
	 * Saving the values in the form
	 *
	 * @return {Array}
	 *
	 * TODO: Optimize function
	 */
	var saveState = function()
	{
		for(var i = 0; i < listingFields.length; i++)
		{
			switch(listingFields[i].type)
			{
				case 'text':
					lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
					break;
				case 'textarea':
					if('1' == listingFields[i].editor)
					{
						var contents = CKEDITOR.instances['f_' + listingFields[i].name].getData();
						
						lastState[i] = {name: listingFields[i].name, value: contents};
					}
					else
					{
						lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
					}
					break;
				case 'image':
				case 'pictures':
					lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
					break;
				case 'combo':
					lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
					break;
				case 'radio':
					var values = listingFields[i].values.split(',');
					var firstElement = true;
					var save = '';

					for(var j = 0; j < values.length; j++)
					{
						if($('#f_' + listingFields[i].name + '_' + values[j]).attr('checked'))
						{
							if(values.length > 1)
							{
								if(!firstElement)
								{
									save += ',';
								}
								else
								{
									firstElement = false;
								}
								save += $('#f_' + listingFields[i].name + '_' + values[j]).val();
								//lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name + '_' + values[j]).val()};
							}
						}
					}
					lastState[i] = {name: listingFields[i].name, value: save};
					break;
				case 'checkbox':
					// TODO: use selectors for getting selected radio element
					var values = listingFields[i].values.split(',');
					var firstElement = true;
					var save = '';

					for(var j = 0; j < values.length; j++)
					{
						if($('#f_' + listingFields[i].name + '_' + values[j]).attr('checked'))
						{
							if(values.length > 1)
							{
								if(!firstElement)
								{
									save += ',';
								}
								else
								{
									firstElement = false;
								}
							}
							save += $('#f_' + listingFields[i].name + '_' + values[j]).val();
						}
					}

					lastState[i] = {name: listingFields[i].name, value: save};
					break;
				case 'storage':
					lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
					break;
				case 'number':
					lastState[i] = {name: listingFields[i].name, value: $('#f_' + listingFields[i].name).val()};
				default:
					break;
			}
		}

		return lastState;
	};

	/**
	 * Return the array of last values
	 *
	 * @return {Array} 
	 */
	this.getLastState = function()
	{
		return saveState();
	};

	/**
	 * Return the current id category
	 *
	 * @return {Integer}
	 */
	this.getIdCategory = function()
	{
		return getIdCategory();
	};

	/**
	 * Return the current id plan
	 *
	 * @return {Integer}
	 */
	this.getIdPlan = function()
	{
		return getIdPlan();
	};

	this.transform = function()
	{
		var i = 0;

		$(obj).find(":input").each(function()
		{
			lastState[i++] = {name: $(this).attr("name"), value: $(this).val()};
		});
	};
};