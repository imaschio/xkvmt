$(function()
{
	var listing_id = $('#listing_id').val();
	var fields = new intelli.fields({
		id: 'fields',
		part: (listing_id > 0 ? 'edit' : 'suggest'),
		restore: true,
		listingId: listing_id,
		session: sessvars.fields
	});

	fields.transform();

	var plans = new intelli.plans(
	{
		id: 'plans',
		idPlan: sessvars.idPlan || $("#old_plan_id").val(),
		type: 'listing',
		page: (listing_id > 0 ? 'edit' : 'suggest'),
		callback: function()
		{
			$('div#crossedCategories').html('');
			$('div#mCrossTree').find('input[type=checkbox]:checked').removeAttr('checked');
			$('#multi_crossed').val('');
			
			var idPlan = $(this).val();
			var numDeepLinks = $('#planDeepLinks_' + idPlan).val();

			fields.fillFields();

			if(1 == intelli.config.mcross_only_sponsored)
			{
				if(0 == $('#planCost_' + idPlan).val())
				{
					$('#multi_crossed').val('');
					intelli.display('mCrossDiv', 'hide');
				}
				else
				{
					intelli.display('mCrossDiv', 'show');
				}
			}

			if(numDeepLinks > 0)
			{
				deeplinks.create(numDeepLinks);
				deeplinks.display('show');
			}
			else
			{
				deeplinks.display('hide');
			}

			if ($('#planCost_' + idPlan).val() > 0 && $("#old_plan_id").val() != idPlan)
			{
				intelli.display('gateways', 'show');
			}
			else
			{
				intelli.display('gateways', 'hide');
			}
			$("#fields").triggerHandler("updated");
			detectFilename();
		}
	});

	plans.init();

	// Changing plans
	$('#plans').on('click', 'input[name="plan"]', function()
	{
		if (!$(this).closest('.b-plan').hasClass('selected'))
		{
			plans.init();
		}
	});

	// Visual options
	$('#plans').on('click', 'input[name="visual_options[]"]', function()
	{
		countVisualOptions(this, 'click');
	});

	// Listing preview
	$('#plans').on('click', '.js-options-preview', function(e)
	{
		e.preventDefault();
		
		var option_ids = $('input[name="visual_options[]"]:checked').map(function()
		{
			return $(this).val();
		}).toArray();

		$.getJSON('ajax.php', {action: 'get-visual-options', option_ids: option_ids}, function(options)
		{
			var template = $("#OpList").html();
			$("#optionsPreview").html(_.template(template, {options:options}));
		});

		$.ajaxSetup({async: true});

		$('#previeListingModal').appendTo('body').modal('show');
	});

	// Setting up the categories tree
	var treeCat = new intelli.tree(
	{
		id: 'tree',
		type: 'radio',
		state: $('#category_id').val(),
		hideRoot: true,
		menuType: intelli.config.categories_tree_type,
		expandableLocked: true,
		callback: function()
		{
			/* array crossed categories */
			var crossed = $('#multi_crossed').val().split(',');
			var catId = $(this).val();
			var letter = $('#prefix_mCrossTree').val();

			if(intelli.inArray(catId, crossed))
			{
				$('#mcrossedCat_' + catId).remove();

				crossed = intelli.remove(crossed, catId);

				$('#multi_crossed').val(crossed.join(','));

				$('#category_id').val($(this).val());
				$('#cat_' + letter + '_' + catId).prop('checked', false);

				intelli.error(intelli.lang.cross_warning);
			}
			else
			{
				$('#categoryTitle > span').text($(this).attr('title'));
				$('#category_id').val($(this).val());
			}

			plans.init();

			var planCost = plans.getPlanCost();

			if(planCost > 0 && $("#old_plan_id").val() != plans.getPlanId())
			{
				intelli.display('gateways', 'show');
			}
			else
			{
				intelli.display('gateways', 'hide');
			}
			
			fields.fillFields();
			$("#fields").triggerHandler("updated");
			detectFilename();
		},
		dropDownCallback: function(id, title)
		{
			/* array crossed categories */
			var crossed = $('#multi_crossed').val().split(',');

			/* hiding any notification boxes */
			intelli.display('notification', 'hide');

			if(intelli.inArray(id, crossed))
			{
				$('#mcrossedCat_' + id).remove();

				crossed = intelli.remove(crossed, id);

				$('#multi_crossed').val(crossed.join(','));

				$('#category_id').val(id);
				$('#cat_' + letter + '_' + id).prop('checked', false);

				intelli.error(intelli.lang.cross_warning);
			}
			else
			{
				$('#categoryTitle > span').text(title);
				$('#category_id').val(id);
			}

			plans.init();
			
			fields.fillFields();
			detectFilename();
		}
	});

	treeCat.init();

	treeCat.expandPath($('#parent_path').val());
	treeCat.setSelected($('#category_id').val());

	// process search autocomplete
	$.ajaxSetup({async: false});
	$("#cat_filter").typeahead(
	{
		source: function (query, process)
		{
			categories = {};

			return $.ajax(
			{
				url: intelli.config.esyn_url + 'ajax.php',
				type: 'get',
				dataType: 'json',
				displayField: 'title',
				data:  { action: 'catsfilter', q: query },
				success: function (data)
				{
					var display = [];

					if (typeof data != 'undefined')
					{
						$.each(data, function (i, category)
						{
							categories[category.title] = category;
							display.push(category.title);
						});

						process(display);
					}

					return false;
				}
			});
		},

		updater: function (item)
		{
			if (item)
			{
				treeCat.setSelectedCats(categories[item].id);
				$("#cat_filter").val(categories[item].display);
			}
		}
	});
	$.ajaxSetup({async: true});

	// Showing up the multi cross tree
	if (1 == intelli.config.mcross_functionality)
	{
		var default_crossed = $('#multi_crossed').val();

		intelli.display('mCrossDiv', 'show');

		/* Setting up the multi cross tree categories */
		var crossedCat = new intelli.tree({
			id: 'mCrossTree',
			type: 'checkbox',
			state: default_crossed,
			hideRoot: true,
			expandableLocked: true,
			defaultCategory: [{id: 0, title: 'ROOT', sub: true, disabled: true, checked: false}],
			callback: function(e)
			{
				/* this object */
				var thisItem = $(this);

				/* id checked category */
				var thisId = thisItem.attr('id').split('_')[2];

				/* id plan */
				var planId = fields.getIdPlan();

				/* id category */
				var catId = fields.getIdCategory();

				/* array crossed categories */
				var crossed = $('#multi_crossed').val().split(',');

				/* max number of crossed categories */
				/* if there is no limit for plan, therefore use script configuration */
				var maxCrossed = ($('#planCrossedMax_' + planId).val() > 0) ? $('#planCrossedMax_' + planId).val() : intelli.config.mcross_number_links;
				
				var error = false;
				var html = '';

				crossed = ('' == crossed) ? [] : crossed;

				if(thisItem.is(':checked'))
				{
					if(crossed.length + 1 > maxCrossed)
					{
						intelli.error(intelli.lang.mcross_warning);

						e.preventDefault();
						return;
					}

					if(catId == thisId)
					{
						intelli.error(intelli.lang.cross_warning);

						e.preventDefault();
						return;
					}

					if(!error)
					{
						html += '<span class="label" id="mcrossedCat_'+ thisId +'">';
						html += thisItem.attr('title');
						html += '</span> ';

						$('#crossedCategories').append(html);

						if(!intelli.inArray(thisId, crossed))
						{
							crossed.push(thisId);
						}

						$('#multi_crossed').val(crossed.join(','));
					}
					else
					{
						e.preventDefault();
					}
				}
				else
				{
					$('#mcrossedCat_' + thisId).remove();

					crossed = intelli.remove(crossed, thisId);

					$('#multi_crossed').val(crossed.join(','));
				}
			}
		});

		/* Initialization multi cross tree */
		crossedCat.init();
	}

	/* Deep links */
	var deeplinks = new intelli.deeplinks({
		id: 'deepLinks',
		container: 'deepLinksDiv',
		restore: true,
		session: sessvars.deeplinks
	});

	deeplinks.transform();

	if (plans && plans.getPlanCost() > 0 && $("#old_plan_id").val() != plans.getPlanId())
	{
		intelli.display('gateways', 'show');
	}

	var numDeepLinks = $('#planDeepLinks_' + fields.getIdPlan()).val();

	if (numDeepLinks > 0)
	{
		deeplinks.init(numDeepLinks);
		deeplinks.display('show');
	}

	fields.fillFields();

	detectFilename();

	if ($('#gateways input[name="payment_gateway"]:checked').length == 0)
	{
		$('#gateways input[name="payment_gateway"]:first').attr('checked', 'checked');
	}

	$('#submit_btn').click(function()
	{
		/* Saving the current values */
		sessvars.fields = fields.getLastState();
		sessvars.deeplinks = deeplinks.getLastState();
		sessvars.idPlan = fields.getIdPlan();

		return true;
	});
});

function metaFetch()
{
	var url = $('#f_url').val();

	$.get(intelli.config.esyn_url + 'ajax.php', { action: 'fetchmetas', url: url }).done(function(data)
	{
		if (CKEDITOR.instances['f_description'])
		{
			CKEDITOR.instances['f_description'].insertText(data);
		}
		else
		{
			$('#f_description').append(' ' + data);
		}
	});

	return false;
}

// handle the form POST response 
function formResponse(resp)
{
	$('#form_listing').ajaxLoaderRemove();
	if (resp.err)
	{
		$('#msg').html(resp.msg).attr('class', 'error');
	}
	else
	{
		$('#form_listing').hide();
		$('#msg').html(resp.msg).attr('class', 'notification');
	}
	$('html').animate({ scrollTop: $('#msg').offset().top }, { duration: 'slow', easing: 'swing'});
}

function countVisualOptions(what, action) {
	var currentPlanId = $('input[name="plan"]:checked', '#plans').val(),
		$totalSumElem = $('span', '#planVisualOptionsTotal_' + currentPlanId),
		totalSum      = Number($totalSumElem.text()),
		$plan_cost    = $('#plan_cost');

	switch (action) {
		case 'click':
			var $this     = $(what),
				thisPrice = Number($this.data('option-price'));

			if ($this.is(':checked') && !$(this).is(':disabled'))
			{
				$totalSumElem.text(totalSum + thisPrice);
				$plan_cost.val(totalSum + thisPrice);
			}
			else
			{
				$totalSumElem.text(totalSum - thisPrice);
				$plan_cost.val(totalSum - thisPrice);
			}

			break;
		default:
			var $vOptions     = $(what).find('input[name="visual_options[]"]');

			$vOptions.each(function(i) {
				if($(this).is(':checked') && !$(this).is(':disabled'))
				{
					var thisPrice = Number($(this).data('option-price'));
					totalSum = totalSum + thisPrice;
				}
			});

			$totalSumElem.text(totalSum);
			$plan_cost.val(totalSum);
	}
}