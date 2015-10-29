/**
 * Class for creating plans section.
 * @class This is the Plans class.  
 *
 * @param {Array} conf
 *
 * @param {String} conf.id The id div of plans element
 * @param {Function} conf.callback The callback function on click event
 *
 */
intelli.plans = function(conf)
{
	var id = conf.id;
	var callback = (typeof conf.callback == 'function') ? conf.callback : function(){};
	var idPlan = (typeof conf.idPlan == 'undefined') ? false : conf.idPlan;
	var idLastSelected = null;
	var type = (typeof conf.type != 'undefined') && intelli.inArray(conf.type, ['listing', 'account']) ? conf.type : 'listing';
	var page = (typeof conf.page != 'undefined') && intelli.inArray(conf.page, ['suggest', 'edit']) ? conf.page : 'suggest';

	this.init = function()
	{
		this.getPlans();

		$('input[name="plan"]', '#' + id).click(callback);
	};

	/**
	 * Return id current plan
	 *
	 * @return {Integer}
	 */
	this.getPlanCost = function()
	{
		if ($('#plans').length > 0)
		{
			var id_plan = $("#plans input[type='radio']:checked").val();
			var plan_cost = $("#planCost_" + id_plan).val();

			return plan_cost;
		}

		return false;
	};

	this.getPlanId = function()
	{
		if ($('#' + id).length > 0)
		{
			return id_plan = $("#" + id + " input[type='radio']:checked").val();
		}

		return false;
	};

	this.getLastId = function()
	{
		if (idLastSelected)
		{
			return idLastSelected;
		}

		return false;
	};

	this.getPlans = function()
	{
		idLastSelected = this.getPlanId();
		
		$('#' + id).empty();

		$.ajaxSetup({async: false});

		var idCategory = $("#category_id").val();

		// Getting listings fields by AJAX
		$.getJSON('ajax.php', {action: 'getplans', idcategory: idCategory, type: type, page: page}, function(plans)
		{
			if (plans && plans.length > 0)
			{
				var template = $("#plansList").html();
				$("#plans").html(_.template(template, {plans:plans}));

				if ('none' == $('#' + id).parents("fieldset").css("display"))
				{
					$('#' + id).parents("fieldset").css("display", "block");
				}
			}
			else
			{
				$('#' + id).parents("fieldset").css("display", "none");
			}
		});

		$.ajaxSetup({async: true});

		setPlan();
	};

	var setPlan = function()
	{
		id_plan = idLastSelected ? idLastSelected : idPlan;

		if (id_plan)
		{
			$('input[name="plan"]').each(function()
			{
				if($(this).val() == id_plan)
				{
					$(this).prop('checked', true).closest('.b-plan').addClass('selected').find('.js-options-preview').removeAttr('style');
					// $('input', '#planVisualOptions_' + id_plan).prop('disabled', false);
					$('#plan_cost').val('');
					countVisualOptions('.b-plan.selected', 'load');
				}
				else
				{
					$(this).prop('checked', false).closest('.b-plan').removeClass('selected').find('.js-options-preview').hide();
					$(this).closest('.b-plan').find('.b-plan__visual-options input').prop('disabled', true);
				}
			});
		}
		else
		{
			var $firstPlan = $("#" + id + " input[type=radio]:first");

			$firstPlan.prop('checked', true).closest('.b-plan').addClass('selected').find('.js-options-preview').removeAttr('style');
			$('input', '#planVisualOptions_' + $firstPlan.val()).prop('disabled', false);
			countVisualOptions('.b-plan.selected', 'load');
		}
	};
}